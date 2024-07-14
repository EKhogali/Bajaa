<?php

namespace App\Http\Controllers;

use App\account;
use App\income_report;
use App\journald;
use App\partner;
use App\sitting;
use App\treasury_transaction;
use App\treasury_transaction_detail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\Integer;

class ReportController extends Controller
{

    public function account_details_report(){
        //-------------------------------------------------------------------------------------------------------------

        if(!request()->has('fromdate') or !request()->has('todate')){

            $account_details_report = DB::table('treasury_transaction_details as d')
                ->leftJoin('treasury_transactions as m','m.id','d.master_id')
                ->leftJoin('accounts as a','a.id','d.account_id')
                ->where('d.archived',0)
                ->where('d.id','<','1')
                ->select('d.amount as d_amount','m.amount as m_amount','d.*','m.*','a.*')
                ->get();

            $total_amount = $account_details_report->sum('d_amount');
            $accounts = account::where('company_id',session::get('company_id') ?? 0)
                ->where('is_fixed_assets',0)
                ->where('is_details',1)
                ->where('archived',0)
                ->get();

            return view('rep.account_details_report')
                ->with('account_details_report',$account_details_report)
//                ->with('selectedAccountName',$selectedAccountName)
                ->with('accounts',$accounts)
                ->with('total_amount',$total_amount);
        }else {
            $fromdate = \Carbon\Carbon::parse(Request('fromdate'))->startOfDay();
            $todate = \Carbon\Carbon::parse(Request('todate'))->endOfDay();

            $companyId = session::get('company_id');
            $financialYear = session::get('financial_year');


            $account_details_report = DB::table('treasury_transaction_details as d')
                ->leftJoin('treasury_transactions as m','m.id','d.master_id')
                ->leftJoin('accounts as a','a.id','d.account_id')
                ->where('d.archived',0)
                ->where('d.company_id', $companyId)
                ->where('d.financial_year', $financialYear)
                ->whereBetween('m.date', [$fromdate,$todate])
                ->where('d.account_id',Request('account_id'))
                ->select('d.amount as d_amount','m.amount as m_amount','d.*','m.*','a.*')
                ->get();

            $total_amount = $account_details_report->sum('d_amount');
            $accounts = account::where('company_id',session::get('company_id') ?? 0)
                ->where('is_fixed_assets',0)
                ->where('is_details',1)
                ->where('archived',0)
                ->get();


            $selectedAccountName = account::where('id',Request('account_id'))->value('name');
//            dd($accounts,$selectedAccountName,Request('account_id'));
            return view('rep.account_details_report')
                ->with('account_details_report',$account_details_report)
                ->with('selectedAccountName',$selectedAccountName)
                ->with('accounts',$accounts)
                ->with('total_amount',$total_amount);


        }
    }

    public function income_report(){

        //-------------------------------------------------------------------------------------------------------------

        if(!request()->has('fromdate') or !request()->has('todate')){

            DB::table('income_reports')
                ->where('created_by',auth()->id())
                ->where('company_id',session::get('company_id'))
                ->where('financial_year',session::get('financial_year'))
                ->delete();
            $income_reports = income_report::all()->sortBy('ordr1');
            $decimal_octets = sitting::where('id',1)->value('decimal_octets');

            return view('rep.income_report')
                ->with('decimal_octets',$decimal_octets)
                ->with('income_reports',$income_reports);
        }else{

        //--------------------------------------------------------------------------------------------------------------
        $fromdate = \Carbon\Carbon::parse(Request('fromdate'))->startOfDay();
        $todate = \Carbon\Carbon::parse(Request('todate'))->endOfDay();
        $companyId = session::get('company_id');
        $financialYear = session::get('financial_year');
        //--------------------------------------------------------------------------------------------------------------

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

//            dd(
//                '$net_profit: '.$net_profit,
//                '$total_in: '.$total_in,
//                '$other_income_total: '.$other_income_total,
//                '$operation_expenses: '.$operation_expenses,
//                '$adminExpenses: '.$adminExpenses,
//                '$ajz: '.$ajz,
//                '$faaed: '.$faaed
//            );

        //-------------------------------------------------------------------------------------------------------------
        if($adminExpenses == 0){ $adminExpenses = 1;}
        if($operation_expenses == 0){ $operation_expenses = 1;}
        if($days == 0){ $days = -1;}
        //-------------------------------------------------------------------------------------------------------------


        // -------------------------------------------------------------------------------------------------------------
        // -------------------------------------------------------------------------------------------------------------
        $rec_id = 0;
        DB::table('income_reports')
            ->where('created_by',auth()->id())
            ->where('company_id',session::get('company_id'))
            ->where('financial_year',session::get('financial_year'))
            ->delete();

        DB::table('income_reports')->insert([
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
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 1,
            'ordr2' => 1,
            'ordr3' => 1,

            'txt' => 'نسبة الربح',

            'currency' => '%',
            'number1' => (($net_profit/$tot_in )/100) ?? 0,
            'number1_2' => 0,
            'number2' => (($net_profit/$tot_in)/100)/$days,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);
        //--------------------------------------------------------------------------------------------------------------
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 2,
            'ordr2' => 2,
            'ordr3' => 2,

            'txt' => 'المبيـــــــــــــــــعات',

            'currency' => '',
            'number1' => 0,
            'number1_2' => 0,
            'number2' => 0,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);
        //--------------------------------------------------------------------------------------------------------------
        $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 3,
            'ordr2' => 3,
            'ordr3' => 3,

            'txt' => 'مبيعات الفتــــرة',

            'currency' => 'دينار',
            'number1' => $tot_in ?? 0,
            'number1_2' => 0,
            'number2' => $tot_in/$days,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);
        //--------------------------------------------------------------------------------------------------------------
        $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 4,
            'ordr2' => 4,
            'ordr3' => 4,

            'txt' => 'ايرادات اخرى',

            'currency' => 'دينار',
            'number1' => $other_income_total ?? 0,
            'number1_2' => 0,
            'number2' => $other_income_total/$days,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);
        //--------------------------------------------------------------------------------------------------------------
        $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 5,
            'ordr2' => 5,
            'ordr3' => 5,

            'txt' => 'فائض الخزينة',

            'currency' => 'دينار',
            'number1' => $faaed ?? 0,
            'number1_2' => 0,
            'number2' => $faaed/$days,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);
        //--------------------------------------------------------------------------------------------------------------
        $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 6,
            'ordr2' => 6,
            'ordr3' => 6,

            'txt' => 'عجز الخزينة',

            'currency' => 'دينار',
            'number1' => $ajz ?? 0,
            'number1_2' => 0,
            'number2' => $ajz/$days,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);
        //--------------------------------------------------------------------------------------------------------------
        $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 7,
            'ordr2' => 7,
            'ordr3' => 7,

            'txt' => 'الاجمالي',

            'currency' => 'دينار',
            'number1' => 0,
            'number1_2' => $tot_in + $other_income_total + $faaed - $ajz ?? 0,
            'number2' => ($tot_in + $faaed - $ajz)/$days,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);
        //--------------------------------------------------------------------------------------------------------------
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 8,
            'ordr2' => 8,
            'ordr3' => 8,

            'txt' => 'مصروفات التشغيل',

            'currency' => '',
            'number1' => 0,
            'number1_2' => 0,
            'number2' => 0,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);
        //--------------------------------------------------------------------------------------------------------------

        $queries = DB::table('treasury_transaction_details as d')
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
            ->whereIn('m.account_id', $operation_expenses_accounts_array)
            ->select(DB::raw('SUM(d.amount) as amount'),'d.account_id as account_id','a.name as name')
            ->groupBy('d.account_id','a.name')
            ->get();



            $total_op_pct = 0;
            foreach ($queries as $query){
                $rec_id += 1;
                $total_op_pct += $query->amount;

                DB::table('income_reports')->insert([
                    'id' => $rec_id,
                    'company_id' => session::get('company_id'),
                    'financial_year' => session::get('financial_year'),
                    'created_by' => auth()->id(),

                    'ordr1' => 9,
                    'ordr2' => 9,
                    'ordr3' => 9,

                    'txt' => $query->name ?? '',

                    'currency' => 'دينار',
                    'number1' => $query->amount,
                    'number1_2' => 0,
                    'number2' => $query->amount / $days,
                    'number3' => (($query->amount /  ($operation_expenses)  )     * 100),
                    'number4' => (($query->amount) / ($tot_in)) * 100,

                    'note' => 0,
                ]);
            }

        //--------------------------------------------------------------------------------------------------------------
        $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 10,
            'ordr2' => 10,
            'ordr3' => 10,

            'txt' => 'اجمالي مصروفات التشغيل',

            'currency' => 'دينار',
            'number1' => 0,
            'number1_2' => $operation_expenses,
            'number2' => $operation_expenses_aday,
            'number3' => ( $total_op_pct / $operation_expenses) * 100,
            'number4' => ( $total_op_pct / $tot_in) * 100,
            'note' => 0,
        ]);
        //--------------------------------------------------------------------------------------------------------------
        $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 11,
            'ordr2' => 11,
            'ordr3' => 11,

            'txt' => 'المصروفات الإدارية',

            'currency' => '',
            'number1' => 0,
            'number1_2' => 0,
            'number2' => 0,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);

        //--------------------------------------------------------------------------------------------------------------

        $queries = DB::table('treasury_transactions as t')
            ->leftjoin('accounts as a','a.id','t.account_id')
            ->where('t.transaction_type_id',1)
            ->where('t.company_id',session::get('company_id'))
            ->where('t.financial_year',session::get('financial_year'))
            ->where('t.archived',0)
            ->wherein('t.account_id',$admin_expenses_accounts_array)
            ->whereBetween('t.date', [$fromdate, $todate])
            ->where('t.tag_id','<>',1)
            ->select(DB::raw('SUM(t.amount) as amount'),'t.account_id as account_id','a.name as name')
            ->groupBy('t.account_id','a.name')
            ->get();

            $total_admin_pct = 0;
        foreach ($queries as $query){
            $rec_id += 1;
            $total_admin_pct += $query->amount;

            DB::table('income_reports')->insert([
                'id' => $rec_id,
                'company_id' => session::get('company_id'),
                'financial_year' => session::get('financial_year'),
                'created_by' => auth()->id(),

                'ordr1' => 12,
                'ordr2' => 12,
                'ordr3' => 12,

                'txt' => $query->name ?? '',

                'currency' => 'دينار',
                'number1' => $query->amount,
                'number1_2' => 0,
                'number2' => $query->amount / $days,
                'number3' => (($query->amount /  ($adminExpenses )  )     * 100),
                'number4' => (($query->amount) / ($tot_in)) * 100,

                'note' => 0,
            ]);
        }
        //--------------------------------------------------------------------------------------------------------------
        $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 13,
            'ordr2' => 13,
            'ordr3' => 13,

            'txt' => 'اجمالي المصروفات الإدارية',

            'currency' => 'دينار',
            'number1' => 0,
            'number1_2' => $adminExpenses,
            'number2' => $adminExpenses_aday,
            'number3' => ( $total_admin_pct / $adminExpenses) * 100,
            'number4' => ( $total_admin_pct / $tot_in) * 100,
            'note' => 0,
        ]);
        //--------------------------------------------------------------------------------------------------------------
        $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 14,
            'ordr2' => 14,
            'ordr3' => 14,

            'txt' => 'صافي الربـــــــــــــــــح',

            'currency' => 'دينار',
            'number1' => 0,
            'number1_2' => $net_profit,
            'number2' => $net_profit/$days,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);
        //--------------------------------------------------------------------------------------------------------------
        $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 15,
            'ordr2' => 15,
            'ordr3' => 15,

            'txt' => 'مسحوبات من صافي الربح',

            'currency' => 'دينار',
            'number1' => 0,
            'number1_2' => 0,
            'number2' => 0,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);

        //--------------------------------------------------------------------------------------------------------------

        $queries = DB::table('treasury_transactions as t')
            ->leftjoin('accounts as a','a.id','t.account_id')
            ->where('t.transaction_type_id',1)
            ->where('t.company_id',session::get('company_id'))
            ->where('t.financial_year',session::get('financial_year'))
            ->where('t.archived',0)
            ->wherein('t.account_id',$pulled_from_net_income_accounts_array)
            ->whereBetween('t.date', [$fromdate, $todate])
            ->where('t.tag_id','<>',1)
            ->select(DB::raw('SUM(t.amount) as amount'),'t.account_id as account_id','a.name as name')
            ->groupBy('t.account_id','a.name')
            ->get();

        foreach ($queries as $query){
            $rec_id += 1;

            DB::table('income_reports')->insert([
                'id' => $rec_id,
                'company_id' => session::get('company_id'),
                'financial_year' => session::get('financial_year'),
                'created_by' => auth()->id(),

                'ordr1' => 16,
                'ordr2' => 16,
                'ordr3' => 16,

                'txt' => $query->name ?? '',

                'currency' => 'دينار',
                'number1' => $query->amount,
                'number1_2' => 0,
                'number2' => $query->amount / $days,
                'number3' => (($query->amount /  ($adminExpenses + $operation_expenses)  )     * 100),
                'number4' => (($query->amount) / ($tot_in)) * 100,

                'note' => 0,
            ]);
        }
        //--------------------------------------------------------------------------------------------------------------

            $total_pulled_from_net_income = $queries->sum('amount');
            $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 17,
            'ordr2' => 17,
            'ordr3' => 17,

            'txt' => 'اجمالي المسحوبات من صافي الربح',

            'currency' => 'دينار',
            'number1' => 0,
            'number1_2' => $total_pulled_from_net_income,
            'number2' => 0,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);
        //--------------------------------------------------------------------------------------------------------------
        $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 18,
            'ordr2' => 18,
            'ordr3' => 18,

            'txt' => 'صافي الربح بعد المسحوبات',

            'currency' => 'دينار',
            'number1' => 0,
            'number1_2' => $net_profit - $dioon_expenses - $total_pulled_from_net_income,
            'number2' => ($net_profit - $dioon_expenses - $total_pulled_from_net_income)/$days,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);


            //--------------------------------------------------------------------------------------------------------------
            $queries = partner::where('company_id',session::get('company_id'))
                ->where('archived',0)
                ->get();



            foreach ($queries as $query){
                $rec_id += 1;
                $partner_type_desc = $query->partnership_type == 0 ? 'مستثمر' : 'شريك';
                DB::table('income_reports')->insert([
                    'id' => $rec_id,
                    'company_id' => session::get('company_id'),
                    'financial_year' => session::get('financial_year'),
                    'created_by' => auth()->id(),

                    'ordr1' => 19,
                    'ordr2' => 19,
                    'ordr3' => 19,

                    'txt' => 'حصة: الـ '.$partner_type_desc.' '.$query->name.' ('.$query->win_percentage.' % )',

                    'currency' => 'دينار',
                    'number1' => ($query->win_percentage * (( $net_profit - $dioon_expenses - $total_pulled_from_net_income) / 100) ) - $dioon_expenses   ,
                    'number1_2' => 0,
                    'number2' => 0,
                    'number3' => 0,
                    'number4' => 0,

                    'note' => 0,
                ]);
            }



        //--------------------------------------------------------------------------------------------------------------
        $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 20,
            'ordr2' => 20,
            'ordr3' => 20,

            'txt' => 'اجمـــــــــالي الحصص',

            'currency' => 'دينار',
            'number1' => 0,
            'number1_2' => $net_profit - $dioon_expenses - $total_pulled_from_net_income,
            'number2' => 0,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);
        //--------------------------------------------------------------------------------------------------------------
        $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 21,
            'ordr2' => 21,
            'ordr3' => 21,

            'txt' => 'الديــــــون والمسحــــوبات',

            'currency' => 'دينار',
            'number1' => 0,
            'number1_2' => 0,
            'number2' => 0,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);


            //--------------------------------------------------------------------------------------------------------------
            $queries = DB::table('partners as p')
                ->leftjoin('treasury_transactions as t', 'p.account_id', '=', 't.account_id')
                ->where('t.company_id', session::get('company_id'))
                ->where('t.financial_year', session::get('financial_year'))
                ->where('t.archived', 0)
                ->where('p.archived', 0)
                ->whereBetween('t.date', [$fromdate, $todate])
                ->select('p.name','p.id', DB::raw('SUM(t.amount) as total_amount'))
                ->groupBy('p.name','p.id')
                ->orderBy('p.id')
                ->get();

            $total_from_party = $queries->sum('total_amount');

            foreach ($queries as $query) {

                $rec_id += 1;
                DB::table('income_reports')->insert([
                    'id' => $rec_id,
                    'company_id' => session::get('company_id'),
                    'financial_year' => session::get('financial_year'),
                    'created_by' => auth()->id(),

                    'ordr1' => 22,
                    'ordr2' => 22,
                    'ordr3' => 22,

                    'txt' => $query->name,

                    'currency' => 'دينار',
                    'number1' => $query->total_amount,
                    'number1_2' => 0,
                    'number2' => 0,
                    'number3' => 0,
                    'number4' => 0,

                    'note' => 0,
                ]);


            }


        //--------------------------------------------------------------------------------------------------------------
        $rec_id += 1;
        DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 23,
            'ordr2' => 23,
            'ordr3' => 23,

            'txt' => 'الحصص بعد خصم المصروفات الرأسمالية والمسحوبات',

            'currency' => 'دينار',
            'number1' => 0,
            'number1_2' => 0,
            'number2' => 0,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);



        //--------------------------------------------------------------------------------------------------------------
//        $queries = DB::table('partners as p')
//            ->leftjoin('treasury_transactions as t', 'p.account_id', '=', 't.account_id')
//            ->where('t.company_id', session::get('company_id'))
//            ->where('t.financial_year', session::get('financial_year'))
//            ->where('t.archived', 0)
//            ->where('p.archived', 0)
//            ->where('t.tag_id', 1)
//            ->whereBetween('t.date', [$fromdate, $todate])
//            ->select('p.id as id', DB::raw('SUM(t.amount) as total_amount'))
//            ->groupBy('p.id')
//            ->orderBy('p.id')
//            ->get();
//
//        $total_from_party = $queries->sum('total_amount');



            $queries = partner::where('company_id',session::get('company_id'))
                ->where('archived',0)
                ->get();

            foreach ($queries as $query){
                $rec_id += 1;

                $total_partner_pulled = DB::table('treasury_transactions')
                    ->where('company_id', session::get('company_id'))
                    ->where('financial_year', session::get('financial_year'))
                    ->where('archived', 0)
                    ->where('tag_id', 1)
                    ->where('account_id', $query->account_id)
                    ->whereBetween('date', [$fromdate, $todate])
                    ->select(DB::raw('SUM(amount)'))->first() ?? 0;
//                    ->sum('amount')->get();

                $partner_type_desc = $query->partnership_type == 0 ? 'مستثمر' : 'شريك';

//dd($total_partner_pulled,$query->account_id,$query);
                DB::table('income_reports')->insert([
                    'id' => $rec_id,
                    'company_id' => session::get('company_id'),
                    'financial_year' => session::get('financial_year'),
                    'created_by' => auth()->id(),

                    'ordr1' => 24,
                    'ordr2' => 24,
                    'ordr3' => 24,

                    'txt' => 'حصة: الـ '.$partner_type_desc.' '.$query->name.' ('.$query->win_percentage.' % )',

                    'currency' => 'دينار',
                    'number1' => ($query->win_percentage * (( $net_profit - $dioon_expenses - $total_pulled_from_net_income) / 100) ) - $dioon_expenses - $total_partner_pulled   ,
                    'number1_2' => 0,
                    'number2' => 0,
                    'number3' => 0,
                    'number4' => 0,

                    'note' => 0,
                ]);
            }
            //--------------------------------------------------------------------------------------------------------------


            DB::table('income_reports')->insert([
            'id' => $rec_id,
            'company_id' => session::get('company_id'),
            'financial_year' => session::get('financial_year'),
            'created_by' => auth()->id(),

            'ordr1' => 25,
            'ordr2' => 25,
            'ordr3' => 25,

            'txt' => 'رصيد الخزينة',

            'currency' => '',
            'number1' => ($tot_in + $other_income_total + $faaed - $ajz) - $operation_expenses - $adminExpenses - $dioon_expenses - $total_from_party - $total_pulled_from_net_income,
            'number1_2' => 0,
            'number2' => 0,
            'number3' => 0,
            'number4' => 0,
            'note' => 0,
        ]);

        // -------------------------------------------------------------------------------------------------------------

        //#########################################################################################################
        DB::table('income_reports')
            ->where('id',1)
            ->update(['number1'=>($net_profit / ($tot_in + $other_income_total + $faaed - $ajz))*100]);
        //#########################################################################################################

        $income_reports = income_report::where('created_by',auth()->id())
            ->where('company_id',session::get('company_id'))
            ->where('financial_year',session::get('financial_year'))
        ->get();
//        $income_reports = income_report::all()->sortBy('ordr1');

        return view('rep.income_report')
            ->with('decimal_octets',$decimal_octets)
            ->with('income_reports',$income_reports)
            ->with('fromdate',$fromdate)
            ->with('todate',$todate);
        }
    }

    public function gl_index(){
        $fromdate = \Carbon\Carbon::now();
        $todate = \Carbon\Carbon::now();


        $ledgers = journald::
            leftJoin('journalms', 'journalms.id', '=', 'journalds.journalm_id')
            ->wherebetween('journalms.date',[$fromdate,$todate])
            -> get();

        $total_creditor = 0;
        $total_debtor =0;

        return view('rep.generalledger')
            ->with('ledgers',$ledgers)
            ->with('fromdate',$fromdate)
            ->with('todate',$todate)
            ->with('total_debtor',$total_debtor)
            ->with('total_creditor',$total_creditor);
    }


    public function l_index(){
        $fromdate = Request()->fromdate ?? \Carbon\Carbon::now();
        $todate = Request()->todate ?? \Carbon\Carbon::now();

        $ledgers = journald::
            leftJoin('journalms', 'journalms.id', '=', 'journalds.journalm_id')
            ->leftJoin('accounts', 'accounts.id', '=', 'journalds.account_id')
            ->wherebetween('journalms.date',[$fromdate,$todate])
            ->  where('account_id',0)->get();
        $total_creditor = 0;
        $total_debtor =0;

        $accounts = account::all();

        return view('rep.ledger')
            ->with('ledgers',$ledgers)
            ->with('fromdate',$fromdate)
            ->with('todate',$todate)
            ->with('total_debtor',$total_debtor)
            ->with('total_creditor',$total_creditor)
            ->with('accounts',$accounts)
            ->with('account_id',Request()->account_id);
    }


    public function tr_index()
    {

        $trailbalance = journald::where('id','<',1)->get();
//        $trailbalance->addselect(DB::raw('0 as '))
//        dd($trailbalance);

        return view('rep.trailbalance')
            ->with('trailbalances',$trailbalance);
    }



    public function exe_g_ledger(){

        $fromdate = Request()->fromdate ?? \Carbon\Carbon::now();
        $todate = Request()->todate ?? \Carbon\Carbon::now();

        $ledgers = DB::table('journalds')
            ->leftJoin('journalms', 'journalds.journalm_id', '=', 'journalms.id')
            ->leftJoin('accounts', 'journalds.account_id', '=', 'accounts.id')
            ->wheredate('date','<=',$todate)
            ->wheredate('date','>=',$fromdate)
            ->get();
//        dd('0909ooooo');
        $total_creditor = DB::table('journalds')
            ->leftJoin('journalms', 'journalms.id', '=', 'journalds.journalm_id')
            ->wheredate('date','<=',$todate)
            ->wheredate('date','>=',$fromdate)
            ->sum('credit_amount');

        $total_debtor = DB::table('journalds')
            ->leftJoin('journalms', 'journalms.id', '=', 'journalds.journalm_id')
            ->wheredate('date','<=',$todate)
            ->wheredate('date','>=',$fromdate)
            ->sum('debit_amount');

        return view('rep.generalledger')
            ->with('ledgers',$ledgers)
            ->with('total_debtor',$total_debtor)
            ->with('total_creditor',$total_creditor)
            ->with('fromdate',$fromdate)
            ->with('todate',$todate);
    }


    public function exe_ledger(){

        $fromdate = Request()->fromdate ?? \Carbon\Carbon::now();
        $todate = Request()->todate ?? \Carbon\Carbon::now();
        $account_id = Request()->account_id;
//dd($account_id);
        $ledgers = DB::table('journalds')
            ->leftJoin('journalms', 'journalds.journalm_id', '=', 'journalms.id')
            ->leftJoin('accounts', 'accounts.id', '=', 'journalds.account_id')
            ->wheredate('date','<=',$todate)
            ->wheredate('date','>=',$fromdate)
            ->where('account_id','=',$account_id)
            ->get();
//        dd('0909ooooo');
        $total_creditor = DB::table('journalds')
            ->leftJoin('journalms', 'journalms.id', '=', 'journalds.journalm_id')
            ->wheredate('date','<=',$todate)
            ->wheredate('date','>=',$fromdate)
            ->where('account_id','=',$account_id)
            ->sum('credit_amount');

        $total_debtor = DB::table('journalds')
            ->leftJoin('journalms', 'journalms.id', '=', 'journalds.journalm_id')
            ->wheredate('date','<=',$todate)
            ->wheredate('date','>=',$fromdate)
            ->where('account_id','=',$account_id)
            ->sum('debit_amount');

        $accounts = account::all();

        return view('rep.ledger')
            ->with('ledgers',$ledgers)
            ->with('total_debtor',$total_debtor)
            ->with('total_creditor',$total_creditor)
            ->with('accounts',$accounts)
            ->with('fromdate',$fromdate)
            ->with('todate',$todate)
            ->with('account_id',$account_id);
    }



    public function tr_exec()
    {

        $accounts = DB::table('accounts')
            ->where('active',1)
            ->get();

        DB::table('trial_balances')
//            ->leftJoin('journalms', 'journalms.id', '=', 'journalds.journalm_id')
            ->where('company_id',session::get('company_id'))
            ->where('financial_year_id',session::get('financial_year_id'))
            ->delete();

        $order = 1;





        foreach ($accounts as $account){

            $tot_debit_amount = DB::table('journalds')
                ->leftJoin('journalms', 'journalms.id', '=', 'journalds.journalm_id')
                ->where('company_id',session::get('company_id'))
                ->where('financial_year',session::get('financial_year'))
                ->wheremonth('date','>=',Request()->frommonth)
                ->wheremonth('date','<=',Request()->tomonth)
                ->where('account_id',$account->id)
                ->sum('debit_amount');

            $tot_credit_amount = DB::table('journalds')
                ->leftJoin('journalms', 'journalms.id', '=', 'journalds.journalm_id')
                ->where('company_id',session::get('company_id'))
                ->where('financial_year',session::get('financial_year'))
                ->wheremonth('date','>=',Request()->frommonth)
                ->wheremonth('date','<=',Request()->tomonth)
                ->where('account_id',$account->id)
                ->sum('credit_amount');


            $level = $this->account_level($account->id);
            DB::table('trial_balances')->insert([
                'order' => $order
                ,'company_id' => session::get('company_id')
                ,'financial_year_id' => session::get('financial_year_id')
                ,'category_id' => $account->category_id
                ,'parent_id' => $account->parent_id
                ,'account_id' => $account->id
                ,'account_name' => $this->account_name_indent($level).$account->name
                ,'level' => $level
                ,'previous_balance' => 0
                ,'total_creditor' => $tot_credit_amount
                ,'total_debtor' => $tot_debit_amount
                ,'current_balance' => ($tot_credit_amount - $tot_debit_amount)
            ]);

            ++$order;
            $tot_debit_amount = 0;
            $tot_credit_amount = 0;
    }
        $trailbalances = DB::table('trial_balances')
            ->where('company_id',session::get('company_id'))
            ->where('financial_year_id',session::get('financial_year_id'))
            ->orderBy('id')
            ->get();


        return view('rep.trailbalance')
            ->with('trailbalances',$trailbalances);
    }



    public function account_name_indent( $level){
        $indents = '';
        for ($x = 1; $x <= $level; $x++){
            $indents = $indents.'-';

        }
        $indents = $indents.'>';
        return $indents;
    }

    public function account_level( $account){

        $accountsTemp = DB::table('accounts')->get();
        $accounts = $accountsTemp->where('id',$account);
        $parentidTmp = $accounts->pluck('parent_id')->first();
        $level = 0;
        $tmp = 0;

        if (($parentidTmp == 0) || (is_null($parentidTmp))) {
            $level = 0;
        }
        else{
            $accounts = $accountsTemp->where('id',$parentidTmp);
            $parentidTmp = $accounts->pluck('parent_id')->first();

            if ($parentidTmp == 0)
                $level = 1;
            else{
                $accounts = $accountsTemp->where('id',$parentidTmp);
                $parentidTmp = $accounts->pluck('parent_id')->first();

                if ($parentidTmp == 0) {
                    $level = 2;
                }
                else{
                    $accounts = $accountsTemp->where('id',$parentidTmp);
                    $parentidTmp = $accounts->pluck('parent_id')->first();
                    if ($parentidTmp == 0)
                        $level = 3;
                    else{
                        $accounts = $accountsTemp->where('id',$parentidTmp);
                        $parentidTmp = $accounts->pluck('parent_id')->first();
                        if ($parentidTmp == 0)
                            $level = 4;
                        else{
                            $accounts = $accountsTemp->where('id',$parentidTmp);
                            $parentidTmp = $accounts->pluck('parent_id')->first();
                            if ($parentidTmp == 0)
                                $level = 5;
                            else{
                                $accounts = $accountsTemp->where('id',$parentidTmp);
                                $parentidTmp = $accounts->pluck('parent_id')->first();
                                if ($parentidTmp == 0)
                                    $level = 6;
                                else{
                                    $accounts = $accountsTemp->where('id',$parentidTmp);
                                    $parentidTmp = $accounts->pluck('parent_id')->first();
                                    if ($parentidTmp == 0)
                                        $level = 7;
//                                            else{
//
//                                            }
                                }
                            }
                        }
                    }
                }
            }

        }

        return $level;
    }
}
