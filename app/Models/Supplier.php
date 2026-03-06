<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'contact_name',
        'contact_email',
        'contact_phone',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function supplierProducts()
    {
        return $this->hasMany(SupplierProduct::class, 'supplier_id', 'supplier_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'supplier_products', 'supplier_id', 'product_id');
    }
}
