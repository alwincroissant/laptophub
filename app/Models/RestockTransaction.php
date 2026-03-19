<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockTransaction extends Model
{
    protected $table = 'restock_transactions';
    protected $primaryKey = 'restock_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'supplier_id',
        'managed_by',
        'quantity_added',
        'unit_cost',
        'restocked_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'restocked_at' => 'datetime',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'managed_by', 'user_id');
    }
}
