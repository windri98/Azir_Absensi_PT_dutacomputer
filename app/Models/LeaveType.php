<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'default_quota',
        'requires_document',
        'is_active',
    ];

    protected $casts = [
        'requires_document' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active leave types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
