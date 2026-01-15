<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company_name',
        'business_type',
        'business_description',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'subscription_plan',
        'subscription_status',
        'subscription_start_date',
        'subscription_end_date',
        'monthly_fee',
        'number_of_locations',
        'number_of_terminals',
        'notes',
        'status',
        'source',
        'assigned_sales_rep',
        'last_login_at',
        'phone_verified_at',
    ];

    protected $hidden = [
        'password',
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
        return $this->hasMany(Order::class, 'user_id');
    }

    public function getDefaultAddressAttribute()
    {
        return $this->addresses()->where('is_default', true)->first();
    }
}