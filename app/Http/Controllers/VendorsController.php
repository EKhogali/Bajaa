<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\VendorTag;
use App\VendorGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\TransactionTag;

use App\VendorTransaction;

class VendorsController extends Controller
{
    public function index(Request $request)
    {
        $companyId = session('company_id');

        $vendors = Vendor::visibleTo($companyId)
            ->when($request->filled('vendor_group_id'), function ($q) use ($request) {
                $q->where('vendor_group_id', $request->vendor_group_id);
            })
            ->with('tags', 'group', 'companies')
            ->get();

        $groups = VendorGroup::orderBy('name')->get();
        $activeGroup = $request->filled('vendor_group_id')
            ? $groups->firstWhere('id', $request->vendor_group_id)
            : null;

        return view('bsc.vendors.index', compact('vendors', 'groups', 'activeGroup'));
    }

    public function create()
    {
        $companyId = session('company_id');
        $vendors = Vendor::visibleTo($companyId)->get();
        $existingTags = TransactionTag::where('company_id', $companyId)->get();
        $groups = VendorGroup::orderBy('name')->get();
        $companies = \App\company::all();

        return view('bsc.vendors.create', compact('vendors', 'existingTags', 'groups', 'companies'));
    }

    public function store(Request $request)
    {
        $companyId = session('company_id');

        $request->validate([
            'name' => 'required|string|max:255',
            'tel' => 'nullable|string|max:50',
            'vendor_group_id' => 'nullable|exists:vendor_groups,id',
            'vendor_scope' => 'required|in:specific,all',
            'company_ids' => 'required_if:vendor_scope,specific|array',
            'company_ids.*' => 'exists:companies,id',
        ]);

        $isGlobal = $request->vendor_scope === 'all';

        $vendor = Vendor::create([
            'company_id' => $companyId, // "home" company, kept for reference
            'name' => $request->name,
            'tel' => $request->tel,
            'balance' => 0,
            'vendor_group_id' => $request->vendor_group_id,
            'is_global' => $isGlobal,
        ]);

        if (!$isGlobal) {
            $vendor->companies()->sync($request->company_ids);
        }

        $vendor->tags()->sync($this->resolveTagIds($request, $companyId));

        return redirect()->route('vendors.index')->with('success', 'تم إضافة المورد بنجاح.');
    }

    public function edit($id)
    {
        $companyId = session('company_id');

        $vendor = Vendor::with('tags', 'companies')->findOrFail($id);
        $existingTags = VendorTag::where('company_id', $companyId)->get();
        $groups = VendorGroup::orderBy('name')->get();
        $companies = \App\company::all();

        return view('bsc.vendors.edit', compact('vendor', 'existingTags', 'groups', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'tel' => 'nullable|string|max:50',
            'vendor_group_id' => 'nullable|exists:vendor_groups,id',
            'vendor_scope' => 'required|in:specific,all',
            'company_ids' => 'required_if:vendor_scope,specific|array',
            'company_ids.*' => 'exists:companies,id',
        ]);

        $isGlobal = $request->vendor_scope === 'all';

        $vendor->update([
            'name' => $request->name,
            'tel' => $request->tel,
            'vendor_group_id' => $request->vendor_group_id,
            'is_global' => $isGlobal,
        ]);

        $vendor->companies()->sync($isGlobal ? [] : $request->company_ids);

        $vendor->tags()->sync($this->resolveTagIds($request, session('company_id')));

        return redirect()->route('vendors.index')->with('success', 'تم تحديث بيانات المورد بنجاح.');
    }

    public function destroy($id)
    {
        $companyId = session('company_id');
        $vendor = Vendor::where('company_id', $companyId)->findOrFail($id);

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
        $submitted = $request->input('tags') ?? [];

        if (empty($submitted) || !is_array($submitted)) {
            return [];
        }

        $tagIds = [];

        foreach ($submitted as $value) {
            $value = trim($value);
            if (empty($value))
                continue;

            if (str_starts_with($value, 'new:')) {
                $name = trim(substr($value, 4));
                if (empty($name))
                    continue;

                $tag = VendorTag::firstOrCreate([
                    'company_id' => $companyId,
                    'name' => $name,
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