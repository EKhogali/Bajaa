<?php

namespace App\Http\Controllers;

use App\company;
use App\treasury_transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('/sys.users.index')->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = company::all();
        return view('/sys.users.create')->with('companies',$companies);
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
            $msg = 'عفواً، يجب تحديد اسم المستخدم قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }
        if (! Request()->has('company_id')  || is_null(Request('company_id'))){
            $msg = 'عفواً، يجب تحديد الشركة قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }
        if (! Request()->has('type')  || is_null(Request('type'))){
            $msg = 'عفواً، يجب تحديد الصفة قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }
        if (! Request()->has('email')  || is_null(Request('email'))){
            $msg = 'عفواً، يجب تحديد البريد الالكتروني قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }
        if (! Request()->has('password')  || is_null(Request('password'))){
            $msg = 'عفواً، يجب تحديد كلمة المرور قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }



        \DB::table('users')->insert([
            'name' => Request('name')
            ,'company_id' => Request('company_id')
            ,'type' => Request('type')
            ,'email' => Request('email')
            ,'password' => Request('password')
            ,'created_by' => auth()->id()
            ,'updated_by' => auth()->id()
        ]);
        return redirect('/users');
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
        $user = User::findorfail($id);
        $companies = company::all();
        return view('/sys.users.edit')
            ->with('user',$user)
            ->with('companies',$companies);
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
            $msg = 'عفواً، يجب تحديد اسم المستخدم قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }
        if (! Request()->has('company_id')  || is_null(Request('company_id'))){
            $msg = 'عفواً، يجب تحديد الشركة قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }
        if (! Request()->has('type')  || is_null(Request('type'))){
            $msg = 'عفواً، يجب تحديد الصفة قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }
        if (! Request()->has('email')  || is_null(Request('email'))){
            $msg = 'عفواً، يجب تحديد البريد الالكتروني قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }


        \DB::table('users')
            ->where('id',$id)
            ->update([
            'name' => Request('name')
            ,'company_id' => Request('company_id')
            ,'type' => Request('type')
            ,'email' => Request('email')
            ,'updated_by' => auth()->id()
        ]);
        return redirect('/users');
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
