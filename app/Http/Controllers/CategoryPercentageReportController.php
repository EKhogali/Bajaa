<?php

namespace App\Http\Controllers;

use App\account;
use App\Category_Percentage_Report;
use App\income_report;
use App\sitting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CategoryPercentageReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function category_percentage_report(){
        if(!request()->has('ch') ){

            DB::table('income_reports')
                ->where('created_by',auth()->id())
                ->where('company_id',session::get('company_id'))
                ->where('financial_year',session::get('financial_year'))
                ->delete();
            $category_percentage_report = Category_Percentage_Report::where('id',1);
            $decimal_octets = sitting::where('id',1)->value('decimal_octets');

            return view('rep.category_percentage_report')
                ->with('decimal_octets',$decimal_octets)
                ->with('reports',$category_percentage_report);
        }else {

            //--------------------------------------------------------------------------------------------------------------
            $fromdate = \Carbon\Carbon::parse(Request('fromdate'))->startOfDay();
            $todate = \Carbon\Carbon::parse(Request('todate'))->endOfDay();
            $companyId = session::get('company_id');
            $financialYear = session::get('financial_year');
            $decimal_octets = sitting::where('id',1)->value('decimal_octets');


            //--------------------------------------------------------------------------------------------------------------
            //**************************************************************************************************************
            // ----------------- All Sittings Accounts

            $operation_expenses_category = sitting::where('id',1)->value('operation_accounts_category');
            $operation_expenses_accounts_array = DB::table('accounts')
                ->where('category_id',$operation_expenses_category)
                ->where('archived',0)
                ->pluck('id')
                ->toArray();


            $admin_expenses_category = sitting::where('id',1)->value('administrative_accounts_category');
            $admin_expenses_accounts_array = DB::table('accounts')
                ->where('category_id',$admin_expenses_category)
                ->where('archived',0)
                ->pluck('id')
                ->toArray();

            $decimal_octets = sitting::where('id',1)->value('decimal_octets');
            $Sales_Accounts_category = sitting::where('id',1)->value('Sales_Accounts_category');

            $cashbox_faaed_account = sitting::where('id',1)->value('Cashbox_Faaed_Account');
            $cashbox_ajz_account = sitting::where('id',1)->value('Cashbox_Ajz_Account');


            $dioon_account_category = sitting::find(1)->value('dioon_account_category');
            $dioon_accounts_array = account::where('category_id',$dioon_account_category)
                ->where('company_id',session::get('company_id'))
                ->where('archived',0)
                ->pluck('id')
                ->toarray();

            $dioon_account_category = sitting::find(1)->value('dioon_account_category');
            $dioon_expenses_accounts_array = DB::table('accounts')
                ->where('category_id',$dioon_account_category)
                ->pluck('id')
                ->toArray();

            $otherincome_category = sitting::find(1)->value('Other_Incom');
            $otherincome_accounts_array = DB::table('accounts')
                ->where('category_id',$otherincome_category)
                ->pluck('id')
                ->toArray();

            $pulled_from_net_income_accounts_category = sitting::find(1)->value('pulled_from_net_income_accounts_category');
            $pulled_from_net_income_accounts_array = DB::table('accounts')
                ->where('category_id',$pulled_from_net_income_accounts_category)
                ->pluck('id')
                ->toArray();
            //**************************************************************************************************************
            //-------------------------------------------------------------------------------------------------------------


            //-------------------------------------------------------------------------------------------------------------
            // عدد الايام للفترة
            $days = DB::table('treasury_transactions')
                ->where('company_id', $companyId)
                ->where('financial_year', $financialYear)
                ->where('archived', 0)
                ->whereBetween('date', [$fromdate, $todate])
                ->select(
                    DB::raw('COUNT(DISTINCT DATE(date)) as distinct_days_count')
//                DB::raw('sum(DISTINCT DATE(date)) as distinct_days_count'),
                )
                ->value('distinct_days_count');

            //-------------------------------------------------------------------------------------------------------------
            // ----------------- other incomes
            $other_income_total = DB::table('treasury_transactions')
                    ->where('transaction_type_id',0)
                    ->where('company_id', $companyId)
                    ->where('financial_year', $financialYear)
                    ->where('archived', 0)
                    ->whereIn('account_id', $otherincome_accounts_array)
                    ->whereBetween('date', [$fromdate, $todate])
                    ->sum('amount') ?? 0;

            // -----------------
            //فائض الخزينة
            $faaed = DB::table('treasury_transactions')
                    ->where('transaction_type_id',0)
                    ->where('company_id', $companyId)
                    ->where('financial_year', $financialYear)
                    ->where('archived', 0)
                    ->where('account_id', $cashbox_faaed_account)
                    ->whereBetween('date', [$fromdate, $todate])
                    ->sum('amount') ?? 0;

            // -----------------
            //عجز الخزينة
            $ajz = DB::table('treasury_transactions')
                ->where('transaction_type_id',1)
                ->where('company_id', $companyId)
                ->where('financial_year', $financialYear)
                ->where('archived', 0)
                ->where('account_id', $cashbox_ajz_account)
                ->whereBetween('date', [$fromdate, $todate])
                ->sum('amount');


            // -----------------
            // (مبيعات الفترة)

            $total_sales = DB::table('treasury_transactions as t')
                ->leftJoin('accounts as a','a.id','t.account_id')
                ->where('t.transaction_type_id',0)
                ->where('t.company_id', $companyId)
                ->where('t.financial_year', $financialYear)
                ->where('t.archived', 0)
                ->where('a.category_id',7)
                ->whereBetween('t.date', [$fromdate, $todate])
                ->sum('t.amount');

            $tot_in = DB::table('treasury_transactions')
                ->where('transaction_type_id',0)
                ->where('company_id', $companyId)
                ->where('financial_year', $financialYear)
                ->where('archived', 0)
                ->where('account_id','<>', $cashbox_ajz_account)
                ->where('account_id','<>', $cashbox_faaed_account)
                ->wherenotin('account_id', $otherincome_accounts_array)
                ->whereBetween('date', [$fromdate, $todate])
                ->sum('amount');

            if($tot_in == 0){$tot_in = -1;}
            // -----------------
            // (مصاريف التشغيل)

            $operation_expenses = DB::table('treasury_transactions')
                ->where('transaction_type_id',1)
                ->where('company_id', $companyId)
                ->where('financial_year', $financialYear)
                ->where('archived', 0)
                ->where('tag_id','<>', 1)
                ->whereBetween('date', [$fromdate, $todate])
                ->whereIn('account_id', $operation_expenses_accounts_array)
                ->sum('amount');


            if ($days > 0) {
                $operation_expenses_aday = $operation_expenses / $days;
            } else {
                $operation_expenses_aday = 0;
            }


            // -------------------------------------------------------------------------------------------------------------
            //(المصاريف الادارية)

            $adminExpenses = DB::table('treasury_transactions')
                ->where('transaction_type_id',1)
                ->where('company_id', $companyId)
                ->where('financial_year', $financialYear)
                ->where('archived', 0)
                ->whereBetween('date', [$fromdate, $todate])
                ->whereIn('account_id', $admin_expenses_accounts_array)
                ->where('tag_id','<>', 1)
                ->sum('amount');

            if ($days > 0) {
                $adminExpenses_aday = $adminExpenses / $days;
            } else {
                $adminExpenses_aday = 0;
            }



            // -------------------------------------------------------------------------------------------------------------
            //( Dioooooooon)

            $dioon_expenses = DB::table('treasury_transactions')
                ->where('transaction_type_id',1)
                ->where('company_id', $companyId)
                ->where('financial_year', $financialYear)
                ->where('archived', 0)
                ->whereBetween('date', [$fromdate, $todate])
                ->whereIn('account_id', $dioon_expenses_accounts_array)
                ->sum('amount');

            if ($days > 0) {
                $dioon_expenses_aday = $dioon_expenses / $days;
            } else {
                $dioon_expenses_aday = 0;
            }

            // -------------------------------------------------------------------------------------------------------------
            // (صافي الربح)

            $total_in = DB::table('treasury_transactions')
                ->where('transaction_type_id',0)
                ->where('company_id', $companyId)
                ->where('financial_year', $financialYear)
                ->where('archived', 0)
                ->whereBetween('date', [$fromdate, $todate])
                ->whereIn('account_id', $otherincome_accounts_array)
                ->sum('amount');

            $total_out = DB::table('treasury_transactions')
                ->where('transaction_type_id',1)
                ->where('company_id', $companyId)
                ->where('financial_year', $financialYear)
                ->where('archived', 0)
                ->where('tag_id','<>', 1)
                ->whereBetween('date', [$fromdate, $todate])
                ->sum('amount');



            //-------------------------------------------------------------------------------------------------------------
            $net_profit =  ($tot_in + $other_income_total + $faaed - $ajz) - $operation_expenses - $adminExpenses ;
            //-------------------------------------------------------------------------------------------------------------

            //-------------------------------------------------------------------------------------------------------------
            if($adminExpenses == 0){ $adminExpenses = 1;}
            if($operation_expenses == 0){ $operation_expenses = 1;}
            if($days == 0){ $days = -1;}
            //-------------------------------------------------------------------------------------------------------------



            //-------------------------------------------------------------------------------------------------------------
            $Meet_Total = 0;
            $Meet_Total = DB::table('treasury_transaction_details as d')
                ->leftjoin('treasury_transactions as m','m.id','d.master_id')
                ->leftjoin('accounts as a','a.id','d.account_id')
                ->where('m.transaction_type_id',1)
                ->where('m.company_id',session::get('company_id'))
                ->where('m.financial_year',session::get('financial_year'))
                ->where('m.archived',0)
                ->where('a.archived',0)
                ->where('d.archived',0)
                ->where('m.tag_id','<>',1)
                ->whereBetween('m.date', [$fromdate, $todate])
                ->where('a.CategoryTxt','لحوم')
                ->sum('d.amount');
            //-------------------------------------------------------------------------------------------------------------
            $Grocery_Total = 0;
            $Grocery_Total = DB::table('treasury_transaction_details as d')
                ->leftjoin('treasury_transactions as m','m.id','d.master_id')
                ->leftjoin('accounts as a','a.id','d.account_id')
                ->where('m.transaction_type_id',1)
                ->where('m.company_id',session::get('company_id'))
                ->where('m.financial_year',session::get('financial_year'))
                ->where('m.archived',0)
                ->where('a.archived',0)
                ->where('d.archived',0)
                ->where('m.tag_id','<>',1)
                ->whereBetween('m.date', [$fromdate, $todate])
                ->where('a.CategoryTxt','غذائية')
                ->sum('d.amount');

            //-------------------------------------------------------------------------------------------------------------




            $rec_id = 0;
            DB::table('category__percentage__reports')
                ->where('created_by',auth()->id())
                ->where('company_id',session::get('company_id'))
                ->where('financial_year',session::get('financial_year'))
                ->delete();

            DB::table('category__percentage__reports')->insert([
                'id' => $rec_id,
                'company_id' => session::get('company_id'),
                'financial_year' => session::get('financial_year'),
                'created_by' => auth()->id(),

                'ordr1' => 0,
                'ordr2' => 0,
                'ordr3' => 0,

                'txt' => 'عدد الايام',

                'currency' => '',
                'number1' => $days,
                'number1_2' => 0,
                'number2' => 0,
                'number3' => 0,
                'number4' => 0,
                'note' => 0,
            ]);
            //--------------------------------------------------------------------------------------------------------------

            $rec_id += 1;
            DB::table('category__percentage__reports')->insert([
                'id' => $rec_id,
                'company_id' => session::get('company_id'),
                'financial_year' => session::get('financial_year'),
                'created_by' => auth()->id(),

                'ordr1' => 0,
                'ordr2' => 0,
                'ordr3' => 0,

                'txt' => 'اجمالي المبيعات',

                'currency' => '',
                'number1' => $tot_in + $other_income_total + $faaed - $ajz,
                'number1_2' => 0,
                'number2' => 0,
                'number3' => 0,
                'number4' => 0,
                'note' => 0,
            ]);
            //--------------------------------------------------------------------------------------------------------------
            $category_percentage_report = Category_Percentage_Report::where('created_by',auth()->id())
                ->where('company_id',session::get('company_id'))
                ->where('financial_year',session::get('financial_year'))
                ->get();






            return view('rep.category_percentage_report')
                ->with('decimal_octets', $decimal_octets)
                ->with('reports', $category_percentage_report);
        }
        }



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
     * @param  \App\Category_Percentage_Report  $category_Percentage_Report
     * @return \Illuminate\Http\Response
     */
    public function show(Category_Percentage_Report $category_Percentage_Report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category_Percentage_Report  $category_Percentage_Report
     * @return \Illuminate\Http\Response
     */
    public function edit(Category_Percentage_Report $category_Percentage_Report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category_Percentage_Report  $category_Percentage_Report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category_Percentage_Report $category_Percentage_Report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category_Percentage_Report  $category_Percentage_Report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category_Percentage_Report $category_Percentage_Report)
    {
        //
    }
}
