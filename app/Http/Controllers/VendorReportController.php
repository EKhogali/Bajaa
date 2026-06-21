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

        $query = VendorTransaction::where('company_id', $companyId)
            ->with(['vendor.tags', 'tags']);

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }
        if ($request->filled('from_date')) {
            $query->where('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('date', '<=', $request->to_date);
        }
        if ($request->filled('vendor_tag_ids')) {
            $query->whereHas('vendor.tags', function ($q) use ($request) {
                $q->whereIn('vendor_tags.id', $request->vendor_tag_ids);
            });
        }
        if ($request->filled('transaction_tag_ids')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->whereIn('transaction_tags.id', $request->transaction_tag_ids);
            });
        }

        $transactions = $query->orderBy('date', 'asc')->get();

        $previousBalance = 0;
        if ($request->filled('vendor_id') && $request->filled('from_date')) {
            $prevTotals = DB::table('vendor_transactions')
                ->where('vendor_id', $request->vendor_id)
                ->where('date', '<', $request->from_date)
                ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
                ->first();
            $previousBalance = ($prevTotals->total_debit ?? 0) - ($prevTotals->total_credit ?? 0);
        }

        $totalDebit = $transactions->sum('debit');
        $totalCredit = $transactions->sum('credit');
        $finalBalance = $previousBalance + $totalDebit - $totalCredit;

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

    public function print(Request $request)
    {
        $companyId = session('company_id');

        $query = VendorTransaction::where('company_id', $companyId)
            ->with(['vendor.tags', 'tags']);

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }
        if ($request->filled('from_date')) {
            $query->where('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('date', '<=', $request->to_date);
        }
        if ($request->filled('vendor_tag_ids')) {
            $query->whereHas('vendor.tags', function ($q) use ($request) {
                $q->whereIn('vendor_tags.id', $request->vendor_tag_ids);
            });
        }
        if ($request->filled('transaction_tag_ids')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->whereIn('transaction_tags.id', $request->transaction_tag_ids);
            });
        }

        $transactions = $query->orderBy('date', 'asc')->get();

        $previousBalance = 0;
        if ($request->filled('vendor_id') && $request->filled('from_date')) {
            $prevTotals = DB::table('vendor_transactions')
                ->where('vendor_id', $request->vendor_id)
                ->where('date', '<', $request->from_date)
                ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
                ->first();
            $previousBalance = ($prevTotals->total_debit ?? 0) - ($prevTotals->total_credit ?? 0);
        }

        $totalDebit = $transactions->sum('debit');
        $totalCredit = $transactions->sum('credit');
        $finalBalance = $previousBalance + $totalDebit - $totalCredit;

        $filterVendor = null;
        if ($request->filled('vendor_id')) {
            $v = Vendor::find($request->vendor_id);
            $filterVendor = $v ? $v->name : null;  // ← PHP 7 compatible
        }

        $filterVendorTags = null;
        if ($request->filled('vendor_tag_ids')) {
            $filterVendorTags = VendorTag::whereIn('id', $request->vendor_tag_ids)
                ->pluck('name')->implode('، ');
        }

        $filterTransactionTags = null;
        if ($request->filled('transaction_tag_ids')) {
            $filterTransactionTags = TransactionTag::whereIn('id', $request->transaction_tag_ids)
                ->pluck('name')->implode('، ');
        }

        $filterFromDate = $request->from_date;
        $filterToDate = $request->to_date;

        return view('rep.vendor_report_print', compact(
            'transactions',
            'previousBalance',
            'totalDebit',
            'totalCredit',
            'finalBalance',
            'filterVendor',
            'filterFromDate',
            'filterToDate',
            'filterVendorTags',
            'filterTransactionTags'
        ));
    }
}