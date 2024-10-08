<?php

namespace App\Http\Controllers;

use App\account;
use App\treasury_transaction;
use App\treasury_transaction_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class TreasuryTransactionDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = account::where('is_details',1)
            ->get();
        return view('trans.treasury_transaction.treasury_transaction_details.create')
            ->with('master_id',Request('master_id'))
            ->with('accounts',$accounts);
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
            'master_id' => 'required|exists:treasury_transactions,id',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric',
            'qty' => 'required|numeric',
        ]);

        $master_amount = DB::table('treasury_transactions')
            ->where('id',Request('master_id'))
            ->sum('amount');
        $details_totals = DB::table('treasury_transaction_details')
            ->where('master_id',Request('master_id'))
            ->sum('amount');
        if(($details_totals + Request('amount') ?? 0) > $master_amount){

            $msg = 'عفواً، لا يمكن حفظ القيمة الجديدة لتجاوزها قيمة اجمالي الايصال';
            session::put('msgtype','notsuccess') ;
            session::put('message',$msg) ;

            return back()->with('message',$msg);
        }else{

        \DB::table('treasury_transaction_details')->insert([

            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),

            'master_id'=>Request('master_id'),
            'account_id'=>Request('account_id'),
            'amount'=>Request('amount') ?? 0,
            'qty'=>Request('qty') ?? 0,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $treasury_transaction = treasury_transaction::findorfail(Request('master_id'));
        return redirect()->route('treasury_transaction.show',['treasury_transaction'=>$treasury_transaction]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\treasury_transaction_detail  $treasury_transaction_detail
     * @return \Illuminate\Http\Response
     */
    public function show(treasury_transaction_detail $treasury_transaction_detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\treasury_transaction_detail  $treasury_transaction_detail
     * @return \Illuminate\Http\Response
     */
    public function edit(treasury_transaction_detail $treasury_transaction_detail)
    {
        $accounts = account::where('is_details',1)
            ->get();
        return view('trans.treasury_transaction.treasury_transaction_details.edit')
//            ->with('master_id',Request('master_id'))
            ->with('treasury_transaction_detail',$treasury_transaction_detail)
            ->with('accounts',$accounts);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\treasury_transaction_detail  $treasury_transaction_detail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, treasury_transaction_detail $treasury_transaction_detail)
    {
        if (! Request()->has('account_id')  || is_null(Request('account_id'))){
            $msg = 'عفواً، يجب تحديد اسم الحساب قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }
        if (! Request()->has('amount')  || is_null(Request('amount'))){
            $msg = 'عفواً، يجب تحديد القيمة قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }
        if (! Request()->has('qty')  || is_null(Request('qty'))){
            $msg = 'عفواً، يجب تحديد الكمية قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        \DB::table('treasury_transaction_details')
            ->where('id',$treasury_transaction_detail->id)
            ->update([
                'account_id'=>Request('account_id'),
                'amount'=>Request('amount') ?? 0,
                'qty'=>Request('qty') ?? 0,
                'updated_by' => auth()->id(),
            ]);

//        return redirect('/accounts');
        return redirect()->route('treasury_transaction.show', Request('master_id'));
    }

    public function print($id)
    {
        $treasury_transaction_details = treasury_transaction_detail::where('master_id',$id)->get();
        $treasury_transactions = treasury_transaction::findorfail($id);

//        dd('55egeeg',$treasury_transaction_details);
        return view('trans.treasury_transaction.treasury_transaction_details.print', compact('treasury_transaction_details'))
            ->with('treasury_transactions',$treasury_transactions);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\treasury_transaction_detail  $treasury_transaction_detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(treasury_transaction_detail $treasury_transaction_detail)
    {

        DB::beginTransaction();

        try {
            $treasury_transaction_detail = treasury_transaction_detail::findOrFail($treasury_transaction_detail->id);
            $treasury_transaction_detail->delete();
            DB::commit();
            $msg = 'تمت العملية بنجاح';
            session::put('msgtype','success');
            return back()->with('message', $msg);//->with('success', 'Model deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

//            Log::error('Error deleting model: ' . $e->getMessage());

            // Return an error message
            return back()->with('message', $msg);

        }
    }
}
