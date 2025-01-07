<?php

namespace App\Http\Controllers;

use App\Payroll_item_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PayrollItemTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payrollItemTypes = Payroll_item_type::all();
        return view('payroll.payroll_items.index')->with('payrollItemTypes',$payrollItemTypes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('payroll.payroll_items.create');
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
//            'company_id' => 'required|exists:companies,id',
//            'archived' => 'nullable|boolean', // Validate archived as a boolean
        ]);

        Payroll_item_type::create([
            'name' => $request->name,
            'type' => $request->type ?? 0,
            'archived' => $request->archived ?? 0,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect('/payroll_item_type')->with('success', 'تم إنشاء القسم بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payroll_item_type  $payroll_item_type
     * @return \Illuminate\Http\Response
     */
    public function show(Payroll_item_type $payroll_item_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payroll_item_type  $payroll_item_type
     * @return \Illuminate\Http\Response
     */
    public function edit(Payroll_item_type $payroll_item_type)
    {
        $payrollItemType = Payroll_item_type::findOrFail($payroll_item_type->id);
        return view('payroll.payroll_items.edit', compact('payrollItemType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payroll_item_type  $payroll_item_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payroll_item_type $payroll_item_type)
    {
        $request->validate([
            'name' => 'required|string|max:255',
//            'company_id' => 'required|exists:companies,id',
//            'archived' => 'nullable|boolean', // Validate archived as a boolean
        ]);

        $payroll_item_type->update([
            'name' => $request->name,
            'archived' => $request->archived ?? $payroll_item_type->archived,
            'type' => $request->type,
            'updated_by' => auth()->id(),
        ]);

        return redirect('/payroll_item_type')->with('success', 'تم تعديل القسم بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payroll_item_type  $payroll_item_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payroll_item_type $payroll_item_type)
    {
        if(
        \DB::table('payroll_transactions')->where('payroll_item_type_id',$payroll_item_type->id)->doesntExist()){

            \DB::table('payroll_item_types')->where('id',$payroll_item_type->id)->delete();
            $msg = 'تمت العملية بنجاح';
            session::put('msgtype','success') ;
        }else {
            $msg = 'عفواً، لا يمكن اجراء عملية الالغاء لارتباط العنصر ببيانات اخرى';
            session::put('msgtype','notsuccess') ;
        }

        return back()->with('message', $msg);
    }
}
