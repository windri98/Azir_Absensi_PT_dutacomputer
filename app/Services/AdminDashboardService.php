<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Complaint;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AdminDashboardService
{
    /**
     * Get main dashboard statistics with caching
     */
    public function getDashboardStats(): array
    {
        // Cache untuk 5 menit untuk improve performance
        return Cache::remember('admin_dashboard_stats', 300, function () {
            try {
                \Log::debug('getDashboardStats entry');
                
                $stats = [
                    'user_count' => User::count(),
                    'admin_count' => $this->getAdminCount(),
                    'attendance_today' => $this->getAttendanceToday(),
                    'late_today' => $this->getLateToday(),
                    'work_leave_today' => $this->getWorkLeaveToday(),
                    'work_leave_this_month' => $this->getWorkLeaveThisMonth(),
                ];
                
                \Log::debug('getDashboardStats computed', $stats);
                
                return $stats;
            } catch (\Exception $e) {
                \Log::error('getDashboardStats error', ['error' => $e->getMessage()]);
                throw $e;
            }
        });
    }

    /**
     * Get complaint statistics
     */
    public function getComplaintStats(): array
    {
        return Cache::remember('admin_complaint_stats', 300, function () {
            return [
                'pending' => Complaint::whereIn('category', ['technical', 'administrative', 'lainnya'])
                    ->where('status', 'pending')
                    ->count(),
                'resolved' => Complaint::whereIn('category', ['technical', 'administrative', 'lainnya'])
                    ->where('status', 'resolved')
                    ->count(),
                'closed' => Complaint::whereIn('category', ['technical', 'administrative', 'lainnya'])
                    ->where('status', 'closed')
                    ->count(),
            ];
        });
    }

    /**
     * Get leave request statistics from complaints table (cuti, sakit, izin)
     */
    public function getLeaveRequestStats(): array
    {
        return Cache::remember('admin_leave_stats', 300, function () {
            return [
                'pending' => Complaint::whereIn('category', ['cuti', 'sakit', 'izin'])
                    ->where('status', 'pending')
                    ->count(),
                'approved' => Complaint::whereIn('category', ['cuti', 'sakit', 'izin'])
                    ->where('status', 'approved')
                    ->count(),
                'rejected' => Complaint::whereIn('category', ['cuti', 'sakit', 'izin'])
                    ->where('status', 'rejected')
                    ->count(),
            ];
        });
    }

    /**
     * Get work leave statistics from attendances table
     */
    public function getWorkLeaveStats(): array
    {
        return Cache::remember('admin_work_leave_stats', 300, function () {
            return [
                'pending' => Attendance::where('status', 'work_leave')
                    ->where('approval_status', 'pending')
                    ->count(),
                'approved' => Attendance::where('status', 'work_leave')
                    ->where('approval_status', 'approved')
                    ->count(),
                'rejected' => Attendance::where('status', 'work_leave')
                    ->where('approval_status', 'rejected')
                    ->count(),
            ];
        });
    }

    /**
     * Get recent pending complaints/leave requests from complaints table
     */
    public function getRecentPendingComplaints(int $perPage = 10)
    {
        return Complaint::with('user')
            ->whereIn('category', ['cuti', 'sakit', 'izin'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'complaints_page');
    }

    /**
     * Get recent pending leave requests (alias for backward compatibility)
     */
    public function getRecentPendingLeaveRequests(int $perPage = 10)
    {
        return $this->getRecentPendingComplaints($perPage);
    }

    /**
     * Get recent work leave requests with pagination from attendances table
     */
    public function getRecentWorkLeaveRequests(int $perPage = 10)
    {
        return Attendance::with('user')
            ->where('status', 'work_leave')
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'work_leave_page');
    }

    /**
     * Get work leave requests with filters
     */
    public function getWorkLeaveRequests(array $filters = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = Attendance::with('user')
            ->where('status', 'work_leave');

        // Search by user name or employee ID
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        // Filter by month
        if (!empty($filters['month'])) {
            $query->whereMonth('date', $filters['month']);
        }

        // Filter by year
        if (!empty($filters['year'])) {
            $query->whereYear('date', $filters['year']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    /**
     * Get attendance statistics for overview
     */
    public function getAttendanceOverview(): array
    {
        $today = now()->toDateString();
        
        return [
            'present_today' => Attendance::whereDate('date', $today)
                ->where('status', 'present')
                ->count(),
            'late_today' => Attendance::whereDate('date', $today)
                ->where('status', 'late')
                ->count(),
            'absent_today' => Attendance::whereDate('date', $today)
                ->where('status', 'absent')
                ->count(),
            'total_today' => Attendance::whereDate('date', $today)->count(),
        ];
    }

    /**
     * Get this week attendance statistics
     */
    public function getThisWeekStats(): array
    {
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();

        $query = Attendance::whereBetween('date', [$startOfWeek, $endOfWeek]);

        return [
            'total_present' => (clone $query)->where('status', 'present')->count(),
            'total_late' => (clone $query)->where('status', 'late')->count(),
            'total_absent' => (clone $query)->where('status', 'absent')->count(),
            'total_work_leave' => (clone $query)->where('status', 'work_leave')->count(),
            'average_work_hours' => round((clone $query)->avg('work_hours') ?? 0, 2),
        ];
    }

    /**
     * Get this month attendance statistics
     */
    public function getThisMonthStats(): array
    {
        $query = Attendance::forMonth(now()->month, now()->year);

        return [
            'total_present' => (clone $query)->where('status', 'present')->count(),
            'total_late' => (clone $query)->where('status', 'late')->count(),
            'total_absent' => (clone $query)->where('status', 'absent')->count(),
            'total_work_leave' => (clone $query)->where('status', 'work_leave')->count(),
            'total_work_hours' => round((clone $query)->sum('work_hours') ?? 0, 2),
        ];
    }

    /**
     * Clear dashboard caches
     */
    public function clearCaches(): void
    {
        Cache::forget('admin_dashboard_stats');
        Cache::forget('admin_complaint_stats');
        Cache::forget('admin_leave_stats');
        Cache::forget('admin_work_leave_stats');
    }

    // Private helper methods

    private function getAdminCount(): int
    {
        return \DB::table('role_user')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', 'admin')
            ->count();
    }

    private function getAttendanceToday(): int
    {
        return Attendance::whereDate('date', now()->toDateString())->count();
    }

    private function getLateToday(): int
    {
        return Attendance::whereDate('date', now()->toDateString())
            ->where('status', 'late')
            ->count();
    }

    private function getWorkLeaveToday(): int
    {
        return Attendance::whereDate('date', now()->toDateString())
            ->where('status', 'work_leave')
            ->count();
    }

    private function getWorkLeaveThisMonth(): int
    {
        return Attendance::forMonth(now()->month, now()->year)
            ->where('status', 'work_leave')
            ->count();
    }
}
