<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    
    public static function middleware(): array
    {
        return [
           new Middleware(('permission:view roles'),only: ['index','list']),
           new Middleware(('permission:create roles'),only: ['store', 'create']),
           new Middleware(('permission:edit roles'),only: ['edit', 'update']),
           new Middleware(('permission:delete roles'),only: ['destroy']),
        ];
    }
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('created_at', 'desc')->paginate(10);
        return view('roles.list', compact('roles'));
    }
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:roles',
        'permissions' => 'nullable|array',
        'permissions.*' => 'exists:permissions,id'
    ]);

    if ($validator->passes()) {
        // Create the role with web guard
        $role = Role::create([
            'name' => $request->input('name'),
            'guard_name' => 'web'  // Explicitly set guard name
        ]);

        // Get permissions with web guard
        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)
                                  ->where('guard_name', 'web')
                                  ->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    return redirect()->route('roles.create')
        ->withInput()
        ->withErrors($validator);
}

public function edit($id)
{
    $role = Role::findOrFail($id);
    $permissions = Permission::where('guard_name', 'web')->get();
    return view('roles.create', compact('role', 'permissions'));
}
public function update(Request $request, $id)
{
    $role = Role::findOrFail($id);
    
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:roles,name,' . $id,
        'permissions' => 'nullable|array',
        'permissions.*' => 'exists:permissions,id'
    ]);

    if ($validator->passes()) {
        $role->update([
            'name' => $request->input('name')
        ]);

        // Sync permissions with web guard
        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)
                                  ->where('guard_name', 'web')
                                  ->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]); // Remove all permissions if none selected
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    return redirect()->back()
        ->withInput()
        ->withErrors($validator);
}

public function create()
{
    $permissions = Permission::where('guard_name', 'web')->get();
    return view('roles.create', compact('permissions'));
}

public function destroy($id)
{
    try {
        $role = Role::findOrFail($id);
        
        $role->syncPermissions([]);
        $role->delete();

        return redirect()->route('roles.list')
            ->with('success', 'Role deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->route('roles.index')->with('error', 'Error deleting role. ' . $e->getMessage());
    }
}
public function show($id)
{
    $role = Role::with('permissions')->findOrFail($id);
    return view('roles.show', compact('role'));
}
   
}