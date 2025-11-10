<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Role;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Show export form
     */
    public function exportForm()
    {
        $users = User::orderBy('name')->get();
        $roles = Role::all();
        $shifts = Shift::all();

        return view('admin.reports.export', compact('users', 'roles', 'shifts'));
    }

    /**
     * Preview data before download
     */
    public function preview(Request $request)
    {
        $data = $this->getFilteredData($request);

        return view('admin.reports.preview', [
            'attendances' => $data['attendances'],
            'summary' => $data['summary'],
            'includeSummary' => $request->has('include_summary'),
        ]);
    }

    /**
     * Download report
     */
    public function download(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:csv,pdf',
        ]);

        $data = $this->getFilteredData($request);
        $format = $request->get('format', 'csv');

        if ($format === 'csv') {
            return $this->downloadCSV($request, $data);
        } else {
            return $this->downloadPDF($request, $data);
        }
    }

    /**
     * Get filtered attendance data
     */
    private function getFilteredData(Request $request)
    {
        $query = Attendance::with('user');

        // Date range filter
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        // User filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Role filter
        if ($request->filled('role_id')) {
            $query->whereHas('user.roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role_id);
            });
        }

        // Shift filter
        if ($request->filled('shift_id')) {
            $query->whereHas('user.shifts', function ($q) use ($request) {
                $q->where('shifts.id', $request->shift_id);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->whereIn('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('user_id')
            ->get();

        // Calculate summary
        $summary = [
            'total_records' => $attendances->count(),
            'total_hadir' => $attendances->whereIn('status', ['present', 'late'])->count(),
            'total_terlambat' => $attendances->where('status', 'late')->count(),
            'total_absent' => $attendances->where('status', 'absent')->count(),
            'total_jam_kerja' => $attendances->sum('work_hours'),
            'avg_jam_kerja' => $attendances->where('work_hours', '>', 0)->avg('work_hours'),
        ];

        return [
            'attendances' => $attendances,
            'summary' => $summary,
        ];
    }

    /**
     * Download as CSV
     */
    private function downloadCSV(Request $request, $data)
    {
        $attendances = $data['attendances'];
        $summary = $data['summary'];
        $columns = $request->get('columns', ['date', 'user_name', 'check_in', 'check_out', 'work_hours', 'status']);

        $filename = 'laporan_absensi_'.date('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($attendances, $summary, $columns, $request) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header info
            fputcsv($file, ['LAPORAN ABSENSI KARYAWAN']);
            fputcsv($file, ['Periode', $request->start_date.' s/d '.$request->end_date]);
            fputcsv($file, ['Dicetak', date('d/m/Y H:i:s')]);
            fputcsv($file, []);

            // Summary if requested
            if ($request->has('include_summary')) {
                fputcsv($file, ['RINGKASAN']);
                fputcsv($file, ['Total Records', $summary['total_records']]);
                fputcsv($file, ['Total Hadir', $summary['total_hadir']]);
                fputcsv($file, ['Total Terlambat', $summary['total_terlambat']]);
                fputcsv($file, ['Total Tidak Hadir', $summary['total_absent']]);
                fputcsv($file, ['Total Jam Kerja', number_format($summary['total_jam_kerja'], 2).' jam']);
                fputcsv($file, ['Rata-rata Jam/Hari', number_format($summary['avg_jam_kerja'], 2).' jam']);
                fputcsv($file, []);
            }

            // Column headers
            $headers = [];
            if (in_array('date', $columns)) {
                $headers[] = 'Tanggal';
            }
            if (in_array('user_name', $columns)) {
                $headers[] = 'Nama';
            }
            if (in_array('user_name', $columns)) {
                $headers[] = 'Email';
            }
            if (in_array('check_in', $columns)) {
                $headers[] = 'Check In';
            }
            if (in_array('check_out', $columns)) {
                $headers[] = 'Check Out';
            }
            if (in_array('work_hours', $columns)) {
                $headers[] = 'Jam Kerja';
            }
            if (in_array('status', $columns)) {
                $headers[] = 'Status';
            }
            if (in_array('location', $columns)) {
                $headers[] = 'Lokasi Check In';
            }
            if (in_array('location', $columns)) {
                $headers[] = 'Lokasi Check Out';
            }
            if (in_array('notes', $columns)) {
                $headers[] = 'Catatan';
            }

            fputcsv($file, $headers);

            // Data rows
            foreach ($attendances as $att) {
                $row = [];
                if (in_array('date', $columns)) {
                    $row[] = \Carbon\Carbon::parse($att->date)->format('d/m/Y');
                }
                if (in_array('user_name', $columns)) {
                    $row[] = $att->user->name;
                }
                if (in_array('user_name', $columns)) {
                    $row[] = $att->user->email;
                }
                if (in_array('check_in', $columns)) {
                    $row[] = $att->check_in ?? '-';
                }
                if (in_array('check_out', $columns)) {
                    $row[] = $att->check_out ?? '-';
                }
                if (in_array('work_hours', $columns)) {
                    $row[] = $att->work_hours ? number_format($att->work_hours, 2) : '0';
                }
                if (in_array('status', $columns)) {
                    $row[] = $this->translateStatus($att->status);
                }
                if (in_array('location', $columns)) {
                    $row[] = $att->check_in_location ?? '-';
                }
                if (in_array('location', $columns)) {
                    $row[] = $att->check_out_location ?? '-';
                }
                if (in_array('notes', $columns)) {
                    $row[] = $att->notes ?? '';
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download as PDF (placeholder)
     */
    private function downloadPDF(Request $request, $data)
    {
        // For now, return CSV with PDF extension
        // In production, you would use something like dompdf or mpdf
        return response()->json([
            'message' => 'PDF export will be implemented soon. Please use CSV format for now.',
        ], 501);
    }

    /**
     * Translate status to Indonesian
     */
    private function translateStatus($status)
    {
        $translations = [
            'present' => 'Hadir',
            'late' => 'Terlambat',
            'absent' => 'Tidak Hadir',
            'sick' => 'Sakit',
            'leave' => 'Cuti',
        ];

        return $translations[$status] ?? ucfirst($status);
    }
}
