<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'check_in_location',
        'check_out_location',
        'notes',
        'work_hours',
        'overtime_hours',
        'leave_letter_path',
        'document_filename',
        'document_uploaded_at',
        'admin_approved_at',
        'admin_rejected_at',
        'approved_by',
        'approval_status',
    ];

    protected $casts = [
        'date' => 'date',
        // check_in and check_out are stored as time (HH:MM:SS), keep as string to avoid date confusion
        'check_in' => 'string',
        'check_out' => 'string',
        'document_uploaded_at' => 'datetime',
        'admin_approved_at' => 'datetime',
        'admin_rejected_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope for specific month and year
     */
    public function scopeForMonth(Builder $query, int $month, ?int $year = null): Builder
    {
        $year = $year ?? now()->year;
        return $query->whereMonth('date', $month)->whereYear('date', $year);
    }

    /**
     * Scope for this month
     */
    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('date', now()->month)->whereYear('date', now()->year);
    }

    /**
     * Scope for this week
     */
    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('date', [
            now()->startOfWeek()->toDateString(),
            now()->endOfWeek()->toDateString()
        ]);
    }

    /**
     * Scope for today
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('date', now()->toDateString());
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for present status
     */
    public function scopePresent(Builder $query): Builder
    {
        return $query->where('status', 'present');
    }

    /**
     * Scope for late status
     */
    public function scopeLate(Builder $query): Builder
    {
        return $query->where('status', 'late');
    }

    /**
     * Scope for work leave status
     */
    public function scopeWorkLeave(Builder $query): Builder
    {
        return $query->where('status', 'work_leave');
    }

    /**
     * Scope for pending approval
     */
    public function scopePendingApproval(Builder $query): Builder
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope for approved
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope with document
     */
    public function scopeWithDocument(Builder $query): Builder
    {
        return $query->whereNotNull('document_filename');
    }

    /**
     * Relasi many-to-one dengan User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi dengan User yang menyetujui/menolak
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Hitung work hours otomatis
     */
    public function calculateWorkHours(): void
    {
        if ($this->check_in && $this->check_out) {
            // Combine date with time to ensure correct same-day diff
            $date = $this->date ? \Carbon\Carbon::parse($this->date)->format('Y-m-d') : now()->format('Y-m-d');
            $checkIn = \Carbon\Carbon::parse($date.' '.$this->check_in);
            $checkOut = \Carbon\Carbon::parse($date.' '.$this->check_out);

            // If checkout passed midnight (unlikely for standard shift), handle gracefully
            if ($checkOut->lessThan($checkIn)) {
                $checkOut->addDay();
            }

            $minutes = $checkOut->diffInMinutes($checkIn);
            $this->work_hours = round($minutes / 60, 2);
        }
    }

    /**
     * Check if attendance has a document (leave letter)
     */
    public function hasDocument(): bool
    {
        return !empty($this->leave_letter_path);
    }

    /**
     * Get the document path based on status
     */
    public function getDocumentPath(): ?string
    {
        if ($this->status === 'work_leave' && $this->leave_letter_path) {
            return $this->leave_letter_path;
        }
        
        return null;
    }

    /**
     * Get the document URL for display
     */
    public function getDocumentUrl(): ?string
    {
        $path = $this->getDocumentPath();
        return $path ? asset('storage/' . $path) : null;
    }

    /**
     * Get document type label
     */
    public function getDocumentTypeLabel(): ?string
    {
        if ($this->status === 'work_leave' && $this->leave_letter_path) {
            return 'Surat Izin Kerja';
        }
        
        return null;
    }

    /**
     * Check if document is required for this status
     */
    public function requiresDocument(): bool
    {
        return $this->status === 'work_leave';
    }
}
