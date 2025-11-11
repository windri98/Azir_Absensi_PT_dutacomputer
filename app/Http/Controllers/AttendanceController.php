<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    /**
     * Display riwayat absensi
     */
    public function index(Request $request)
    {
        \Log::info('Attendance index method called', [
            'user_id' => Auth::id(),
            'request_params' => $request->all()
        ]);

        $query = Attendance::with('user');

        // For attendance riwayat page, always show current user's data only
        // (Admin can view other users' data through different routes if needed)
        $query->where('user_id', Auth::id());

        // Filter by period or date range
        $period = $request->get('period', 'week');
        
        if ($period === 'week') {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            $query->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()]);
        } elseif ($period === 'month') {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            $query->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()]);
        } elseif ($period === 'custom') {
            if ($request->filled('start_date')) {
                $query->where('date', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->where('date', '<=', $request->end_date);
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->paginate(15);

        // Calculate statistics - for regular users, always filter to their data only
        // For admin/managers in attendance history, show only their own data unless explicitly viewing someone else's
        $baseStatsQuery = Attendance::query();
        
        // Always filter by current user for this page (even admin sees their own stats)
        $baseStatsQuery->where('user_id', Auth::id());

        // Apply same filters to statistics base query
        if ($period === 'week') {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            $baseStatsQuery->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()]);
        } elseif ($period === 'month') {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            $baseStatsQuery->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()]);
        } elseif ($period === 'custom') {
            if ($request->filled('start_date')) {
                $baseStatsQuery->where('date', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $baseStatsQuery->where('date', '<=', $request->end_date);
            }
        }
        if ($request->filled('status')) {
            $baseStatsQuery->where('status', $request->status);
        }

        // Calculate statistics using separate queries to avoid conflicts
        $stats = [
            'total_days' => (clone $baseStatsQuery)->count(),
            'present_days' => (clone $baseStatsQuery)->whereIn('status', ['present', 'late'])->count(),
            'late_days' => (clone $baseStatsQuery)->where('status', 'late')->count(),
            'total_hours' => (clone $baseStatsQuery)->sum('work_hours') ?? 0,
        ];

        // Debug info
        \Log::info('Attendance Statistics Debug', [
            'period' => $period,
            'user_id' => Auth::id(),
            'is_admin' => Auth::user()->hasAnyRole(['admin', 'manager']),
            'week_start' => $period === 'week' ? Carbon::now()->startOfWeek()->toDateString() : null,
            'week_end' => $period === 'week' ? Carbon::now()->endOfWeek()->toDateString() : null,
            'stats' => $stats,
            'attendances_count' => $attendances->total()
        ]);

        return view('attendance.riwayat', compact('attendances', 'stats'));
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
            'type' => 'required|in:work_leave',
            'notes' => 'required|string',
            'work_leave_document' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120', // 5MB
        ]);

        $attendanceData = [
            'user_id' => Auth::id(),
            'date' => $request->date,
            'status' => $request->type,
            'notes' => $request->notes,
        ];

        // Handle file upload for work leave
        if ($request->type === 'work_leave' && $request->hasFile('work_leave_document')) {
            $file = $request->file('work_leave_document');
            $filename = time() . '_work_leave_' . $file->getClientOriginalName();
            $path = $file->storeAs('attendance/work-leave-documents', $filename, 'public');
            
            $attendanceData['leave_letter_path'] = $path;
            $attendanceData['document_filename'] = $file->getClientOriginalName();
            $attendanceData['document_uploaded_at'] = now();
        }

        $attendance = Attendance::create($attendanceData);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan izin berhasil',
            'data' => $attendance,
            'has_document' => $attendance->hasDocument(),
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
            'total_work_leave' => (clone $query)->where('status', 'work_leave')->count(),
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

    /**
     * Download attendance document
     */
    public function downloadDocument(Attendance $attendance)
    {
        // Check authorization - user can only download their own documents or admin/manager can download all
        if (!Auth::user()->hasAnyRole(['admin', 'manager']) && $attendance->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $path = $attendance->getDocumentPath();
        if (!$path) {
            abort(404, 'Document not found');
        }

        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }

        $filename = $attendance->document_filename ?: basename($path);
        return response()->download($fullPath, $filename);
    }

    /**
     * View attendance document (for images and PDFs)
     */
    public function viewDocument(Attendance $attendance)
    {
        // Check authorization
        if (!Auth::user()->hasAnyRole(['admin', 'manager']) && $attendance->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $path = $attendance->getDocumentPath();
        if (!$path) {
            abort(404, 'Document not found');
        }

        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }

        $mimeType = mime_content_type($fullPath);
        return response()->file($fullPath, ['Content-Type' => $mimeType]);
    }

    /**
     * Delete attendance document
     */
    public function deleteDocument(Attendance $attendance)
    {
        // Check authorization - user can only delete their own documents or admin/manager can delete all
        if (!Auth::user()->hasAnyRole(['admin', 'manager']) && $attendance->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $path = $attendance->getDocumentPath();
        if (!$path) {
            return response()->json(['success' => false, 'message' => 'Document not found'], 404);
        }

        // Delete file from storage
        if (\Storage::disk('public')->exists($path)) {
            \Storage::disk('public')->delete($path);
        }

        // Clear document fields
        $updateData = [
            'leave_letter_path' => null,
            'document_filename' => null,
            'document_uploaded_at' => null,
        ];

        $attendance->update($updateData);

        return response()->json([
            'success' => true, 
            'message' => 'Document deleted successfully'
        ]);
    }
}
