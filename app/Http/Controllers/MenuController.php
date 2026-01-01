<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use App\Models\AddonGroup;

class MenuController extends Controller
{
    /**
     * Display the menu page
     * Now uses LOCAL DATABASE instead of API
     */
    public function index(Request $request): Response
    {
        $category = $request->get('category');
        $search = $request->get('search');

        // Build query for menu items
        $query = MenuItem::query()
            ->active()
            ->with(['category', 'addonGroups.addons' => function ($q) {
                $q->where('status', 'active')
                  ->orderBy('sort_order')
                  ->orderBy('name');
            }]);

        // Filter by category
        if ($category) {
            $query->byCategory($category);
        }

        // Filter by search
        if ($search) {
            $query->search($search);
        }

        // Get items ordered
        $dbItems = $query->ordered()->get();
        
        // Get active deals
        $dealsQuery = \App\Models\Deal::where('status', true)
            ->with([
                'category', 
                'addons.addons', 
                // 'items', // Auto-loaded by items.addonGroups? No, need to specify items if not present in nested path.
                // relationships are nested as 'relation.nested'. 
                // 'items.variants' is invalid because variants is not a relation.
                // We just need 'items' and 'items.addonGroups.addons'.
                'items.addonGroups.addons'
            ]);
        if ($category) {
            // Filter deals by category if selected
             $dealsQuery->where('category_id', function($q) use ($category) {
                 $q->select('id')->from('menu_categories')->where('slug', $category)->orWhere('id', $category);
             });
        }
        $dbDeals = $dealsQuery->get();

        // Transform Items
        $mappedItems = $dbItems->map(function ($item) {
            return [
                'type' => 'item',
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
                'description' => $item->description,
                'category' => $item->category_name,
                'categorySlug' => $item->category_slug,
                'categoryId' => $item->menu_category_id,
                'api_group_id' => $item->addon_group_id,
                'price' => (float)$item->price,
                'originalPrice' => (float)$item->original_price,
                'discount' => $item->discount,
                'image' => $item->image ?? 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&h=300&fit=crop',
                'isNew' => $item->is_new,
                'isFeatured' => $item->is_featured,
                'reviews' => $item->reviews,
                'isTaxable' => $item->is_taxable,
                'labelColor' => $item->label_color,
                'meals' => $item->meals,
                'ingredients' => $item->ingredients,
                'nutrition' => $item->nutrition,
                'allergies' => $item->allergies,
                'tags' => $item->tags,
                'variants' => $item->variants,
                'addonGroupId' => $item->addon_group_id,
                'addon_groups' => $item->addonGroups->map(function ($group) {
                    return [
                        'id' => $group->id,
                        'api_id' => $group->api_id,
                        'name' => $group->name,
                        'description' => $group->description,
                        'min_select' => $group->min_select,
                        'max_select' => $group->max_select,
                        'status' => $group->status,
                        'addons' => $group->addons->map(function ($addon) {
                            return [
                                'id' => $addon->id,
                                'api_id' => $addon->api_id,
                                'name' => $addon->name,
                                'price' => $addon->price,
                                'description' => $addon->description,
                                'status' => $addon->status,
                            ];
                        })->toArray(),
                    ];
                })->toArray(),
            ];
        });

        // Transform Deals
        $mappedDeals = $dbDeals->map(function ($deal) {
            return [
                'type' => 'deal',
                'id' => $deal->id,
                'name' => $deal->name,
                'slug' => \Illuminate\Support\Str::slug($deal->name),
                'description' => $deal->description,
                'category' => $deal->category ? $deal->category->name : 'Deals', 
                'categorySlug' => $deal->category ? $deal->category->slug : 'deals',
                'categoryId' => $deal->category_id,
                'api_group_id' => null, 
                'price' => (float)$deal->price,
                'originalPrice' => (float)$deal->price, 
                'discount' => 0,
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop',
                'isNew' => false,
                'isFeatured' => false, 
                'reviews' => 0,
                'isTaxable' => $deal->is_taxable,
                'labelColor' => $deal->label_color ?? '#e74c3c', // Red for deals
                'meals' => [],
                'ingredients' => [], 
                'nutrition' => [],
                'allergies' => [],
                'tags' => [],
                'variants' => [], 
                'addonGroupId' => null,
                'addon_groups' => $deal->addons->map(function ($group) {
                    return [
                        'id' => $group->id,
                        'api_id' => $group->api_id,
                        'name' => $group->name,
                        'description' => $group->description,
                        'min_select' => $group->min_select,
                        'max_select' => $group->max_select,
                        'status' => $group->status,
                        'addons' => $group->addons->map(function ($addon) {
                            return [
                                'id' => $addon->id,
                                'api_id' => $addon->api_id,
                                'name' => $addon->name,
                                'price' => $addon->price,
                                'description' => $addon->description,
                                'status' => $addon->status,
                            ];
                        })->toArray(),
                    ];
                })->toArray(),
                'items' => $deal->items->map(function ($item) {
                     return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,
                        'image' => $item->image,
                        'variants' => $item->variants,
                        'ingredients' => $item->ingredients,
                        'meals' => $item->meals,
                        'allergies' => $item->allergies,
                        'nutrition' => $item->nutrition,
                        'addon_groups' => $item->addonGroups->map(function ($group) {
                            return [
                                'id' => $group->id,
                                'name' => $group->name,
                                'min_select' => $group->min_select,
                                'max_select' => $group->max_select,
                                'addons' => $group->addons,
                            ];
                        }),
                     ];
                })->toArray(),
                'image' => $deal->image ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop',
            ];
        });

        // Merge Items and Deals
        $menuItems = $mappedItems->concat($mappedDeals)->toArray();

        // Get categories
        $categories = MenuCategory::active()
            ->ordered()
            ->withCount(['deals' => function($q) {
                $q->where('status', true);
            }])
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'imageUrl' => $cat->image_url,
                    'active' => $cat->active,
                    'itemCount' => $cat->item_count + $cat->deals_count, 
                    'totalItems' => $cat->total_items + $cat->deals_count,
                    'subcategories' => $cat->subcategories,
                ];
            })->toArray();

        // ✅ Get addon groups with their addons
        $addonGroups = AddonGroup::where('status', 'active')
            ->with(['addons' => function ($query) {
                $query->where('status', 'active')
                      ->orderBy('sort_order')
                      ->orderBy('name');
            }])
            ->orderBy('sort_order')
            ->get()
            ->map(function ($group) {
                return [
                    'id' => $group->id,
                    'api_id'=>$group->api_id,
                    'name' => $group->name,
                    'description' => $group->description,
                    'min_select' => $group->min_select,
                    'max_select' => $group->max_select,
                    'status' => $group->status,
                    'addons' => $group->addons->map(function ($addon) {
                        return [
                            'id' => $addon->id,
                            'api_id'=>$addon->api_id,
                            'name' => $addon->name,
                            'price' => $addon->price,
                            'description' => $addon->description,
                            'status' => $addon->status,
                        ];
                    })->toArray(),
                ];
            })->toArray();

        return Inertia::render('Menu', [
            'menuItems' => $menuItems,
            'categories' => $categories,
            'addonGroups' => $addonGroups, // ✅ Pass addon groups to frontend
            'initialCategory' => $category,
            'initialSearch' => $search,
        ]);
    }
}