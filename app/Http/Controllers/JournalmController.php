<?php

namespace App\Http\Controllers;

use App\account;
use App\journalm;
use App\journald;
use App\Sittings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class JournalmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = account::where('active',1)->get();
        $journals = journalm::
        where('company_id',session::get('company_id' ?? 0))
        ->where('financial_year',session::get('financial_year' ?? 0))
            ->orderby('id','desc')->get();

//        $journals = journalm::where('company_id',1)->orderby('id','desc')->get();
//        dd($accounts);
        return view('journals.index',compact('accounts'))
            ->with('journals',$journals);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = account::where('active',1)->get();
        return view('journals.create',compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        DB::table('journalms')->insert([
            'company_id' => session::get('company_id')
            ,'financial_year' => session::get('financial_year')//\Carbon\Carbon::parse(Request()->date)->format('Y')
            ,'date' => \Carbon\Carbon::now()//Request()->date
            ,'code' => '2' //Request()->code
            ,'description' => Request()->description
        ]);
//dd(DB::getPdo()->lastInsertId());
        return redirect('/journals');
    }



    public function storeall(Request $request)
    {
        DB::beginTransaction();
        try {

            DB::table('journalms')->insert([
                'company_id' => 1//Request()->date
                ,'financial_year' => 1//Request()->date
                ,'date' => Request()->date
                ,'code' => Request()->code
                ,'description' => Request()->description
            ]);


//            foreach ($condition as $key => $condition) {
//                $detailorder = new DetailOrder;
//
//                //you can use to ignore $key::=> $detailorder->serivce_id = $condition['service_id'];
//
//                $detailorder->serivce_id = $input['service_id'][$key];
//                $detailorder->order_type = $input['order_type'][$key];
//                $detailorder->select_plan = $input['select_plan'][$key];
//                $detailorder->qty = $input['qty'][$key];
//                $detailorder->unit_price = $input['unit_price'][$key];
//                //$detailorder->mandays = $input['mandays'][$key];
//                $detailorder->note = $input['note'][$key];
//                $allOrders[]=$detailorder;
//            }

            DB::commit();
        } catch (\exception $e){
            DB::rollBack();
        }


        return redirect('/journals');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\journalm  $journalm
     * @return \Illuminate\Http\Response
     */
    public function show($journalm)
    {
//        $j = DB::table('journalds')->find($journalm);
        $j = journalm::find($journalm);
//        dd('777889');
//        $journalds = journald::where('journalm_id',$journalm)->get();
        $journalds = journald::where('journalm_id',$journalm)->get();
//        dd($j);
        return view('journals.show')->with('journalm',$j)->with('journalds',$journalds);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\journalm  $journalm
     * @return \Illuminate\Http\Response
     */
    public function edit( $journalmid)
    {
        $journalm = journalm::find($journalmid);
        return view('journals.edit',['journalm'=>$journalm]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\journalm  $journalm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $journalm)
    {
//        dd($request);
        DB::table('journalms')
            ->where('id', $journalm)
            ->update([
                'date' => Request()->date
                ,'code' => Request()->code
                ,'description'=> Request()->description
            ]);
        return redirect('/journals');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\journalm  $journalm
     * @return \Illuminate\Http\Response
     */
    public function destroy( $journalm)
    {
        DB::table('journalds')
            ->where('journalm_id',$journalm)
            ->delete();
        DB::table('journalms')
            ->where('id',$journalm)
            ->delete();
        $accounts = account::where('active',1)->get();
        $journals = journalm::orderby('id','desc')->get();

        return view('journals.index',compact('accounts'))
            ->with('journals',$journals);
    }
}
