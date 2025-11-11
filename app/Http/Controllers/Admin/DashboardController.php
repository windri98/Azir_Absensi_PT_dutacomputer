<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $adminCount = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.name', 'admin')->count();
        $attendanceToday = Attendance::where('date', now()->toDateString())->count();
        $lateToday = Attendance::where('date', now()->toDateString())->where('status', 'late')->count();
        
        // Statistik izin kerja
        $workLeaveToday = Attendance::where('date', now()->toDateString())->where('status', 'work_leave')->count();
        $workLeaveThisMonth = Attendance::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->where('status', 'work_leave')
            ->count();

        // Statistik keluhan karyawan (technical/administrative complaints)
        $pendingComplaints = Complaint::whereIn('category', ['technical', 'administrative', 'lainnya'])
            ->where('status', 'pending')
            ->count();
        $resolvedComplaints = Complaint::whereIn('category', ['technical', 'administrative', 'lainnya'])
            ->where('status', 'resolved')
            ->count();
        $closedComplaints = Complaint::whereIn('category', ['technical', 'administrative', 'lainnya'])
            ->where('status', 'closed')
            ->count();

        // Statistik pengajuan izin/cuti
        $pendingLeaveRequests = Complaint::whereIn('category', ['cuti', 'sakit', 'izin'])
            ->where('status', 'pending')
            ->count();
        $approvedLeaveRequests = Complaint::whereIn('category', ['cuti', 'sakit', 'izin'])
            ->where('status', 'approved')
            ->count();
        $rejectedLeaveRequests = Complaint::whereIn('category', ['cuti', 'sakit', 'izin'])
            ->where('status', 'rejected')
            ->count();

        // Pengajuan izin/cuti terbaru yang masih pending
        $recentComplaints = Complaint::with('user')
            ->whereIn('category', ['cuti', 'sakit', 'izin'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Pengajuan izin kerja terbaru (dengan dokumen)
        $recentWorkLeave = Attendance::with('user')
            ->where('status', 'work_leave')
            ->whereNotNull('document_filename')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

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
     * Kelola pengajuan izin kerja
     */
    public function workLeaveRequests()
    {
        $query = Attendance::with('user')
            ->where('status', 'work_leave');

        // Search by user name or employee ID
        if (request('search')) {
            $search = request('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        // Filter by month and year
        if (request('month')) {
            $query->whereMonth('date', request('month'));
        }
        if (request('year')) {
            $query->whereYear('date', request('year'));
        }

        $workLeaves = $query->orderBy('created_at', 'desc')->paginate(15);

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

        return view('admin.work-leave.detail', compact('attendance'));
    }

    /**
     * Approve/reject izin kerja
     */
    public function workLeaveAction(Attendance $attendance, $action)
    {
        try {
            \Log::info('workLeaveAction called', [
                'attendance_id' => $attendance->id,
                'user_id' => $attendance->user_id,
                'status' => $attendance->status,
                'action' => $action,
                'admin_user_id' => auth()->id(),
            ]);

            if ($attendance->status !== 'work_leave') {
                return response()->json(['success' => false, 'message' => 'Data tidak valid - bukan pengajuan izin kerja'], 400);
            }

            if (!in_array($action, ['approve', 'reject'])) {
                return response()->json(['success' => false, 'message' => 'Action tidak valid'], 400);
            }

            // Update status dan notes
            $actionText = $action === 'approve' ? 'DISETUJUI' : 'DITOLAK';
            $currentNotes = $attendance->notes ?? '';
            
            $updateData = [
                'notes' => $currentNotes . "\n\n[ADMIN ACTION: " . $actionText . " pada " . now()->format('Y-m-d H:i:s') . " oleh " . auth()->user()->name . "]",
                'approved_by' => auth()->id(),
                'approval_status' => $action === 'approve' ? 'approved' : 'rejected',
            ];
            
            if ($action === 'approve') {
                $updateData['admin_approved_at'] = now();
                $updateData['admin_rejected_at'] = null;
            } else {
                $updateData['admin_rejected_at'] = now();
                $updateData['admin_approved_at'] = null;
            }
            
            $attendance->update($updateData);

            $message = $action === 'approve' ? 'Izin kerja telah disetujui' : 'Izin kerja telah ditolak';
            
            return response()->json([
                'success' => true,
                'message' => $message,
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
