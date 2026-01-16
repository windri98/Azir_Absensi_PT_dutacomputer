<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceService
{
    /**
     * Process check-in for a user
     */
    public function processCheckIn(array $data): array
    {
        $userId = Auth::id();
        $today = Carbon::today();

        // Check if already checked in today
        $existingAttendance = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        if ($existingAttendance && $existingAttendance->check_in) {
            return [
                'success' => false,
                'message' => 'Anda sudah melakukan check-in hari ini',
            ];
        }

        $checkInTime = Carbon::now();
        $workStartTime = Carbon::createFromTime(8, 0, 0);

        $status = $checkInTime->gt($workStartTime) ? 'late' : 'present';

        $locationString = is_array($data['location'])
            ? json_encode($data['location'])
            : $data['location'];

        $attendance = Attendance::updateOrCreate(
            ['user_id' => $userId, 'date' => $today],
            [
                'check_in' => $checkInTime->format('H:i:s'),
                'check_in_location' => $locationString,
                'status' => $status,
                'notes' => $data['note'] ?? null,
            ]
        );

        return [
            'success' => true,
            'message' => 'Check-in berhasil',
            'data' => $attendance,
        ];
    }

    /**
     * Process check-out for a user
     */
    public function processCheckOut(array $data): array
    {
        $userId = Auth::id();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return [
                'success' => false,
                'message' => 'Anda belum melakukan check-in hari ini',
            ];
        }

        if ($attendance->check_out) {
            return [
                'success' => false,
                'message' => 'Anda sudah melakukan check-out hari ini',
            ];
        }

        $locationString = is_array($data['location'])
            ? json_encode($data['location'])
            : $data['location'];

        $checkOutTime = Carbon::now();
        $attendance->check_out = $checkOutTime->format('H:i:s');
        $attendance->check_out_location = $locationString;
        $attendance->notes = $data['notes'] ?? $attendance->notes;
        $attendance->calculateWorkHours();
        $attendance->save();

        return [
            'success' => true,
            'message' => 'Check-out berhasil',
            'data' => $attendance,
        ];
    }

    /**
     * Get today's attendance status for a user
     */
    public function getTodayStatus(?int $userId = null): array
    {
        $userId = $userId ?? Auth::id();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        return [
            'has_checked_in' => $attendance && $attendance->check_in ? true : false,
            'has_checked_out' => $attendance && $attendance->check_out ? true : false,
            'attendance' => $attendance,
        ];
    }

    /**
     * Get attendance statistics for a user
     */
    public function getStatistics(int $userId, ?int $month = null, ?int $year = null): array
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        return Attendance::where('user_id', $userId)
            ->forMonth($month, $year)
            ->selectRaw("
                COUNT(*) as total_days,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as total_present,
                SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as total_late,
                SUM(CASE WHEN status = 'work_leave' THEN 1 ELSE 0 END) as total_work_leave,
                COALESCE(SUM(work_hours), 0) as total_work_hours
            ")
            ->first()
            ->toArray();
    }

    /**
     * Get attendance history with filters
     */
    public function getHistory(int $userId, array $filters = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = Attendance::with('user')
            ->where('user_id', $userId);

        // Apply period filter
        $period = $filters['period'] ?? 'week';

        if ($period === 'week') {
            $query->whereBetween('date', [
                Carbon::now()->startOfWeek()->toDateString(),
                Carbon::now()->endOfWeek()->toDateString()
            ]);
        } elseif ($period === 'month') {
            $query->whereBetween('date', [
                Carbon::now()->startOfMonth()->toDateString(),
                Carbon::now()->endOfMonth()->toDateString()
            ]);
        } elseif ($period === 'custom') {
            if (!empty($filters['start_date'])) {
                $query->where('date', '>=', $filters['start_date']);
            }
            if (!empty($filters['end_date'])) {
                $query->where('date', '<=', $filters['end_date']);
            }
        }

        // Apply status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->paginate(15);
    }

    /**
     * Calculate statistics for history view
     */
    public function getHistoryStats(int $userId, array $filters = []): array
    {
        $query = Attendance::where('user_id', $userId);

        // Apply same filters as history
        $period = $filters['period'] ?? 'week';

        if ($period === 'week') {
            $query->whereBetween('date', [
                Carbon::now()->startOfWeek()->toDateString(),
                Carbon::now()->endOfWeek()->toDateString()
            ]);
        } elseif ($period === 'month') {
            $query->whereBetween('date', [
                Carbon::now()->startOfMonth()->toDateString(),
                Carbon::now()->endOfMonth()->toDateString()
            ]);
        } elseif ($period === 'custom') {
            if (!empty($filters['start_date'])) {
                $query->where('date', '>=', $filters['start_date']);
            }
            if (!empty($filters['end_date'])) {
                $query->where('date', '<=', $filters['end_date']);
            }
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return [
            'total_days' => (clone $query)->count(),
            'present_days' => (clone $query)->whereIn('status', ['present', 'late'])->count(),
            'late_days' => (clone $query)->where('status', 'late')->count(),
            'total_hours' => (clone $query)->sum('work_hours') ?? 0,
        ];
    }

    /**
     * Get user's active shift for today
     */
    public function getUserActiveShift(User $user)
    {
        $today = Carbon::today();

        return $user->shifts()
            ->where(function ($query) use ($today) {
                $query->where(function ($q) {
                    $q->whereNull('shift_user.start_date')
                        ->whereNull('shift_user.end_date');
                })->orWhere(function ($q) use ($today) {
                    $q->where('shift_user.start_date', '<=', $today)
                        ->where(function ($query) use ($today) {
                            $query->whereNull('shift_user.end_date')
                                ->orWhere('shift_user.end_date', '>=', $today);
                        });
                });
            })
            ->first();
    }
}
