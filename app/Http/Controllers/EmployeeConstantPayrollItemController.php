<?php

namespace App\Http\Controllers;

use App\Employee_constant_payroll_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EmployeeConstantPayrollItemController extends Controller
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
//        $request->validate([
//            'descrpt' => 'required|string|max:255',
//            'type' => 'required|integer|in:0,1', // 0 for addition, 1 for subtraction
//        ]);
//        dd($request->all());
        Employee_constant_payroll_item::create([
            'employee_id' => $request->employee_id,
            'descrpt' => $request->descrpt,
            'amount' => $request->amount,
            'payroll_item_type_id' => $request->payroll_item_type_id,
            'archived' => false,
        ]);

        return redirect()->back()->with('success', 'تمت إضافة العنصر بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee_constant_payroll_item  $employee_constant_payroll_item
     * @return \Illuminate\Http\Response
     */
    public function show(Employee_constant_payroll_item $employee_constant_payroll_item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee_constant_payroll_item  $employee_constant_payroll_item
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee_constant_payroll_item $employee_constant_payroll_item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee_constant_payroll_item  $employee_constant_payroll_item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee_constant_payroll_item $employee_constant_payroll_item)
    {
        $request->validate([
            'descrpt' => 'required|string|max:255',
            'type' => 'required|integer|in:0,1',
        ]);
//dd($employee_constant_payroll_item,$request->descrpt,$request->type,$request->amount );
//        $payrollItemLabel->update([
            $employee_constant_payroll_item->update([
            'descrpt' => $request->descrpt,
//            'type' => $request->type,
            'amount' => $request->amount,
//            'archived' => $request->archived,
        ]);

        return redirect()->back()->with('success', 'تم تحديث العنصر بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee_constant_payroll_item  $employee_constant_payroll_item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee_constant_payroll_item $employee_constant_payroll_item)
    {
        if(
        \DB::table('employee_constant_payroll_items')->where('id',0)->doesntExist()){

            \DB::table('employee_constant_payroll_items')->where('id',$employee_constant_payroll_item->id)->delete();
            $msg = 'تمت العملية بنجاح';
            session::put('msgtype','success') ;
        }else {
            $msg = 'عفواً، لا يمكن اجراء عملية الالغاء لارتباط العنصر ببيانات اخرى';
            session::put('msgtype','notsuccess') ;
        }

        return back()->with('message', $msg);
    }
}
