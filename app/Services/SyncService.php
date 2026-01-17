<?php

namespace App\Services;

use App\Models\SyncQueue;
use App\Models\Attendance;
use Illuminate\Support\Collection;

class SyncService
{
    /**
     * Process sync queue
     */
    public function processSyncQueue(): array
    {
        $pendingItems = SyncQueue::pending()->get();
        $processed = 0;
        $failed = 0;

        foreach ($pendingItems as $item) {
            try {
                $this->processSyncItem($item);
                $item->markAsSynced();
                $processed++;
            } catch (\Exception $e) {
                $item->markAsFailed($e->getMessage());
                $failed++;
            }
        }

        return [
            'processed' => $processed,
            'failed' => $failed,
            'total' => $processed + $failed,
        ];
    }

    /**
     * Process single sync item
     */
    public function processSyncItem(SyncQueue $item): void
    {
        $data = $item->data;

        switch ($item->action) {
            case 'create':
                $this->handleCreate($item->model, $data);
                break;
            case 'update':
                $this->handleUpdate($item->model, $item->model_id, $data);
                break;
            case 'delete':
                $this->handleDelete($item->model, $item->model_id);
                break;
            default:
                throw new \Exception("Unknown action: {$item->action}");
        }
    }

    /**
     * Handle create action
     */
    private function handleCreate(string $model, array $data): void
    {
        switch ($model) {
            case 'Attendance':
                Attendance::create($data);
                break;
            default:
                throw new \Exception("Unknown model: {$model}");
        }
    }

    /**
     * Handle update action
     */
    private function handleUpdate(string $model, int $modelId, array $data): void
    {
        switch ($model) {
            case 'Attendance':
                Attendance::findOrFail($modelId)->update($data);
                break;
            default:
                throw new \Exception("Unknown model: {$model}");
        }
    }

    /**
     * Handle delete action
     */
    private function handleDelete(string $model, int $modelId): void
    {
        switch ($model) {
            case 'Attendance':
                Attendance::findOrFail($modelId)->delete();
                break;
            default:
                throw new \Exception("Unknown model: {$model}");
        }
    }

    /**
     * Add item to sync queue
     */
    public function queueAction(int $userId, string $action, string $model, int $modelId, array $data): SyncQueue
    {
        return SyncQueue::create([
            'user_id' => $userId,
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'data' => $data,
            'status' => 'pending',
        ]);
    }

    /**
     * Get pending sync items for user
     */
    public function getPendingItems(int $userId): Collection
    {
        return SyncQueue::where('user_id', $userId)
            ->pending()
            ->get();
    }

    /**
     * Get failed sync items
     */
    public function getFailedItems(int $limit = 50): Collection
    {
        return SyncQueue::failed()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Retry failed items
     */
    public function retryFailedItems(int $maxRetries = 3): array
    {
        $failedItems = SyncQueue::failed()
            ->where('retry_count', '<', $maxRetries)
            ->get();

        $processed = 0;
        $failed = 0;

        foreach ($failedItems as $item) {
            try {
                $this->processSyncItem($item);
                $item->markAsSynced();
                $processed++;
            } catch (\Exception $e) {
                $item->markAsFailed($e->getMessage());
                $failed++;
            }
        }

        return [
            'processed' => $processed,
            'failed' => $failed,
        ];
    }

    /**
     * Clear old synced items
     */
    public function clearOldSyncedItems(int $daysOld = 30): int
    {
        return SyncQueue::synced()
            ->where('created_at', '<', now()->subDays($daysOld))
            ->delete();
    }
}
