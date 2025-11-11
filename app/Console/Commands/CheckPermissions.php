<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class CheckPermissions extends Command
{
    protected $signature = 'check:permissions {user_id?}';
    protected $description = 'Check user permissions';

    public function handle()
    {
        $userId = $this->argument('user_id') ?? 1;
        
        $user = User::find($userId);
        if (!$user) {
            $this->error('User not found');
            return;
        }

        $this->info('User: ' . $user->name . ' (' . $user->email . ')');
        $this->info('Roles: ' . $user->roles->pluck('name')->implode(', '));
        
        // Check specific permissions
        $permissionsToCheck = [
            'dashboard.admin',
            'dashboard.view',
            'users.view',
            'roles.view',
            'reports.view',
            'reports.export'
        ];

        foreach ($permissionsToCheck as $permission) {
            $hasPermission = $user->hasPermission($permission);
            $this->info("Permission '{$permission}': " . ($hasPermission ? 'YES' : 'NO'));
        }

        // Check role methods
        $this->info('Has admin role: ' . ($user->hasRole('admin') ? 'YES' : 'NO'));
        $this->info('Has superadmin role: ' . ($user->hasRole('superadmin') ? 'YES' : 'NO'));

        // Check superadmin role
        $superadminRole = Role::where('name', 'superadmin')->first();
        if ($superadminRole) {
            $this->info('Superadmin role permissions: ' . $superadminRole->permissions()->count());
            $users = $superadminRole->users()->get();
            $this->info('Users with superadmin role: ' . $users->pluck('name')->implode(', '));
        }

        // Check if user has getAllPermissions method working
        $allPerms = $user->getAllPermissions();
        $this->info('Total permissions via getAllPermissions(): ' . $allPerms->count());
    }
}