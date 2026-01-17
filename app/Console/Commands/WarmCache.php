<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;

class WarmCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up the application cache';

    /**
     * Execute the console command.
     */
    public function handle(CacheService $cacheService)
    {
        $this->info('Warming up cache...');

        try {
            $cacheService->warmUp();
            $this->info('Cache warmed up successfully!');
        } catch (\Exception $e) {
            $this->error('Error warming up cache: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
