<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class TransactionProduct extends Model
{
    //

    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'date',
        'updated_at' => 'datetime',
        'type' => 'string',
    ];

    protected $attributes = [
        'type' => 'in', // Default type is 'in'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($transactionProduct) {
            $transactionProduct->type = $transactionProduct->type ?: 'in'; // Ensure type is set

            if (! $transactionProduct->user_id) {
                $transactionProduct->user_id = auth()->id(); // Set user_id to the authenticated user
            }
        });
    }

    public function getStockAttribute()
    {
        return $this->type === 'in' ? $this->quantity : -$this->quantity;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function datatables()
    {
        return DataTables::of(self::query()->with(['product', 'supplier', 'user']))
            ->addColumn('product', function ($transactionProduct) {
                return $transactionProduct->product ? $transactionProduct->product->name : '-';
            })
            ->addColumn('supplier', function ($transactionProduct) {
                return $transactionProduct->supplier ? $transactionProduct->supplier->name : '-';
            })
            ->addColumn('user', function ($transactionProduct) {
                return $transactionProduct->user ? $transactionProduct->user->name : '-';
            })
            ->make(true);
    }
}
