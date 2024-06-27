<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function search(Request $request)
    {

        $user = User::find($request->input('user_id'));
        $roles = ['none', 'admin', 'manager'];

        return view('admin.users.index', compact('user', 'roles'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:none,admin,manager',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $user->role = $request->input('role');
        $user->save();

        return back()->with('success', 'Роль пользователя успешно обновлена.');
    }
}
