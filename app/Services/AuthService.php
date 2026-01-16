<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Attempt login
     */
    public function attemptLogin(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Logout user
     */
    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    /**
     * Register new user (admin only)
     */
    public function registerUser(array $data): User
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

        // Assign default role (employee)
        $defaultRole = Role::where('name', 'employee')->first();
        if ($defaultRole) {
            $user->roles()->attach($defaultRole->id);
        }

        return $user;
    }

    /**
     * Change user password
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): array
    {
        if (!Hash::check($currentPassword, $user->password)) {
            return [
                'success' => false,
                'message' => 'Password lama tidak sesuai',
            ];
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        return [
            'success' => true,
            'message' => 'Password berhasil diubah',
        ];
    }

    /**
     * Get dashboard redirect based on user permissions
     */
    public function getDashboardRedirect(User $user): string
    {
        // Check if user wants specific dashboard type
        $requestedType = request()->get('type');

        if ($requestedType === 'admin' && $user->hasPermission('dashboard.admin')) {
            return 'admin.dashboard';
        }

        if ($requestedType === 'user' && $user->hasPermission('dashboard.view')) {
            return 'user.dashboard';
        }

        // Default: admin goes to admin dashboard, others go to user dashboard
        if ($user->hasPermission('dashboard.admin')) {
            return 'admin.dashboard';
        }

        return 'user.dashboard';
    }
}
