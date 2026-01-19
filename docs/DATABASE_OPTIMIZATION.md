# Database Optimization & Indexes

## Overview

Database optimization menggunakan strategic indexes untuk improve query performance dan reduce load time.

## Implemented Indexes

### 1. Attendances Table

| Column(s) | Index Name | Purpose |
|-----------|-----------|---------|
| user_id, date | attendances_user_id_date_index | Fast lookup of attendance records by user and date |
| status | attendances_status_index | Filter by attendance status (present, absent, etc) |
| created_at | attendances_created_at_index | Sort and filter by creation time |

**Typical Queries:**
```sql
-- Find all attendance for user in date range
SELECT * FROM attendances WHERE user_id = ? AND date BETWEEN ? AND ?

-- Get all absent records
SELECT * FROM attendances WHERE status = 'absent'

-- Recent attendance records
SELECT * FROM attendances ORDER BY created_at DESC LIMIT 10
```

### 2. Complaints Table

| Column(s) | Index Name | Purpose |
|-----------|-----------|---------|
| user_id | complaints_user_id_index | Get all complaints from specific user |
| status | complaints_status_index | Filter by complaint status |
| created_at | complaints_created_at_index | Recent complaints |

**Typical Queries:**
```sql
-- Get all complaints from user
SELECT * FROM complaints WHERE user_id = ?

-- Get pending complaints
SELECT * FROM complaints WHERE status = 'pending'

-- Recent complaints
SELECT * FROM complaints ORDER BY created_at DESC
```

### 3. Users Table

| Column(s) | Index Name | Purpose |
|-----------|-----------|---------|
| email | users_email_index | Login by email (unique) |
| employee_id | users_employee_id_index | Find user by employee ID |

**Typical Queries:**
```sql
-- Find user by email
SELECT * FROM users WHERE email = ?

-- Find user by employee ID
SELECT * FROM users WHERE employee_id = ?
```

### 4. Audit Logs Table

| Column(s) | Index Name | Purpose |
|-----------|-----------|---------|
| user_id, created_at | Composite | Get user's actions in time range |
| model, model_id | Composite | Track changes to specific record |

### 5. Additional Indexes

- **sync_queue**: (user_id, status) - For offline sync queue
- **notifications**: (user_id, read_at) - For unread notifications
- **leave_types**: (name, code) - Unique constraints with indexes

## Query Optimization

### 1. Eager Loading (N+1 Prevention)

**Bad (N+1 Problem):**
```php
$attendances = Attendance::all(); // Query 1
foreach ($attendances as $attendance) {
    echo $attendance->user->name; // Query per record (N queries)
}
```

**Good (Eager Loading):**
```php
$attendances = Attendance::with('user')->get(); // 2 queries total
foreach ($attendances as $attendance) {
    echo $attendance->user->name; // No additional queries
}
```

### 2. Selective Columns

**Bad (Load All Columns):**
```php
$users = User::all();
```

**Good (Load Only Needed Columns):**
```php
$users = User::select('id', 'name', 'email')->get();
```

### 3. Pagination Instead of All

**Bad (Load All Records):**
```php
$attendances = Attendance::all();
```

**Good (Paginate):**
```php
$attendances = Attendance::paginate(15);
```

### 4. Optimize Where Clauses

**Use Indexes in WHERE:**
```php
// Fast (uses index on user_id)
$attendance = Attendance::where('user_id', $userId)->get();

// Fast (uses index on status)
$complaints = Complaint::where('status', 'pending')->get();

// Slow (no index)
$users = User::where('phone', $phone)->get(); // Add index if needed
```

### 5. Avoid Function Calls in WHERE

**Bad (Disables Index):**
```php
$users = User::whereRaw('YEAR(created_at) = ?', [2024])->get();
```

**Good (Uses Index):**
```php
$users = User::whereBetween('created_at', [
    Carbon::parse('2024-01-01'),
    Carbon::parse('2024-12-31'),
])->get();
```

## Index Management

### View Current Indexes

```bash
# SSH ke database container
docker-compose exec db bash

# Connect to MySQL
mysql -u absensi_user -p

# View indexes on attendances table
SHOW INDEXES FROM attendances;

# View detailed index info
SELECT * FROM information_schema.STATISTICS WHERE TABLE_NAME='attendances';
```

### Add New Index (if needed)

```bash
# Create migration
php artisan make:migration add_new_index_to_table

# In migration
Schema::table('tablename', function (Blueprint $table) {
    $table->index(['column1', 'column2']);
});

# Run migration
php artisan migrate
```

### Drop Index (if obsolete)

```bash
# In migration
Schema::table('tablename', function (Blueprint $table) {
    $table->dropIndex('index_name');
});
```

## Performance Monitoring

### Check Slow Queries

Enable slow query log:

```bash
# In docker-compose.yml, update MySQL command
command: --default-authentication-plugin=mysql_native_password --slow-query-log --long-query-time=1
```

View slow queries:

```bash
docker-compose exec db mysql -u root -p -e "SELECT * FROM mysql.slow_log;"
```

### Analyze Query Performance

```bash
# Use EXPLAIN to analyze query
EXPLAIN SELECT * FROM attendances WHERE user_id = 1;

# With extended info
EXPLAIN FORMAT=JSON SELECT * FROM attendances WHERE user_id = 1;
```

Expected output shows:
- **key**: Index used (should not be NULL)
- **rows**: Estimated rows examined
- **type**: 'const', 'ref', 'range' (better) vs 'ALL' (bad)

### Monitor Query Count in Laravel

In production logging:

```php
// config/logging.php
'log_queries' => env('DB_LOG_QUERIES', false),
```

Then in .env:

```env
DB_LOG_QUERIES=true
```

View query count:

```php
use Illuminate\Support\Facades\DB;

// In tinker or controller
DB::enableQueryLog();
// ... run queries ...
echo count(DB::getQueryLog()); // Shows query count
```

## Connection Pooling

### MySQL Connection Pooling

Configured in `config/database.php`:

```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST'),
    'port' => env('DB_PORT'),
    'database' => env('DB_DATABASE'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'unix_socket' => env('DB_SOCKET'),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'prefix_indexes' => true,
    'strict' => true,
    'engine' => null,
    'options' => array_filter([
        PDO::ATTR_TIMEOUT => 20,
    ]),
],
```

### Connection Pooling with ProxySQL (Optional)

For high-concurrency scenarios, use ProxySQL:

```yaml
# docker-compose.yml
proxysql:
  image: proxysql/proxysql:latest
  container_name: dutacomputer-proxysql
  environment:
    - PROXYSQL_ADMIN_USERNAME=admin
    - PROXYSQL_ADMIN_PASSWORD=admin
  ports:
    - "6032:6032"
    - "3306:3306"
  networks:
    - absensi-network
```

## Caching Strategy

### Query Caching with Redis

```php
use Illuminate\Support\Facades\Cache;

// Cache attendance data for 1 hour
$attendance = Cache::remember("attendance:user:{$userId}:{$month}", 
    60 * 60, 
    function () use ($userId, $month) {
        return Attendance::where('user_id', $userId)
            ->whereMonth('date', $month)
            ->get();
    }
);
```

### Cache Invalidation

Invalidate cache when data changes:

```php
class AttendanceController
{
    public function checkIn(Request $request)
    {
        $attendance = Attendance::create([...]);
        
        // Invalidate cache
        Cache::forget("attendance:user:{$request->user()->id}:" . now()->month);
        
        return $attendance;
    }
}
```

## Best Practices

1. **Always Index Foreign Keys**
   ```php
   $table->foreign('user_id')->references('id')->on('users');
   $table->index('user_id'); // Also add index
   ```

2. **Use Composite Indexes Wisely**
   ```php
   // Good: Used for both searches
   $table->index(['user_id', 'date']);
   
   // Bad: Overlapping indexes
   $table->index(['user_id', 'date']);
   $table->index(['user_id']);
   ```

3. **Monitor Index Size**
   ```sql
   SELECT TABLE_NAME, INDEX_NAME, STAT_VALUE * @@innodb_page_size / 1024 / 1024 as size_mb
   FROM mysql.innodb_index_stats
   WHERE STAT_NAME = 'size';
   ```

4. **Rebuild Indexes Periodically**
   ```sql
   OPTIMIZE TABLE attendances;
   OPTIMIZE TABLE complaints;
   ```

5. **Archive Old Data**
   ```sql
   -- Archive attendance records > 1 year old
   INSERT INTO attendance_archive SELECT * FROM attendances WHERE date < DATE_SUB(NOW(), INTERVAL 1 YEAR);
   DELETE FROM attendances WHERE date < DATE_SUB(NOW(), INTERVAL 1 YEAR);
   ```

## Performance Targets

| Metric | Target | Current |
|--------|--------|---------|
| Page Load Time | < 2s | - |
| API Response (p95) | < 200ms | - |
| Database Query (p95) | < 100ms | - |
| Concurrent Users | 500+ | - |

## Testing Performance

### Load Testing

```bash
# Using Apache Bench
ab -n 1000 -c 50 https://api.example.com/api/v1/attendances

# Using wrk
wrk -t4 -c100 -d30s https://api.example.com/api/v1/attendances
```

### Laravel Tinker Query Analysis

```php
php artisan tinker

# Enable query logging
DB::enableQueryLog();

# Run query
$attendances = Attendance::with('user')->whereDate('date', today())->get();

# View queries
dd(DB::getQueryLog());
```

## Troubleshooting

### Issue: Slow Query Performance

1. Check if indexes exist:
   ```sql
   SHOW INDEXES FROM attendances;
   ```

2. Analyze query plan:
   ```sql
   EXPLAIN SELECT * FROM attendances WHERE user_id = 1;
   ```

3. Check query is using index (key column should not be NULL)

4. Consider adding new index if needed

### Issue: High Database Connection Count

1. Enable connection pooling
2. Use connection pooling tools (ProxySQL)
3. Optimize long-running queries
4. Set connection timeout

### Issue: Large Query Result Sets

1. Implement pagination
2. Add WHERE clause to filter
3. Use pagination in API endpoints
4. Archive old data

## Summary

- ✅ Strategic indexes implemented
- ✅ Eager loading for N+1 prevention
- ✅ Query optimization guidelines
- ✅ Connection pooling configured
- ✅ Caching strategy available
- ✅ Performance monitoring tools

## References

- [MySQL Indexing Documentation](https://dev.mysql.com/doc/refman/8.0/en/optimization-indexes.html)
- [Laravel Query Optimization](https://laravel.com/docs/eloquent#querying-relationships)
- [EXPLAIN Statement](https://dev.mysql.com/doc/refman/8.0/en/explain.html)
