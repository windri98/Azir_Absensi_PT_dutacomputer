<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Complaint;
use App\Models\User;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    private AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Show dashboard - OPTIMIZED with caching
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Get today's attendance using service
        $todayStatus = $this->attendanceService->getTodayStatus($userId);
        $todayAttendance = $todayStatus['attendance'];

        // Get monthly statistics - cache untuk 1 jam
        \Log::debug('dashboard monthly stats request', ['user_id' => $userId]);
        $monthlyStats = Cache::remember("dashboard_monthly_stats_{$userId}", 3600, function () use ($userId) {
            $thisMonth = Carbon::now();
            return [
                'present' => Attendance::where('user_id', $userId)
                    ->whereMonth('date', $thisMonth->month)
                    ->whereYear('date', $thisMonth->year)
                    ->where('status', 'present')
                    ->count(),
                'late' => Attendance::where('user_id', $userId)
                    ->whereMonth('date', $thisMonth->month)
                    ->whereYear('date', $thisMonth->year)
                    ->where('status', 'late')
                    ->count(),
                'work_leave' => Attendance::where('user_id', $userId)
                    ->whereMonth('date', $thisMonth->month)
                    ->whereYear('date', $thisMonth->year)
                    ->where('status', 'work_leave')
                    ->count(),
            ];
        });

        // Get recent attendances (last 7 days) - optimized query
        \Log::debug('dashboard view rendered', ['monthly_stats' => $monthlyStats]);
        $recentAttendances = Attendance::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->limit(7)
            ->get();

        // Get pending complaints count
        $pendingComplaints = Complaint::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        // For admin/manager: Get overall statistics with cache
        $overallStats = null;
        if ($user->hasAnyRole(['admin', 'manager'])) {
            $overallStats = Cache::remember('dashboard_admin_stats', 600, function () {
                return [
                    'total_users' => User::count(),
                    'today_present' => Attendance::whereDate('date', Carbon::today())
                        ->whereIn('status', ['present', 'late'])
                        ->count(),
                    'pending_complaints' => Complaint::where('status', 'pending')->count(),
                ];
            });
        }

        // #region agent log
        $this->logDebug('dashboard view rendered', ['monthly_stats' => $monthlyStats], 'H2');
        // #endregion

        return view('pages.dashboard', compact(
            'user',
            'todayAttendance',
            'monthlyStats',
            'recentAttendances',
            'pendingComplaints',
            'overallStats'
        ));
    }
}
