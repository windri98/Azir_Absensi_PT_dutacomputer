<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Helpers\QRCodeHelper;

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
        'shift_id',
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
     * Get all permissions for this user through their roles
     */
    public function getAllPermissions()
    {
        return $this->roles()->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id');
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permission): bool
    {
        // Admin and superadmin role has all permissions
        if ($this->hasRole('admin') || $this->hasRole('superadmin')) {
            return true;
        }

        $allPermissions = $this->getAllPermissions();

        if (is_string($permission)) {
            return $allPermissions->contains('name', $permission);
        }

        if ($permission instanceof \App\Models\Permission) {
            return $allPermissions->contains('id', $permission->id);
        }

        return false;
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
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

    /**
     * Generate QR code untuk user ini
     */
    public function getQRCode(): string
    {
        return QRCodeHelper::generateForUser($this);
    }

    /**
     * Get QR code data untuk scan
     */
    public function getQRCodeData(): array
    {
        return [
            'employee_id' => $this->employee_id,
            'user_id' => $this->id,
            'name' => $this->name,
            'type' => 'attendance',
            'generated_at' => now()->toDateTimeString()
        ];
    }
}
