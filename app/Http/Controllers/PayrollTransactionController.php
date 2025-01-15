<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Payroll_item_type;
use App\Payroll_transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PayrollTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $monthYear = request()->query('month', now()->format('Y-m'));
        [$year, $month] = explode('-', $monthYear);

        $transactions = Payroll_transaction::with(['employee', 'company', 'payrollItemType'])
            ->where('year', $year)
            ->where('month', $month)
            ->get();

        return view('payroll.payroll_transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::where('company_id',session::get('company_id'))
            ->where('archived',0)
            ->get();
        $payrollItemTypes = Payroll_item_type::where('archived',0)
            ->get();

        return view('payroll.payroll_transactions.create', compact('employees', 'payrollItemTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        [$year, $month] = explode('-', $request->month);

        Payroll_transaction::create([
            'year' => $year,
            'month' => $month,
            'amount' => $request->amount,
            'employee_id' => $request->employee_id,
            'notes' => $request->notes,
            'company_id' => \session()->get('company_id'),
            'payroll_item_type_id' => $request->payroll_item_type_id,
            'archived' => 0,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('payroll_transaction.index')->with('message', 'تمت الإضافة بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payroll_transaction  $payroll_transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Payroll_transaction $payroll_transaction)
    {
        $employees = Employee::where('company_id',session::get('company_id'))
            ->where('archived',0)
            ->get();
        $payrollItemTypes = Payroll_item_type::where('archived',0)
            ->get();

        return view('payroll.payroll_transactions.edit', compact('employees', 'payrollItemTypes','payroll_transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payroll_transaction  $payroll_transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Payroll_transaction $payroll_transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payroll_transaction  $payroll_transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payroll_transaction $payroll_transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payroll_transaction  $payroll_transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payroll_transaction $payroll_transaction)
    {
        //
    }
}
