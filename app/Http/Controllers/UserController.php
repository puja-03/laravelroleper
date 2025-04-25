<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller  implements HasMiddleware
{
public static function middleware(): array
{
    return [
        new Middleware(('permission:view user'),only: ['index']),
           new Middleware(('permission:edit users'),only: ['edit', 'update']),
        ];
}
    public function index()
{
    $users = User::with('roles')->orderBy('created_at', 'desc')->paginate(10);
    return view('user.list', compact('users'));
}
    public function create()
    {
    }
    
    public function edit($id)
{
    $user = User::with('roles')->findOrFail($id);
    $roles = Role::all();
    return view('user.edit', compact('user', 'roles')); // Changed from user.form to user.edit
}

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'roles' => 'nullable|array',
        'roles.*' => 'exists:roles,id'
    ]);

    if ($validator->passes()) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    return redirect()->back()
        ->withErrors($validator)
        ->withInput($request->except('password'));
}
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);
    
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        'password' => 'nullable|string|min:8',
        'roles' => 'nullable|array',
        'roles.*' => 'exists:roles,id'
    ]);

    if ($validator->passes()) {
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Get valid roles from database
        if ($request->has('roles')) {
            $validRoles = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
            $user->syncRoles($validRoles);
        } else {
            $user->syncRoles([]); // Remove all roles if none selected
        }

        return redirect()->route('user.index')
            ->with('success', 'User updated successfully.');
    }

    return redirect()->back()
        ->withErrors($validator)
        ->withInput($request->except('password'));
}
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting your own account
        if ($user->id === auth()->id()) {
            return redirect()->route('user.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->syncRoles([]); // Remove all roles first
        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'User deleted successfully.');
    }
 }