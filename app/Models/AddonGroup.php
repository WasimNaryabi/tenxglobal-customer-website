<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddonGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'api_id',
        'name',
        'description',
        'min_selections',
        'max_selections',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'api_id' => 'integer',
        'min_selections' => 'integer',
        'max_selections' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the addons for this group
     */
    public function addons()
    {
        return $this->hasMany(Addon::class)->orderBy('sort_order');
    }

    /**
     * Get the menu items that have this addon group
     */
    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_addon_group')
            ->withTimestamps();
    }

    /**
     * Scope to get only active groups
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}