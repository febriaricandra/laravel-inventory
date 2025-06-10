<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class Privilege extends Role
{
    //

    public static function datatables()
    {

        return DataTables::of(self::query())
            ->addColumn('permissions', function (Role $role) {
                return $role->permissions->pluck('name')->implode(', ');
            })
            ->addColumn('actions', function (Role $role) {
                $actionbtn = '<a href="' . route('roles.edit', $role->id) . '" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Edit</a> ';
                $actionbtn .= '<form action="' . route('roles.destroy', $role->id) . '" method="POST" style="display:inline-block;" onsubmit="return confirm(\'Are you sure you want to delete this role?\');">';
                $actionbtn .= csrf_field();
                $actionbtn .= method_field('DELETE');
                $actionbtn .= '<button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">Delete</button>';
                $actionbtn .= '</form>';
                return $actionbtn;
            })
            ->rawColumns(['permissions', 'actions'])
            ->make(true);
    }
}
