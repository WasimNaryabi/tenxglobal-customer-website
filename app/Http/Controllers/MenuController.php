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
            ->with('category');

        // Filter by category
        if ($category) {
            $query->byCategory($category);
        }

        // Filter by search
        if ($search) {
            $query->search($search);
        }

        // Get items ordered
        $menuItems = $query->ordered()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
                'description' => $item->description,
                'category' => $item->category_name,
                'categorySlug' => $item->category_slug,
                'categoryId' => $item->menu_category_id,
                'api_group_id' => $item->addon_group_id, // ✅ This is the key!
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
            ];
        })->toArray();

        // Get categories
        $categories = MenuCategory::active()
            ->ordered()
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'imageUrl' => $cat->image_url,
                    'active' => $cat->active,
                    'itemCount' => $cat->item_count,
                    'totalItems' => $cat->total_items,
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