<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;

class ClearCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-custom {--type=all : Type of cache to clear (all, user, attendance, report)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear application cache';

    /**
     * Execute the console command.
     */
    public function handle(CacheService $cacheService)
    {
        $type = $this->option('type');

        $this->info("Clearing {$type} cache...");

        try {
            if ($type === 'all') {
                $cacheService->invalidateAll();
                $this->info('All cache cleared successfully!');
            } else {
                $this->info("Cache type '{$type}' clearing not implemented yet");
            }
        } catch (\Exception $e) {
            $this->error('Error clearing cache: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
