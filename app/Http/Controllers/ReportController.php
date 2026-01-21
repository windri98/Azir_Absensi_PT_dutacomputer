<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Show laporan page
     */
    public function index(Request $request)
    {
        // Check permission instead of role
        if (! Auth::user()->hasPermission('reports.view_all')) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();
        $query = Attendance::with('user');

        // Jika bukan admin/superadmin, hanya boleh lihat data sendiri
        if (! $user->hasRole('admin') && ! $user->hasRole('superadmin')) {
            $query->where('user_id', $user->id);
        } else {
            // Admin bisa filter user_id jika diinginkan
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->paginate(20);

        // Hanya admin/superadmin yang bisa melihat semua user untuk filter
        $users = ($user->hasRole('admin') || $user->hasRole('superadmin'))
            ? User::orderBy('name')->get()
            : collect([$user]);

        return view('reports.laporan', compact('attendances', 'users'));
    }

    /**
     * Show report history
     */
    public function history(Request $request)
    {
        $query = Attendance::where('user_id', Auth::id());

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->paginate(20);

        return view('reports.report-history', compact('attendances'));
    }

    /**
     * Show customer report (for admin/manager)
     */
    public function customerReport(Request $request)
    {
        if (! Auth::user()->hasPermission('reports.view_all')) {
            abort(403, 'Unauthorized');
        }

        $users = User::with(['attendances' => function ($query) use ($request) {
            if ($request->filled('month')) {
                $query->whereMonth('date', $request->month);
            }
            if ($request->filled('year')) {
                $query->whereYear('date', $request->year);
            }
        }])->get();

        return view('reports.customer-report', compact('users'));
    }

    /**
     * Export report as JSON (for download/API)
     */
    public function export(Request $request)
    {
        if (! Auth::user()->hasPermission('reports.export')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $query = Attendance::with('user');

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        // Jika ?format=csv maka export CSV, default JSON
        if ($request->get('format') === 'csv') {
            $filename = 'laporan-absensi-' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];
            $callback = function() use ($attendances) {
                $handle = fopen('php://output', 'w');
                // Header CSV tanpa kutip berlebih, delimiter koma
                fputcsv($handle, ['Tanggal', 'Nama Pegawai', 'Jam Masuk', 'Jam Pulang', 'Status Kehadiran'], ',');
                foreach ($attendances as $a) {
                    $tanggal = \Carbon\Carbon::parse($a->date)->format('d-m-Y');
                    $masuk = $a->clock_in ? date('H:i', strtotime($a->clock_in)) : '-';
                    $pulang = $a->clock_out ? date('H:i', strtotime($a->clock_out)) : '-';
                    $statusMap = [
                        'present' => 'Hadir',
                        'late' => 'Terlambat',
                        'leave' => 'Izin',
                        'absent' => 'Alpha',
                        'sick' => 'Sakit',
                        'overtime' => 'Lembur',
                    ];
                    $status = $statusMap[$a->status] ?? ucfirst($a->status);
                    $nama = $a->user ? $a->user->name : '-';
                    fputcsv($handle, [
                        $tanggal,
                        $nama,
                        $masuk,
                        $pulang,
                        $status,
                    ], ',');
                }
                fclose($handle);
            };
            return response()->stream($callback, 200, $headers);
        }

        // Default: JSON
        return response()->json([
            'success' => true,
            'data' => $attendances,
        ]);
    }

    /**
     * Get summary statistics for report
     */
    public function summary(Request $request)
    {
        if (! Auth::user()->hasPermission('reports.view_all')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $query = Attendance::whereMonth('date', $month)
            ->whereYear('date', $year);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $summary = [
            'total_present' => (clone $query)->where('status', 'present')->count(),
            'total_late' => (clone $query)->where('status', 'late')->count(),
            'total_absent' => (clone $query)->where('status', 'absent')->count(),
            'total_sick' => (clone $query)->where('status', 'sick')->count(),
            'total_leave' => (clone $query)->where('status', 'leave')->count(),
            'total_work_hours' => (clone $query)->sum('work_hours'),
            'average_work_hours' => (clone $query)->avg('work_hours'),
        ];

        return response()->json([
            'success' => true,
            'data' => $summary,
        ]);
    }

    /**
     * Show users list for reports
     */
    public function users(Request $request)
    {
        if (! Auth::user()->hasPermission('reports.view_all')) {
            abort(403, 'Unauthorized');
        }

        $query = User::with(['attendances' => function($q) {
            $q->whereMonth('date', Carbon::now()->month)
              ->whereYear('date', Carbon::now()->year);
        }, 'roles']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->orderBy('name')->paginate(12);

        return view('reports.users', compact('users'));
    }

    /**
     * Show detailed user report
     */
    public function userDetail($userId, Request $request)
    {
        if (! Auth::user()->hasPermission('reports.view_all')) {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($userId);
        
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Build query for attendances
        $query = Attendance::where('user_id', $userId);
        
        if ($startDate && $endDate) {
            $query->where('date', '>=', $startDate)
                  ->where('date', '<=', $endDate);
        } else {
            $query->whereMonth('date', $month)
                  ->whereYear('date', $year);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        // Calculate statistics
        $stats = [
            'total_days' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'sick' => $attendances->where('status', 'sick')->count(),
            'leave' => $attendances->where('status', 'leave')->count(),
            'overtime' => $attendances->where('status', 'overtime')->count(),
            'total_work_hours' => $attendances->sum('work_hours'),
            'average_work_hours' => $attendances->avg('work_hours'),
            'total_overtime_hours' => $attendances->sum('overtime_hours'),
        ];

        // Monthly breakdown
        $monthlyData = [];
        for ($i = 1; $i <= 31; $i++) {
            $date = Carbon::create($year, $month, $i);
            if (!$date->isValid() || $date->day !== $i) continue;
            
            $dayAttendance = $attendances->firstWhere('date', $date->format('Y-m-d'));
            $monthlyData[] = [
                'date' => $date,
                'attendance' => $dayAttendance,
                'is_weekend' => $date->isWeekend(),
            ];
        }

        return view('reports.user-detail', compact('user', 'attendances', 'stats', 'monthlyData', 'month', 'year'));
    }
}
