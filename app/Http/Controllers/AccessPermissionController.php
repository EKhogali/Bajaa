<?php

namespace App\Http\Controllers;

use App\User;
use App\user_permission;
use Illuminate\Http\Request;

class AccessPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function guardManagerOnly(): void
    {
        if (!in_array(auth()->user()->type, [1, 2], true)) {
            abort(403);
        }
    }

    public function index()
    {
        $this->guardManagerOnly();

        $users = User::where('type', '!=', 1)->orderBy('name')->get();
        $modules = config('access_modules');

        $grants = user_permission::whereIn('user_id', $users->pluck('id'))
            ->get()
            ->groupBy('user_id')
            ->map(fn ($rows) => $rows->pluck('permission_name')->toArray());

        return view('sys.access_permissions.index', compact('users', 'modules', 'grants'));
    }

    public function update(Request $request, $id)
    {
        $this->guardManagerOnly();

        $user = User::findOrFail($id);
        $validKeys = array_keys(config('access_modules'));
        $selected = array_values(array_intersect($request->input('modules', []), $validKeys));

        user_permission::where('user_id', $user->id)
            ->whereIn('permission_name', $validKeys)
            ->delete();

        foreach ($selected as $key) {
            user_permission::create([
                'user_id' => $user->id,
                'permission_name' => $key,
            ]);
        }

        return redirect()->route('access_permissions.index')
            ->with('success', 'تم تحديث صلاحيات المستخدم بنجاح.');
    }
}