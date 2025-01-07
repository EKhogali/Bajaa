<?php

namespace App\Http\Controllers;

use App\Department;
use App\Employee;
use App\Job;
use App\Payroll_item_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::where('company_id',session::get('company_id'))->get();
        return view('payroll.employees.index')->with('employees',$employees);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::where('company_id',session::get('company_id'))->get();
        $jobs = Job::where('company_id',session::get('company_id'))->get();
        return view('payroll.employees.create')
            ->with('departments',$departments)
            ->with('jobs',$jobs)
            ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:employees,code',
//            'job_id' => 'required|exists:jobs,id',
//            'department_id' => 'required|exists:departments,id',
//            'basic_salary' => 'required|numeric|min:0',
//            'hire_date' => 'required|date',
//            'gender' => 'required|integer|in:0,1', // 0: Male, 1: Female
//            'dob' => 'required|date|before:today',
//            'marital_state_id' => 'required|integer|in:0,1,2,3', // 0: Single, 1: Married, 2: Divorced, 3: Widow
//            'archived' => 'required|boolean',
        ]);

        Employee::create([
            'name' => $request->name,
            'code' => $request->code,
            'job_id' => $request->job_id,
            'department_id' => $request->department_id,
            'basic_salary' => $request->basic_salary,
            'hire_date' => $request->hire_date,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'marital_state_id' => $request->marital_state_id,
            'archived' => $request->archived,
            'company_id' => session()->get('company_id'), // Assuming `company_id` is stored in the session
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('employees.index')->with('success', 'تم إضافة الموظف بنجاح.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        $constantPayrollItems = $employee->constantPayrollItems;
        $payroll_item_types = Payroll_item_type::where('archived',0)->get();

        return view('payroll.employees.show', [
            'employee' => $employee,
            'constantPayrollItems' => $constantPayrollItems,
            'payroll_item_types' => $payroll_item_types,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
