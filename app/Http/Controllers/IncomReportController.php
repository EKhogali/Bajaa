<?php

namespace App\Http\Controllers;

use App\account;
use App\income_report;
use App\partner;
use App\sitting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class IncomReportController extends Controller
{
    public function income_report2(){

        $net_profit = 0;
        $tot_in = 0;
        $other_income_total = 0;
        $faaed = 0;
        $ajz = 0;
        $decimal_octets = sitting::where('id',1)->value('decimal_octets');
        $days = 1;

        if(!request()->has('ch') ){

            DB::table('income_reports')
                ->where('created_by',auth()->id())
                ->where('company_id',session::get('company_id'))
                ->where('financial_year',session::get('financial_year'))
                ->delete();
            $income_reports = income_report::where('id',1);

            return view('rep.income_report2')
                ->with('decimal_octets',$decimal_octets)
                ->with('income_reports',$income_reports);
        }else{
            //--------------------------------------------------------------------------------------------------------------
            $fromdate = \Carbon\Carbon::parse(Request('fromdate'))->startOfDay();
            $todate = \Carbon\Carbon::parse(Request('todate'))->endOfDay();
            $companyId = session::get('company_id');
            $financialYear = session::get('financial_year');
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
//            ALTER TABLE `income_reports`
//MODIFY COLUMN `number1_2` DECIMAL(15,2);
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
                'number1' => $total_sales ?? 0,
                'number1_2' => 0,
                'number2' => $total_sales/$days,
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
                'number2' => ($tot_in + $other_income_total + $faaed - $ajz)/$days,
//            'number2' => ($tot_in + $faaed - $ajz)/$days,
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
                ->join('treasury_transactions as m', 'm.id', '=', 'd.master_id')
                ->join('accounts as a', 'a.id', '=', 'd.account_id')
                ->where('m.transaction_type_id', 1)
                ->where('m.company_id', $companyId)
                ->where('m.financial_year', $financialYear)
                ->where('m.archived', 0)
                ->where('a.archived', 0)
                ->where('d.archived', 0)
                ->where('m.tag_id', '<>', 1)
                ->whereBetween('m.date', [$fromdate, $todate])
                ->whereIn('m.account_id', $operation_expenses_accounts_array)
                ->selectRaw('SUM(d.amount) as amount, d.account_id, a.name')
                ->groupBy('d.account_id', 'a.name')
                ->get();

            $total_op_pct = $queries->sum('amount') ?? 0;

            foreach ($queries as $query) {
                $rec_id += 1;

                $incomeReports[] = [
                    'id' => $rec_id,
                    'company_id' => Session::get('company_id'),
                    'financial_year' => Session::get('financial_year'),
                    'created_by' => auth()->id(),
                    'ordr1' => 9,
                    'ordr2' => 9,
                    'ordr3' => 9,
                    'txt' => $query->name ?? '',
                    'currency' => 'دينار',
                    'number1' => $query->amount,
                    'number1_2' => 0,
                    'number2' => $query->amount / $days,
                    'number3' => (($query->amount / ($operation_expenses)) * 100) ?? 0,
                    'number4' => (($query->amount) / ($tot_in + $other_income_total + $faaed - $ajz)) * 100 ?? 0,
                    'note' => 0,
                ];
            }

            DB::table('income_reports')->insert($incomeReports);


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
                'number3' => ( $total_op_pct / $operation_expenses) * 100 ?? 0,
                'number4' => ( $total_op_pct / ($tot_in + $other_income_total + $faaed - $ajz)) * 100 ?? 0,
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

            // Optimized Query for Admin Expenses
            $queries = DB::table('treasury_transactions as t')
                ->join('accounts as a', 'a.id', '=', 't.account_id')
                ->selectRaw('SUM(t.amount) as amount, t.account_id, a.name')
                ->where([
                    ['t.transaction_type_id', '=', 1],
                    ['t.company_id', '=', session('company_id')],
                    ['t.financial_year', '=', session('financial_year')],
                    ['t.archived', '=', 0],
                    ['t.tag_id', '<>', 1],
                ])
                ->whereIn('t.account_id', $admin_expenses_accounts_array)
                ->whereBetween('t.date', [$fromdate, $todate])
                ->groupBy('t.account_id', 'a.name')
                ->get();

            $total_admin_pct = $queries->sum('amount') ?? 0;
            $incomeReports = [];

            foreach ($queries as $query) {
                $rec_id += 1;

                $incomeReports[] = [
                    'id' => $rec_id,
                    'company_id' => Session::get('company_id'),
                    'financial_year' => Session::get('financial_year'),
                    'created_by' => auth()->id(),
                    'ordr1' => 12,
                    'ordr2' => 12,
                    'ordr3' => 12,
                    'txt' => $query->name ?? '',
                    'currency' => 'دينار',
                    'number1' => $query->amount,
                    'number1_2' => 0,
                    'number2' => $query->amount / $days,
                    'number3' => (($query->amount / ($adminExpenses)) * 100) ?? 0,
                    'number4' => (($query->amount) / ($tot_in + $other_income_total + $faaed - $ajz)) * 100 ?? 0,
                    'note' => 0,
                ];
            }

            DB::table('income_reports')->insert($incomeReports);

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
                'number3' => ( $total_admin_pct / $adminExpenses) * 100 ?? 0,
                'number4' => ( $total_admin_pct / ($tot_in + $other_income_total + $faaed - $ajz)) * 100 ?? 0,
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

            // Optimized Query for Pulled from Net Income
            $queries = DB::table('treasury_transactions as t')
                ->join('accounts as a', 'a.id', '=', 't.account_id')
                ->selectRaw('SUM(t.amount) as amount, t.account_id, a.name')
                ->where([
                    ['t.transaction_type_id', '=', 1],
                    ['t.company_id', '=', session('company_id')],
                    ['t.financial_year', '=', session('financial_year')],
                    ['t.archived', '=', 0],
                    ['t.tag_id', '<>', 1],
                ])
                ->whereIn('t.account_id', $pulled_from_net_income_accounts_array)
                ->whereBetween('t.date', [$fromdate, $todate])
                ->groupBy('t.account_id', 'a.name')
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
                    'number3' => (($query->amount /  ($adminExpenses + $operation_expenses)  )     * 100) ?? 0,
                    'number4' => (($query->amount) / ($tot_in)) * 100 ?? 0,

                    'note' => 0,
                ]);
            }
            //--------------------------------------------------------------------------------------------------------------
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
            // Optimized Partner Query with Bulk Insert
            $partners = partner::where([
                ['company_id', '=', session('company_id')],
                ['archived', '=', 0],
            ])->get();

            $incomeReports = [];
            foreach ($partners as $partner) {
                $rec_id++;
                $partner_type_desc = $partner->partnership_type == 0 ? 'مستثمر' : 'شريك';
                $incomeReports[] = [
                    'id' => $rec_id,
                    'company_id' => session('company_id'),
                    'financial_year' => session('financial_year'),
                    'created_by' => auth()->id(),
                    'ordr1' => 19,
                    'ordr2' => 19,
                    'ordr3' => 19,
                    'txt' => 'حصة: الـ ' . $partner_type_desc . ' ' . $partner->name . ' (' . $partner->win_percentage . ' % )',
                    'currency' => 'دينار',
                    'number1' => ($partner->win_percentage * (($net_profit - $dioon_expenses - $total_pulled_from_net_income) / 100)) - $dioon_expenses,
                    'number1_2' => 0,
                    'number2' => 0,
                    'number3' => 0,
                    'number4' => 0,
                    'note' => 0,
                ];
            }

// Bulk Insert for Improved Performance
            if (!empty($incomeReports)) {
                DB::table('income_reports')->insert($incomeReports);
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
            // Fetch partner transactions with aggregation
            $queries = DB::table('partners as p')
                ->leftJoin('treasury_transactions as t', 'p.account_id', '=', 't.account_id')
                ->where([
                    ['t.company_id', '=', session('company_id')],
                    ['t.financial_year', '=', session('financial_year')],
                    ['t.archived', '=', 0],
                    ['p.archived', '=', 0],
                ])
                ->whereBetween('t.date', [$fromdate, $todate])
                ->select('p.name', 'p.id', DB::raw('SUM(t.amount) as total_amount'))
                ->groupBy('p.name', 'p.id')
                ->orderBy('p.id')
                ->get();

// Calculate the total amount from all parties
            $total_from_party = $queries->sum('total_amount');

// Prepare bulk insert data for income_reports
            $incomeReportsData = [];
            foreach ($queries as $query) {
                $rec_id++;
                $incomeReportsData[] = [
                    'id' => $rec_id,
                    'company_id' => session('company_id'),
                    'financial_year' => session('financial_year'),
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
                ];
            }

            if (!empty($incomeReportsData)) {
                DB::table('income_reports')->insert($incomeReportsData);
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


            $partnerQueries = partner::with([
                'treasuryTransactions' => function ($query) use ($fromdate, $todate) {
                    $query->where([
                        ['company_id', '=', session('company_id')],
                        ['financial_year', '=', session('financial_year')],
                        ['archived', '=', 0],
                        ['tag_id', '=', 1]
                    ])->whereBetween('date', [$fromdate, $todate]);
                }
            ])
                ->where([
                    ['company_id', '=', session('company_id')],
                    ['archived', '=', 0]
                ])
                ->get();

            $incomeReports = [];
            foreach ($partnerQueries as $query) {
                $rec_id++;
                $total_partner_pulled = $query->treasuryTransactions->sum('amount') ?? 0;
                $partner_type_desc = $query->partnership_type == 0 ? 'مستثمر' : 'شريك';

                $incomeReports[] = [
                    'id' => $rec_id,
                    'company_id' => session('company_id'),
                    'financial_year' => session('financial_year'),
                    'created_by' => auth()->id(),
                    'ordr1' => 24,
                    'ordr2' => 24,
                    'ordr3' => 24,
                    'txt' => 'حصة: الـ ' . $partner_type_desc . ' ' . $query->name . ' (' . $query->win_percentage . ' % )',
                    'currency' => 'دينار',
                    'number1' => ($query->win_percentage * (($net_profit - $dioon_expenses - $total_pulled_from_net_income) / 100)) - $dioon_expenses - $total_partner_pulled,
                    'number1_2' => 0,
                    'number2' => 0,
                    'number3' => 0,
                    'number4' => 0,
                    'note' => 0,
                ];
            }


            DB::table('income_reports')->insert($incomeReports);



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








            //#########################################################################################################
            DB::table('income_reports')
                ->where('id',1)
                ->update(['number1'=>($net_profit / ($tot_in + $other_income_total + $faaed - $ajz))*100]);
            //#########################################################################################################

            $income_reports = income_report::where('created_by',auth()->id())
                ->where('company_id',session::get('company_id'))
                ->where('financial_year',session::get('financial_year'))
                ->get();

            return view('rep.income_report2')
                ->with('decimal_octets',$decimal_octets)
                ->with('income_reports',$income_reports)
                ->with('fromdate',$fromdate)
                ->with('todate',$todate);

        }

    }


}
