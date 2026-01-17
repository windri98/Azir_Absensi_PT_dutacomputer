<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * Get personal attendance report
     */
    public function personalReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $attendances = Attendance::where('user_id', Auth::id())
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->orderBy('date', 'desc')
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

        return response()->json([
            'success' => true,
            'data' => [
                'attendances' => $attendances,
                'statistics' => $stats,
            ],
        ]);
    }

    /**
     * Get all users attendance report (admin only)
     */
    public function allUsersReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $report = $this->exportService->generateAttendanceReport(
            $request->start_date,
            $request->end_date
        );

        return response()->json([
            'success' => true,
            'data' => $report,
        ]);
    }

    /**
     * Get specific user report (admin only)
     */
    public function userReport(Request $request, $userId)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->orderBy('date', 'desc')
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

        return response()->json([
            'success' => true,
            'data' => [
                'attendances' => $attendances,
                'statistics' => $stats,
            ],
        ]);
    }

    /**
     * Export personal report as PDF
     */
    public function exportPersonalPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        return $this->exportService->exportAttendancePdf(
            Auth::user(),
            $request->start_date,
            $request->end_date
        );
    }

    /**
     * Export personal report as CSV
     */
    public function exportPersonalCsv(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $csv = $this->exportService->exportAttendanceCsv(
            Auth::user(),
            $request->start_date,
            $request->end_date
        );

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="attendance.csv"');
    }

    /**
     * Export all users report as PDF
     */
    public function exportAllPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        return $this->exportService->exportAllAttendancePdf(
            $request->start_date,
            $request->end_date
        );
    }

    /**
     * Export all users report as CSV
     */
    public function exportAllCsv(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $csv = $this->exportService->exportAllAttendanceCsv(
            $request->start_date,
            $request->end_date
        );

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="all-attendance.csv"');
    }
}
