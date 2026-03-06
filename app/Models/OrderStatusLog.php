<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    protected $table = 'order_status_logs';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'status_id',
        'changed_by',
        'changed_at',
        'note',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id', 'status_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by', 'user_id');
    }
}
