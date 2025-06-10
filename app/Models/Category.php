<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Category extends Model
{
    // Guarded property to allow mass assignment
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

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function datatables()
    {
        return DataTables::of(self::query())
        ->addColumn('actions', function ($category) {
            $editUrl = route('categories.edit', $category->id);
            $deleteUrl = route('categories.destroy', $category->id); // Assuming you have a route for destroy

            $actionbtn = '<a href="' . $editUrl . '" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Edit</a> ';
            
            // For delete, it's best to use a form to send a DELETE request
            // or use JavaScript to make an AJAX call.
            // Here's a simple button that you'd typically enhance with JavaScript.
            $actionbtn .= '<form action="' . $deleteUrl . '" method="POST" style="display:inline-block;" onsubmit="return confirm(\'Are you sure you want to delete this category?\');">';
            $actionbtn .= csrf_field();
            $actionbtn .= method_field('DELETE');
            $actionbtn .= '<button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">Delete</button>';
            $actionbtn .= '</form>';
            
            return $actionbtn;
        })
        ->rawColumns(['actions'])
        ->toJson();
    }
}