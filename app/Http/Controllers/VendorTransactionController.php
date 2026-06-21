<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\VendorTransaction;
use App\TransactionTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorTransactionController extends Controller
{
    // 1. عرض جدول الحركات
    public function index()
    {
        $companyId = session('company_id');

        $transactions = VendorTransaction::where('company_id', $companyId)
            ->with(['vendor', 'tags'])
            ->orderBy('date', 'desc')
            ->get();

        return view('vendor_transactions.index', compact('transactions'));
    }

    // 2. نموذج إضافة حركة جديدة
    public function create()
    {
        $companyId = session('company_id');
        $vendors = Vendor::where('company_id', $companyId)->get();

        return view('vendor_transactions.create', compact('vendors'));
    }

    // 3. حفظ الحركة في قاعدة البيانات
    public function store(Request $request)
    {
        $companyId = session('company_id');

        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'date' => 'required|date',
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $companyId) {
            // تحديد القيمة بناءً على نوع الحركة
            $debit = $request->type === 'debit' ? $request->amount : 0;
            $credit = $request->type === 'credit' ? $request->amount : 0;

            $transaction = VendorTransaction::create([
                'company_id' => $companyId,
                'vendor_id' => $request->vendor_id,
                'date' => $request->date,
                'debit' => $debit,
                'credit' => $credit,
                'description' => $request->description,
            ]);

            // معالجة الوسوم التلقائية للحركة
            if ($request->filled('tags')) {
                $tagNames = explode(',', $request->tags);
                $tagIds = [];

                foreach ($tagNames as $name) {
                    $trimmedName = trim($name);
                    if (empty($trimmedName)) continue;

                    $tag = TransactionTag::firstOrCreate([
                        'company_id' => $companyId,
                        'name' => $trimmedName
                    ]);

                    $tagIds[] = $tag->id;
                }
                $transaction->tags()->sync($tagIds);
            }

            // تحديث رصيد المورد تلقائياً
            $this->updateVendorBalance($request->vendor_id);
        });

        return redirect()->route('transactions.index')->with('success', 'تم تسجيل الحركة المالية وتحديث الرصيد بنجاح.');
    }

public function edit($id)
{
    $companyId    = session('company_id');
    $transaction  = VendorTransaction::where('company_id', $companyId)->with('tags')->findOrFail($id);
    $vendors      = Vendor::where('company_id', $companyId)->get();
    $existingTags = TransactionTag::where('company_id', $companyId)->get();

    $currentType   = $transaction->debit > 0 ? 'debit' : 'credit';
    $currentAmount = $transaction->debit > 0 ? $transaction->debit : $transaction->credit;

    return view('vendor_transactions.edit', compact(
        'transaction',
        'vendors',
        'existingTags',
        'currentType',
        'currentAmount'
    ));
}

    // 5. تحديث بيانات الحركة المالية
    public function update(Request $request, $id)
    {
        $companyId = session('company_id');
        $transaction = VendorTransaction::where('company_id', $companyId)->findOrFail($id);

        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'date' => 'required|date',
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $transaction, $companyId) {
            $oldVendorId = $transaction->vendor_id;

            $debit = $request->type === 'debit' ? $request->amount : 0;
            $credit = $request->type === 'credit' ? $request->amount : 0;

            $transaction->update([
                'vendor_id' => $request->vendor_id,
                'date' => $request->date,
                'debit' => $debit,
                'credit' => $credit,
                'description' => $request->description,
            ]);

            // تحديث الوسوم
            if ($request->has('tags')) {
                $tagNames = explode(',', $request->tags);
                $tagIds = [];

                foreach ($tagNames as $name) {
                    $trimmedName = trim($name);
                    if (empty($trimmedName)) continue;

                    $tag = TransactionTag::firstOrCreate([
                        'company_id' => $companyId,
                        'name' => $trimmedName
                    ]);

                    $tagIds[] = $tag->id;
                }
                $transaction->tags()->sync($tagIds);
            }

            // إعادة حساب رصيد المورد القديم والمورد الجديد (في حال تم تغييره)
            $this->updateVendorBalance($oldVendorId);
            if ($oldVendorId != $request->vendor_id) {
                $this->updateVendorBalance($request->vendor_id);
            }
        });

        return redirect()->route('transactions.index')->with('success', 'تم تحديث الحركة المادية وإعادة تسوية الأرصدة بنجاح.');
    }

    // 6. حذف الحركة المالية تماماً
    public function destroy($id)
    {
        $companyId = session('company_id');
        $transaction = VendorTransaction::where('company_id', $companyId)->findOrFail($id);
        $vendorId = $transaction->vendor_id;

        DB::transaction(function () use ($transaction, $vendorId) {
            $transaction->tags()->sync([]);
            $transaction->delete();
            $this->updateVendorBalance($vendorId);
        });

        return redirect()->route('transactions.index')->with('success', 'تم حذف الحركة المالية وتعديل رصيد الحساب بنجاح.');
    }

    // دالة داخلية مساعدة لتحديث رصيد المورد بشكل ديناميكي ثابت
    private function updateVendorBalance($vendorId)
    {
        $totals = DB::table('vendor_transactions')
            ->where('vendor_id', $vendorId)
            ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->first();

        $calculatedBalance = ($totals->total_debit ?? 0) - ($totals->total_credit ?? 0);

        DB::table('vendors')->where('id', $vendorId)->update(['balance' => $calculatedBalance]);
    }
}