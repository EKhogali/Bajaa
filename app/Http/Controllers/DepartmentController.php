<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::where('company_id',session::get('company_id'))->get();
        return view('payroll.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('payroll.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
//            'company_id' => 'required|exists:companies,id',
//            'archived' => 'nullable|boolean', // Validate archived as a boolean
        ]);

        // Create the department
        Department::create([
            'name' => $request->name,
            'archived' => $request->archived ?? 0,
            'company_id' => session()->get('company_id'),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect('/departments')->with('success', 'تم إنشاء القسم بنجاح.');
//        return redirect()->route('payroll.departments.index')->with('success', 'تم إنشاء القسم بنجاح.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        $department = Department::findOrFail($department->id);
        return view('payroll.departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
//            'company_id' => 'required|exists:companies,id',
//            'archived' => 'nullable|boolean', // Validate archived as a boolean
        ]);

        $department->update([
            'name' => $request->name,
            'archived' => $request->archived ?? $department->archived, // Keep existing value if not provided
            'updated_by' => auth()->id(), // Set the updater's ID
        ]);

//        return redirect()->route('payroll.departments.index')->with('success', 'تم تحديث القسم بنجاح.');
        return redirect('/departments')->with('success', 'تم تعديل القسم بنجاح.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        if(
        \DB::table('employees')->where('department_id',$department->id)->doesntExist()){

            \DB::table('departments')->where('id',$department->id)->delete();
            $msg = 'تمت العملية بنجاح';
            session::put('msgtype','success') ;
        }else {
            $msg = 'عفواً، لا يمكن اجراء عملية الالغاء لارتباط العنصر ببيانات اخرى';
            session::put('msgtype','notsuccess') ;
        }

        return back()->with('message', $msg);
//        return redirect('/departments');
    }
}
