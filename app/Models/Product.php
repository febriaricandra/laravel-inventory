<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Category;


class Product extends Model
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

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getPriceAttribute($value)
    {
        return number_format($value, 2, ',', '.'); // Format price as currency (Rupiah)
    }

    public static function datatables()
    {
        return DataTables::of(self::query())
            ->addColumn('categories', function ($product) {
                return $product->categories ? $product->categories->name : '-';
            })
            ->addColumn('image', function ($product) {
                // Remove 'public' from the path since it's already handled by the storage:link
                return $product->image ? '<img src="' . asset('storage/products/' . $product->image) . '" alt="' . $product->name . '" class="w-16 h-16 object-cover">' : '-';
            })
            ->addColumn('actions', function ($product) {
                $actionbtn = '<a href="' . route('products.edit', $product->id) . '" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Edit</a> ';
                $actionbtn .= '<form action="' . route('products.destroy', $product->id) . '" method="POST" style="display:inline-block;" onsubmit="return confirm(\'Are you sure you want to delete this product?\');">';
                $actionbtn .= csrf_field();
                $actionbtn .= method_field('DELETE');
                $actionbtn .= '<button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">Delete</button>';
                $actionbtn .= '</form>';
                return $actionbtn;
            })
            ->rawColumns(['actions', 'image'])
            ->make(true);
    }
}
