<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    public $incrementing = true;

    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'full_name',
        'email',
        'password_hash',
        'contact_number',
        'address',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password_hash',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'password_hash' => 'hashed',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    /**
     * Get the role that the user belongs to.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'user_id');
    }

    public function defaultAddress()
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'user_id')->where('is_default', true);
    }
}
