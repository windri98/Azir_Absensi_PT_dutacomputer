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
        // Only admin and manager can access
        if (! Auth::user()->hasAnyRole(['admin', 'manager'])) {
            abort(403, 'Unauthorized');
        }

        $query = Attendance::with('user');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->paginate(20);

        $users = User::orderBy('name')->get();

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
        if (! Auth::user()->hasAnyRole(['admin', 'manager'])) {
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
        if (! Auth::user()->hasAnyRole(['admin', 'manager'])) {
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
        if (! Auth::user()->hasAnyRole(['admin', 'manager'])) {
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
}
