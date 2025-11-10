<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'start_time', 'end_time',
    ];

    /**
     * Relasi many-to-many dengan User
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['start_date', 'end_date'])
            ->withTimestamps();
    }
}
