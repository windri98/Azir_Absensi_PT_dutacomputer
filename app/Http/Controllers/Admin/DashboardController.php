<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\AdminDashboardService;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    private AdminDashboardService $adminDashboardService;

    public function __construct(AdminDashboardService $adminDashboardService)
    {
        $this->adminDashboardService = $adminDashboardService;
    }

    /**
     * Admin dashboard - OPTIMIZED with service and caching
     */
    public function index()
    {
        // Get all stats from service (includes caching)
        $stats = $this->adminDashboardService->getDashboardStats();
        $complaintStats = $this->adminDashboardService->getComplaintStats();
        $leaveStats = $this->adminDashboardService->getLeaveRequestStats();

        // Unpacking stats
        $userCount = $stats['user_count'];
        $adminCount = $stats['admin_count'];
        $attendanceToday = $stats['attendance_today'];
        $lateToday = $stats['late_today'];
        $workLeaveToday = $stats['work_leave_today'];
        $workLeaveThisMonth = $stats['work_leave_this_month'];

        // Complaint stats
        $pendingComplaints = $complaintStats['pending'];
        $resolvedComplaints = $complaintStats['resolved'];
        $closedComplaints = $complaintStats['closed'];

        // Leave stats
        $pendingLeaveRequests = $leaveStats['pending'];
        $approvedLeaveRequests = $leaveStats['approved'];
        $rejectedLeaveRequests = $leaveStats['rejected'];

        // Get recent items
        $recentComplaints = $this->adminDashboardService->getRecentPendingLeaveRequests(10);
        $recentWorkLeave = $this->adminDashboardService->getRecentWorkLeaveRequests(10);

        return view('admin.dashboard', compact(
            'userCount',
            'adminCount',
            'attendanceToday',
            'lateToday',
            'workLeaveToday',
            'workLeaveThisMonth',
            'pendingComplaints',
            'resolvedComplaints',
            'closedComplaints',
            'pendingLeaveRequests',
            'approvedLeaveRequests',
            'rejectedLeaveRequests',
            'recentComplaints',
            'recentWorkLeave'
        ));
    }

    /**
     * Kelola pengajuan izin kerja - OPTIMIZED with service
     */
    public function workLeaveRequests()
    {
        $filters = [
            'search' => request('search'),
            'month' => request('month'),
            'year' => request('year'),
        ];

        $workLeaves = $this->adminDashboardService->getWorkLeaveRequests($filters);

        return view('admin.work-leave.index', compact('workLeaves'));
    }

    /**
     * Detail pengajuan izin kerja
     */
    public function workLeaveDetail(Attendance $attendance)
    {
        if ($attendance->status !== 'work_leave') {
            abort(404, 'Data tidak ditemukan');
        }

        $attendance->load('user', 'approvedBy');

        return view('admin.work-leave.detail', compact('attendance'));
    }

    /**
     * Approve/reject izin kerja - OPTIMIZED with service
     */
    public function workLeaveAction(Attendance $attendance, $action)
    {
        try {
            if ($attendance->status !== 'work_leave') {
                return response()->json(['success' => false, 'message' => 'Data tidak valid'], 400);
            }

            if (!in_array($action, ['approve', 'reject'])) {
                return response()->json(['success' => false, 'message' => 'Action tidak valid'], 400);
            }

            $leaveService = app(\App\Services\LeaveService::class);
            $method = $action === 'approve' ? 'approveWorkLeave' : 'rejectWorkLeave';
            $result = $leaveService->$method($attendance, auth()->id());

            // Clear admin dashboard cache
            $this->adminDashboardService->clearCaches();

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'action' => $action,
                'attendance_id' => $attendance->id
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in workLeaveAction: ' . $e->getMessage(), [
                'attendance_id' => $attendance->id ?? 'unknown',
                'action' => $action,
                'user_id' => auth()->id(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses permintaan'
            ], 500);
        }
    }
}
