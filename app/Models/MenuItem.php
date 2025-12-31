<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    protected $fillable = [
        'api_id',
        'name',
        'slug',
        'description',
        'menu_category_id',
        'category_name',
        'category_slug',
        'price',
        'original_price',
        'discount',
        'image',
        'is_new',
        'is_featured',
        'reviews',
        'is_taxable',
        'label_color',
        'meals',
        'ingredients',
        'nutrition',
        'allergies',
        'tags',
        'variants',
        'addon_group_id',
        'api_group_id',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount' => 'integer',
        'is_new' => 'boolean',
        'is_featured' => 'boolean',
        'reviews' => 'integer',
        'is_taxable' => 'boolean',
        'meals' => 'array',
        'ingredients' => 'array',
        'nutrition' => 'array',
        'allergies' => 'array',
        'tags' => 'array',
        'variants' => 'array',
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the category for this item
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    /**
     * Scope to get only active items
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to get featured items
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get new items
     */
    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    /**
     * Scope to filter by category
     */
    public function scopeByCategory($query, $categorySlug)
    {
        return $query->where('category_slug', $categorySlug);
    }

    /**
     * Scope to search items
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('category_name', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}