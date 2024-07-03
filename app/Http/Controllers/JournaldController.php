<?php

namespace App\Http\Controllers;

use App\account;
use App\journald;
use App\journalm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournaldController extends Controller
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
        $accounts = account::all();
        return view('journald.create')
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

        DB::table('journalds')->insert([
           'journalm_id' => Request()->journalm_id
           ,'account_id' => Request()->account_id
           ,'credit_amount' => Request()->credit_amount ?: 0 //if null then zero
           ,'debit_amount' => Request()->debit_amount ?: 0 //if null then zero
           ,'description' => Request()->description ?? ''
        ]);
//        sleep(1);

        $this->updateJournalTotals(Request()->journalm_id);
        $journalms = journalm::find(Request()->journalm_id);



        $journalds = journald::where('journalm_id',Request()->journalm_id)->get();

        return view('journals.show')->with('journalm',$journalms)->with('journalds',$journalds);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\journald  $journald
     * @return \Illuminate\Http\Response
     */
    public function show(journald $journald)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\journald  $journald
     * @return \Illuminate\Http\Response
     */
    public function edit($journald)
    {
        $accounts = account::all();
        $journald = journald::find($journald);
        return view('journald.edit',['journald'=>$journald,'accounts'=>$accounts]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\journald  $journald
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, journald $journald)
    {
//        dd($journald);
        DB::table('journalds')
            ->where('id', $journald->id)
            ->update([
                'account_id' => Request()->account_id
                ,'credit_amount' => Request()->credit_amount ?? 0
                ,'debit_amount'=> Request()->debit_amount ?? 0
                ,'description'=> Request()->description ?? ''
            ]);
//
        $this->updateJournalTotals($journald->journalm_id);

        $j = journalm::find($journald->journalm_id);
        $journalds = journald::where('journalm_id',$j->id)->get();
        return view('journals.show')->with('journalm',$j)->with('journalds',$journalds);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\journald  $journald
     * @return \Illuminate\Http\Response
     */
    public function destroy(journald $journald)
    {

        DB::table('journalds')
            ->where('id',$journald->id)
            ->delete();


        $this->updateJournalTotals($journald->journalm_id);
        $j = journalm::find($journald->journalm_id);
        $journalds = journald::where('journalm_id',$j->id)->get();



        return view('journals.show')->with('journalm',$j)->with('journalds',$journalds);
    }




    public function updateJournalTotals($id){

        $total_creditor = DB::table('journalds')
            ->where('journalm_id', $id)
            ->sum('credit_amount');

        $total_debtor = DB::table('journalds')
            ->where('journalm_id', $id)
            ->sum('debit_amount');

        $balanaced = ($total_creditor == $total_debtor) && ($total_debtor != 0);

        DB::table('journalms')
            ->where('id', $id)
            ->update([
                'total_creditor' => $total_creditor
                ,'total_debtor' => $total_debtor
                ,'balanced'=> $balanaced
            ]);
    }
}
