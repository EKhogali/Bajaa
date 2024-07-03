<?php

namespace App\Http\Controllers;


use App\company;
use App\financial_year;
use App\treasury;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class homeController extends Controller
{


    public function __construct(){
        $this->middleware('auth');

        session()->put('success','تمت العملية بنجاح!');
        session()->put('notsuccess_pre','عفواً، لا يمكن اجراء عملية الغاء ');
        session()->put('notsuccess_after',' لارتباطه ببعض البيانات الأخرى');
//        \session(['company_id'=>1]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->type == 3){ //admin
            $companies = company::where('archived',0)->get();
            $financial_years = financial_year::where('archived',0)
                ->get();
        }else{
            $companies = company::where('user_id',auth()->id())
                ->where('archived',0)
                ->get();
            $financial_years = financial_year::where('company_id', $companies->first()->id)//$companies->pluck('id')->toarray()
                ->where('archived',0)
                ->get();
        }

        $companyRec = $companies->first();
        $financial_yearRec = $financial_years->first();
        session::put('company_name',$companyRec->name);
        session::put('financial_year',$financial_yearRec->year);

        return view('home/home',[
            'companyRec' => $companyRec
            ,'financial_yearRec' => $financial_yearRec
            ,'companies' => $companies
            ,'financial_years' => $financial_years
        ]);
    }

    public function company_and_financial_year(){
        session::put('company_id',Request()->company_id);
        session::put('financial_year_id',Request()->financial_year_id);


        $companyRec = \DB::table('companies')->find(session::get('company_id'));
        $financial_yearRec = \DB::table('financial_years')->find(session::get('financial_year_id'));
        session::put('company_name',$companyRec->name);
        session::put('financial_year',$financial_yearRec->financial_year);

        if(auth()->user()->type == 3){ //admin
            $companies = company::where('archived',0)->get();
            $financial_years = financial_year::where('archived',0)
                ->get();
        }else{
            $companies = company::where('user_id',auth()->id())
                ->where('archived',0)
                ->get();
            $financial_years = financial_year::where('company_id', Request()->company_id)
                ->where('archived',0)
                ->get();
        }
//
//        $current_treasury_id = treasury::where('company_id',$companies[0]->id)->first();
//        DB::table('users')->where('id',auth()->id())->update([
//            'current_company_id'=> $financial_years[0]->id
//            ,'current_treasury_id'=> $current_treasury_id
//            ,'current_financial_year_id'=> $financial_years[0]->year
//        ]);

        $fy_startdate = Carbon::createFromDate($financial_yearRec->financial_year,1,1)->format('Y-m-d');
        $fy_enddate = Carbon::createFromDate($financial_yearRec->financial_year,12,31)->format('Y-m-d');
        session::put('fy_startdate',$fy_startdate);
        session::put('fy_enddate',$fy_enddate);
        session::put('current_year',$financial_yearRec->financial_year);


        return view('home/home',[
            'companyRec' => $companyRec
            ,'financial_yearRec' => $financial_yearRec
            ,'company_name' => $companyRec->name
            ,'financial_year' => $financial_yearRec->financial_year
            ,'companies' => $companies
            ,'financial_years' => $financial_years
            ,'fy_startdate' => $fy_startdate
            ,'fy_enddate' => $fy_enddate
        ]);
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
        //
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
        //
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
