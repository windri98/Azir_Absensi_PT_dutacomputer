<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'password',
        'phone',
        'address',
        'birth_date',
        'photo',
        'annual_leave_quota',
        'sick_leave_quota',
        'special_leave_quota',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi many-to-many dengan Role
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Relasi one-to-many dengan Attendance
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relasi one-to-many dengan Complaint
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Relasi many-to-many dengan Shift
     */
    public function shifts(): BelongsToMany
    {
        return $this->belongsToMany(Shift::class)
            ->withPivot(['start_date', 'end_date'])
            ->withTimestamps();
    }

    /**
     * Check apakah user memiliki role tertentu
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Check apakah user memiliki salah satu dari roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Hitung cuti yang sudah dipakai tahun ini
     */
    public function getUsedLeaveCount(?string $category = null): int
    {
        $year = now()->year;

        $query = $this->complaints()
            ->whereYear('created_at', $year)
            ->where('status', 'approved');

        if ($category) {
            $query->where('category', $category);
        }

        return $query->count();
    }

    /**
     * Hitung sisa cuti tahunan
     */
    public function getRemainingAnnualLeave(): int
    {
        $used = $this->getUsedLeaveCount('cuti');

        return max(0, $this->annual_leave_quota - $used);
    }

    /**
     * Hitung sisa cuti sakit
     */
    public function getRemainingSickLeave(): int
    {
        $used = $this->getUsedLeaveCount('sakit');

        return max(0, $this->sick_leave_quota - $used);
    }

    /**
     * Hitung sisa cuti khusus
     */
    public function getRemainingSpecialLeave(): int
    {
        $used = $this->getUsedLeaveCount('izin');

        return max(0, $this->special_leave_quota - $used);
    }
}
