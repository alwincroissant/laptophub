<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use SoftDeletes, Searchable;

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

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray()
    {
        return [
            'product_id' => $this->product_id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    protected $casts = [
        'price' => 'decimal:2',
        'is_archived' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function supplierProducts()
    {
        return $this->hasMany(SupplierProduct::class, 'product_id', 'product_id');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_products', 'product_id', 'supplier_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id')
            ->orderBy('sort_order', 'asc')
            ->orderBy('image_id', 'asc');
    }
}
