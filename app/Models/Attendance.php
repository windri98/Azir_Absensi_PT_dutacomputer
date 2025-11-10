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
    ];

    protected $casts = [
        'date' => 'date',
        // check_in and check_out are stored as time (HH:MM:SS), keep as string to avoid date confusion
        'check_in' => 'string',
        'check_out' => 'string',
    ];

    /**
     * Relasi many-to-one dengan User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
}
