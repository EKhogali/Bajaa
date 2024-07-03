<?php

namespace App\Http\Controllers;

use App\account;
use App\category;
use App\sitting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SittingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sitting = sitting::findorfail(1);
        return view('sys.sitting')->with('sitting',$sitting);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\sitting  $sitting
     * @return \Illuminate\Http\Response
     */
    public function show(sitting $sitting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\sitting  $sitting
     * @return \Illuminate\Http\Response
     */
    public function edit(sitting $sitting)
    {
        $accounts = account::where('is_details',0)
            ->where('is_fixed_assets',0)
            ->where('archived',0)
            ->get();
        $categories = category::where('archived',0)
            ->get();
        return view('sys.sitting_edit')
            ->with('accounts',$accounts)
            ->with('categories',$categories)
            ->with('sitting',$sitting);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\sitting  $sitting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, sitting $sitting)
    {
        if (! Request()->has('Cashbox_Faaed_Account')  || is_null(Request('Cashbox_Faaed_Account'))){
            $msg = 'عفواً، يجب تحديد حساب فائض الخزينة قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        if (! Request()->has('Cashbox_Ajz_Account')  || is_null(Request('Cashbox_Ajz_Account'))){
            $msg = 'عفواً، يجب تحديد حساب عجز الخزينة قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        if (! Request()->has('Operation_Account')  || is_null(Request('Operation_Account'))){
            $msg = 'عفواً، يجب تحديد حساب المصروفات التشغيلية قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        if (! Request()->has('Administrative_Account')  || is_null(Request('Administrative_Account'))){
            $msg = 'عفواً، يجب تحديد حساب المصروفات الادارية قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        if (! Request()->has('Other_Incom')  || is_null(Request('Other_Incom'))){
            $msg = 'عفواً، يجب تحديد حساب المصروفات الاخرى قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        if (! Request()->has('dioon_account_category')  || is_null(Request('dioon_account_category'))){
            $msg = 'عفواً، يجب تحديد مجموعة حسابات الديون الاخرى قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        \DB::table('sittings')
            ->where('id',$sitting->id)
            ->update([
                'Other_Incom' => Request('Other_Incom'),
                'administrative_accounts_category' => Request('Administrative_Account'),
                'operation_accounts_category' => Request('Operation_Account'),
                'Cashbox_Ajz_Account' => Request('Cashbox_Ajz_Account'),
                'Cashbox_Faaed_Account' => Request('Cashbox_Faaed_Account'),
                'dioon_account_category' => Request('dioon_account_category'),
                'pulled_from_net_income_accounts_category' => Request('pulled_from_net_income_accounts_category'),
                'decimal_octets' => Request('decimal_octets'),
            ]);

        return redirect('/sitting');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\sitting  $sitting
     * @return \Illuminate\Http\Response
     */
    public function destroy(sitting $sitting)
    {
        //
    }
}
