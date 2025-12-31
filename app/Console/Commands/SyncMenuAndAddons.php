<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MenuSyncService;

class SyncMenuAndAddons extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sync:menu-addons 
                            {--menu : Sync only menu items and categories}
                            {--addons : Sync only addon groups and addons}
                            {--stats : Show current statistics}';

    /**
     * The console command description.
     */
    protected $description = 'Sync menu items, categories, addon groups and addons from POS API';

    /**
     * Execute the console command.
     */
    public function handle(MenuSyncService $syncService)
    {
        // Show stats only
        if ($this->option('stats')) {
            $this->displayStats($syncService);
            return 0;
        }

        $this->info('🔄 Starting sync from POS API...');
        $this->info('API: https://smashngrubpos.10xglobal.co.uk/api/menu');
        $this->newLine();

        try {
            $result = null;

            // Sync based on options
            if ($this->option('menu')) {
                $this->info('📦 Syncing Menu Only...');
                $result = $syncService->syncMenu();
            } elseif ($this->option('addons')) {
                $this->info('📦 Syncing Addons Only...');
                $result = $syncService->syncAddonsOnly();
            } else {
                $this->info('📦 Syncing Everything (Menu + Addons)...');
                $result = $syncService->syncAll();
            }

            // Check result
            if (!$result['success']) {
                $this->error('❌ Sync failed: ' . ($result['error'] ?? 'Unknown error'));
                return 1;
            }

            $this->newLine();
            $this->info('✅ Sync completed successfully!');
            $this->newLine();

            // Display results table
            $tableData = [];
            
            if (isset($result['categories_synced'])) {
                $tableData[] = ['Categories', $result['categories_synced']];
            }
            if (isset($result['items_synced'])) {
                $tableData[] = ['Menu Items', $result['items_synced']];
            }
            if (isset($result['addon_groups_synced'])) {
                $tableData[] = ['Addon Groups', $result['addon_groups_synced']];
            }
            if (isset($result['addons_synced'])) {
                $tableData[] = ['Addons', $result['addons_synced']];
            }
            if (isset($result['duration'])) {
                $tableData[] = ['Duration', $result['duration'] . 's'];
            }

            $this->table(['Type', 'Count'], $tableData);

            // Show errors if any
            if (!empty($result['errors'])) {
                $this->newLine();
                $this->warn('⚠️  Some errors occurred:');
                foreach (array_slice($result['errors'], 0, 10) as $error) {
                    $this->line('  • ' . $error);
                }
                if (count($result['errors']) > 10) {
                    $this->line('  ... and ' . (count($result['errors']) - 10) . ' more errors');
                }
            }

            $this->newLine();
            $this->displayStats($syncService);

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Sync failed: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Display current statistics
     */
    private function displayStats(MenuSyncService $syncService)
    {
        $stats = $syncService->getStats();

        $this->info('📊 Current Statistics:');
        $this->newLine();

        $this->table(
            ['Category', 'Metric', 'Count'],
            [
                ['Menu', 'Categories', $stats['menu']['categories']],
                ['Menu', 'Total Items', $stats['menu']['items']],
                ['Menu', 'Active Items', $stats['menu']['active_items']],
                ['Addons', 'Addon Groups', $stats['addons']['groups']],
                ['Addons', 'Total Addons', $stats['addons']['addons']],
                ['Addons', 'Active Groups', $stats['addons']['active_groups']],
            ]
        );

        if ($stats['last_sync']) {
            $this->newLine();
            $this->info('Last Sync: ' . $stats['last_sync']->synced_at);
            $this->line('Status: ' . ucfirst($stats['last_sync']->status));
        }
    }
}