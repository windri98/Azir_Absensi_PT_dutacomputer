<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * User Seeder dengan Role Assignment
 *
 * Seeder ini membuat contoh users dengan role yang sudah di-assign
 * Jalankan: php artisan db:seed --class=UserWithRoleSeeder
 */
class UserWithRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan roles sudah ada
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $employeeRole = Role::where('name', 'employee')->first();
        $supervisorRole = Role::where('name', 'supervisor')->first();

        // Buat Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'employee_id' => 'ADM001',
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'address' => 'Jl. Admin No. 123, Jakarta',
                'birth_date' => '1990-01-15',
            ]
        );
        if ($adminRole && ! $admin->roles->contains($adminRole->id)) {
            $admin->roles()->attach($adminRole->id);
        }

        // Buat Manager User
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'employee_id' => 'MGR001',
                'name' => 'Manager User',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'address' => 'Jl. Manager No. 456, Bandung',
                'birth_date' => '1985-05-20',
            ]
        );
        if ($managerRole && ! $manager->roles->contains($managerRole->id)) {
            $manager->roles()->attach($managerRole->id);
        }

        // Buat Employee Users
        $employee1 = User::firstOrCreate(
            ['email' => 'employee1@example.com'],
            [
                'employee_id' => 'EMP001',
                'name' => 'Employee One',
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                'address' => 'Jl. Karyawan No. 789, Surabaya',
                'birth_date' => '1995-03-10',
            ]
        );
        if ($employeeRole && ! $employee1->roles->contains($employeeRole->id)) {
            $employee1->roles()->attach($employeeRole->id);
        }

        $employee2 = User::firstOrCreate(
            ['email' => 'employee2@example.com'],
            [
                'employee_id' => 'EMP002',
                'name' => 'Employee Two',
                'password' => Hash::make('password123'),
                'phone' => '081234567893',
                'address' => 'Jl. Pekerja No. 321, Semarang',
                'birth_date' => '1992-08-25',
            ]
        );
        if ($employeeRole && ! $employee2->roles->contains($employeeRole->id)) {
            $employee2->roles()->attach($employeeRole->id);
        }

        // Buat Supervisor User
        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor@example.com'],
            [
                'employee_id' => 'SPV001',
                'name' => 'Supervisor User',
                'password' => Hash::make('password123'),
                'phone' => '081234567894',
                'address' => 'Jl. Supervisor No. 654, Yogyakarta',
                'birth_date' => '1988-12-05',
            ]
        );
        if ($supervisorRole && ! $supervisor->roles->contains($supervisorRole->id)) {
            $supervisor->roles()->attach($supervisorRole->id);
        }

        $this->command->info('Users with roles created successfully!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@example.com / password123');
        $this->command->info('Manager: manager@example.com / password123');
        $this->command->info('Employee 1: employee1@example.com / password123');
        $this->command->info('Employee 2: employee2@example.com / password123');
        $this->command->info('Supervisor: supervisor@example.com / password123');
    }
}
