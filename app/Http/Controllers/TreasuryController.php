<?php

namespace App\Http\Controllers;

use App\account;
use App\treasury;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TreasuryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $treasuries = treasury::where('company_id',session::get('company_id'))->get();
        return view('bsc.treasuries.index')
            ->with('treasuries',$treasuries);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $treasury_accounts = account::where('category_id','2')
//            ->wherenotin('id',function ($query){
//                $query->select('account_id')->from('treasuries');
//            })
//            ->get();
        $treasury_accounts = account::where('id',1)->get();
        return view('bsc.treasuries.create')->with('treasury_accounts',$treasury_accounts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (! Request()->has('name')  || is_null(Request('name'))){
            $msg = 'عفواً، يجب تحديد اسم الخزينة قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }
        if (! Request()->has('account_id')  || is_null(Request('account_id'))){
            $msg = 'عفواً، يجب تحديد الحساب قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        \DB::table('treasuries')->insert([
            'name' => Request('name')
            ,'account_id' => 1//Request('account_id')
            ,'company_id' => session::get('company_id')
            ,'created_by' => auth()->id()
            ,'updated_by' => auth()->id()
        ]);
        return redirect('/treasuries');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $treasury = Treasury::findOrFail($id);

        $treasury_accounts = Account::where('category_id', '2')
            ->whereNotIn('id', function ($query) use ($treasury) {
                $query->select('account_id')->from('treasuries')->where('id', $treasury->account_id);
            })
            ->get();
        return view('bsc.treasuries.edit')
            ->with('treasury',$treasury)
            ->with('treasury_accounts',$treasury_accounts);
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

        if (! Request()->has('name')  || is_null(Request('name'))){
            $msg = 'عفواً، يجب تحديد اسم الخزينة قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }
        if (! Request()->has('account_id')  || is_null(Request('account_id'))) {
            $msg = 'عفواً، يجب تحديد الحساب قبل المتابعة في العملية';
            session::put('msgtype', 'notsuccess');
            return back()->with('message', $msg);
        }

        \DB::table('treasuries')
            ->where('id',$id)
            ->update([
                'name' => Request('name')
                ,'account_id' => 1//Request('account_id')
                ,'updated_by' => auth()->id()
            ]);
        return redirect('/treasuries');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
