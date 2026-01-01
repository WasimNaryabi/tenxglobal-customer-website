<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'upload_id',
        'status',
        'is_taxable',
        'label_color',
        'category_id',
        'subcategory_id',
        'api_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
        'is_taxable' => 'boolean',
    ];

    public function items()
    {
        return $this->belongsToMany(MenuItem::class, 'deal_menu_item', 'deal_id', 'menu_item_id');
    }

    public function addons()
    {
        return $this->belongsToMany(AddonGroup::class, 'deal_addon', 'deal_id', 'addon_group_id');
    }

    // public function allergies()
    // {
    //     return $this->hasMany(DealAllergy::class); 
    // }
    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }
}
