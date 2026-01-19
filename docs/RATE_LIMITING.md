# Rate Limiting Configuration

## Overview

Rate limiting melindungi aplikasi dari abuse, brute force attacks, dan DoS attacks dengan membatasi jumlah request yang dapat dilakukan dalam periode waktu tertentu.

## Rate Limit Rules

### 1. API Rate Limiting (Default)
- **Limit:** 60 requests per minute per user/IP
- **Applied to:** All authenticated API endpoints
- **Config:** `config('app.rate_limit', 60)`

### 2. Health Check Rate Limiting
- **Limit:** 120 requests per minute per IP
- **Applied to:** `/api/health/*` endpoints
- **Rationale:** Health checks should be more lenient for monitoring systems

### 3. Authentication Rate Limiting
- **Limit:** 5 attempts per minute per IP
- **Applied to:** Login endpoints
- **Protection:** Brute force attack prevention

### 4. Complaint Creation Rate Limiting
- **Limit:** 3 per minute per user
- **Applied to:** POST `/complaints`
- **Rationale:** Prevent spam complaints

### 5. File Upload Rate Limiting
- **Limit:** 10 per minute per user
- **Applied to:** All file upload endpoints
- **Rationale:** Prevent server resource exhaustion

### 6. Export Operations Rate Limiting
- **Limit:** 5 per minute per user
- **Applied to:** Export endpoints (PDF/CSV)
- **Rationale:** Prevent heavy report generation overload

## Configuration

### In .env (Production)

```env
# Global rate limit per minute (can be overridden per endpoint)
RATE_LIMIT_PER_MINUTE=60
```

### In code (AppServiceProvider.php)

Rate limiting is configured in `app/Providers/AppServiceProvider.php` using Laravel's RateLimiter:

```php
RateLimiter::for('api', function (Request $request) {
    $limit = (int) config('app.rate_limit', 60);
    return Limit::perMinute($limit)->by(
        $request->user()?->id ?: $request->ip()
    );
});
```

## Using Rate Limiting in Routes

### Apply to a Single Route

```php
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:auth');
```

### Apply to Route Group

```php
Route::middleware(['throttle:api'])->group(function () {
    Route::get('/user', [UserController::class, 'show']);
    Route::post('/user', [UserController::class, 'update']);
});
```

### Health Check Routes (Already Configured)

```php
Route::middleware('throttle:health')->group(function () {
    Route::get('/health', [HealthController::class, 'check']);
    Route::get('/health/ready', [HealthController::class, 'readiness']);
    Route::get('/health/live', [HealthController::class, 'liveness']);
    Route::get('/health/ping', [HealthController::class, 'simple']);
});
```

## HTTP Response Headers

When rate limiting is active, responses include:

```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 45
X-RateLimit-Reset: 1673046123
```

## Rate Limit Exceeded Response

When limit is exceeded, server returns HTTP 429 (Too Many Requests):

```json
{
    "message": "Too Many Requests"
}
```

## Custom Rate Limiting Per Endpoint

### Example: More Strict Limit for Sensitive Endpoint

```php
RateLimiter::for('sensitive', function (Request $request) {
    return Limit::perMinute(2)->by($request->user()->id);
});

Route::delete('/user/{id}', [UserController::class, 'destroy'])
    ->middleware('throttle:sensitive');
```

### Example: Different Limit for Different User Roles

```php
RateLimiter::for('admin', function (Request $request) {
    if ($request->user()?->isAdmin()) {
        return Limit::perMinute(500)->by($request->user()->id);
    }
    return Limit::perMinute(60)->by($request->user()->id);
});
```

## Monitoring Rate Limits

### Check Current Usage

```bash
# View rate limiting in action with curl verbose
curl -v https://api.example.com/api/health

# Look for X-RateLimit-* headers
```

### Log Rate Limit Violations

Add to middleware or controller:

```php
Log::warning('Rate limit exceeded', [
    'user_id' => $request->user()?->id,
    'ip' => $request->ip(),
    'endpoint' => $request->path(),
]);
```

## Bypass Rate Limiting

In development or for monitoring systems:

```php
RateLimiter::for('bypass', function (Request $request) {
    // No limit
    return Limit::none();
});

// Or with condition
RateLimiter::for('conditional', function (Request $request) {
    if ($request->ip() === '127.0.0.1') {
        return Limit::none();
    }
    return Limit::perMinute(60)->by($request->ip());
});
```

## Storage Backend

Rate limiting data is stored in configured cache driver:

- **Production:** Redis (configured in `config/cache.php`)
- **Development:** Database cache
- **Testing:** Array cache (in-memory, not persistent)

Ensure cache driver is properly configured for reliable rate limiting!

## Best Practices

1. **Use IP + User ID:** Identify both anonymous and authenticated users
   ```php
   ->by($request->user()?->id ?: $request->ip())
   ```

2. **Different Limits for Different Operations:**
   - Stricter for write operations
   - Lenient for read operations
   - Very lenient for health checks

3. **Monitor and Adjust:** Track rate limit violations and adjust limits based on real usage

4. **Document Limits:** Clearly document rate limits in API documentation

5. **Inform Users:** Return helpful error messages when limit is exceeded
   ```json
   {
       "message": "Rate limit exceeded. Please try again in 1 minute."
   }
   ```

6. **Use Cache Backend:** Always use a fast cache backend (Redis) in production

## Testing Rate Limits

### Using Laravel Testing

```php
public function test_rate_limiting()
{
    // Make 5 requests
    for ($i = 0; $i < 5; $i++) {
        $this->post('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
    }

    // 6th request should be rate limited
    $response = $this->post('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(429);
}
```

### Load Testing with Apache Bench

```bash
# Send 100 requests with 10 concurrent
ab -n 100 -c 10 https://api.example.com/api/health
```

## Troubleshooting

### Rate Limiting Not Working

1. Check cache driver is running:
   ```bash
   docker-compose ps redis
   ```

2. Verify Redis password is set correctly

3. Check logs for rate limiting errors:
   ```bash
   docker-compose logs app | grep "rate"
   ```

### False Positives (Legitimate Users Rate Limited)

1. Review rate limit thresholds
2. Consider adjusting limits based on user behavior
3. Implement different limits for different user tiers

## References

- [Laravel Rate Limiting Documentation](https://laravel.com/docs/rate-limiting)
- [HTTP 429 Too Many Requests](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/429)
