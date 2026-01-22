<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class HealthController extends Controller
{
    /**
     * Health check endpoint
     * 
     * Returns application health status including database, Redis, and disk space.
     * Used by load balancers and monitoring systems.
     *
     * @return JsonResponse
     */
    public function check(): JsonResponse
    {
        $health = [
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
            'environment' => config('app.env'),
            'version' => config('app.version', '1.0.0'),
            'services' => [],
            'checks' => [],
        ];

        // Check database connection
        try {
            DB::connection()->getPdo();
            $health['services']['database'] = 'ok';
            $health['checks']['database'] = true;
        } catch (\Exception $e) {
            $health['status'] = 'degraded';
            $health['services']['database'] = 'error';
            $health['checks']['database'] = false;
        }

        // Check Redis connection only when configured as cache driver
        if (config('cache.default') === 'redis') {
            try {
                Redis::ping();
                $health['services']['redis'] = 'ok';
                $health['checks']['redis'] = true;
            } catch (\Exception $e) {
                $health['status'] = 'degraded';
                $health['services']['redis'] = 'error';
                $health['checks']['redis'] = false;
            }
        } else {
            $health['services']['redis'] = 'skipped';
            $health['checks']['redis'] = null;
        }

        // Check disk space
        try {
            $disk = disk_free_space('/');
            $diskTotal = disk_total_space('/');
            $diskUsagePercent = (($diskTotal - $disk) / $diskTotal) * 100;

            $health['services']['disk'] = [
                'status' => 'ok',
                'free_bytes' => $disk,
                'total_bytes' => $diskTotal,
                'usage_percent' => round($diskUsagePercent, 2),
            ];

            // Alert if disk usage > 90%
            if ($diskUsagePercent > 90) {
                $health['status'] = 'degraded';
                $health['checks']['disk'] = false;
                $health['alerts'][] = 'Disk usage is critical (>90%)';
            } else {
                $health['checks']['disk'] = true;
            }
        } catch (\Exception $e) {
            $health['services']['disk'] = 'error';
            $health['checks']['disk'] = false;
        }

        // Check storage directory is writable
        try {
            $testFile = storage_path('health-check-' . time() . '.tmp');
            file_put_contents($testFile, 'health-check');
            unlink($testFile);
            $health['services']['storage'] = 'ok';
            $health['checks']['storage'] = true;
        } catch (\Exception $e) {
            $health['status'] = 'degraded';
            $health['services']['storage'] = 'error';
            $health['checks']['storage'] = false;
        }

        // Determine HTTP status code
        $httpStatus = $health['status'] === 'healthy' ? 200 : 503;

        return response()->json($health, $httpStatus);
    }

    /**
     * Readiness check endpoint
     * 
     * Indicates if the application is ready to serve traffic.
     * Returns 200 only when all critical services are healthy.
     *
     * @return JsonResponse
     */
    public function readiness(): JsonResponse
    {
        $ready = true;
        $messages = [];

        // Check database
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $ready = false;
            $messages[] = 'Database not ready';
        }

        // Check Redis if cache driver is Redis
        if (config('cache.default') === 'redis') {
            try {
                Redis::ping();
            } catch (\Exception $e) {
                $ready = false;
                $messages[] = 'Redis not ready';
            }
        }

        return response()->json([
            'ready' => $ready,
            'messages' => $messages,
        ], $ready ? 200 : 503);
    }

    /**
     * Liveness check endpoint
     * 
     * Indicates if the application is still running.
     * Used by container orchestration systems.
     *
     * @return JsonResponse
     */
    public function liveness(): JsonResponse
    {
        return response()->json([
            'alive' => true,
            'timestamp' => now()->toIso8601String(),
        ], 200);
    }

    /**
     * Simple health check for load balancers
     * 
     * Returns minimal response for fast load balancer checks.
     *
     * @return JsonResponse
     */
    public function simple(): JsonResponse
    {
        return response()->json(['status' => 'ok']);
    }
}
