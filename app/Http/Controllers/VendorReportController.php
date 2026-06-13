<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\VendorTransaction;
use App\VendorTag;
use App\TransactionTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorReportController extends Controller
{
    public function index(Request $request)
    {
        $companyId = session('company_id');
        
        // باني استعلام تقرير الحركات الأساسي
        $query = VendorTransaction::where('company_id', $companyId)
            ->with(['vendor.tags', 'tags']);

        // 1. الفلترة حسب المورد المختار
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // 2. الفلترة حسب النطاق الزمني للمستندات
        if ($request->filled('from_date')) {
            $query->where('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('date', '<=', $request->to_date);
        }

        // 3. الفلترة المتقدمة بحسب وسوم الموردين (Vendor Tags)
        if ($request->filled('vendor_tag_id')) {
            $query->whereHas('vendor.tags', function ($q) use ($request) {
                $q->where('vendor_tags.id', $request->vendor_tag_id);
            });
        }

        // 4. الفلترة المتقدمة بحسب وسوم الحركات المباشرة (Transaction Tags)
        if ($request->filled('transaction_tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('transaction_tags.id', $request->transaction_tag_id);
            });
        }

        // جلب النتائج النهائية مصنفة حسب تاريخ القيد المالي
        $transactions = $query->orderBy('date', 'asc')->get();

        // حساب الرصيد السابق التراكمي (الافتتاحي قبل تاريخ البداية من أجل صحة الحسابات)
        $previousBalance = 0;
        if ($request->filled('vendor_id') && $request->filled('from_date')) {
            $prevTotals = DB::table('vendor_transactions')
                ->where('vendor_id', $request->vendor_id)
                ->where('date', '<', $request->from_date)
                ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
                ->first();

            $previousBalance = ($prevTotals->total_debit ?? 0) - ($prevTotals->total_credit ?? 0);
        }

        // حساب مجاميع الحركات الحالية المعروضة بالفترة للتلخيص
        $totalDebit = $transactions->sum('debit');
        $totalCredit = $transactions->sum('credit');
        $finalBalance = $previousBalance + $totalDebit - $totalCredit;

        // جلب جميع بيانات القوائم المنسدلة المربوطة بالشركة المحددة لعرض الفلاتر
        $vendors = Vendor::where('company_id', $companyId)->get();
        $vendorTags = VendorTag::where('company_id', $companyId)->get();
        $transactionTags = TransactionTag::where('company_id', $companyId)->get();

        return view('rep.vendor_report', compact(
            'transactions',
            'vendors',
            'vendorTags',
            'transactionTags',
            'previousBalance',
            'totalDebit',
            'totalCredit',
            'finalBalance'
        ));
    }
}