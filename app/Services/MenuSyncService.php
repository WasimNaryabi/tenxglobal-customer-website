<?php

namespace App\Services;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\AddonGroup;
use App\Models\Addon;
use App\Models\Deal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MenuSyncService
{
    private $apiBaseUrl = 'https://smashngrub.10xglobal.co.uk/api/menu';
    
    /**
     * Sync all data (menu + addons + deals)
     */
    public function syncAll(): array
    {
        $startTime = now();
        $itemsSynced = 0;
        $categoriesSynced = 0;
        $addonGroupsSynced = 0;
        $addonsSynced = 0;
        $dealsSynced = 0;
        $errors = [];
        
        DB::beginTransaction();
        
        try {
            // Sync categories first
            $categoriesResult = $this->syncCategories();
            $categoriesSynced = $categoriesResult['count'];
            if (!empty($categoriesResult['errors'])) {
                $errors = array_merge($errors, $categoriesResult['errors']);
            }
            
            // Sync menu items
            $itemsResult = $this->syncMenuItems();
            $itemsSynced = $itemsResult['count'];
            if (!empty($itemsResult['errors'])) {
                $errors = array_merge($errors, $itemsResult['errors']);
            }
            
            // Sync addons
            $addonsResult = $this->syncAddons();
            $addonGroupsSynced = $addonsResult['groups_count'];
            $addonsSynced = $addonsResult['addons_count'];
            if (!empty($addonsResult['errors'])) {
                $errors = array_merge($errors, $addonsResult['errors']);
            }

            // Sync deals
            $dealsResult = $this->syncDeals();
            $dealsSynced = $dealsResult['count'];
            if (!empty($dealsResult['errors'])) {
                $errors = array_merge($errors, $dealsResult['errors']);
            }
            
            DB::commit();
            
            // Log the sync
            $this->logSync('success', $itemsSynced, $categoriesSynced, $addonGroupsSynced, $addonsSynced, 'Complete sync successful. Deals: ' . $dealsSynced, $errors);
            
            return [
                'success' => true,
                'items_synced' => $itemsSynced,
                'categories_synced' => $categoriesSynced,
                'addon_groups_synced' => $addonGroupsSynced,
                'addons_synced' => $addonsSynced,
                'deals_synced' => $dealsSynced,
                'duration' => now()->diffInSeconds($startTime),
                'errors' => $errors,
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Complete sync failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->logSync('failed', 0, 0, 0, 0, $e->getMessage(), []);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Sync deals from API
     */
    public function syncDeals(): array
    {
        $errors = [];
        $count = 0;
        $page = 1;
        $hasMorePages = true;

        try {
            while ($hasMorePages) {
                // Assuming endpoint is /deals/list based on conventions
                $response = Http::timeout(60)
                    ->retry(2, 100)
                    ->acceptJson()
                    ->get($this->apiBaseUrl . '/deals/list', ['page' => $page]);

                if (!$response->successful()) {
                    throw new \Exception('Deals API returned status: ' . $response->status());
                }

                $data = $response->json();
                $deals = $data['data'] ?? [];
                $pagination = $data['pagination'] ?? null;

                foreach ($deals as $dealData) {
                    try {
                        $deal = Deal::updateOrCreate(
                            ['api_id' => $dealData['id']],
                            [
                                'name' => $dealData['name'],
                                'description' => $dealData['description'] ?? null,
                                'price' => $dealData['price'] ?? 0,
                                'status' => ($dealData['status'] ?? 1) == 1,
                                'is_taxable' => ($dealData['is_taxable'] ?? 0) == 1,
                                'label_color' => $dealData['label_color'] ?? null,
                                'category_id' => isset($dealData['category_id']) 
                                    ? MenuCategory::where('api_id', $dealData['category_id'])->value('id') 
                                    : null, 
                                'image' => $dealData['image_url'] ?? $dealData['image'] ?? null,
                            ]
                        );

                        // Sync Addons Pivot
                        if (isset($dealData['addon_groups']) && is_array($dealData['addon_groups'])) {
                            // Extract API IDs from the nested objects
                            $apiGroupIds = array_map(function($g) { return $g['id']; }, $dealData['addon_groups']);
                            $localGroupIds = AddonGroup::whereIn('api_id', $apiGroupIds)->pluck('id')->toArray();
                            $deal->addons()->sync($localGroupIds);
                        }

                        // Sync Menu Items Pivot
                        $itemsKey = isset($dealData['menu_items']) ? 'menu_items' : 'items';
                        if (isset($dealData[$itemsKey]) && is_array($dealData[$itemsKey])) {
                            $apiItemIds = array_map(function($i) { return $i['id']; }, $dealData[$itemsKey]);
                            $localItemIds = MenuItem::whereIn('api_id', $apiItemIds)->pluck('id')->toArray();
                            $deal->items()->sync($localItemIds);
                        }

                        $count++;
                    } catch (\Exception $e) {
                        $errors[] = "Failed to sync deal {$dealData['name']}: " . $e->getMessage();
                    }
                }

                if ($pagination && $page < $pagination['last_page']) {
                    $page++;
                } else {
                    $hasMorePages = false;
                }
            }
        } catch (\Exception $e) {
            $errors[] = "Failed to fetch deals: " . $e->getMessage();
        }

        return ['count' => $count, 'errors' => $errors];
    }
    
    /**
     * Sync only menu data (categories + items)
     */
    public function syncMenu(): array
    {
        $startTime = now();
        $itemsSynced = 0;
        $categoriesSynced = 0;
        $errors = [];
        
        DB::beginTransaction();
        
        try {
            // Sync categories first
            $categoriesResult = $this->syncCategories();
            $categoriesSynced = $categoriesResult['count'];
            if (!empty($categoriesResult['errors'])) {
                $errors = array_merge($errors, $categoriesResult['errors']);
            }
            
            // Sync menu items
            $itemsResult = $this->syncMenuItems();
            $itemsSynced = $itemsResult['count'];
            if (!empty($itemsResult['errors'])) {
                $errors = array_merge($errors, $itemsResult['errors']);
            }
            
            DB::commit();
            
            // Log the sync
            $this->logSync('success', $itemsSynced, $categoriesSynced, 0, 0, 'Menu synced successfully', $errors);
            
            return [
                'success' => true,
                'items_synced' => $itemsSynced,
                'categories_synced' => $categoriesSynced,
                'duration' => now()->diffInSeconds($startTime),
                'errors' => $errors,
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Menu sync failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->logSync('failed', 0, 0, 0, 0, $e->getMessage(), []);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Sync only addons data (groups + addons)
     */
    public function syncAddonsOnly(): array
    {
        $startTime = now();
        $errors = [];
        
        DB::beginTransaction();
        
        try {
            $addonsResult = $this->syncAddons();
            
            DB::commit();
            
            // Log the sync
            $this->logSync(
                'success', 
                0, 
                0, 
                $addonsResult['groups_count'], 
                $addonsResult['addons_count'], 
                'Addons synced successfully', 
                $addonsResult['errors']
            );
            
            return [
                'success' => true,
                'addon_groups_synced' => $addonsResult['groups_count'],
                'addons_synced' => $addonsResult['addons_count'],
                'duration' => now()->diffInSeconds($startTime),
                'errors' => $addonsResult['errors'],
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Addons sync failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->logSync('failed', 0, 0, 0, 0, $e->getMessage(), []);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Sync categories from API
     */
    private function syncCategories(): array
    {
        $errors = [];
        $count = 0;
        
        try {
            $response = Http::timeout(60)
                ->retry(2, 100)
                ->acceptJson()
                ->get($this->apiBaseUrl . '/categories/list');
            
            if (!$response->successful()) {
                throw new \Exception('Categories API returned status: ' . $response->status());
            }
            
            $categories = $response->json('data') ?? [];
            
            foreach ($categories as $index => $category) {
                try {
                    MenuCategory::updateOrCreate(
                        ['api_id' => $category['id']],
                        [
                            'name' => $category['name'],
                            'slug' => Str::slug($category['name']),
                            'image_url' => $category['image_url'] ?? null,
                            'active' => ($category['active'] ?? "0") == "1",
                            'item_count' => $category['menu_items_count'] ?? 0,
                            'total_items' => $category['total_menu_items'] ?? 0,
                            'subcategories' => $category['subcategories'] ?? null,
                            'sort_order' => $index,
                        ]
                    );
                    
                    $count++;
                } catch (\Exception $e) {
                    $errors[] = "Failed to sync category {$category['name']}: " . $e->getMessage();
                }
            }
            
        } catch (\Exception $e) {
            $errors[] = "Failed to fetch categories: " . $e->getMessage();
        }
        
        return ['count' => $count, 'errors' => $errors];
    }
    
    /**
     * Sync menu items from API
     */
    private function syncMenuItems(): array
    {
        $errors = [];
        $count = 0;
        $page = 1;
        $hasMorePages = true;
        
        try {
            while ($hasMorePages) {
                $response = Http::timeout(60)
                    ->retry(2, 100)
                    ->acceptJson()
                    ->get($this->apiBaseUrl . '/items/list', ['page' => $page]);
                
                if (!$response->successful()) {
                    throw new \Exception('Menu items API returned status: ' . $response->status());
                }
                
                $data = $response->json();
                $items = $data['data'] ?? [];
                $pagination = $data['pagination'] ?? null;
                
                foreach ($items as $item) {
                    try {
                        $this->syncMenuItem($item);
                        $count++;
                    } catch (\Exception $e) {
                        $errors[] = "Failed to sync item {$item['name']}: " . $e->getMessage();
                    }
                }
                
                // Check if there are more pages
                if ($pagination && $page < $pagination['last_page']) {
                    $page++;
                } else {
                    $hasMorePages = false;
                }
            }
            
        } catch (\Exception $e) {
            $errors[] = "Failed to fetch menu items: " . $e->getMessage();
        }
        
        return ['count' => $count, 'errors' => $errors];
    }
    
    /**
     * Sync addons from API
     */
    private function syncAddons(): array
    {
        $errors = [];
        $groupsCount = 0;
        $addonsCount = 0;
        $processedGroups = [];
        $page = 1;
        $hasMorePages = true;
        
        try {
            while ($hasMorePages) {
                $response = Http::timeout(60)
                    ->retry(2, 100)
                    ->acceptJson()
                    ->get($this->apiBaseUrl . '/addons/all', ['page' => $page]);
                
                if (!$response->successful()) {
                    throw new \Exception('Addons API returned status: ' . $response->status());
                }
                
                $data = $response->json();
                $addons = $data['data'] ?? [];
                $pagination = $data['pagination'] ?? null;
                
                if (empty($addons) && $page === 1) {
                    return [
                        'groups_count' => 0,
                        'addons_count' => 0,
                        'errors' => ['No addons found in API response'],
                    ];
                }
                
                foreach ($addons as $addonData) {
                    try {
                        // 1. Sync Group if present
                        $groupData = $addonData['addon_group'] ?? null;
                        $groupId = null;

                        if ($groupData) {
                            $group = AddonGroup::updateOrCreate(
                                ['api_id' => $groupData['id']],
                                [
                                    'name' => $groupData['name'],
                                    'description' => $groupData['description'] ?? null,
                                    'min_select' => $groupData['min_select'] ?? 0,
                                    'max_select' => $groupData['max_select'] ?? 1,
                                    'status' => $groupData['status'] ?? 'active',
                                    'sort_order' => 0,
                                ]
                            );
                            $groupId = $group->id;
                            
                            // Count unique groups synced in this run
                            if (!in_array($group->id, $processedGroups)) {
                                $groupsCount++;
                                $processedGroups[] = $group->id;
                            }
                        }

                        // 2. Sync Addon
                        if ($groupId) {
                             Addon::updateOrCreate(
                                ['api_id' => $addonData['id']],
                                [
                                    'addon_group_id' => $groupId,
                                    'name' => $addonData['name'],
                                    'description' => $addonData['description'] ?? null,
                                    'price' => $addonData['price'] ?? 0,
                                    'status' => $addonData['status'] ?? 'active',
                                    'sort_order' => $addonData['sort_order'] ?? 0,
                                ]
                            );
                            $addonsCount++;
                        }
                    } catch (\Exception $e) {
                         $errors[] = "Failed to sync addon {$addonData['name']}: " . $e->getMessage();
                    }
                }

                if ($pagination && $page < $pagination['last_page']) {
                    $page++;
                } else {
                    $hasMorePages = false;
                }
            }
            
        } catch (\Exception $e) {
            $errors[] = "Failed to fetch addons: " . $e->getMessage();
        }
        
        return [
            'groups_count' => $groupsCount,
            'addons_count' => $addonsCount,
            'errors' => $errors,
        ];
    }
    
    /**
     * Sync a single menu item
     */
    private function syncMenuItem(array $item): void
    {
        // Calculate discount
        $originalPrice = (float)$item['price'];
        $discount = 0;
        $currentPrice = $originalPrice;
        
        if (isset($item['is_saleable']) && $item['is_saleable'] == "1" && isset($item['resale_price'])) {
            $resalePrice = (float)$item['resale_price'];
            if ($resalePrice > 0 && $resalePrice < $originalPrice) {
                $discount = round((($originalPrice - $resalePrice) / $originalPrice) * 100);
                $currentPrice = $resalePrice;
            }
        }
        
        // Get or create category
        $categoryName = $item['category']['name'] ?? 'Uncategorized';
        $categorySlug = Str::slug($categoryName);
        $category = MenuCategory::where('api_id', $item['category']['id'])->first();
        
        if (!$category) {
            $category = MenuCategory::create([
                'api_id' => $item['category']['id'],
                'name' => $categoryName,
                'slug' => $categorySlug,
                'active' => true,
            ]);
        }
        
        // Check if item is new
        $isNew = false;
        if (isset($item['created_at'])) {
            try {
                $createdDate = new \DateTime($item['created_at']);
                $now = new \DateTime();
                $diff = $now->diff($createdDate);
                $isNew = $diff->days <= 30;
            } catch (\Exception $e) {
                $isNew = false;
            }
        }
        
        // Generate description
        $description = $item['description'] ?? $this->generateDescription($item);
        
        // Get reviews
        $reviews = 0;
        if (isset($item['nutrition']['calories'])) {
            $reviews = min((int)$item['nutrition']['calories'], 500);
        } else {
            $reviews = rand(50, 300);
        }
        
        // Transform ingredients
        $ingredients = array_map(function ($ing) {
            return [
                'id' => $ing['id'],
                'name' => $ing['product_name'],
                'quantity' => (float)$ing['quantity'],
                'cost' => (float)($ing['cost'] ?? 0),
            ];
        }, $item['ingredients'] ?? []);
        
        // Create or update menu item
        $menuItem = MenuItem::updateOrCreate(
            ['api_id' => $item['id']],
            [
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'description' => $description,
                'menu_category_id' => $category->id,
                'category_name' => $categoryName,
                'category_slug' => $categorySlug,
                'price' => $currentPrice,
                'original_price' => $originalPrice,
                'discount' => $discount,
                'image' => $item['image_url'] ?? null,
                'is_new' => $isNew,
                'is_featured' => ($item['status'] ?? "0") == "1",
                'reviews' => $reviews,
                'is_taxable' => ($item['is_taxable'] ?? "0") == "1",
                'label_color' => $item['label_color'] ?? '#8E44AD',
                'meals' => $item['meals'] ?? null,
                'ingredients' => $ingredients,
                'nutrition' => $item['nutrition'] ?? null,
                'allergies' => $item['allergies'] ?? null,
                'tags' => $item['tags'] ?? null,
                'variants' => $item['variants'] ?? null,
                'addon_group_id' => $item['addon_group_id'] ?? null,
                'active' => true,
            ]
        );

        // Sync Addon Groups Pivot
        if (isset($item['addon_group_ids']) && is_array($item['addon_group_ids'])) {
            $apiGroupIds = $item['addon_group_ids'];
            $localGroupIds = AddonGroup::whereIn('api_id', $apiGroupIds)->pluck('id')->toArray();
            $menuItem->addonGroups()->sync($localGroupIds);
        }
    }
    
    /**
     * Generate description from ingredients
     */
    private function generateDescription(array $item): string
    {
        if (!empty($item['ingredients'])) {
            $ingredientNames = array_map(function ($ing) {
                return strtolower($ing['product_name']);
            }, array_slice($item['ingredients'], 0, 3));
            
            return ucfirst(implode(', ', $ingredientNames));
        }
        
        return 'Delicious ' . strtolower($item['name']);
    }
    
    /**
     * Log sync operation
     */
    private function logSync(
        string $status, 
        int $itemsSynced, 
        int $categoriesSynced, 
        int $addonGroupsSynced, 
        int $addonsSynced, 
        string $message, 
        array $errors
    ): void {
        DB::table('menu_sync_logs')->insert([
            'status' => $status,
            'items_synced' => $itemsSynced,
            'categories_synced' => $categoriesSynced,
            'addon_groups_synced' => $addonGroupsSynced,
            'addons_synced' => $addonsSynced,
            'message' => $message,
            'errors' => !empty($errors) ? json_encode($errors) : null,
            'synced_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    
    /**
     * Get last sync info
     */
    public function getLastSync(): ?object
    {
        return DB::table('menu_sync_logs')
            ->orderBy('synced_at', 'desc')
            ->first();
    }
    
    /**
     * Get sync statistics
     */
    public function getStats(): array
    {
        return [
            'menu' => [
                'categories' => MenuCategory::count(),
                'items' => MenuItem::count(),
                'active_items' => MenuItem::where('active', true)->count(),
            ],
            'addons' => [
                'groups' => AddonGroup::count(),
                'addons' => Addon::count(),
                'active_groups' => AddonGroup::where('status', 'active')->count(),
            ],
            'deals' => [
                'total' => Deal::count(),
            ],
            'last_sync' => $this->getLastSync(),
        ];
    }
}