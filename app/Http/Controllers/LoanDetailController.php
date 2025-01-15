<?php

namespace App\Http\Controllers;

use App\loan_detail;
use App\loan_header;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanDetailController extends Controller
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
    public function generate()
    {
        // Retrieve the loan header by ID
        $loanHeader = loan_header::findOrFail(request('loan_id'));

        // Calculate monthly deduction
        $monthlyAmount = $loanHeader->amount / $loanHeader->months;

        // Starting date from the loan header
        $startDate = Carbon::create($loanHeader->start_year, $loanHeader->start_month);

        // Prepare batch inserts
        $details = [];
        for ($x = 0; $x < $loanHeader->months; $x++) {
            $currentDate = $startDate->copy()->addMonths($x);

            $details[] = [
                'loan_header_id' => $loanHeader->id,
                'year' => $currentDate->year,
                'month' => $currentDate->month,
                'amount' => $monthlyAmount,
                'done' => false,
                'company_id' => $loanHeader->company_id,
                'archived' => false,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert details in a single batch
        DB::table('loan_details')->insert($details);

        // Fetch loan details to pass to the view
        $loanDetails = loan_detail::where('loan_header_id', $loanHeader->id)->get();

        // Redirect to the show view with loan details
        return view('payroll.loans.show', compact('loanDetails', 'loanHeader'))
            ->with('success', 'تم توليد الخصومات الشهرية بنجاح.');
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
//    public function store(Request $request)
//    {
//        //
//    }

    public function store(Request $request)
    {
//        $validated = $request->validate([
//            'loan_header_id' => 'required|exists:loan_headers,id',
//            'year' => 'required|integer|min:2000',
//            'month' => 'required|integer|between:1,12',
//            'amount' => 'required|numeric|min:0',
//            'done' => 'nullable|boolean',
//        ]);

        $startDate = $request->input('month');
        list($year, $month) = explode('-', $startDate);

        DB::table('loan_details')->insert([
            'loan_header_id'=>Request('loan_header_id'),
            'year'=>$year,
            'month'=>$month,
            'amount'=>Request('amount'),
            'company_id'=>session()->get('company_id'),
//            'archived'=>0,
            'created_by'=>auth()->id(),
            'updated_by'=>auth()->id(),
        ]);


        return redirect()->back()->with('message', 'تم إضافة الخصم بنجاح!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\loan_detail  $loan_detail
     * @return \Illuminate\Http\Response
     */
    public function show(loan_detail $loan_detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\loan_detail  $loan_detail
     * @return \Illuminate\Http\Response
     */
    public function edit(loan_detail $loan_detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\loan_detail  $loan_detail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, loan_detail $loan_detail)
    {
        $startDate = $request->input('month');
        list($year, $month) = explode('-', $startDate);

        DB::table('loan_details')->where('id',$loan_detail->id)
            ->update([
                'year'=>$year,
                'month'=>$month,
                'amount'=>Request('amount'),
                'archived'=>Request()->has('archived'),
                'done'=>Request()->has('done'),
                'updated_by'=>auth()->id(),
            ]);
        return redirect()->back()->with('message', 'تم تعديل الخصم بنجاح!');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\loan_detail  $loan_detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(loan_detail $loan_detail)
    {
        $loan_detail->delete();
        return redirect()->back()->with('message', 'تم حذف الخصم بنجاح!');
    }
}
