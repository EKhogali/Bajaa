<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\VendorTag;
use App\VendorGroup;
use App\User;
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
            ->visibleToUser(auth()->id())
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
        $vendors = Vendor::visibleTo($companyId)->visibleToUser(auth()->id())->get();
        $existingTags = TransactionTag::where('company_id', $companyId)->get();
        $groups = VendorGroup::orderBy('name')->get();
        $companies = \App\company::all();

        $canManageVendorAccess = in_array((int) auth()->id(), Vendor::RESTRICTION_MANAGERS, true);
        $allUsers = $canManageVendorAccess
    ? User::when(
        auth()->id() !== User::HIDDEN_FROM_OTHERS_ID,
        fn ($q) => $q->where('id', '!=', User::HIDDEN_FROM_OTHERS_ID)
      )->orderBy('name')->get()
    : collect();
        $restrictedUserIds = [];

        return view('bsc.vendors.create', compact(
            'vendors', 'existingTags', 'groups', 'companies',
            'canManageVendorAccess', 'allUsers', 'restrictedUserIds'
        ));
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

        $this->syncVendorRestriction($request, $vendor);

        return redirect()->route('vendors.index')->with('success', 'تم إضافة المورد بنجاح.');
    }

    public function edit($id)
    {
        $companyId = session('company_id');

        $vendor = Vendor::with('tags', 'companies', 'restrictedUsers')->findOrFail($id);

        $canManageVendorAccess = in_array((int) auth()->id(), Vendor::RESTRICTION_MANAGERS, true);

        // Managers can always reach the edit screen — even for a vendor
        // they've restricted themselves out of — so a restriction mistake
        // can never lock them out of fixing it. Everyone else still 404s.
        if (!$vendor->isVisibleToUser(auth()->id()) && !$canManageVendorAccess) {
            abort(404);
        }

        $existingTags = VendorTag::where('company_id', $companyId)->get();
        $groups = VendorGroup::orderBy('name')->get();
        $companies = \App\company::all();

        $allUsers = $canManageVendorAccess
    ? User::when(
        auth()->id() !== User::HIDDEN_FROM_OTHERS_ID,
        fn ($q) => $q->where('id', '!=', User::HIDDEN_FROM_OTHERS_ID)
      )->orderBy('name')->get()
    : collect();
        $restrictedUserIds = $vendor->restrictedUsers->pluck('id')->toArray();

        return view('bsc.vendors.edit', compact(
            'vendor', 'existingTags', 'groups', 'companies',
            'canManageVendorAccess', 'allUsers', 'restrictedUserIds'
        ));
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $canManageVendorAccess = in_array((int) auth()->id(), Vendor::RESTRICTION_MANAGERS, true);

        if (!$vendor->isVisibleToUser(auth()->id()) && !$canManageVendorAccess) {
            abort(404);
        }

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

        $this->syncVendorRestriction($request, $vendor);

        return redirect()->route('vendors.index')->with('success', 'تم تحديث بيانات المورد بنجاح.');
    }

    public function destroy($id)
    {
        $companyId = session('company_id');
        $vendor = Vendor::where('company_id', $companyId)->findOrFail($id);

        $canManageVendorAccess = in_array((int) auth()->id(), Vendor::RESTRICTION_MANAGERS, true);

        if (!$vendor->isVisibleToUser(auth()->id()) && !$canManageVendorAccess) {
            abort(404);
        }

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
     * Apply the "restrict this vendor to specific users" setting from the
     * vendor form — only when submitted by one of Vendor::RESTRICTION_MANAGERS.
     * A non-manager's request never includes these fields (the form doesn't
     * render them), so this silently does nothing for anyone else, leaving
     * any existing restriction untouched.
     */
    private function syncVendorRestriction(Request $request, Vendor $vendor): void
{
    if (!in_array(auth()->id(), Vendor::RESTRICTION_MANAGERS, true)) {
        return;
    }

    if (!$request->filled('restrict_visibility')) {
        return;
    }

    $request->validate([
        'restrict_visibility' => 'in:all,specific',
        'restricted_user_ids' => 'nullable|array',
        'restricted_user_ids.*' => 'exists:users,id',
    ]);

    $isRestricted = $request->restrict_visibility === 'specific';
    $submittedIds = $isRestricted ? ($request->restricted_user_ids ?? []) : [];

    // The hidden user's checkbox is never shown to another manager, so
    // never let saving this form from someone else silently drop them
    // from a restriction they were already part of.
    if ($isRestricted && auth()->id() !== User::HIDDEN_FROM_OTHERS_ID) {
        $alreadyIncluded = $vendor->restrictedUsers()
            ->where('user_id', User::HIDDEN_FROM_OTHERS_ID)
            ->exists();

        if ($alreadyIncluded) {
            $submittedIds[] = User::HIDDEN_FROM_OTHERS_ID;
        }
    }

    $vendor->restrictedUsers()->sync($submittedIds);
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