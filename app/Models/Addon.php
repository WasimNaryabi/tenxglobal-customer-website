<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'api_id',
        'addon_group_id',
        'api_group_id',
        'name',
        'description',
        'price',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    /**
     * Get the addon group
     */
    public function addonGroup()
    {
        return $this->belongsTo(AddonGroup::class);
    }

    /**
     * Scope to get only active addons
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}