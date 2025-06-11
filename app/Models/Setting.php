<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Setting extends Model
{
    protected $fillable = [
        'configKey',
        'configValue',
        'name',
        'path',
    ];

    public static function getSetting($key)
    {
        return self::where('configKey', $key)->first();
    }

    public static function datatables()
    {
        return DataTables::of(self::query())
            ->addColumn('actions', function ($setting) {
                $actionbtn = '<a href="'.route('settings.edit', $setting->id).'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Edit</a> ';

                return $actionbtn;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
