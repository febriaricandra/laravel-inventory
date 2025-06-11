<?php

namespace App\Http\Controllers;

use App\Models\Privilege;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $resource = 'roles';

    public function index()
    {
        return view("{$this->resource}.index");
    }

    public function useDatatables()
    {
        return (new Privilege)->datatables();
    }

    public function show(Role $role)
    {
        $permissions = $role->permissions;

        return view("{$this->resource}.show", compact('role', 'permissions'));
    }

    public function create()
    {
        $permissions = Permission::all();

        return view("{$this->resource}.create", compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route("{$this->resource}.index")->with('success', 'Role created successfully');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();

        return view("{$this->resource}.edit", compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'permissions' => 'required|array',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route("{$this->resource}.index")->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route("{$this->resource}.index")->with('success', 'Role deleted successfully');
    }
}
