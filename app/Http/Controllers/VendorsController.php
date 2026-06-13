<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\VendorTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorsController extends Controller
{
    // List vendors for the active company only
    public function index()
    {
        $companyId = session('company_id');

        $vendors = Vendor::where('company_id', $companyId)
            ->with('tags')
            ->get();

        return view('bsc.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('bsc.vendors.create');
    }

    // Store vendor and process tags on-the-fly
    public function store(Request $request)
    {
        $companyId = session('company_id');

        $request->validate([
            'name' => 'required|string|max:255',
            'tel' => 'nullable|string|max:50',
        ]);

        $vendor = Vendor::create([
            'company_id' => $companyId,
            'name' => $request->name,
            'tel' => $request->tel,
            'balance' => 0,
        ]);

        // Process tags on the fly if provided (expects comma-separated string)
        if ($request->filled('tags')) {
            $tagNames = explode(',', $request->tags);
            $tagIds = [];

            foreach ($tagNames as $name) {
                $trimmedName = trim($name);
                if (empty($trimmedName)) continue;

                // Find or create tag inside the active company scope
                $tag = VendorTag::firstOrCreate([
                    'company_id' => $companyId,
                    'name' => $trimmedName
                ]);

                $tagIds[] = $tag->id;
            }

            $vendor->tags()->sync($tagIds);
        }

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }


// Show the form for editing the specified vendor
    public function edit($id)
    {
        $companyId = session('company_id');
        $vendor = Vendor::where('company_id', $companyId)->with('tags')->findOrFail($id);

        return view('bsc.vendors.edit', compact('vendor'));
    }

    // Update logic for vendor profiles and sync tags on the fly
    public function update(Request $request, $id)
    {
        $companyId = session('company_id');
        $vendor = Vendor::where('company_id', $companyId)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'tel' => 'nullable|string|max:50',
        ]);

        $vendor->update([
            'name' => $request->name,
            'tel' => $request->tel,
        ]);

        // Re-process multi-filter tags framework mapping matches
        if ($request->has('tags')) {
            $tagNames = explode(',', $request->tags);
            $tagIds = [];

            foreach ($tagNames as $name) {
                $trimmedName = trim($name);
                if (empty($trimmedName)) continue;

                $tag = VendorTag::firstOrCreate([
                    'company_id' => $companyId,
                    'name' => $trimmedName
                ]);

                $tagIds[] = $tag->id;
            }
            $vendor->tags()->sync($tagIds);
        } else {
            $vendor->tags()->sync([]);
        }

        return redirect()->route('vendors.index')->with('success', 'تم تحديث بيانات المورد بنجاح.');
    }

    // Delete logic to drop vendor account record safely
    public function destroy($id)
    {
        $companyId = session('company_id');
        $vendor = Vendor::where('company_id', $companyId)->findOrFail($id);
        
        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'تم حذف المورد بنجاح.');
    }



    // Button action to manually recalculate all vendor balances for the active company
    public function recalculateBalances()
    {
        $companyId = session('company_id');

        // Fetch all vendors belonging to this company
        $vendors = Vendor::where('company_id', $companyId)->get();

        foreach ($vendors as $vendor) {
            // Calculate dynamic balance total: sum(debit) - sum(credit)
            $totals = DB::table('vendor_transactions')
                ->where('vendor_id', $vendor->id)
                ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
                ->first();

            $calculatedBalance = ($totals->total_debit ?? 0) - ($totals->total_credit ?? 0);

            // Sync the changes back to storage
            $vendor->update(['balance' => $calculatedBalance]);
        }

        return redirect()->route('vendors.index')->with('success', 'Balances recalculated successfully.');
    }
}