<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Display riwayat absensi
     */
    public function index(Request $request)
    {
        $query = Attendance::with('user');

        // Filter by user (untuk admin/manager bisa lihat semua, employee hanya miliknya)
        if (! Auth::user()->hasAnyRole(['admin', 'manager'])) {
            $query->where('user_id', Auth::id());
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
            ->orderBy('check_in', 'desc')
            ->paginate(15);

        return view('attendance.riwayat', compact('attendances'));
    }

    /**
     * Check in absensi
     */
    public function checkIn(Request $request)
    {
        Log::info('Check-in request received', [
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
        ]);

        $request->validate([
            'location' => 'required',
            'note' => 'nullable|string|max:500',
        ]);

        $today = Carbon::today();

        // Check apakah sudah check in hari ini
        $existingAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->first();

        if ($existingAttendance && $existingAttendance->check_in) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-in hari ini',
            ], 400);
        }

        $checkInTime = Carbon::now();
        $workStartTime = Carbon::createFromTime(8, 0, 0); // 08:00 AM

        $status = 'present';
        if ($checkInTime->gt($workStartTime)) {
            $status = 'late';
        }

        // Format location string
        $locationString = is_array($request->location)
            ? json_encode($request->location)
            : $request->location;

        $attendance = Attendance::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'date' => $today,
            ],
            [
                'check_in' => $checkInTime->format('H:i:s'),
                'check_in_location' => $locationString,
                'status' => $status,
                'notes' => $request->note,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil',
            'data' => $attendance,
        ]);
    }

    /**
     * Check out absensi
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'location' => 'required',
            'notes' => 'nullable|string',
        ]);

        $today = Carbon::today();

        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->first();

        if (! $attendance || ! $attendance->check_in) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan check-in hari ini',
            ], 400);
        }

        if ($attendance->check_out) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-out hari ini',
            ], 400);
        }

        // Format location string
        $locationString = is_array($request->location)
            ? json_encode($request->location)
            : $request->location;

        $checkOutTime = Carbon::now();
        $attendance->check_out = $checkOutTime->format('H:i:s');
        $attendance->check_out_location = $locationString;
        $attendance->notes = $request->notes;

        // Hitung work hours
        // Recalculate work hours in minutes/decimal hours
        $attendance->calculateWorkHours();
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil',
            'data' => $attendance,
        ]);
    }

    /**
     * Get status absensi hari ini
     */
    public function todayStatus()
    {
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'has_checked_in' => $attendance && $attendance->check_in ? true : false,
                'has_checked_out' => $attendance && $attendance->check_out ? true : false,
                'attendance' => $attendance,
            ],
        ]);
    }

    /**
     * Submit izin/sakit
     */
    public function submitLeave(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:sick,leave',
            'notes' => 'required|string',
        ]);

        $attendance = Attendance::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'status' => $request->type,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan izin berhasil',
            'data' => $attendance,
        ]);
    }

    /**
     * Get statistik absensi (untuk dashboard)
     */
    public function statistics(Request $request)
    {
        $userId = Auth::id();
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $query = Attendance::where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year);

        // Clone query per status untuk mencegah mixing conditions
        $statistics = [
            'total_days' => (clone $query)->count(),
            'total_present' => (clone $query)->where('status', 'present')->count(),
            'total_late' => (clone $query)->where('status', 'late')->count(),
            'total_absent' => (clone $query)->where('status', 'absent')->count(),
            'total_sick' => (clone $query)->where('status', 'sick')->count(),
            'total_leave' => (clone $query)->where('status', 'leave')->count(),
            'total_work_hours' => (clone $query)->sum('work_hours'),
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics,
        ]);
    }

    /**
     * Show absensi page
     */
    public function showAbsensi()
    {
        $user = Auth::user();

        // Ambil shift user yang sedang aktif (hari ini dalam range start_date dan end_date)
        $today = Carbon::today();
        $userShift = $user->shifts()
            ->where(function ($query) use ($today) {
                $query->where(function ($q) {
                    // Permanent shift (tidak ada tanggal)
                    $q->whereNull('shift_user.start_date')
                        ->whereNull('shift_user.end_date');
                })
                    ->orWhere(function ($q) use ($today) {
                        // Shift dengan start_date <= today dan (end_date >= today atau null)
                        $q->where('shift_user.start_date', '<=', $today)
                            ->where(function ($query) use ($today) {
                                $query->whereNull('shift_user.end_date')
                                    ->orWhere('shift_user.end_date', '>=', $today);
                            });
                    });
            })
            ->first();

        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', Carbon::today())
            ->first();

        // Ambil riwayat absensi user, urut terbaru
        $attendances = Attendance::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->get();

        return view('attendance.absensi', compact('user', 'todayAttendance', 'attendances', 'userShift'));
    }

    /**
     * Show clock-in page
     */
    public function showClockIn()
    {
        $user = Auth::user();
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', Carbon::today())
            ->first();

        return view('attendance.clock-in', compact('user', 'todayAttendance'));
    }

    /**
     * Show clock-out page
     */
    public function showClockOut()
    {
        $user = Auth::user();
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', Carbon::today())
            ->first();

        if (! $todayAttendance || ! $todayAttendance->check_in) {
            return redirect()->route('attendance.absensi')->with('error', 'Anda belum check-in hari ini');
        }

        return view('attendance.clock-out', compact('user', 'todayAttendance'));
    }

    /**
     * Show QR scan page
     */
    public function showQrScan()
    {
        return view('attendance.qr-scan');
    }

    /**
     * Show clock overtime page
     */
    public function showClockOvertime()
    {
        $user = Auth::user();
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', Carbon::today())
            ->first();

        return view('attendance.clock-overtime', compact('user', 'todayAttendance'));
    }
}
