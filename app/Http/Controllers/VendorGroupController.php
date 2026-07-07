<?php

namespace App\Http\Controllers;

use App\VendorGroup;
use App\Vendor;
use Illuminate\Http\Request;

class VendorGroupController extends Controller
{
    public function index()
    {
        $companyId = session('company_id');

        $groups = VendorGroup::where('company_id', $companyId)
            ->withCount('vendors')
            ->orderBy('name')
            ->get();

        return view('vendor_groups.index', compact('groups'));
    }

    public function create()
    {
        return view('vendor_groups.create');
    }

    public function store(Request $request)
    {
        $companyId = session('company_id');

        $request->validate(['name' => 'required|string|max:255']);

        VendorGroup::create([
            'company_id' => $companyId,
            'name'       => $request->name,
        ]);

        return redirect()->route('vendor_groups.index')->with('success', 'تمت إضافة تصنيف المورد بنجاح.');
    }

    public function edit($id)
    {
        $companyId = session('company_id');
        $group = VendorGroup::where('company_id', $companyId)->findOrFail($id);

        return view('vendor_groups.edit', compact('group'));
    }

    public function update(Request $request, $id)
    {
        $companyId = session('company_id');
        $group = VendorGroup::where('company_id', $companyId)->findOrFail($id);

        $request->validate(['name' => 'required|string|max:255']);

        $group->update(['name' => $request->name]);

        return redirect()->route('vendor_groups.index')->with('success', 'تم تحديث التصنيف بنجاح.');
    }

    public function destroy($id)
    {
        $companyId = session('company_id');
        $group = VendorGroup::where('company_id', $companyId)->findOrFail($id);

        if (Vendor::where('vendor_group_id', $group->id)->exists()) {
            return back()->with('error', 'عفواً، لا يمكن حذف هذا التصنيف لارتباطه بموردين حاليين.');
        }

        $group->delete();

        return redirect()->route('vendor_groups.index')->with('success', 'تم حذف التصنيف بنجاح.');
    }
}