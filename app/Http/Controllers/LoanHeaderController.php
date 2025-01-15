<?php

namespace App\Http\Controllers;

use App\Employee;
use App\loan_header;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoanHeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loanHeaders = loan_header::where('company_id', session('company_id'))->with('employee')->get();
        return view('payroll.loans.index', compact('loanHeaders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::where('company_id', session('company_id'))
            ->where('archived',0)
            ->get();
        return view('payroll.loans.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'descrpt' => 'nullable|string',
            'amount' => 'required|numeric',
//            'months' => 'required|integer',
//            'start_year' => 'required|integer',
//            'start_month' => 'required|integer',
//            'employee_id' => 'required|exists:employees,id',
        ]);
        $startDate = explode('-', $request->start_date);
        loan_header::create([
            'descrpt' => $request->descrpt,
            'amount' => $request->amount ?? 0,
            'months' => $request->months ?? 0,
            'start_year' => $startDate[0] ?? 0,
            'start_month' => $startDate[1] ?? 0,
            'employee_id' => $request->employee_id,
            'archived' => $request->archived,
            'company_id' => session()->get('company_id'),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);


        return redirect()->route('loan_headers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\loan_header  $loan_header
     * @return \Illuminate\Http\Response
     */
    public function show(loan_header $loan_header)
    {
        $loanHeader = loan_header::with('loanDetails')->findOrFail($loan_header->id);

        return view('payroll.loans.show', compact('loanHeader'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\loan_header  $loan_header
     * @return \Illuminate\Http\Response
     */
    public function edit(loan_header $loan_header)
    {
        $employees = Employee::where('company_id', session('company_id'))->get();
        return view('payroll.loans.edit', compact(['loan_header','employees']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\loan_header  $loan_header
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, loan_header $loan_header)
    {
        $request->validate([
            'descrpt' => 'required|string|max:255',
        ]);

        $startDate = explode('-', $request->start_date);
        $loan_header->update([
            'descrpt' => $request->descrpt,
            'amount' => $request->amount ?? 0,
            'months' => $request->months ?? 0,
            'start_year' => $startDate[0] ?? 0,
            'start_month' => $startDate[1] ?? 0,
            'employee_id' => $request->employee_id,
            'archived' => $request->archived,
            'updated_by' => auth()->id(),
        ]);

        return redirect('/loans')->with('success', 'تم تعديل القسم بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\loan_header  $loan_header
     * @return \Illuminate\Http\Response
     */
    public function destroy(loan_header $loan_header)
    {
        if(1==1){

            \DB::table('loan_headers')->where('id',$loan_header->id)->delete();
            $msg = 'تمت العملية بنجاح';
            session::put('msgtype','success') ;
        }else {
            $msg = 'عفواً، لا يمكن اجراء عملية الالغاء لارتباط العنصر ببيانات اخرى';
            session::put('msgtype','notsuccess') ;
        }

        return back()->with('message', $msg);
    }
}
