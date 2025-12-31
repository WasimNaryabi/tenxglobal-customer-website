<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'phone',
        'name',
        'email',
        'phone_verified_at',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getDefaultAddressAttribute()
    {
        return $this->addresses()->where('is_default', true)->first();
    }
}