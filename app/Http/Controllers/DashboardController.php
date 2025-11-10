<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Complaint;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get today's attendance
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', Carbon::today())
            ->first();

        // Get this month's statistics
        $thisMonth = Carbon::now();
        $monthlyStats = [
            'present' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', $thisMonth->month)
                ->whereYear('date', $thisMonth->year)
                ->where('status', 'present')
                ->count(),
            'late' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', $thisMonth->month)
                ->whereYear('date', $thisMonth->year)
                ->where('status', 'late')
                ->count(),
            'absent' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', $thisMonth->month)
                ->whereYear('date', $thisMonth->year)
                ->where('status', 'absent')
                ->count(),
            'sick' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', $thisMonth->month)
                ->whereYear('date', $thisMonth->year)
                ->where('status', 'sick')
                ->count(),
            'leave' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', $thisMonth->month)
                ->whereYear('date', $thisMonth->year)
                ->where('status', 'leave')
                ->count(),
        ];

        // Get recent attendances (last 7 days)
        $recentAttendances = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        // Get pending complaints count
        $pendingComplaints = Complaint::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // For admin/manager: Get overall statistics
        $overallStats = null;
        if ($user->hasAnyRole(['admin', 'manager'])) {
            $overallStats = [
                'total_users' => User::count(),
                'today_present' => Attendance::whereDate('date', Carbon::today())
                    ->whereIn('status', ['present', 'late'])
                    ->count(),
                'pending_complaints' => Complaint::where('status', 'pending')->count(),
            ];
        }

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
