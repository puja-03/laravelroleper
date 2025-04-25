<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;  // Add this import
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
           new Middleware(('permission:view permissions'),only: ['index']),
           new Middleware(('permission:create permissions'),only: ['create', 'store']),
           new Middleware(('permission:edit permissions'),only: ['edit', 'update']),
           new Middleware(('permission:delete permissions'),only: ['destroy']),
        ];
    }
    public function index()
    {
        $permissions = Permission::orderBy('created_at', 'desc')->paginate(10);
        return view('permissions.list',[
            'permissions' => $permissions
        ]);
    }
    public function create()
    {
        return view('permissions.create');
    }

    public function edit($id)
{
    $permission = Permission::findOrFail($id);
    return view('permissions.create', compact('permission'));
}

public function update(Request $request, $id)
{
    $permission = Permission::findOrFail($id);
    
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:permissions,name,' . $id,
    ]);

    if ($validator->passes()) {
        $permission->update([
            'name' => $request->input('name')
        ]);
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }
    
    return redirect()->back()->withInput()->withErrors($validator);
}

    // public function show($id)
    // {
    //     return view('permissions.show', ['id' => $id]);
    // }

    public function show($id)
{
    $permission = Permission::with('roles')->findOrFail($id);
    return view('permissions.show', compact('permission'));
}
    public function store(Request $request)   
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions',
        ]);
    
        if ($validator->passes()) { 
            Permission::create([
                'name' => $request->input('name')
            ]); 
            return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
        }
        else{ 
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);  // Fixed route name
        }
    }
    public function destroy($id)
    {
        // Logic to delete the permission
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}