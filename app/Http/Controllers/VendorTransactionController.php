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
    public function index(Request $request)
    {
        $companyId = session('company_id');

        $transactions = VendorTransaction::where('company_id', $companyId)
            ->when($request->filled('vendor_group_id'), function ($q) use ($request) {
                $q->whereHas('vendor', function ($v) use ($request) {
                    $v->where('vendor_group_id', $request->vendor_group_id);
                });
            })
            ->when($request->filled('vendor_id'), function ($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id);
            })
            ->with(['vendor', 'tags'])
            ->orderBy('date', 'desc')
            ->get();

        $groups = \App\VendorGroup::orderBy('name')->get();

        $vendors = Vendor::visibleTo($companyId)
            ->when($request->filled('vendor_group_id'), function ($q) use ($request) {
                $q->where('vendor_group_id', $request->vendor_group_id);
            })
            ->orderBy('name')
            ->get();

        // Can only add a transaction if there's at least one vendor (account) visible
        // to the current company under the selected classification (or at all, if none selected)
        $canAddTransaction = $vendors->isNotEmpty();

        return view('vendor_transactions.index', compact('transactions', 'groups', 'vendors', 'canAddTransaction'));
    }

    // 2. نموذج إضافة حركة جديدة
    public function create(Request $request)
    {
        $companyId = session('company_id');
        $vendors = Vendor::visibleTo($companyId)
            ->when($request->filled('vendor_group_id'), function ($q) use ($request) {
                $q->where('vendor_group_id', $request->vendor_group_id);
            })
            ->get();

        if ($vendors->isEmpty()) {
            return redirect()->route('transactions.index', $request->only('vendor_group_id'))
                ->with('error', 'عفواً، لا يوجد حسابات (موردين) مرتبطة بهذا التصنيف ضمن الشركة الحالية، لا يمكن إضافة حركة.');
        }

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
                    if (empty($trimmedName))
                        continue;

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
        $companyId = session('company_id');
        $transaction = VendorTransaction::where('company_id', $companyId)->with('tags')->findOrFail($id);
        $vendors = Vendor::visibleTo($companyId)->get();
        $existingTags = TransactionTag::where('company_id', $companyId)->get();

        $currentType = $transaction->debit > 0 ? 'debit' : 'credit';
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
                    if (empty($trimmedName))
                        continue;

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


    public function receipt($id)
    {
        $companyId = session('company_id');
        $transaction = VendorTransaction::where('company_id', $companyId)
            ->with(['vendor', 'tags'])
            ->findOrFail($id);

        return view('vendor_transactions.receipt', compact('transaction'));
    }



}