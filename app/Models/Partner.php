<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'contact_person',
        'phone',
        'latitude',
        'longitude',
    ];

    /**
     * Relasi ke aktivitas teknisi.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
