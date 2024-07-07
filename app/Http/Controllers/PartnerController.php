<?php

namespace App\Http\Controllers;

use App\account;
use App\category;
use App\company;
use App\financial_year;
use App\partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partners = partner::where('company_id',session::get('company_id'))
            ->where('archived',0)
            ->get();

        return view('bsc.partners.index',[
            'partners' => $partners
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = account::where('archived',0)
            ->where('is_details','<>',1)
            ->where('is_fixed_assets','<>',1)
            ->get();
        return view('bsc.partners.create',compact('accounts'));
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
            $msg = 'عفواً، يجب تحديد اسم الشريك قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        if (! Request()->has('account_id')  || is_null(Request('account_id'))){
            $msg = 'عفواً، يجب تحديد الحساب قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        if (! Request()->has('partnership_type')  || is_null(Request('partnership_type'))){
            $msg = 'عفواً، يجب تحديد نوع الشراكة قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        if (! Request()->has('win_percentage')  || is_null(Request('win_percentage'))){
            $msg = 'عفواً، يجب تحديد نسبة الشراكة قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }


        \DB::table('partners')->insert([
            'name' => Request('name'),
            'partnership_type' => Request('partnership_type'),
            'company_id' => session::get('company_id'),
            'account_id' => Request('account_id'),
            'win_percentage' => Request('win_percentage'),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect('/partners');
//        redirect()->back()->with('success', 'PartnerSeeder added successfully!');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show(partner $partner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function edit(partner $partner)
    {
        $accounts = account::where('is_details','<>',1)
            ->where('is_fixed_assets','<>',1)
            ->where('archived',0)->get();
        return view('bsc.partners.edit')
            ->with('partner',$partner)
            ->with('accounts',$accounts)
            ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, partner $partner)
    {

        if (! Request()->has('name')  || is_null(Request('name'))){
            $msg = 'عفواً، يجب تحديد اسم الشريك قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        if (! Request()->has('account_id')  || is_null(Request('account_id'))){
            $msg = 'عفواً، يجب تحديد الحساب قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        if (! Request()->has('partnership_type')  || is_null(Request('partnership_type'))){
            $msg = 'عفواً، يجب تحديد نوع الشراكة قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        if (! Request()->has('win_percentage')  || is_null(Request('win_percentage'))){
            $msg = 'عفواً، يجب تحديد نسبة الشراكة قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        \DB::table('partners')
            ->where('id',$partner->id)
            ->update([
                'name' => Request('name'),
                'partnership_type' => Request('partnership_type'),
                'account_id' => Request('account_id'),
                'win_percentage' => Request('win_percentage'),
                'updated_by' => auth()->id(),
            ]);

        return redirect('/partners');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(partner $partner)
    {
        if(        \DB::table('partners')->where('id',$partner->id)->doesntExist()){

            \DB::table('partners')->where('id',$partner->id)->delete();
            $msg = 'Success';
        }else{
            $msg = 'Success';
        }

        return redirect('/companies')->with('message', $msg);
    }
}
