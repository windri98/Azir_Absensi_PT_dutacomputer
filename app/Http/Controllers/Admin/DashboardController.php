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
        $attendanceToday = Attendance::whereDate('date', now()->toDateString())->count();
        $lateToday = Attendance::whereDate('date', now()->toDateString())->where('status', 'late')->count();

        // Statistik pengajuan izin/cuti
        $pendingComplaints = Complaint::where('status', 'pending')->count();
        $approvedComplaints = Complaint::where('status', 'approved')->count();
        $rejectedComplaints = Complaint::where('status', 'rejected')->count();

        // Pengajuan terbaru yang masih pending
        $recentComplaints = Complaint::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'userCount',
            'adminCount',
            'attendanceToday',
            'lateToday',
            'pendingComplaints',
            'approvedComplaints',
            'rejectedComplaints',
            'recentComplaints'
        ));
    }
}
