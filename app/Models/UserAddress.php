<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_addresses';
    protected $primaryKey = 'address_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'region',
        'city',
        'postal_code',
        'street_address',
        'is_default',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function formattedAddress(): string
    {
        return "{$this->street_address}, {$this->city}, {$this->region} {$this->postal_code}";
    }
}
