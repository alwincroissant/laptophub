<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierProduct extends Model
{
    protected $table = 'supplier_products';
    protected $primaryKey = 'supplier_product_id';
    public $timestamps = false;

    protected $fillable = [
        'supplier_id',
        'product_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
