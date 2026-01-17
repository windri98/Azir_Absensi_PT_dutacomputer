<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncQueue extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model',
        'data',
        'status',
        'error_message',
        'retry_count',
    ];

    protected $casts = [
        'data' => 'json',
    ];

    /**
     * Get the user who initiated the sync
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for pending syncs
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed syncs
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for synced items
     */
    public function scopeSynced($query)
    {
        return $query->where('status', 'synced');
    }

    /**
     * Mark as synced
     */
    public function markAsSynced()
    {
        $this->update([
            'status' => 'synced',
            'error_message' => null,
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed($errorMessage)
    {
        $this->increment('retry_count');
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }
}
