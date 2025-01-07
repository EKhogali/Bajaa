<?php

namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::where('company_id',session::get('company_id'))->get();
        return view('payroll.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('payroll.jobs.create');
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

        Job::create([
            'name' => $request->name,
            'archived' => $request->archived ?? 0,
            'company_id' => session()->get('company_id'),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect('/jobs')->with('success', 'تم إنشاء القسم بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        $jobs = Job::findOrFail($job->id);
        return view('payroll.jobs.edit', compact('job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        $request->validate([
            'name' => 'required|string|max:255',
//            'company_id' => 'required|exists:companies,id',
//            'archived' => 'nullable|boolean', // Validate archived as a boolean
        ]);

        $job->update([
            'name' => $request->name,
            'archived' => $request->archived ?? $job->archived, // Keep existing value if not provided
            'updated_by' => auth()->id(), // Set the updater's ID
        ]);

        return redirect('/jobs')->with('success', 'تم تعديل القسم بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        if(
        \DB::table('employees')->where('job_id',$job->id)->doesntExist()){

            \DB::table('jobs')->where('id',$job->id)->delete();
            $msg = 'تمت العملية بنجاح';
            session::put('msgtype','success') ;
        }else {
            $msg = 'عفواً، لا يمكن اجراء عملية الالغاء لارتباط العنصر ببيانات اخرى';
            session::put('msgtype','notsuccess') ;
        }

        return back()->with('message', $msg);

    }
}
