<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'sick_letter_path',
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
     * Check if attendance has a document (sick letter or leave letter)
     */
    public function hasDocument(): bool
    {
        return !empty($this->sick_letter_path) || !empty($this->leave_letter_path);
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
