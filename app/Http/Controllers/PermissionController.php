<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:gestionar roles y permisos']);
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        Permission::create($request->only('name'));

        return redirect()->route('roles.index')->with('success', 'Permiso creado exitosamente.');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id
        ]);

        $permission->update($request->only('name'));

        return redirect()->route('roles.index')->with('success', 'Permiso actualizado exitosamente.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('roles.index')->with('success', 'Permiso eliminado exitosamente.');
    }
}

