<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Get all attendances for authenticated user
     */
    public function index(Request $request)
    {
        $query = Attendance::where('user_id', Auth::id());

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        // Filter by month
        if ($request->has('month') && $request->has('year')) {
            $query->forMonth($request->month, $request->year);
        }

        // Limit results
        $limit = $request->get('limit', 30);
        $attendances = $query->orderBy('date', 'desc')->paginate($limit);

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
            'data' => $attendance,
        ]);
    }

    /**
     * Check-in
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'location' => 'nullable|string',
        ]);

        $today = now()->toDateString();
        $user = Auth::user();

        // Check if already checked in today
        $existing = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing && $existing->check_in) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah check-in hari ini',
            ], 400);
        }

        $attendance = $existing ?? new Attendance([
            'user_id' => $user->id,
            'date' => $today,
        ]);

        $attendance->check_in = now()->format('H:i:s');
        $attendance->check_in_location = $request->location;
        $attendance->status = 'present';
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil',
            'data' => $attendance,
        ]);
    }

    /**
     * Check-out
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'location' => 'nullable|string',
        ]);

        $today = now()->toDateString();
        $user = Auth::user();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->firstOrFail();

        if (!$attendance->check_in) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum check-in hari ini',
            ], 400);
        }

        if ($attendance->check_out) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah check-out hari ini',
            ], 400);
        }

        $attendance->check_out = now()->format('H:i:s');
        $attendance->check_out_location = $request->location;
        $attendance->calculateWorkHours();
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil',
            'data' => $attendance,
        ]);
    }

    /**
     * Update attendance (admin only)
     */
    public function update(Request $request, $id)
    {
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
            'data' => $attendance,
        ]);
    }

    /**
     * Get today's attendance
     */
    public function today()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', now()->toDateString())
            ->first();

        return response()->json([
            'success' => true,
            'data' => $attendance,
        ]);
    }

    /**
     * Get attendance statistics
     */
    public function statistics(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $attendances = Attendance::where('user_id', Auth::id())
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

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
