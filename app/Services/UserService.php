<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Attendance;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    /**
     * Get users list with statistics
     */
    public function getUsersList(array $filters = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = User::with(['roles', 'shifts'])
            ->withCount([
                'attendances as total_hadir' => function ($q) {
                    $q->whereMonth('date', date('m'))
                        ->whereYear('date', date('Y'))
                        ->whereNotNull('check_in');
                },
                'attendances as total_terlambat' => function ($q) {
                    $q->whereMonth('date', date('m'))
                        ->whereYear('date', date('Y'))
                        ->where('status', 'late');
                },
            ])
            ->withSum([
                'attendances as total_jam_kerja' => function ($q) {
                    $q->whereMonth('date', date('m'))
                        ->whereYear('date', date('Y'))
                        ->whereNotNull('check_out');
                },
            ], DB::raw('CAST(work_hours AS DECIMAL(10,2))'));

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('employee_id', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('id', 'desc')->paginate(15);
    }

    /**
     * Create new user
     */
    public function createUser(array $data, ?int $createdBy = null): User
    {
        $user = User::create([
            'employee_id' => $data['employee_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'birth_date' => $data['birth_date'] ?? null,
            'gender' => $data['gender'],
        ]);

        if (!empty($data['roles'])) {
            $user->roles()->attach($data['roles']);
        }

        if (!empty($data['shifts'])) {
            $user->shifts()->attach($data['shifts']);
        }

        // Audit log
        if ($createdBy) {
            $this->logAction($createdBy, 'create', 'User', $user->id, [
                'employee_id' => $user->employee_id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
        }

        return $user;
    }

    /**
     * Update user
     */
    public function updateUser(User $user, array $data, ?int $updatedBy = null): User
    {
        $oldData = [
            'employee_id' => $user->employee_id,
            'name' => $user->name,
            'email' => $user->email,
        ];

        $user->employee_id = $data['employee_id'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->address = $data['address'] ?? null;
        $user->birth_date = $data['birth_date'] ?? null;
        $user->gender = $data['gender'];
        
        if (isset($data['annual_leave_quota'])) {
            $user->annual_leave_quota = $data['annual_leave_quota'];
        }
        if (isset($data['sick_leave_quota'])) {
            $user->sick_leave_quota = $data['sick_leave_quota'];
        }
        if (isset($data['special_leave_quota'])) {
            $user->special_leave_quota = $data['special_leave_quota'];
        }

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        if (isset($data['shifts'])) {
            $user->shifts()->sync($data['shifts']);
        }

        // Audit log
        if ($updatedBy) {
            $this->logAction($updatedBy, 'update', 'User', $user->id, [
                'old' => $oldData,
                'new' => [
                    'employee_id' => $user->employee_id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
        }

        return $user;
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user, ?int $deletedBy = null): bool
    {
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];

        $result = $user->delete();

        if ($deletedBy && $result) {
            $this->logAction($deletedBy, 'delete', 'User', $userData['id'], $userData);
        }

        return $result;
    }

    /**
     * Get user attendance statistics
     */
    public function getUserAttendanceStats(User $user, ?int $month = null, ?int $year = null): array
    {
        $month = $month ?? date('m');
        $year = $year ?? date('Y');

        return [
            'total_hadir' => $user->attendances()
                ->forMonth($month, $year)
                ->whereNotNull('check_in')
                ->count(),
            'total_terlambat' => $user->attendances()
                ->forMonth($month, $year)
                ->where('status', 'late')
                ->count(),
            'total_jam_kerja' => $user->attendances()
                ->forMonth($month, $year)
                ->whereNotNull('check_out')
                ->sum(DB::raw('CAST(work_hours AS DECIMAL(10,2))')),
        ];
    }

    /**
     * Update user profile
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? $user->phone;
        $user->address = $data['address'] ?? $user->address;
        $user->birth_date = $data['birth_date'] ?? $user->birth_date;

        if (isset($data['photo']) && $data['photo']) {
            // Delete old photo
            if ($user->photo && Storage::exists('public/' . $user->photo)) {
                Storage::delete('public/' . $user->photo);
            }
            $user->photo = $data['photo']->store('photos', 'public');
        }

        $user->save();

        return $user;
    }

    /**
     * Update profile photo
     */
    public function updateProfilePhoto(User $user, $photo): string
    {
        // Delete old photo
        if ($user->photo && Storage::exists('public/' . $user->photo)) {
            Storage::delete('public/' . $user->photo);
        }

        $photoPath = $photo->store('photos', 'public');
        $user->photo = $photoPath;
        $user->save();

        return $photoPath;
    }

    /**
     * Delete profile photo
     */
    public function deleteProfilePhoto(User $user): bool
    {
        if ($user->photo && Storage::exists('public/' . $user->photo)) {
            Storage::delete('public/' . $user->photo);
            $user->photo = null;
            $user->save();
            return true;
        }

        return false;
    }

    /**
     * Log action to audit log
     */
    private function logAction(int $userId, string $action, string $modelType, int $modelId, array $changes): void
    {
        AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'changes' => $changes,
            'ip_address' => request()->ip(),
        ]);
    }
}
