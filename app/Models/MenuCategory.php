<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuCategory extends Model
{
    protected $fillable = [
        'api_id',
        'name',
        'slug',
        'image_url',
        'active',
        'item_count',
        'total_items',
        'subcategories',
        'sort_order',
    ];

    protected $casts = [
        'active' => 'boolean',
        'subcategories' => 'array',
        'item_count' => 'integer',
        'total_items' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the menu items for this category
     */
    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    /**
     * Scope to get only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}