<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only cache GET requests
        if ($request->method() !== 'GET') {
            return $next($request);
        }

        // Don't cache if user is not authenticated
        if (!$request->user()) {
            return $next($request);
        }

        // Generate cache key
        $cacheKey = $this->generateCacheKey($request);

        // Check if response is cached
        if (Cache::has($cacheKey)) {
            return response(Cache::get($cacheKey), 200)
                ->header('X-Cache', 'HIT')
                ->header('Content-Type', 'application/json');
        }

        // Get response
        $response = $next($request);

        // Cache successful responses
        if ($response->getStatusCode() === 200) {
            $ttl = $this->getTtl($request);
            Cache::put($cacheKey, $response->getContent(), $ttl);
            $response->header('X-Cache', 'MISS');
        }

        return $response;
    }

    /**
     * Generate cache key from request
     */
    private function generateCacheKey(Request $request): string
    {
        $user = $request->user();
        $path = $request->path();
        $query = $request->query();

        ksort($query);
        $queryString = http_build_query($query);

        return "api:response:{$user->id}:{$path}:{$queryString}";
    }

    /**
     * Get TTL based on endpoint
     */
    private function getTtl(Request $request): int
    {
        $path = $request->path();

        if (str_contains($path, 'attendances')) {
            return 1800; // 30 minutes
        }

        if (str_contains($path, 'reports')) {
            return 3600; // 1 hour
        }

        if (str_contains($path, 'users')) {
            return 7200; // 2 hours
        }

        return 3600; // 1 hour default
    }
}
