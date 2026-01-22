<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'partner_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'status',
        'evidence_path',
        'signature_path',
        'signature_name',
        'signed_at',
        'latitude',
        'longitude',
        'location_address',
        'approved_by',
        'approved_at',
        'rejected_reason',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'signed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Relasi ke user pembuat aktivitas.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke mitra.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Relasi ke admin/HRD yang menyetujui.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
