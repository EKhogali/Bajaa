<?php

namespace App\Http\Controllers;

use App\Department;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PayrolProccessing extends Controller
{
    public function generateMonthlyPayroll(Request $request)
    {
//        $year = $request->year ?? now()->year;
//        $month = $request->month ?? now()->month;
        $month = $request->input('month', now()->format('Y-m')); // Default to the current month
        $year = date('Y', strtotime($month));
        $month = date('m', strtotime($month));


        $employees = Employee::with(['job', 'department'])
            ->whereHas('department', function ($query) {
                $query->where('company_id', session('company_id'));
            })
            ->where('archived', 0)
            ->get();
        if ($request->filled('department')) {
            $employees = Employee::with(['job', 'department'])
                ->whereHas('department', function ($query) {
                    $query->where('company_id', session('company_id'));
                })
                ->where('archived', 0)
                ->where('department_id', $request->input('department'))
                ->get();
        }

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


        $departments = Department::all();
        return view('payroll.payroll', [
            'payrollData' => $payrollData,
            'year' => $year,
            'month' => $month,
            'departments' => $departments,
        ]);
    }



        public function generateMonthlyPayrolla(Request $request)
    {
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        $employees = Employee::where('company_id',\session()->get('company_id'))
            ->where('archived',0)
            ->get();
        foreach ($employees as $employee){
            $constantAdditions = DB::table('employee_constant_payroll_items as ec')
                ->leftJoin('payroll_item_types as pit','pit.id','ec.payroll_item_type_id')
                ->where('ec.employee_id',$employee->id)
                ->where('pit.type',0)
                ->where('pit.archived',0)
                ->get();

            $constantDeductions = DB::table('employee_constant_payroll_items as ec')
                ->leftJoin('payroll_item_types as pit','pit.id','ec.payroll_item_type_id')
                ->where('ec.employee_id',$employee->id)
                ->where('pit.type',1)
                ->where('pit.archived',0)
                ->get();

            $changedAdditions = DB::table('payroll_transactions as t')
                ->leftJoin('payroll_item_types as pit','pit.id','t.payroll_item_type_id')
                ->where('t.employee_id',$employee->id)
                ->where('pit.type',0)
                ->where('pit.archived',0)
                ->get();

            $changedDeductions = DB::table('payroll_transactions as t')
                ->leftJoin('payroll_item_types as pit','pit.id','t.payroll_item_type_id')
                ->where('t.employee_id',$employee->id)
                ->where('pit.type',1)
                ->where('pit.archived',0)
                ->get();

            // Calculate totals for additions and deductions
            $totalConstantAdditions = $constantAdditions->sum('amount');
            $totalChangedAdditions = $changedAdditions->sum('amount');
            $totalConstantDeductions = $constantDeductions->sum('amount');
            $totalChangedDeductions = $changedDeductions->sum('amount');
            $dueAmount = $employee->basic_salary + $totalConstantAdditions + $totalChangedAdditions
                - $totalConstantDeductions - $totalChangedDeductions;

            $payrollData = [
                'code' => $employee->code,
                'name' => $employee->name,
                'job' => $employee->job->name,
                'department' => $employee->department->name,
                'basic_salary' => $employee->basic_salary,
                'const_additions' => $totalConstantAdditions,
                'changed_additions' => $totalChangedAdditions,
                'const_deductions' => $totalConstantDeductions,
                'changed_deductions' => $totalChangedDeductions,
                'due_amount' => $dueAmount,
                'employee_id' => $employee->id,
                'year' => $year,
                'month' => $month,
            ];
        }


        dd($departments);
        $departments = Department::all();
        return view('payroll.payroll', compact('payrollData', 'year', 'month'))
            ->with('departments',$departments);
    }


    public function showSlip($employee_id, $year, $month)
    {
        $month = date('m', strtotime($month));
        $employee = Employee::with([
            'job',
            'department',
            'constantPayrollItems.payrollItemType', // Eager-load payrollItemType for constants
            'payrollTransactions.payrollItemType', // Eager-load payrollItemType for transactions
        ])->findOrFail($employee_id);

        // Group additions and deductions
//        $constantAdditions = $employee->constantPayrollItems->where('type', 0);
        $constantAdditions = DB::table('employee_constant_payroll_items as ec')
            ->leftJoin('payroll_item_types as pit','pit.id','ec.payroll_item_type_id')
            ->where('ec.employee_id',$employee_id)
            ->where('pit.type',0)
            ->where('pit.archived',0)
            ->get();

//        $constantDeductions = $employee->constantPayrollItems->where('type', 1);
        $constantDeductions = DB::table('employee_constant_payroll_items as ec')
            ->leftJoin('payroll_item_types as pit','pit.id','ec.payroll_item_type_id')
            ->where('ec.employee_id',$employee_id)
            ->where('pit.type',1)
            ->where('pit.archived',0)
            ->get();

//        $changedAdditions = $employee->payrollTransactions->where('type', 0);
        $changedAdditions = DB::table('payroll_transactions as t')
            ->leftJoin('payroll_item_types as pit','pit.id','t.payroll_item_type_id')
            ->where('t.employee_id',$employee_id)
            ->where('pit.type',0)
            ->where('pit.archived',0)
            ->get();

//        $changedDeductions = $employee->payrollTransactions->where('type', 1);
        $changedDeductions = DB::table('payroll_transactions as t')
            ->leftJoin('payroll_item_types as pit','pit.id','t.payroll_item_type_id')
            ->where('t.employee_id',$employee_id)
            ->where('pit.type',1)
            ->where('pit.archived',0)
            ->get();

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
