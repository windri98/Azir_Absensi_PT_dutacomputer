<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Send notification to user
     */
    public function sendToUser(User $user, string $type, string $title, string $message, array $data = []): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Send notification to multiple users
     */
    public function sendToUsers(array $userIds, string $type, string $title, string $message, array $data = []): Collection
    {
        $notifications = collect();

        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $notifications->push($this->sendToUser($user, $type, $title, $message, $data));
            }
        }

        return $notifications;
    }

    /**
     * Send notification to all users
     */
    public function sendToAll(string $type, string $title, string $message, array $data = []): void
    {
        User::chunk(100, function ($users) use ($type, $title, $message, $data) {
            foreach ($users as $user) {
                $this->sendToUser($user, $type, $title, $message, $data);
            }
        });
    }

    /**
     * Get user notifications
     */
    public function getUserNotifications(User $user, int $limit = 20): Collection
    {
        return $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get unread notifications
     */
    public function getUnreadNotifications(User $user): Collection
    {
        return $user->notifications()
            ->unread()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification): bool
    {
        $notification->markAsRead();
        return true;
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(User $user): bool
    {
        $user->notifications()
            ->unread()
            ->update(['read_at' => now()]);

        return true;
    }

    /**
     * Delete notification
     */
    public function delete(Notification $notification): bool
    {
        return $notification->delete();
    }

    /**
     * Send attendance notification
     */
    public function sendAttendanceNotification(User $user, string $action): Notification
    {
        $title = $action === 'check_in' ? 'Check-in Berhasil' : 'Check-out Berhasil';
        $message = $action === 'check_in'
            ? 'Anda telah berhasil melakukan check-in pada ' . now()->format('H:i:s')
            : 'Anda telah berhasil melakukan check-out pada ' . now()->format('H:i:s');

        return $this->sendToUser($user, 'attendance', $title, $message, [
            'action' => $action,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Send leave approval notification
     */
    public function sendLeaveApprovalNotification(User $user, string $status): Notification
    {
        $title = $status === 'approved' ? 'Cuti Disetujui' : 'Cuti Ditolak';
        $message = $status === 'approved'
            ? 'Permohonan cuti Anda telah disetujui'
            : 'Permohonan cuti Anda telah ditolak';

        return $this->sendToUser($user, 'leave', $title, $message, [
            'status' => $status,
        ]);
    }
}
