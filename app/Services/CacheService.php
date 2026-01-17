<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CacheService
{
    const CACHE_TTL = 3600; // 1 hour
    const ATTENDANCE_CACHE_TTL = 1800; // 30 minutes
    const USER_CACHE_TTL = 7200; // 2 hours
    const REPORT_CACHE_TTL = 3600; // 1 hour

    /**
     * Get user with cache
     */
    public function getUser($userId)
    {
        $key = "user:{$userId}";
        
        return Cache::remember($key, self::USER_CACHE_TTL, function () use ($userId) {
            return \App\Models\User::with('roles', 'permissions')->find($userId);
        });
    }

    /**
     * Get user attendances with cache
     */
    public function getUserAttendances($userId, $month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;
        $key = "attendances:user:{$userId}:{$year}-{$month}";

        return Cache::remember($key, self::ATTENDANCE_CACHE_TTL, function () use ($userId, $month, $year) {
            return \App\Models\Attendance::where('user_id', $userId)
                ->forMonth($month, $year)
                ->get();
        });
    }

    /**
     * Get today's attendance with cache
     */
    public function getTodayAttendance($userId)
    {
        $key = "attendance:today:{$userId}";

        return Cache::remember($key, 300, function () use ($userId) { // 5 minutes
            return \App\Models\Attendance::where('user_id', $userId)
                ->whereDate('date', now())
                ->first();
        });
    }

    /**
     * Get attendance statistics with cache
     */
    public function getAttendanceStatistics($userId, $month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;
        $key = "stats:user:{$userId}:{$year}-{$month}";

        return Cache::remember($key, self::REPORT_CACHE_TTL, function () use ($userId, $month, $year) {
            $attendances = \App\Models\Attendance::where('user_id', $userId)
                ->forMonth($month, $year)
                ->get();

            return [
                'total_days' => $attendances->count(),
                'present' => $attendances->where('status', 'present')->count(),
                'late' => $attendances->where('status', 'late')->count(),
                'absent' => $attendances->where('status', 'absent')->count(),
                'work_leave' => $attendances->where('status', 'work_leave')->count(),
                'total_work_hours' => $attendances->sum('work_hours'),
                'total_overtime_hours' => $attendances->sum('overtime_hours'),
            ];
        });
    }

    /**
     * Get all users with cache
     */
    public function getAllUsers($limit = 20, $page = 1)
    {
        $key = "users:all:{$limit}:{$page}";

        return Cache::remember($key, self::USER_CACHE_TTL, function () use ($limit, $page) {
            return \App\Models\User::paginate($limit, ['*'], 'page', $page);
        });
    }

    /**
     * Get user roles with cache
     */
    public function getUserRoles($userId)
    {
        $key = "user:roles:{$userId}";

        return Cache::remember($key, self::USER_CACHE_TTL, function () use ($userId) {
            return \App\Models\User::find($userId)?->roles()->get();
        });
    }

    /**
     * Get user permissions with cache
     */
    public function getUserPermissions($userId)
    {
        $key = "user:permissions:{$userId}";

        return Cache::remember($key, self::USER_CACHE_TTL, function () use ($userId) {
            return \App\Models\User::find($userId)?->getAllPermissions();
        });
    }

    /**
     * Invalidate user cache
     */
    public function invalidateUserCache($userId)
    {
        Cache::forget("user:{$userId}");
        Cache::forget("user:roles:{$userId}");
        Cache::forget("user:permissions:{$userId}");
    }

    /**
     * Invalidate attendance cache
     */
    public function invalidateAttendanceCache($userId, $date = null)
    {
        if ($date) {
            $month = date('m', strtotime($date));
            $year = date('Y', strtotime($date));
        } else {
            $month = now()->month;
            $year = now()->year;
        }

        Cache::forget("attendances:user:{$userId}:{$year}-{$month}");
        Cache::forget("attendance:today:{$userId}");
        Cache::forget("stats:user:{$userId}:{$year}-{$month}");
    }

    /**
     * Invalidate all cache
     */
    public function invalidateAll()
    {
        Cache::flush();
    }

    /**
     * Get cache statistics
     */
    public function getStatistics()
    {
        return [
            'driver' => config('cache.default'),
            'ttl' => self::CACHE_TTL,
        ];
    }

    /**
     * Warm up cache
     */
    public function warmUp()
    {
        $users = \App\Models\User::all();

        foreach ($users as $user) {
            $this->getUser($user->id);
            $this->getUserRoles($user->id);
            $this->getUserPermissions($user->id);
            $this->getTodayAttendance($user->id);
        }
    }
}
