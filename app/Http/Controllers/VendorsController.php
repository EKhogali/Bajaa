<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\VendorTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\TransactionTag;

use App\VendorTransaction;

class VendorsController extends Controller
{
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
    $companyId    = session('company_id');
    $vendors      = Vendor::where('company_id', $companyId)->get();
    $existingTags = TransactionTag::where('company_id', $companyId)->get(); // ← add this

    return view('bsc.vendors.create', compact('vendors', 'existingTags'));
}

    public function store(Request $request)
    {
        $companyId = session('company_id');

        $request->validate([
            'name' => 'required|string|max:255',
            'tel'  => 'nullable|string|max:50',
        ]);

        $vendor = Vendor::create([
            'company_id' => $companyId,
            'name'       => $request->name,
            'tel'        => $request->tel,
            'balance'    => 0,
        ]);

        $vendor->tags()->sync($this->resolveTagIds($request, $companyId));

        return redirect()->route('vendors.index')->with('success', 'تم إضافة المورد بنجاح.');
    }

    public function edit($id)
    {
        $companyId = session('company_id');

        $vendor       = Vendor::where('company_id', $companyId)->with('tags')->findOrFail($id);
        $existingTags = VendorTag::where('company_id', $companyId)->get();

        return view('bsc.vendors.edit', compact('vendor', 'existingTags'));
    }

    public function update(Request $request, $id)
    {
        $companyId = session('company_id');
        $vendor    = Vendor::where('company_id', $companyId)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'tel'  => 'nullable|string|max:50',
        ]);

        $vendor->update([
            'name' => $request->name,
            'tel'  => $request->tel,
        ]);

        $vendor->tags()->sync($this->resolveTagIds($request, $companyId));

        return redirect()->route('vendors.index')->with('success', 'تم تحديث بيانات المورد بنجاح.');
    }

    public function destroy($id)
    {
        $companyId = session('company_id');
        $vendor    = Vendor::where('company_id', $companyId)->findOrFail($id);

        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'تم حذف المورد بنجاح.');
    }

    public function recalculateBalances()
    {
        $companyId = session('company_id');

        Vendor::where('company_id', $companyId)->each(function ($vendor) {
            $totals = DB::table('vendor_transactions')
                ->where('vendor_id', $vendor->id)
                ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
                ->first();

            $vendor->update([
                'balance' => ($totals->total_debit ?? 0) - ($totals->total_credit ?? 0),
            ]);
        });

        return redirect()->route('vendors.index')->with('success', 'تم إعادة احتساب الأرصدة بنجاح.');
    }

    /**
     * Resolve tag IDs from the multi-select array input.
     * Tags submitted as new names (prefixed with "new:") are created on the fly.
     * Existing tag IDs are used directly.
     */
    private function resolveTagIds(Request $request, int $companyId): array
{
    $submitted = $request->input('tags') ?? []; // ← null-safe fallback

    if (empty($submitted) || !is_array($submitted)) {
        return [];
    }

    $tagIds = [];

    foreach ($submitted as $value) {
        $value = trim($value);
        if (empty($value)) continue;

        if (str_starts_with($value, 'new:')) {
            $name = trim(substr($value, 4));
            if (empty($name)) continue;

            $tag = VendorTag::firstOrCreate([
                'company_id' => $companyId,
                'name'       => $name,
            ]);
        } else {
            $tag = VendorTag::where('company_id', $companyId)
                ->findOrFail((int) $value);
        }

        $tagIds[] = $tag->id;
    }

    return array_unique($tagIds);
}




}