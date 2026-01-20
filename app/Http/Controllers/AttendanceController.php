<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    private AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Display riwayat absensi - OPTIMIZED with service
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $filters = [
            'period' => $request->get('period', 'week'),
            'status' => $request->get('status'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        // Get attendance history using optimized service
        $attendances = $this->attendanceService->getHistory($userId, $filters);
        $stats = $this->attendanceService->getHistoryStats($userId, $filters);

        Log::info('Attendance history loaded', [
            'user_id' => $userId,
            'filters' => $filters,
            'total_records' => $attendances->total()
        ]);

        return view('attendance.riwayat', compact('attendances', 'stats'));
    }

    /**
     * Check in absensi - OPTIMIZED with service
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'location' => 'required',
            'note' => 'nullable|string|max:500',
        ]);

        $data = [
            'location' => $request->location,
            'note' => $request->note,
        ];

        $result = $this->attendanceService->processCheckIn($data);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Check out absensi - OPTIMIZED with service
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'location' => 'required',
            'notes' => 'nullable|string',
        ]);

        $data = [
            'location' => $request->location,
            'notes' => $request->notes,
        ];

        $result = $this->attendanceService->processCheckOut($data);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Get status absensi hari ini - OPTIMIZED lightweight endpoint
     */
    public function todayStatus()
    {
        $status = $this->attendanceService->getTodayStatus();

        return response()->json([
            'success' => true,
            'data' => $status,
        ]);
    }

    /**
     * Submit izin/sakit - delegated to LeaveService
     */
    public function submitLeave(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:work_leave',
            'notes' => 'required|string',
            'work_leave_document' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
        ]);

        $data = [
            'date' => $request->date,
            'notes' => $request->notes,
            'document' => $request->file('work_leave_document'),
        ];

        $result = app(\App\Services\LeaveService::class)->submitWorkLeave($data);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Get statistik absensi - OPTIMIZED using aggregation
     */
    public function statistics(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $statistics = $this->attendanceService->getStatistics(Auth::id(), $month, $year);

        return response()->json([
            'success' => true,
            'data' => $statistics,
        ]);
    }

    /**
     * Show absensi page - OPTIMIZED
     */
    public function showAbsensi()
    {
        try {
            \Log::debug('showAbsensi entry', ['user_id' => Auth::id()]);
            $user = Auth::user();
            $userShift = $this->attendanceService->getUserActiveShift($user);
            $todayAttendance = $this->attendanceService->getTodayStatus()['attendance'] ?? null;

            // Get recent attendance history (last 7 days)
            $attendances = Attendance::where('user_id', Auth::id())
                ->orderBy('date', 'desc')
                ->orderBy('check_in', 'desc')
                ->limit(7)
                ->get();

            \Log::debug('showAbsensi success', ['attendances_count' => $attendances->count()]);

            return view('attendance.absensi', compact('user', 'todayAttendance', 'attendances', 'userShift'));
        } catch (\Exception $e) {
            \Log::error('showAbsensi error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Show clock-in page - OPTIMIZED
     */
    public function showClockIn()
    {
        $user = Auth::user();
        $todayStatus = $this->attendanceService->getTodayStatus();
        $todayAttendance = $todayStatus['attendance'] ?? null;

        return view('attendance.clock-in', compact('user', 'todayAttendance'));
    }

    /**
     * Show clock-out page - OPTIMIZED
     */
    public function showClockOut()
    {
        $user = Auth::user();
        $todayStatus = $this->attendanceService->getTodayStatus();
        $todayAttendance = $todayStatus['attendance'] ?? null;

        if (!$todayAttendance || !$todayStatus['has_checked_in']) {
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
     * Show clock overtime page - OPTIMIZED
     */
    public function showClockOvertime()
    {
        $user = Auth::user();
        $todayStatus = $this->attendanceService->getTodayStatus();
        $todayAttendance = $todayStatus['attendance'] ?? null;

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
