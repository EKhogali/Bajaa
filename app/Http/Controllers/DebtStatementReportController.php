<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\VendorGroup;
use App\company;
use Illuminate\Http\Request;

class DebtStatementReportController extends Controller
{
    private function buildQuery(Request $request)
    {
        $companyIds = $this->resolveCompanyIds($request);

        $fromDate = $request->input('fromdate');
        $toDate   = $request->input('todate');

        $query = Vendor::query()->with([
            'company',
            'group',
            // Only pull the transactions inside the requested date range so we
            // can compute a period balance without touching the stored
            // "all-time" balance column.
            'transactions' => function ($t) use ($fromDate, $toDate) {
                if ($fromDate) {
                    $t->whereDate('date', '>=', $fromDate);
                }
                if ($toDate) {
                    $t->whereDate('date', '<=', $toDate);
                }
            },
        ]);

        if ($companyIds !== null) {
            $query->visibleToAny($companyIds);
        }

        if ($request->filled('vendor_id')) {
            $query->where('id', $request->vendor_id);
        } elseif ($request->filled('vendor_group_id')) {
            $query->where('vendor_group_id', $request->vendor_group_id);
        }

        return $query;
    }

    /**
     * Decorate each vendor with the balance that should be displayed:
     * - if a from/to date was supplied, that's the net movement (debit - credit)
     *   of the transactions inside that range;
     * - otherwise it's simply the vendor's stored running balance (unchanged
     *   behaviour, so existing links/behaviour aren't affected).
     */
    private function applyDisplayBalance($vendors, Request $request)
    {
        $hasDateFilter = $request->filled('fromdate') || $request->filled('todate');

        return $vendors->map(function ($vendor) use ($hasDateFilter) {
            if ($hasDateFilter) {
                $vendor->display_balance = $vendor->transactions->sum('debit') - $vendor->transactions->sum('credit');
            } else {
                $vendor->display_balance = $vendor->balance;
            }
            return $vendor;
        });
    }

    public function index(Request $request)
    {
        $companyId = session('company_id');

        $vendors = $this->buildQuery($request)->orderBy('name')->get();
        $vendors = $this->applyDisplayBalance($vendors, $request);

        $totalDebit = $vendors->sum(function ($v) {
            return $v->display_balance > 0 ? $v->display_balance : 0; });
        $totalCredit = $vendors->sum(function ($v) {
            return $v->display_balance < 0 ? abs($v->display_balance) : 0; });
        $netBalance = $vendors->sum('display_balance');

        $allVendors = Vendor::where('company_id', $companyId)->orderBy('name')->get();
        $groups = VendorGroup::where('company_id', $companyId)->orderBy('name')->get();
        $companies = company::all();

        // BUG FIX: id is an Eloquent attribute, not a method — calling it as
        // ->id() throws "Call to undefined method App\User::id()".
        $isAdminOrSupervisor = auth()->check() && in_array(auth()->user()->id, [1, 2]);

        return view('rep.debt_statement', compact(
            'vendors', 'allVendors', 'groups', 'companies', 'isAdminOrSupervisor',
            'totalDebit', 'totalCredit', 'netBalance'
        ));
    }

    public function print(Request $request)
    {
        $vendors = $this->buildQuery($request)->orderBy('name')->get();
        $vendors = $this->applyDisplayBalance($vendors, $request);

        $totalDebit = $vendors->sum(function ($v) {
            return $v->display_balance > 0 ? $v->display_balance : 0; });
        $totalCredit = $vendors->sum(function ($v) {
            return $v->display_balance < 0 ? abs($v->display_balance) : 0; });
        $netBalance = $vendors->sum('display_balance');

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

        // Shown in the printed report's meta block when a date range is used.
        $fromDate = $request->input('fromdate');
        $toDate   = $request->input('todate');
        if ($fromDate && $toDate) {
            $periodLabel = 'الفترة من ' . $fromDate . ' إلى ' . $toDate;
        } elseif ($fromDate) {
            $periodLabel = 'من تاريخ ' . $fromDate;
        } elseif ($toDate) {
            $periodLabel = 'حتى تاريخ ' . $toDate;
        } else {
            $periodLabel = 'كل الفترات';
        }

        return view('rep.debt_statement_print', compact(
            'vendors',
            'totalDebit',
            'totalCredit',
            'netBalance',
            'scopeLabel',
            'companyLabel',
            'periodLabel'
        ));
    }

    private function resolveCompanyIds(Request $request)
    {
        $isPrivileged = auth()->check() && in_array(auth()->user()->id, [1, 2]);
        $companyFilter = $isPrivileged ? $request->input('company_filter', 'current') : 'current';

        if ($companyFilter === 'all') {
            return null;
        }
        if ($companyFilter === 'current') {
            return [session('company_id')];
        }
        return [(int) $companyFilter];
    }
}