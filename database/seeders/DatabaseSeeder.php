<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles, permissions, shifts, dan users with roles
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,  // Tambahkan permission seeder
            ShiftSeeder::class,
            UserWithRoleSeeder::class,
        ]);
        
        $this->command->info('');
        $this->command->info('✅ Database seeding completed!');
        $this->command->info('');
        $this->command->info('📝 Login credentials:');
        $this->command->info('Super Admin: superadmin@example.com / password123');
        $this->command->info('Admin: admin@example.com / password123');
        $this->command->info('HR: hr@example.com / password123');
        $this->command->info('Manager: manager@example.com / password123');
        $this->command->info('Supervisor: supervisor@example.com / password123');
        $this->command->info('Employee 1: employee1@example.com / password123');
        $this->command->info('Employee 2: employee2@example.com / password123');
        $this->command->info('Intern: intern@example.com / password123');
        $this->command->info('Contractor: contractor@example.com / password123');
        $this->command->info('');
    }
}
