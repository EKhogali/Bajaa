<?php

namespace App\Http\Controllers;

use App\company;
use App\financial_year;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $current_user = User::findOrFail(auth()->id());
//        if($current_user->type == 'admin'){
//            $companies = company::all();
//            $financial_years = financial_year::all();
//        }else{
//            $companies = company::findOrFail(auth()->user()->company_id);
//            $financial_years = financial_year::where('company_id', $companies->id)->get();
//        }
        $companies = company::all();
        $financial_years = financial_year::all();

        return view('companies.index',[
            'companies' => $companies
            ,'financial_years' => $financial_years
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('archived',0)->get();
        return view('companies.create')->with('users',$users);
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
            $msg = 'عفواً، يجب تحديد اسم الشركة قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }


        try {
            DB::beginTransaction();

            $companyId = DB::table('companies')->insertGetId([
                'name' => request('name'),
                'address' => request('address') ?? '',
                'tel' => request('tel') ?? '',
                'daily_rent_amount' => request('daily_rent_amount') ?? 0,
                'daily_salary_amount' => request('daily_salary_amount') ?? 0,
                'active' => 1,
                'user_id' => request('user_id'),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            DB::table('treasuries')->insert([
                'name' => 'خزينة: '.request('name'),
                'account_id' => 1, // request('account_id')
                'company_id' => $companyId,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            $msg = 'تمت العملية بنجاح';
            session::put('msgtype','success') ;

        } catch (\Exception $e) {
            DB::rollBack();
            $msg = 'عفواً، لا يمكن اجراء عملية الاضافة : '.$e->getMessage();
            session::put('msgtype','notsuccess') ;

//            Log::error('Error inserting data into companies and treasuries: ' . $e->getMessage());
//
//            // Handle the error, for example by returning an error response
//            return response()->json(['error' => 'Failed to insert data'], 500);
        }


        return redirect('/companies')->with('message', $msg);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\company  $company
     * @return \Illuminate\Http\Response
     */
    public function show( $company)
    {
        $company = company::find($company);
//        $financial_years = company::find(Request()->company_id)->financial_year; dd($financial_years);
        $financial_years = financial_year::where('company_id',$company->id)->get();



        return view('companies.show',[
            'company' => $company
            ,'financial_years' => $financial_years
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(company $company)
    {
        $users = User::where('archived',0)->get();
        return view('/companies.edit',[
            'company' => $company
            ,'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, company $company)
    {
        \DB::table('companies')
            ->where('id',$company->id)
            ->update([
                'name' => Request('name')
                ,'address' => Request('address') ?? ''
                ,'tel' => Request('tel') ?? ''
                ,'daily_rent_amount' => Request('daily_rent_amount') ?? 0
                ,'daily_salary_amount' => Request('daily_salary_amount') ?? 0
                ,'user_id' => Request('user_id') ?? ''
            ]);
        return redirect('/companies');
    }

    public function update_state()
    {
//        dd(Request('active'),Request('company'));
        \DB::table('companies')
            ->where('id',Request('company') )
            ->update([
                'active' => Request()->has('active')//Request('active')
            ]);

        $company = \DB::table('companies')->find(Request('company'));
        $financial_years = financial_year::where('company_id',$company->id)->get();

        return view('companies.show',[
            'company' => $company
            ,'financial_years' => $financial_years
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(company $company)
    {
        if(        \DB::table('journalms')->where('company_id',$company->id)->doesntExist()){

            \DB::table('companies')->where('id',$company->id)->delete();
            $msg = 'Success';
        }else{
            $msg = 'Success';
}

        return redirect('/companies')->with('message', $msg);
    }
}
