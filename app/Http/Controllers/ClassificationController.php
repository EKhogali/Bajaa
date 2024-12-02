<?php

namespace App\Http\Controllers;

use App\Classification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classifications = Classification::all();
        return view('classifications.index')
            ->with('classifications',$classifications);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('classifications.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \DB::table('classifications')->insert([
            'name'=>Request('name') ?? 0
            ,'archived'=> 0
            ,'show_in_daily_report'=> Request()->has('show_in_daily_report'),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);
        return redirect('/classifications');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Classification  $classification
     * @return \Illuminate\Http\Response
     */
    public function show(Classification $classification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Classification  $classification
     * @return \Illuminate\Http\Response
     */
    public function edit(Classification $classification)
    {
        return view('classifications.edit')->with('classifications',$classification);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Classification  $classification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Classification $classification)
    {
        if (! Request()->has('name')  || is_null(Request('name'))) {
            $msg = 'عفواً، يجب ادخال اسم التصنيف قبل المتابعة في العملية';
            session::put('msgtype', 'notsuccess');
            return back()->with('message', $msg);
        }

        \DB::table('classifications')
            ->where('id',$classification->id)
            ->update([
                'name' => Request('name')
                ,'archived'=> Request()->has('archived')
                ,'show_in_daily_report'=> Request()->has('show_in_daily_report')
                ,'updated_by' => auth()->id()
            ]);
        return redirect('/classifications');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Classification  $classification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classification $classification)
    {
        if( \DB::table('accounts')
                ->where('classification_id',$classification->id)
                ->doesntExist()
        ){
            \DB::table('classifications')->where('id',$classification->id)->delete();
            $msg = 'تمت العملية بنجاح';
            session::put('msgtype','success') ;
        }
        else {
            $msg = 'عفواً، لا يمكن اجراء عملية الالغاء لارتباط العنصر ببيانات اخرى';
            session::put('msgtype','notsuccess') ;
        }
        return redirect('/classifications');
    }
}
