<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class AssignRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-role 
                            {user? : User ID or email}
                            {role? : Role name (admin, manager, employee, supervisor)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a role to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get user identifier (ID or email)
        $userIdentifier = $this->argument('user') ?? $this->ask('Enter user ID or email');

        // Find user by ID or email
        $user = is_numeric($userIdentifier)
            ? User::find($userIdentifier)
            : User::where('email', $userIdentifier)->first();

        if (! $user) {
            $this->error('User not found!');

            return 1;
        }

        $this->info("User found: {$user->name} ({$user->email})");

        // Get available roles
        $roles = Role::all()->pluck('name')->toArray();

        if (empty($roles)) {
            $this->error('No roles found! Please run: php artisan db:seed --class=RoleSeeder');

            return 1;
        }

        // Get role name
        $roleName = $this->argument('role') ?? $this->choice(
            'Select a role to assign',
            $roles
        );

        $role = Role::where('name', $roleName)->first();

        if (! $role) {
            $this->error('Role not found!');

            return 1;
        }

        // Check if user already has this role
        if ($user->roles->contains($role->id)) {
            $this->warn("User already has the '{$role->name}' role!");

            if (! $this->confirm('Do you want to continue anyway?')) {
                return 0;
            }
        }

        // Attach role to user
        $user->roles()->attach($role->id);

        $this->info("✓ Role '{$role->name}' successfully assigned to {$user->name}");

        // Display user's current roles
        $currentRoles = $user->roles->pluck('name')->toArray();
        $this->info('Current roles: '.implode(', ', $currentRoles));

        return 0;
    }
}
