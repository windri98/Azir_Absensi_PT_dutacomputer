<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get attendance report with filters
     */
    public function getAttendanceReport(array $filters = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = Attendance::with('user');

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('date', '<=', $filters['end_date']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('date', 'desc')->paginate(20);
    }

    /**
     * Get summary statistics
     */
    public function getSummaryStats(?int $userId = null, ?int $month = null, ?int $year = null): array
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $query = Attendance::forMonth($month, $year);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return [
            'total_present' => (clone $query)->present()->count(),
            'total_late' => (clone $query)->late()->count(),
            'total_absent' => (clone $query)->where('status', 'absent')->count(),
            'total_sick' => (clone $query)->where('status', 'sick')->count(),
            'total_leave' => (clone $query)->where('status', 'leave')->count(),
            'total_work_leave' => (clone $query)->workLeave()->count(),
            'total_work_hours' => (clone $query)->sum('work_hours'),
            'average_work_hours' => (clone $query)->avg('work_hours'),
        ];
    }

    /**
     * Get user detailed report
     */
    public function getUserDetailReport(int $userId, array $filters = []): array
    {
        $user = User::findOrFail($userId);
        
        $month = $filters['month'] ?? Carbon::now()->month;
        $year = $filters['year'] ?? Carbon::now()->year;

        $query = Attendance::where('user_id', $userId);

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('date', [$filters['start_date'], $filters['end_date']]);
        } else {
            $query->forMonth($month, $year);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        $stats = [
            'total_days' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'sick' => $attendances->where('status', 'sick')->count(),
            'leave' => $attendances->where('status', 'leave')->count(),
            'work_leave' => $attendances->where('status', 'work_leave')->count(),
            'total_work_hours' => $attendances->sum('work_hours'),
            'average_work_hours' => $attendances->avg('work_hours'),
        ];

        // Build monthly calendar data
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

        return [
            'user' => $user,
            'attendances' => $attendances,
            'stats' => $stats,
            'monthly_data' => $monthlyData,
            'month' => $month,
            'year' => $year,
        ];
    }

    /**
     * Export attendance to CSV
     */
    public function exportToCsv(array $filters = []): \Closure
    {
        $month = $filters['month'] ?? now()->month;
        $year = $filters['year'] ?? now()->year;

        $query = Attendance::with('user')
            ->forMonth($month, $year)
            ->orderBy('date', 'desc');

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        $attendances = $query->get();

        $ownerInfo = 'Pemilik Data: Admin';
        if (!empty($filters['user_id'])) {
            $user = User::find($filters['user_id']);
            if ($user) {
                $ownerInfo = 'Pemilik Data: ' . $user->name . ' (' . $user->email . ')';
            }
        }

        return function () use ($attendances, $month, $year, $ownerInfo) {
            $file = fopen('php://output', 'w');
            
            // Header info
            fputcsv($file, ['LAPORAN ABSENSI']);
            fputcsv($file, [$ownerInfo]);
            fputcsv($file, ['Periode', date('F', mktime(0, 0, 0, $month, 1)) . ' ' . $year]);
            fputcsv($file, ['Dicetak', date('d/m/Y H:i:s')]);
            fputcsv($file, []);
            
            // Table header
            fputcsv($file, ['Tanggal', 'Nama', 'Email', 'Check In', 'Check Out', 'Status', 'Jam Kerja']);

            foreach ($attendances as $att) {
                fputcsv($file, [
                    $att->date,
                    $att->user->name,
                    $att->user->email,
                    $att->check_in ?? '-',
                    $att->check_out ?? '-',
                    $this->translateStatus($att->status),
                    $att->work_hours ?? 0,
                ]);
            }
            
            fclose($file);
        };
    }

    /**
     * Get users with attendance summary
     */
    public function getUsersWithSummary(array $filters = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $query = User::with(['attendances' => function ($q) use ($month, $year) {
            $q->forMonth($month, $year);
        }, 'roles']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        return $query->orderBy('name')->paginate(12);
    }

    /**
     * Translate status to Indonesian
     */
    private function translateStatus(string $status): string
    {
        $map = [
            'present' => 'Hadir',
            'late' => 'Terlambat',
            'leave' => 'Izin',
            'absent' => 'Alpha',
            'sick' => 'Sakit',
            'work_leave' => 'Izin Kerja',
            'overtime' => 'Lembur',
        ];

        return $map[$status] ?? ucfirst($status);
    }
}
