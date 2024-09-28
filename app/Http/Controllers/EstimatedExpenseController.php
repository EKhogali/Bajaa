<?php

namespace App\Http\Controllers;

use App\account;
use App\estimated_expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class EstimatedExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaction_type_id = 1; //Request('trans_type');

        $estimated_expenses = estimated_expense::where('company_id',session::get('company_id'))
            ->where('financial_year',session::get('financial_year'))
            ->where('archived',0)
            ->orderBy('date', 'desc')
            ->get();

        return view('trans.estimated_expense.index')
            ->with('transaction_type_id',$transaction_type_id)
            ->with('estimated_expenses',$estimated_expenses)
            ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = account::where('archived',0)
            ->where('is_details',0)
            ->get();


        return view('trans.estimated_expense.create')
            ->with('transaction_type_id',request('transaction_type_id'))
            ->with('accounts',$accounts)
            ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string|max:255',
        ]);


        \DB::table('estimated_expenses')->insert([

            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),

            'transaction_type_id' => request('transaction_type_id'),
            'date' => request('date'),
            'account_id' => request('account_id'),
            'amount' => request('amount'),
            'description' => request('description'),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'created_at' => Carbon::today(),
            'updated_at' => Carbon::today(),
        ]);


        return redirect()->route('estimated_expense.index',['transaction_type_id'=>Request('transaction_type_id')]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\estimated_expense  $estimated_expense
     * @return \Illuminate\Http\Response
     */
    public function show(estimated_expense $estimated_expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\estimated_expense  $estimated_expense
     * @return \Illuminate\Http\Response
     */
    public function edit(estimated_expense $estimated_expense)
    {
        $accounts = account::where('is_details',0)
            ->where('archived',0)
            ->get();
        return view('trans.estimated_expense.edit',)
            ->with('estimated_expenses',$estimated_expense)
            ->with('transaction_type_id',request('transaction_type_id'))
            ->with('accounts',$accounts);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\estimated_expense  $estimated_expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, estimated_expense $estimated_expense)
    {
        if (! Request()->has('date')  || is_null(Request('date'))){
            $msg = 'عفواً، يجب تحديد التاريخ قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }
        if (! Request()->has('account_id')  || is_null(Request('account_id'))){
            $msg = 'عفواً، يجب تحديد الحساب قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }
//        }
        if (! Request()->has('amount')  || is_null(Request('amount'))) {
            $msg = 'عفواً، يجب تحديد القيمة قبل المتابعة في العملية';
            session::put('msgtype', 'notsuccess');
            return back()->with('message', $msg);
        }

        \DB::table('estimated_expenses')
            ->where('id',$estimated_expense->id)
            ->update([
                'date' => Request('date')
                ,'account_id' => Request('account_id')
                ,'description' => Request('description')
                ,'amount' => Request('amount')
                ,'updated_by' => auth()->id()
            ]);
        return redirect()->route('estimated_expense.index',['transaction_type_id'=>Request('transaction_type_id')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\estimated_expense  $estimated_expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(estimated_expense $estimated_expense)
    {
        DB::beginTransaction();

        try {

            DB::table('estimated_expenses')->where('id',$estimated_expense->id)->delete();
            $msg = 'تمت العملية بنجاح';
            session::put('msgtype','success') ;
            DB::commit();
            return back()->with('message', $msg);


        } catch (\Exception $e) {
            DB::rollBack();
//                return back()->with('message', 'لا يمكن اجراء العملية لوجود مشكلة ما، يُرجى معاودة المحاولة في وقت لاحق');
            return back()->with('message', $e);

        }
    }
}
