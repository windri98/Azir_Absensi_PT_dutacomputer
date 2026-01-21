<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    private AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Get all attendances for authenticated user - OPTIMIZED
     */
    public function index(Request $request)
    {
        $filters = [
            'period' => $request->get('period', 'month'),
            'status' => $request->get('status'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        $attendances = $this->attendanceService->getHistory(Auth::id(), $filters);

        return response()->json([
            'success' => true,
            'data' => $attendances->items(),
            'pagination' => [
                'total' => $attendances->total(),
                'per_page' => $attendances->perPage(),
                'current_page' => $attendances->currentPage(),
                'last_page' => $attendances->lastPage(),
            ],
        ]);
    }

    /**
     * Get single attendance record
     */
    public function show($id)
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $this->formatAttendanceResponse($attendance),
        ]);
    }

    /**
     * Check-in - OPTIMIZED with service
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'location' => 'nullable|string',
        ]);

        $data = [
            'location' => $request->location,
            'note' => $request->get('note'),
        ];

        $result = $this->attendanceService->processCheckIn($data);

        if (! $result['success']) {
            return response()->json($result, 400);
        }

        return response()->json(
            array_merge($result, ['data' => $this->formatAttendanceResponse($result['data'])]),
            200
        );
    }

    /**
     * Check-out - OPTIMIZED with service
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'location' => 'nullable|string',
        ]);

        $data = [
            'location' => $request->location,
            'notes' => $request->get('notes'),
        ];

        $result = $this->attendanceService->processCheckOut($data);

        if (! $result['success']) {
            return response()->json($result, 400);
        }

        return response()->json(
            array_merge($result, ['data' => $this->formatAttendanceResponse($result['data'])]),
            200
        );
    }

    /**
     * Update attendance (admin only)
     */
    public function update(Request $request, $id)
    {
        if (! $request->user()->hasPermission('attendance.edit_all')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:present,late,absent,work_leave',
            'check_in' => 'nullable|date_format:H:i:s',
            'check_out' => 'nullable|date_format:H:i:s',
            'notes' => 'nullable|string',
        ]);

        $attendance = Attendance::findOrFail($id);

        $attendance->update($request->only(['status', 'check_in', 'check_out', 'notes']));
        $attendance->calculateWorkHours();
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully',
            'data' => $this->formatAttendanceResponse($attendance),
        ]);
    }

    /**
     * Get today's attendance - LIGHTWEIGHT response
     */
    public function today()
    {
        $status = $this->attendanceService->getTodayStatus();

        return response()->json([
            'success' => true,
            'data' => [
                'has_checked_in' => $status['has_checked_in'],
                'has_checked_out' => $status['has_checked_out'],
                'attendance' => $status['attendance'] ? $this->formatLightweightAttendance($status['attendance']) : null,
            ],
        ]);
    }

    /**
     * Get attendance statistics - OPTIMIZED with aggregation
     */
    public function statistics(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $stats = $this->attendanceService->getStatistics(Auth::id(), $month, $year);

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Format attendance response - remove unnecessary fields
     */
    private function formatAttendanceResponse(Attendance $attendance): array
    {
        return [
            'id' => $attendance->id,
            'date' => $attendance->date?->toDateString(),
            'check_in' => $attendance->check_in,
            'check_out' => $attendance->check_out,
            'status' => $attendance->status,
            'work_hours' => $attendance->work_hours,
            'notes' => $attendance->notes,
        ];
    }

    /**
     * Format lightweight attendance for mobile
     */
    private function formatLightweightAttendance(Attendance $attendance): array
    {
        return [
            'id' => $attendance->id,
            'check_in' => $attendance->check_in,
            'check_out' => $attendance->check_out,
            'status' => $attendance->status,
            'work_hours' => $attendance->work_hours,
        ];
    }
}
