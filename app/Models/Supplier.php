<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Supplier extends Model
{
    //

    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function datatables()
    {
        $activeUser = auth()->user();

        return DataTables::of(self::query())
            ->addColumn('actions', function ($supplier) use ($activeUser) {
                $actionbtn = '';
                if ($activeUser->can('suppliers.edit')) {
                    $actionbtn .= '<a href="'.route('suppliers.edit', $supplier->id).'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Edit</a> ';
                }
                if ($activeUser->can('suppliers.delete')) {
                    $actionbtn .= '<form action="'.route('suppliers.destroy', $supplier->id).'" method="POST" style="display:inline-block;" onsubmit="return confirm(\'Are you sure you want to delete this supplier?\');">';
                    $actionbtn .= csrf_field();
                    $actionbtn .= method_field('DELETE');
                    $actionbtn .= '<button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">Delete</button>';
                    $actionbtn .= '</form>';
                }

                return $actionbtn;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
