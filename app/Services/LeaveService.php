<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;

class LeaveService
{
    /**
     * Submit work leave request
     */
    public function submitWorkLeave(array $data): array
    {
        $attendanceData = [
            'user_id' => Auth::id(),
            'date' => $data['date'],
            'status' => 'work_leave',
            'notes' => $data['notes'],
            'approval_status' => 'pending',
        ];

        // Handle file upload
        if (isset($data['document']) && $data['document']) {
            $file = $data['document'];
            $filename = time() . '_work_leave_' . $file->getClientOriginalName();
            $path = $file->storeAs('attendance/work-leave-documents', $filename, 'public');

            $attendanceData['leave_letter_path'] = $path;
            $attendanceData['document_filename'] = $file->getClientOriginalName();
            $attendanceData['document_uploaded_at'] = now();
        }

        $attendance = Attendance::create($attendanceData);

        // Send notification to admins
        $this->notifyAdminsAboutWorkLeave($attendance);

        return [
            'success' => true,
            'message' => 'Pengajuan izin berhasil',
            'data' => $attendance,
            'has_document' => $attendance->hasDocument(),
        ];
    }

    /**
     * Submit complaint/leave request through complaints table
     */
    public function submitComplaint(array $data): Complaint
    {
        $complaintData = [
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'] ?? 'general',
            'priority' => $data['priority'] ?? 'medium',
            'status' => 'pending',
            'notes' => $data['admin_notes'] ?? null,
        ];

        if (isset($data['attachment']) && $data['attachment']) {
            $attachmentPath = $data['attachment']->store('complaints', 'public');
            $complaintData['attachment'] = $attachmentPath;
        }

        return Complaint::create($complaintData);
    }

    /**
     * Get leave balance for a user
     */
    public function getLeaveBalance(User $user): array
    {
        return [
            'annual' => $user->getRemainingAnnualLeave(),
            'sick' => $user->getRemainingSickLeave(),
            'special' => $user->getRemainingSpecialLeave(),
        ];
    }

    /**
     * Get leave history for a user
     */
    public function getLeaveHistory(int $userId, array $filters = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = Complaint::where('user_id', $userId)
            ->whereIn('category', ['cuti', 'sakit', 'izin']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Get work leave history from attendances
     */
    public function getWorkLeaveHistory(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return Attendance::where('user_id', $userId)
            ->where('status', 'work_leave')
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Approve work leave
     */
    public function approveWorkLeave(Attendance $attendance, int $adminId): array
    {
        if ($attendance->status !== 'work_leave') {
            return ['success' => false, 'message' => 'Data tidak valid'];
        }

        $admin = User::find($adminId);
        $currentNotes = $attendance->notes ?? '';

        $attendance->update([
            'notes' => $currentNotes . "\n\n[ADMIN ACTION: DISETUJUI pada " . now()->format('Y-m-d H:i:s') . " oleh " . $admin->name . "]",
            'approved_by' => $adminId,
            'approval_status' => 'approved',
            'admin_approved_at' => now(),
            'admin_rejected_at' => null,
        ]);

        return ['success' => true, 'message' => 'Izin kerja telah disetujui'];
    }

    /**
     * Reject work leave
     */
    public function rejectWorkLeave(Attendance $attendance, int $adminId): array
    {
        if ($attendance->status !== 'work_leave') {
            return ['success' => false, 'message' => 'Data tidak valid'];
        }

        $admin = User::find($adminId);
        $currentNotes = $attendance->notes ?? '';

        $attendance->update([
            'notes' => $currentNotes . "\n\n[ADMIN ACTION: DITOLAK pada " . now()->format('Y-m-d H:i:s') . " oleh " . $admin->name . "]",
            'approved_by' => $adminId,
            'approval_status' => 'rejected',
            'admin_rejected_at' => now(),
            'admin_approved_at' => null,
        ]);

        return ['success' => true, 'message' => 'Izin kerja telah ditolak'];
    }

    /**
     * Respond to complaint
     */
    public function respondToComplaint(Complaint $complaint, array $data, int $responderId): Complaint
    {
        $complaint->update([
            'response' => $data['response'],
            'status' => $data['status'],
            'responded_by' => $responderId,
            'responded_at' => now(),
        ]);

        return $complaint;
    }

    /**
     * Delete complaint
     */
    public function deleteComplaint(Complaint $complaint): bool
    {
        if ($complaint->attachment && Storage::exists('public/' . $complaint->attachment)) {
            Storage::delete('public/' . $complaint->attachment);
        }

        return $complaint->delete();
    }

    /**
     * Notify admins about new work leave request
     */
    private function notifyAdminsAboutWorkLeave(Attendance $attendance): void
    {
        try {
            $user = $attendance->user;
            $notificationService = app(NotificationService::class);

            // Get all admin and manager users
            $admins = User::role(['admin', 'manager'])
                ->select('id', 'name', 'email')
                ->get();

            if ($admins->isEmpty()) {
                return;
            }

            $title = "Pengajuan Izin Kerja Baru dari {$user->name}";
            $message = "{$user->name} mengajukan izin kerja untuk tanggal " . $attendance->date->format('d M Y');

            // Send notification to each admin
            foreach ($admins as $admin) {
                $notificationService->sendToUser($admin, 'work_leave', $title, $message, [
                    'attendance_id' => $attendance->id,
                    'user_id' => $user->id,
                    'date' => $attendance->date->toDateString(),
                    'status' => $attendance->status,
                    'approval_status' => $attendance->approval_status,
                ]);
            }

            Log::info('Admin notifications sent for work leave', [
                'attendance_id' => $attendance->id,
                'admin_count' => $admins->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send work leave notifications: ' . $e->getMessage(), [
                'attendance_id' => $attendance->id,
            ]);
        }
    }
}
