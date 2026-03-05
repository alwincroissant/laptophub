<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_statuses';
    protected $primaryKey = 'status_id';
    public $timestamps = false;

    protected $fillable = [
        'status_name',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'status_id', 'status_id');
    }
}
