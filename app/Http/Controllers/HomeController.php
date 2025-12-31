<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\MenuItem;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home', [
            'popularItems' => $this->getPopularItems(),
            'offers' => $this->getOffers(),
            'newItems' => $this->getNewItems(),
            'trendingMenu' => $this->getTrendingMenu(),
            'topTrending' => $this->getTopTrending(),
            'fastFood' => $this->getFastFood(),
        ]);
    }


private function getPopularItems(int $limit = 6): array
{
    $items = MenuItem::query()
        ->active()
        ->with('category')
        // ✅ choose your popular rule (pick one or keep both)
        ->where(function ($q) {
            $q->where('is_featured', 1); // only if you have this column
        })
        ->ordered()                 // your scope
        ->limit($limit)
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
                'description' => $item->description,
                'category' => $item->category_name,      // your accessor
                'categorySlug' => $item->category_slug,  // your accessor
                'categoryId' => $item->menu_category_id,
                'price' => (float) $item->price,
                'originalPrice' => (float) $item->original_price,
                'discount' => $item->discount,
                'image' => $item->image
                    ?? 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&h=300&fit=crop',
                'isNew' => (bool) $item->is_new,
                'isFeatured' => (bool) $item->is_featured,
                'reviews' => $item->reviews,
                'isTaxable' => (bool) $item->is_taxable,
                'labelColor' => $item->label_color,
                'meals' => $item->meals,
                'ingredients' => $item->ingredients,
                'nutrition' => $item->nutrition,
                'allergies' => $item->allergies,
                'tags' => $item->tags,
                'variants' => $item->variants,
                'addonGroupId' => $item->addon_group_id,
            ];
        })
        ->toArray();

    return $items;
}


    private function getOffers(): array
    {
        return [
            ['id' => 1, 'title' => 'Special Offer', 'image' => 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&h=500&fit=crop', 'badge' => 'Special Offer', 'badgeColor' => 'bg-yellow-400 text-gray-900'],
            ['id' => 2, 'title' => 'Filet mignon', 'subtitle' => 'Premium Quality', 'discount' => '30% OFF', 'image' => 'https://images.unsplash.com/photo-1588168333986-5078d3ae3976?w=600&h=500&fit=crop', 'size' => 'large', 'overlay' => true],
            ['id' => 3, 'title' => 'Hot Burgers', 'image' => 'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=400&h=500&fit=crop', 'badge' => '50% OFF', 'badgeColor' => 'bg-red-600 text-white'],
        ];
    }

    private function getNewItems(): array
    {
        return [
            ['id' => 7, 'name' => 'Grill Sandwich', 'description' => 'Grilled Cheese', 'priceRange' => '12.00-15.99', 'price' => 12.00, 'image' => 'https://images.unsplash.com/photo-1603064752734-4c48eff53d05?w=200&h=200&fit=crop'],
            ['id' => 8, 'name' => 'Vegetable', 'description' => 'Fresh Veggies', 'priceRange' => '8.99-12.99', 'price' => 8.99, 'image' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=200&h=200&fit=crop'],
            ['id' => 9, 'name' => 'Cupcake', 'description' => 'Sweet Treats', 'priceRange' => '4.99-7.99', 'price' => 4.99, 'image' => 'https://images.unsplash.com/photo-1534308983496-4fabb1a015ee?w=200&h=200&fit=crop'],
            ['id' => 10, 'name' => 'Chicken wings', 'description' => 'Spicy Wings', 'priceRange' => '16.99-21.99', 'price' => 16.99, 'image' => 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=200&h=200&fit=crop'],
            ['id' => 11, 'name' => 'Fried rice', 'description' => 'Asian Style', 'priceRange' => '10.99-14.99', 'price' => 10.99, 'image' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=200&h=200&fit=crop'],
            ['id' => 12, 'name' => 'Kebab', 'description' => 'Meat Skewers', 'priceRange' => '14.99-19.99', 'price' => 14.99, 'image' => 'https://images.unsplash.com/photo-1606728035253-49e8a23146de?w=200&h=200&fit=crop'],
        ];
    }

    private function getTrendingMenu(): array
    {
        return [
            ['id' => 1, 'name' => 'CHICKEN BEEF PIZZA', 'category' => 'Hot Burgers', 'description' => 'Far far away, behind the word mountains', 'price' => '22.00'],
            ['id' => 2, 'name' => 'DOUBLE PIZZA', 'category' => 'Italian Style', 'description' => 'Delicious double cheese pizza', 'price' => '18.00'],
            ['id' => 3, 'name' => 'CHICKEN BURGER-Veg', 'category' => 'Veggie Delight', 'description' => 'Healthy vegetarian burger', 'price' => '16.00'],
            ['id' => 4, 'name' => 'CHICKEN BEEF PIZZA', 'category' => 'Premium Quality', 'description' => 'Loaded with premium toppings', 'price' => '24.00'],
            ['id' => 5, 'name' => 'CHICKEN MIXED PIZZA', 'category' => 'Special Mix', 'description' => 'Mixed with chicken and vegetables', 'price' => '18.00'],
            ['id' => 6, 'name' => 'CHICKEN BEEF PIZZA', 'category' => 'Chef Special', 'description' => 'Our chef special recipe', 'price' => '25.00'],
            ['id' => 7, 'name' => 'CHICKEN BEEF PIZZA', 'category' => 'Family Size', 'description' => 'Large pizza for family', 'price' => '30.00'],
            ['id' => 8, 'name' => 'CHICKEN PIZZA-Mix', 'category' => 'Best Seller', 'description' => 'Most popular pizza', 'price' => '20.00'],
        ];
    }

    private function getTopTrending(): array
    {
        return [
            ['id' => 13, 'name' => 'Paneer Tikka', 'description' => 'Tasty Pizza', 'price' => '12.00', 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=200&h=150&fit=crop'],
            ['id' => 14, 'name' => 'Paneer Tikka', 'description' => 'Indian Style', 'price' => '10.00', 'image' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=200&h=150&fit=crop'],
            ['id' => 15, 'name' => 'Paneer Tikka', 'description' => 'Spicy Special', 'price' => '12.00', 'image' => 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=200&h=150&fit=crop'],
            ['id' => 16, 'name' => 'Paneer Tikka', 'description' => 'Chef Choice', 'price' => '14.00', 'image' => 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=200&h=150&fit=crop'],
            ['id' => 17, 'name' => 'Paneer Tikka', 'description' => 'Hot & Spicy', 'price' => '11.00', 'image' => 'https://images.unsplash.com/photo-1603064752734-4c48eff53d05?w=200&h=150&fit=crop'],
            ['id' => 18, 'name' => 'Paneer Tikka', 'description' => 'Classic Recipe', 'price' => '13.00', 'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200&h=150&fit=crop'],
        ];
    }

    private function getFastFood(): array
    {
        return [
            ['id' => 19, 'name' => 'Medium burger', 'description' => 'The Chicken', 'price' => 12.99, 'discount' => 20, 'image' => 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=300&h=200&fit=crop'],
            ['id' => 20, 'name' => 'Medium burger', 'description' => 'Cheese Lover', 'price' => 14.99, 'discount' => 15, 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=300&h=200&fit=crop'],
            ['id' => 21, 'name' => 'Medium burger', 'description' => 'Beef Special', 'price' => 16.99, 'discount' => 25, 'image' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=300&h=200&fit=crop'],
            ['id' => 22, 'name' => 'Medium burger', 'description' => 'Veggie Delight', 'price' => 11.99, 'discount' => 30, 'image' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=300&h=200&fit=crop'],
            ['id' => 23, 'name' => 'Medium burger', 'description' => 'Spicy Hot', 'price' => 13.99, 'discount' => 10, 'image' => 'https://images.unsplash.com/photo-1603064752734-4c48eff53d05?w=300&h=200&fit=crop'],
            ['id' => 24, 'name' => 'Medium burger', 'description' => 'Double Patty', 'price' => 18.99, 'discount' => 40, 'image' => 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=300&h=200&fit=crop'],
        ];
    }
}