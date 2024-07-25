<?php

namespace App\Http\Controllers;

use App\account;
use App\financial_year;
use App\treasury;
use App\treasury_transaction;
use App\treasury_transaction_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TreasuryTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trans_type = Request('trans_type');

        $treasury_transaction =treasury_transaction::where('transaction_type_id',$trans_type)
            ->where('company_id',session::get('company_id'))
            ->where('financial_year',session::get('financial_year'))
            ->where('archived',0)
            ->orderBy('date', 'desc')
            ->get();

        return view('trans.treasury_transaction.index')
            ->with('trans_type',$trans_type)
            ->with('treasury_transaction',$treasury_transaction)
            ;
    }


    public function print($id)
    {
        $treasury_transaction = treasury_transaction::with('account', 'treasury')->findOrFail($id);
//        dd('55egeeg',$treasury_transaction);
        return view('trans.treasury_transaction.print', compact('treasury_transaction'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = account::where('is_details',0)
            ->where('archived',0)
            ->where('is_details',0)
            ->get();
//        $treasuries = treasury::where('company_id',session::get('company_id'))
//            ->where('archived',0)
//            ->get();


        return view('trans.treasury_transaction.create')
            ->with('trans_type',request('trans_type'))
            ->with('accounts',$accounts)
//            ->with('treasuries',$treasuries)
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
// Validate incoming request
        $validatedData = $request->validate([
            'manual_no' => 'nullable|string|max:255',
            'date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
//            'treasury_id' => 'required|exists:treasuries,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string|max:255',
        ]);



        $last_trans = treasury_transaction::where('transaction_type_id',request('trans_type'))
            ->where('company_id',session::get('company_id'))
            ->where('financial_year',session::get('financial_year'))->max('company_serial') ?? 0;
        $last_trans ++;

//        $financial_year = financial_year::where('id',auth()->user()->curent_financial_year_id)->first();

        \DB::table('treasury_transactions')->insert([

            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),

            'company_serial' => $last_trans,
            'manual_no' => session::get('company_id').''.$last_trans,

            'transaction_type_id' => request('trans_type'),
            'date' => request('date'),
            'account_id' => request('account_id'),
            'treasury_id' => 1,
            'amount' => request('amount'),
            'description' => request('description'),
            'tag_id' => request()->has('tag_id') ? request('tag_id') : 0,
            'client_id' => 0,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);


        return redirect()->route('treasury_transactions.index',['trans_type'=>Request('trans_type')]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $treasury_transaction = treasury_transaction::findorfail($id);
        $treasury_transaction_details = treasury_transaction_detail::where('master_id',$id)->get();//dd($id,$treasury_transaction,$treasury_transaction_details);
        return view('trans.treasury_transaction.show')
            ->with('treasury_transaction',$treasury_transaction)
            ->with('treasury_transaction_details',$treasury_transaction_details);
    }
    public function show_in($id)
    {
        $treasury_transaction = treasury_transaction::findorfail($id);
        return view('trans.treasury_transaction.show_in')
            ->with('treasury_transaction',$treasury_transaction);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accounts = account::where('is_details',0)
            ->where('archived',0)
            ->get();
//        $treasuries = treasury::where('company_id',session::get('company_id'))
//            ->where('archived',0)
//            ->get();
        $treasury_transaction = treasury_transaction::findorfail($id);
        return view('trans.treasury_transaction.edit',compact('treasury_transaction',$treasury_transaction))
            ->with('trans_type',request('trans_type'))
            ->with('accounts',$accounts);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
//        if (! Request()->has('treasury_id')  || is_null(Request('treasury_id'))) {
//            $msg = 'عفواً، يجب تحديد الخزينة قبل المتابعة في العملية';
//            session::put('msgtype', 'notsuccess');
//            return back()->with('message', $msg);
//        }
        if (! Request()->has('amount')  || is_null(Request('amount'))) {
            $msg = 'عفواً، يجب تحديد القيمة قبل المتابعة في العملية';
            session::put('msgtype', 'notsuccess');
            return back()->with('message', $msg);
        }

        \DB::table('treasury_transactions')
            ->where('id',$id)
            ->update([
                'date' => Request('date')
//                ,'manual_no' => Request('manual_no')
                ,'account_id' => Request('account_id')
//                ,'treasury_id' => Request('treasury_id')
                ,'description' => Request('description')
                ,'amount' => Request('amount')
                ,'tag_id' => request()->has('tag_id') ? request('tag_id') : 0
                ,'updated_by' => auth()->id()
            ]);
        return redirect()->route('treasury_transactions.index',['trans_type'=>Request('trans_type')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            if(
                DB::table('treasury_transaction_details')->where('master_id',$id)->select('*')->doesntExist()
                ){
                    DB::table('treasury_transactions')->where('id',$id)->delete();
                    $msg = 'تمت العملية بنجاح';
                    session::put('msgtype','success') ;
                    DB::commit();
                    return back()->with('message', $msg);
                }else {
                    $msg = 'عفواً، لا يمكن اجراء عملية الالغاء لارتباط العنصر ببيانات اخرى';
                    session::put('msgtype','notsuccess') ;
                    DB::rollBack();
                    return back()->with('message', $msg);
                }

            } catch (\Exception $e) {
                DB::rollBack();
//                return back()->with('message', 'لا يمكن اجراء العملية لوجود مشكلة ما، يُرجى معاودة المحاولة في وقت لاحق');
                return back()->with('message', $e);

        }
    }
}
