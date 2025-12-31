<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MenuSyncService;

class SyncMenuFromApi extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'menu:sync {--force : Force sync even if recently synced}';

    /**
     * The console command description.
     */
    protected $description = 'Sync menu items and categories from API to local database';

    /**
     * Execute the console command.
     */
    public function handle(MenuSyncService $syncService): int
    {
        $this->info('Starting menu sync from API...');
        $this->newLine();

        // Check last sync time unless forced
        if (!$this->option('force')) {
            $lastSync = $syncService->getLastSync();
            if ($lastSync && now()->diffInMinutes($lastSync->synced_at) < 5) {
                $this->warn('Menu was synced less than 5 minutes ago.');
                $this->info('Use --force to sync anyway.');
                return self::FAILURE;
            }
        }

        $bar = $this->output->createProgressBar();
        $bar->start();

        try {
            $result = $syncService->syncAll();
            $bar->finish();
            $this->newLine(2);

            if ($result['success']) {
                $this->info('✓ Menu synced successfully!');
                $this->newLine();
                $this->table(
                    ['Metric', 'Value'],
                    [
                        ['Items Synced', $result['items_synced']],
                        ['Categories Synced', $result['categories_synced']],
                        ['Duration', $result['duration'] . 's'],
                        ['Errors', count($result['errors'])],
                    ]
                );

                if (!empty($result['errors'])) {
                    $this->newLine();
                    $this->warn('Errors encountered:');
                    foreach ($result['errors'] as $error) {
                        $this->error('  • ' . $error);
                    }
                }

                return self::SUCCESS;
            } else {
                $this->error('✗ Sync failed: ' . $result['error']);
                return self::FAILURE;
            }
        } catch (\Exception $e) {
            $bar->finish();
            $this->newLine(2);
            $this->error('✗ Sync failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}