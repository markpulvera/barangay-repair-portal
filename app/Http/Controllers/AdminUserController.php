<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function updateRole(Request $request, $id)
{
    $request->validate([
        'role' => 'required|in:admin,resident,user',
    ]);

    $user = User::findOrFail($id);
    
    // Set role to 'resident' (or 'user' depending on what your database uses)
    $user->role = $request->input('role');
    $user->save();

    return redirect()->back()->with('success', 'User role updated successfully.');
}
}