<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MenuSyncService;

class MenuSyncController extends Controller
{
    protected $syncService;

    public function __construct(MenuSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    /**
     * Sync all data (menu + addons)
     */
    public function syncAll(Request $request)
    {
        try {
            $result = $this->syncService->syncAll();

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Complete sync successful',
                    'data' => [
                        'categories' => $result['categories_synced'],
                        'items' => $result['items_synced'],
                        'addon_groups' => $result['addon_groups_synced'],
                        'addons' => $result['addons_synced'],
                        'duration' => $result['duration'] . 's',
                        'errors' => $result['errors'] ?? [],
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'Sync failed'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync only menu
     */
    public function syncMenu(Request $request)
    {
        try {
            $result = $this->syncService->syncMenu();

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Menu sync successful',
                    'data' => [
                        'categories' => $result['categories_synced'],
                        'items' => $result['items_synced'],
                        'duration' => $result['duration'] . 's',
                        'errors' => $result['errors'] ?? [],
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'Menu sync failed'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Menu sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync only addons
     */
    public function syncAddons(Request $request)
    {
        try {
            $result = $this->syncService->syncAddonsOnly();

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Addons sync successful',
                    'data' => [
                        'addon_groups' => $result['addon_groups_synced'],
                        'addons' => $result['addons_synced'],
                        'duration' => $result['duration'] . 's',
                        'errors' => $result['errors'] ?? [],
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'Addons sync failed'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Addons sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sync statistics
     */
    public function stats()
    {
        try {
            $stats = $this->syncService->getStats();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get last sync info
     */
    public function lastSync()
    {
        try {
            $lastSync = $this->syncService->getLastSync();

            if ($lastSync) {
                return response()->json([
                    'success' => true,
                    'data' => $lastSync
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'No sync history found'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get last sync: ' . $e->getMessage()
            ], 500);
        }
    }
}