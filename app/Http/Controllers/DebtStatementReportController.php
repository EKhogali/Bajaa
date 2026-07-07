<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\VendorGroup;
use App\company;
use Illuminate\Http\Request;

class DebtStatementReportController extends Controller
{
    private function resolveCompanyIds(Request $request)
    {
        $companyFilter = $request->input('company_filter', 'current');

        if ($companyFilter === 'all') {
            return null; // no constraint
        }
        if ($companyFilter === 'current') {
            return [session('company_id')];
        }
        return [(int) $companyFilter];
    }

    private function buildQuery(Request $request)
    {
        $companyIds = $this->resolveCompanyIds($request);

        $query = Vendor::query()->with(['company', 'group']);

        if ($companyIds !== null) {
            $query->whereIn('company_id', $companyIds);
        }

        if ($request->filled('vendor_id')) {
            $query->where('id', $request->vendor_id);
        } elseif ($request->filled('vendor_group_id')) {
            $query->where('vendor_group_id', $request->vendor_group_id);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $companyId = session('company_id');

        $vendors = $this->buildQuery($request)->orderBy('name')->get();

        $totalDebit  = $vendors->sum(function ($v) { return $v->balance > 0 ? $v->balance : 0; });
        $totalCredit = $vendors->sum(function ($v) { return $v->balance < 0 ? abs($v->balance) : 0; });
        $netBalance  = $vendors->sum('balance');

        $allVendors = Vendor::where('company_id', $companyId)->orderBy('name')->get();
        $groups     = VendorGroup::where('company_id', $companyId)->orderBy('name')->get();
        $companies  = company::all();

        return view('rep.debt_statement', compact(
            'vendors', 'allVendors', 'groups', 'companies',
            'totalDebit', 'totalCredit', 'netBalance'
        ));
    }

    public function print(Request $request)
    {
        $vendors = $this->buildQuery($request)->orderBy('name')->get();

        $totalDebit  = $vendors->sum(function ($v) { return $v->balance > 0 ? $v->balance : 0; });
        $totalCredit = $vendors->sum(function ($v) { return $v->balance < 0 ? abs($v->balance) : 0; });
        $netBalance  = $vendors->sum('balance');

        // Build the scope phrase shown on the printed report
        if ($request->filled('vendor_id')) {
            $vendor = Vendor::find($request->vendor_id);
            $scopeLabel = 'مورد محدد: ' . ($vendor->name ?? 'غير معروف');
        } elseif ($request->filled('vendor_group_id')) {
            $group = VendorGroup::find($request->vendor_group_id);
            $scopeLabel = 'تصنيف محدد: ' . ($group->name ?? 'غير معروف');
        } else {
            $scopeLabel = 'تقرير شامل لكل الموردين';
        }

        $companyFilter = $request->input('company_filter', 'current');
        if ($companyFilter === 'all') {
            $companyLabel = 'كل الشركات';
        } elseif ($companyFilter === 'current') {
            $companyLabel = session('company_name', 'الشركة الحالية');
        } else {
            $companyLabel = optional(company::find($companyFilter))->name ?? 'غير محدد';
        }

        return view('rep.debt_statement_print', compact(
            'vendors', 'totalDebit', 'totalCredit', 'netBalance', 'scopeLabel', 'companyLabel'
        ));
    }
}