<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class ExportService
{
    /**
     * Export attendance to PDF
     */
    public function exportAttendancePdf(User $user, string $startDate, string $endDate): string
    {
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get();

        $data = [
            'user' => $user,
            'attendances' => $attendances,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now(),
        ];

        $pdf = Pdf::loadView('exports.attendance-pdf', $data);
        return $pdf->download("attendance-{$user->id}-{$startDate}-{$endDate}.pdf");
    }

    /**
     * Export attendance to CSV
     */
    public function exportAttendanceCsv(User $user, string $startDate, string $endDate): string
    {
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get();

        $csv = "Tanggal,Check-in,Check-out,Status,Jam Kerja,Overtime\n";

        foreach ($attendances as $attendance) {
            $csv .= "{$attendance->date},{$attendance->check_in},{$attendance->check_out},";
            $csv .= "{$attendance->status},{$attendance->work_hours},{$attendance->overtime_hours}\n";
        }

        return $csv;
    }

    /**
     * Export all users attendance to PDF
     */
    public function exportAllAttendancePdf(string $startDate, string $endDate): string
    {
        $attendances = Attendance::whereBetween('date', [$startDate, $endDate])
            ->with('user')
            ->orderBy('date', 'asc')
            ->get();

        $data = [
            'attendances' => $attendances,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now(),
        ];

        $pdf = Pdf::loadView('exports.all-attendance-pdf', $data);
        return $pdf->download("all-attendance-{$startDate}-{$endDate}.pdf");
    }

    /**
     * Export all users attendance to CSV
     */
    public function exportAllAttendanceCsv(string $startDate, string $endDate): string
    {
        $attendances = Attendance::whereBetween('date', [$startDate, $endDate])
            ->with('user')
            ->orderBy('date', 'asc')
            ->get();

        $csv = "Nama,Email,Tanggal,Check-in,Check-out,Status,Jam Kerja,Overtime\n";

        foreach ($attendances as $attendance) {
            $csv .= "{$attendance->user->name},{$attendance->user->email},";
            $csv .= "{$attendance->date},{$attendance->check_in},{$attendance->check_out},";
            $csv .= "{$attendance->status},{$attendance->work_hours},{$attendance->overtime_hours}\n";
        }

        return $csv;
    }

    /**
     * Export user statistics
     */
    public function exportStatisticsPdf(User $user, int $month, int $year): string
    {
        $attendances = Attendance::where('user_id', $user->id)
            ->forMonth($month, $year)
            ->get();

        $stats = [
            'total_days' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'work_leave' => $attendances->where('status', 'work_leave')->count(),
            'total_work_hours' => $attendances->sum('work_hours'),
            'total_overtime_hours' => $attendances->sum('overtime_hours'),
        ];

        $data = [
            'user' => $user,
            'month' => $month,
            'year' => $year,
            'stats' => $stats,
            'attendances' => $attendances,
            'generatedAt' => now(),
        ];

        $pdf = Pdf::loadView('exports.statistics-pdf', $data);
        return $pdf->download("statistics-{$user->id}-{$month}-{$year}.pdf");
    }

    /**
     * Generate attendance report
     */
    public function generateAttendanceReport(string $startDate, string $endDate): Collection
    {
        return Attendance::whereBetween('date', [$startDate, $endDate])
            ->with('user')
            ->get()
            ->groupBy('user_id')
            ->map(function ($attendances) {
                return [
                    'user' => $attendances->first()->user,
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
}
