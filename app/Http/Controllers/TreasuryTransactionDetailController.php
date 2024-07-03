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
        $accounts = account::where('company_id',session::get('company_id'))
            ->where('is_details',1)
            ->get();
        return view('\trans.treasury_transaction.treasury_transaction_details.create')
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

//        return back();
        $treasury_transaction = treasury_transaction::findorfail(Request('master_id'));
//        dd($treasury_transaction);
        return redirect()->route('treasury_transaction.show',['treasury_transaction'=>$treasury_transaction]);
//        return view('trans.treasury_transaction.show', compact($request->input('master_id')));
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
        $accounts = account::where('company_id',session::get('company_id'))
            ->where('is_details',1)
            ->get();
        return view('\trans.treasury_transaction.treasury_transaction_details.edit')
//            ->with('master_id',Request('master_id'))
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
        //
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
