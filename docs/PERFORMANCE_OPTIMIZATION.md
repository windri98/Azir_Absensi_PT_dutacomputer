# Performance Optimization Guide

## Overview

Dokumen ini menjelaskan strategi optimasi performa untuk mendukung 500+ concurrent users.

## Performance Targets

| Metric | Target | Current |
|--------|--------|---------|
| Page Load Time | < 2 seconds | TBD |
| API Response Time (p95) | < 200ms | TBD |
| Mobile App Startup | < 3 seconds | TBD |
| Database Query Time (p95) | < 100ms | TBD |
| Concurrent Users | 500+ | TBD |

## Backend Optimization

### 1. Database Optimization

#### Indexes
```sql
-- Attendance queries
CREATE INDEX idx_attendances_user_date ON attendances(user_id, date);
CREATE INDEX idx_attendances_status ON attendances(status);
CREATE INDEX idx_attendances_created_at ON attendances(created_at);

-- Complaints queries
CREATE INDEX idx_complaints_user_id ON complaints(user_id);
CREATE INDEX idx_complaints_status ON complaints(status);
CREATE INDEX idx_complaints_created_at ON complaints(created_at);

-- Users queries
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_employee_id ON users(employee_id);
```

#### Query Optimization
```php
// Bad: N+1 query problem
$attendances = Attendance::all();
foreach ($attendances as $attendance) {
    echo $attendance->user->name; // Query per iteration
}

// Good: Eager loading
$attendances = Attendance::with('user')->get();
foreach ($attendances as $attendance) {
    echo $attendance->user->name; // No additional queries
}
```

#### Connection Pooling
```php
// config/database.php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST'),
    'port' => env('DB_PORT'),
    'database' => env('DB_DATABASE'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'options' => [
        PDO::ATTR_PERSISTENT => true,
    ],
],
```

### 2. Caching Strategy

#### Redis Configuration
```php
// config/cache.php
'redis' => [
    'driver' => 'redis',
    'connection' => 'cache',
    'lock_connection' => 'default',
],

// config/session.php
'driver' => env('SESSION_DRIVER', 'redis'),
'connection' => 'sessions',
```

#### Cache Warming
```bash
# Warm up cache on deployment
php artisan cache:warm
```

#### Cache Invalidation
```php
// Invalidate cache when data changes
Cache::forget("user:{$userId}");
Cache::forget("attendances:user:{$userId}:{$year}-{$month}");
```

### 3. API Optimization

#### Response Compression
```php
// Enable gzip compression in web server
// nginx: gzip on;
// Apache: mod_deflate
```

#### Pagination
```php
// Always paginate large datasets
$attendances = Attendance::paginate(20);

// Use cursor pagination for large datasets
$attendances = Attendance::cursorPaginate(20);
```

#### Field Selection
```php
// Only select needed fields
$users = User::select('id', 'name', 'email')->get();

// Not: User::all() // Selects all fields
```

### 4. Queue Optimization

#### Background Jobs
```php
// Move heavy operations to queue
dispatch(new GenerateReport($userId))->onQueue('reports');

// Use batch processing
Bus::batch([
    new ProcessAttendance($user1),
    new ProcessAttendance($user2),
])->dispatch();
```

#### Job Optimization
```php
// Use chunking for large datasets
User::chunk(100, function ($users) {
    foreach ($users as $user) {
        // Process user
    }
});
```

## Frontend Optimization

### 1. Code Splitting

#### Route-based Code Splitting
```javascript
// router/index.js
const Dashboard = () => import('../pages/Dashboard.vue');
const Attendance = () => import('../pages/Attendance.vue');
const Reports = () => import('../pages/Reports.vue');
```

#### Component Lazy Loading
```vue
<template>
  <div>
    <Suspense>
      <template #default>
        <HeavyComponent />
      </template>
      <template #fallback>
        <Loading />
      </template>
    </Suspense>
  </div>
</template>

<script setup>
const HeavyComponent = defineAsyncComponent(() =>
  import('./HeavyComponent.vue')
);
</script>
```

### 2. Bundle Optimization

#### Vite Configuration
```javascript
// vite.config.js
export default defineConfig({
  build: {
    rollupOptions: {
      output: {
        manualChunks: {
          'vendor': ['vue', 'vue-router', 'pinia'],
          'utils': ['axios', 'date-fns'],
        },
      },
    },
    chunkSizeWarningLimit: 500,
  },
});
```

### 3. Image Optimization

#### Image Compression
```bash
# Use image optimization tools
imagemin src/images --out-dir=dist/images
```

#### Responsive Images
```vue
<template>
  <img
    :src="image"
    :srcset="`${image}?w=400 400w, ${image}?w=800 800w`"
    sizes="(max-width: 600px) 400px, 800px"
    alt="Description"
  />
</template>
```

### 4. CSS Optimization

#### Tailwind CSS Purging
```javascript
// tailwind.config.js
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.{js,jsx,ts,tsx,vue}',
  ],
};
```

## Mobile Optimization

### 1. Offline-First Architecture

#### Local Storage
```javascript
// Store data locally
await databaseService.saveAttendance(attendance);

// Sync when online
if (navigator.onLine) {
  await syncService.syncPendingData();
}
```

### 2. Bundle Size Optimization

#### Tree Shaking
```javascript
// Good: Named imports
import { checkIn } from './services/attendance';

// Bad: Default import
import * as attendanceService from './services/attendance';
```

### 3. Performance Monitoring

#### React Native Performance
```javascript
// Monitor performance
import { PerformanceObserver } from 'react-native-performance';

const observer = new PerformanceObserver((list) => {
  for (const entry of list.getEntries()) {
    console.log(`${entry.name}: ${entry.duration}ms`);
  }
});

observer.observe({ entryTypes: ['measure'] });
```

## Monitoring & Profiling

### 1. Backend Monitoring

#### Laravel Debugbar
```php
// Enable in development
// .env: DEBUGBAR_ENABLED=true
```

#### Query Logging
```php
// Log slow queries
DB::listen(function ($query) {
    if ($query->time > 1000) {
        Log::warning('Slow query', ['query' => $query->sql]);
    }
});
```

### 2. Frontend Monitoring

#### Lighthouse
```bash
# Run Lighthouse audit
npm run lighthouse
```

#### Web Vitals
```javascript
import { getCLS, getFID, getFCP, getLCP, getTTFB } from 'web-vitals';

getCLS(console.log);
getFID(console.log);
getFCP(console.log);
getLCP(console.log);
getTTFB(console.log);
```

### 3. APM (Application Performance Monitoring)

#### Sentry Integration
```php
// config/sentry.php
'dsn' => env('SENTRY_LARAVEL_DSN'),
'traces_sample_rate' => 0.1,
```

## Load Testing

### 1. Apache Bench
```bash
# Test with 1000 requests, 100 concurrent
ab -n 1000 -c 100 http://localhost:8000/api/v1/attendances
```

### 2. Wrk
```bash
# Test with 12 threads, 400 connections, 30 seconds
wrk -t12 -c400 -d30s http://localhost:8000/api/v1/attendances
```

### 3. Locust
```python
from locust import HttpUser, task, between

class AttendanceUser(HttpUser):
    wait_time = between(1, 3)

    @task
    def get_attendances(self):
        self.client.get("/api/v1/attendances")
```

## Deployment Optimization

### 1. Server Configuration

#### Nginx Configuration
```nginx
# Enable gzip compression
gzip on;
gzip_types text/plain text/css application/json application/javascript;

# Enable caching
expires 1h;
add_header Cache-Control "public, immutable";

# Enable HTTP/2
listen 443 ssl http2;
```

#### PHP Configuration
```ini
; php.ini
max_execution_time = 30
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 10M

; Enable opcache
opcache.enable = 1
opcache.memory_consumption = 256
```

### 2. Database Optimization

#### MySQL Configuration
```ini
; my.cnf
[mysqld]
max_connections = 1000
innodb_buffer_pool_size = 4G
innodb_log_file_size = 512M
query_cache_size = 256M
```

## Performance Checklist

### Before Production
- [ ] Database indexes created
- [ ] Caching configured
- [ ] API response compression enabled
- [ ] Frontend code split
- [ ] Images optimized
- [ ] CSS purged
- [ ] Bundle size < 500KB
- [ ] Load testing completed
- [ ] Monitoring configured
- [ ] Logging configured

### Regular Maintenance
- [ ] Weekly: Monitor slow queries
- [ ] Monthly: Review performance metrics
- [ ] Quarterly: Load testing
- [ ] Annually: Performance audit

## Resources

- Laravel Performance: https://laravel.com/docs/performance
- Vue 3 Performance: https://vuejs.org/guide/best-practices/performance.html
- Web Vitals: https://web.dev/vitals/
- Lighthouse: https://developers.google.com/web/tools/lighthouse

---

**Last Updated**: 2026-01-18
