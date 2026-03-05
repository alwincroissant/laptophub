<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $table = 'brands';
    protected $primaryKey = 'brand_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', 'brand_id');
    }
}
