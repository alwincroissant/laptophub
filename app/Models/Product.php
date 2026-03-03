<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $timestamps = true;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'description',
        'compatibility',
        'image_url',
        'price',
        'stock_qty',
        'low_stock_threshold',
        'is_archived',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_archived' => 'boolean',
    ];
}
