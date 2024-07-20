<?php

namespace App\Http\Controllers;

use App\company;
use App\financial_year;
use Illuminate\Http\Request;
use App\DB;
use Illuminate\View\View;
use phpDocumentor\Reflection\DocBlock\Tags\Version;

class FinancialYearController extends Controller
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
       return \view('financial_years.create',[
           'company_id'=>Request()->company_id
       ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd(Request()->company_id);
        \DB::table('financial_years')->insert([
            'financial_year'=>Request()->financial_year ?? 0
            ,'company_id'=>Request()->company_id
            ,'state_id'=> 0
        ]);

        $company = company::find(Request()->company_id);
        $financial_years = financial_year::where('company_id',$company->id)->get();
        created_by = auth()->id();
            updated_by = auth()->id();


        return view('companies.show',[
            'company' => $company
            ,'financial_years' => $financial_years
            ,'company_id'=>Request()->company_id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\financial_year  $financial_year
     * @return \Illuminate\Http\Response
     */
    public function show(financial_year $financial_year)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\financial_year  $financial_year
     * @return \Illuminate\Http\Response
     */
    public function edit(financial_year $financial_year)
    {dd($financial_year);
        return view('/financial_years.edit',[
            'financial_year' => $financial_year
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\financial_year  $financial_year
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, financial_year $financial_year)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\financial_year  $financial_year
     * @return \Illuminate\Http\Response
     */
    public function destroy(financial_year $financial_year)
    {


        \DB::table('financial_years')
            ->where('id',$financial_year->id)
            ->delete();

//        try {
//            \DB::table('financial_years')
//                ->where('id',$financial_year->id)
//                ->delete();
//        }
//        catch (\Illuminate\Database\QueryException $e) {
//            if ($e->getCode() == 23000)
//            {
//                //SQLSTATE[23000]: Integrity constraint violation
//                abort('Resource cannot be deleted due to existence of related resources.');
//            }
//        }
//
//        Session::flash('success', 'Grade Level deleted successfully.');


        $company = company::find($financial_year->company_id);
        $financial_years = financial_year::where('company_id',$financial_year->company_id)->get();

        return view('companies.show',[
            'company' => $company
            ,'financial_years' => $financial_years
            ,'company_id'=>Request()->company_id
        ]);
    }
}
