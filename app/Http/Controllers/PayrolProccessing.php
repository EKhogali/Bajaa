<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PayrolProccessing extends Controller
{
    public function generateMonthlyPayroll(Request $request)
    {
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        // Fetch active employees with their job and department details
        $employees = Employee::with(['job', 'department'])
            ->whereHas('department', function ($query) {
                $query->where('company_id', session('company_id'));
            })
            ->where('archived', 0)
            ->get();

        // Fetch constant additions and deductions
        $constAdditions = DB::table('employee_constant_payroll_items as c')
            ->join('payroll_item_types as i', 'c.payroll_item_type_id', '=', 'i.id')
            ->where('i.type', 0)
            ->where('i.archived', 0)
            ->where('c.archived', 0)
            ->select('c.employee_id', 'c.amount')
            ->get()
            ->groupBy('employee_id');

        $constDeductions = DB::table('employee_constant_payroll_items as c')
            ->join('payroll_item_types as i', 'c.payroll_item_type_id', '=', 'i.id')
            ->where('i.type', 1)
            ->where('i.archived', 0)
            ->where('c.archived', 0)
            ->select('c.employee_id', 'c.amount')
            ->get()
            ->groupBy('employee_id');

        // Fetch monthly additions and deductions
        $monthlyTransactions = DB::table('payroll_transactions as t')
            ->join('payroll_item_types as i', 't.payroll_item_type_id', '=', 'i.id')
            ->where('t.company_id', session('company_id'))
            ->where('t.year', $year)
            ->where('t.month', $month)
            ->where('i.archived', 0)
            ->select('t.employee_id', 'i.type', 't.amount')
            ->get()
            ->groupBy('employee_id');

        $payrollData = $employees->map(function ($employee) use ($constAdditions, $constDeductions, $monthlyTransactions) {
            $employeeId = $employee->id;

            $constantAdditions = isset($constAdditions[$employeeId])
                ? $constAdditions[$employeeId]->sum('amount')
                : 0;

            $constantDeductions = isset($constDeductions[$employeeId])
                ? $constDeductions[$employeeId]->sum('amount')
                : 0;

            $monthlyAdditions = isset($monthlyTransactions[$employeeId])
                ? $monthlyTransactions[$employeeId]->where('type', 0)->sum('amount')
                : 0;

            $monthlyDeductions = isset($monthlyTransactions[$employeeId])
                ? $monthlyTransactions[$employeeId]->where('type', 1)->sum('amount')
                : 0;

            $dueAmount = $employee->basic_salary
                + $constantAdditions + $monthlyAdditions
                - $constantDeductions - $monthlyDeductions;

            return [
                'code' => $employee->code,
                'name' => $employee->name,
                'job' => $employee->job->name,
                'department' => $employee->department->name,
                'basic_salary' => $employee->basic_salary,
                'const_additions' => $constantAdditions,
                'changed_additions' => $monthlyAdditions,
                'const_deductions' => $constantDeductions,
                'changed_deductions' => $monthlyDeductions,
                'due_amount' => $dueAmount,
                'employee_id' => $employeeId,
            ];
        });


        return view('payroll.payroll', [
            'payrollData' => $payrollData,
            'year' => $year,
            'month' => $month,
        ]);
    }



        public function generateMonthlyPayrolla(Request $request)
    {
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        // Fetch employees and their related payroll data
        $employees = Employee::with(['job', 'department', 'constantPayrollItems', 'payrollTransactions' => function ($query) use ($year, $month) {
            $query->where('year', $year)->where('month', $month);
        }])->get();

        $payrollData = $employees->map(function ($employee) use ($month, $year) {
            $additions = $employee->constantPayrollItems->where('type', 0)->sum('amount')
                + $employee->payrollTransactions->where('payroll_item_type_id', 0)->sum('amount');
            $deductions = $employee->constantPayrollItems->where('type', 1)->sum('amount')
                + $employee->payrollTransactions->where('payroll_item_type_id', 1)->sum('amount');
            $dueAmount = $employee->basic_salary + $additions - $deductions;

            return [
                'code' => $employee->code,
                'name' => $employee->name,
                'job' => $employee->job->name,
                'department' => $employee->department->name,
                'basic_salary' => $employee->basic_salary,
                'additions' => $additions,
                'deductions' => $deductions,
                'due_amount' => $dueAmount,
                'employee_id' => $employee->id,
                'year' => $year,
                'month' => $month,
            ];
        });
//        dd($payrollData,$employees,$year,$month);
        return view('payroll.payroll', compact('payrollData', 'year', 'month'));
    }


    public function showSlip($employee_id, $year, $month)
    {
        $employee = Employee::with([
            'job',
            'department',
            'constantPayrollItems.payrollItemType', // Eager-load payrollItemType for constants
            'payrollTransactions.payrollItemType', // Eager-load payrollItemType for transactions
        ])->findOrFail($employee_id);

        // Group additions and deductions
        $constantAdditions = $employee->constantPayrollItems->where('type', 0);
        $constantDeductions = $employee->constantPayrollItems->where('type', 1);
        $changedAdditions = $employee->payrollTransactions->where('type', 0);
        $changedDeductions = $employee->payrollTransactions->where('type', 1);

        // Calculate totals for additions and deductions
        $totalConstantAdditions = $constantAdditions->sum('amount');
        $totalChangedAdditions = $changedAdditions->sum('amount');
        $totalConstantDeductions = $constantDeductions->sum('amount');
        $totalChangedDeductions = $changedDeductions->sum('amount');

        // Calculate the due amount
        $dueAmount = $employee->basic_salary
            + $totalConstantAdditions
            + $totalChangedAdditions
            - $totalConstantDeductions
            - $totalChangedDeductions;

        // Pass data to the view
        return view('payroll.payslip', compact(
            'employee',
            'year',
            'month',
            'constantAdditions',
            'constantDeductions',
            'changedAdditions',
            'changedDeductions',
            'totalConstantAdditions',
            'totalChangedAdditions',
            'totalConstantDeductions',
            'totalChangedDeductions',
            'dueAmount'
        ));
    }





    public function showSlip_old($employee_id, $year, $month)
    {
        $employee = Employee::with(['constantPayrollItems', 'payrollTransactions' => function ($query) use ($year, $month) {
            $query->where('year', $year)->where('month', $month);
        }])->findOrFail($employee_id);

        // Constant Additions and Deductions
        $constantAdditions = $employee->constantPayrollItems->where('type', 0)->sum('amount');
        $constantDeductions = $employee->constantPayrollItems->where('type', 1)->sum('amount');

        // Changed Additions and Deductions
        $changedAdditions = $employee->payrollTransactions->where('payroll_item_type_id', 0)->sum('amount');
        $changedDeductions = $employee->payrollTransactions->where('payroll_item_type_id', 1)->sum('amount');

        // Total Additions and Deductions
        $totalAdditions = $constantAdditions + $changedAdditions;
        $totalDeductions = $constantDeductions + $changedDeductions;

        // Due Amount Calculation
        $dueAmount = $employee->basic_salary + $totalAdditions - $totalDeductions;

        return view('payroll.payslip', compact(
            'employee',
            'year',
            'month',
            'constantAdditions',
            'constantDeductions',
            'changedAdditions',
            'changedDeductions',
            'totalAdditions',
            'totalDeductions',
            'dueAmount'
        ));
    }



}
