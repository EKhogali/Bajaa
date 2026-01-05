<?php

namespace App\Http\Controllers;

use App\User;
use App\user_permission;
use Illuminate\Http\Request;



class UserPermissionController extends Controller
{

private array $allowedPermissions = [
    'account_details_report',
];


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
        return \view('sys.users.user_permissions.create', [
            'user_id' => Request()->user_id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     \DB::table('user_permissions')->insert([
    //         'user_id' => Request()->user_id
    //         ,
    //         'permission_name' => Request()->permission_name ?? ''
    //     ]);

    //     $user = User::find(Request()->user_id);
    //     $user_permissions = user_permission::where('user_id', $user->id)->get();


    //     return view('sys.users.show', [
    //         'user' => $user
    //         ,
    //         'user_permissions' => $user_permissions
    //     ]);
    // }

public function store(Request $request)
{
    // Allow only admin (type 1) or supervisor (type 2)
    if (!(auth()->user()->type == 1 || auth()->user()->type == 2)) {
        abort(403, 'Unauthorized');
    }

    $request->validate([
        'user_id' => 'required|exists:users,id',
        'permission_name' => 'required|string|max:255',
    ]);

    // ✅ Check if permission is predefined
    if (!in_array($request->permission_name, $this->allowedPermissions)) {
        return redirect()->back()->withErrors(['permission_name' => 'صلاحية غير مسموح بها']);
    }

    // ✅ Prevent duplicates
    $exists = user_permission::where('user_id', $request->user_id)
        ->where('permission_name', $request->permission_name)
        ->exists();

    if ($exists) {
        return redirect()->back()->withErrors(['permission_name' => 'هذه الصلاحية موجودة بالفعل لهذا المستخدم']);
    }

    user_permission::create([
        'user_id' => $request->user_id,
        'permission_name' => $request->permission_name,
    ]);

    return redirect()->back()->with('message', 'تمت إضافة الصلاحية بنجاح');
}


    /**
     * Display the specified resource.
     *
     * @param  \App\user_permission  $user_permission
     * @return \Illuminate\Http\Response
     */
    public function show(user_permission $user_permission)
    {
        //account_details_report
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\user_permission  $user_permission
     * @return \Illuminate\Http\Response
     */
    public function edit(user_permission $user_permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\user_permission  $user_permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, user_permission $user_permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\user_permission  $user_permission
     * @return \Illuminate\Http\Response
     */
    // public function destroy(user_permission $user_permission)
    // {
    //     \DB::table('user_permissions')
    //         ->where('id', $user_permission->id)
    //         ->delete();
    // }

    public function destroy($id)
{
    // Allow only admin (type 1) or supervisor (type 2)
    if (!(auth()->user()->type == 1 || auth()->user()->type == 2)) {
        abort(403, 'Unauthorized');
    }

    $permission = user_permission::findOrFail($id);
    $permission->delete();

    return redirect()->back()->with('message', 'تم حذف الصلاحية بنجاح');
}

}
