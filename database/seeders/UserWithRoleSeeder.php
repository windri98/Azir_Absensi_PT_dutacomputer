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
        $superadminRole = Role::where('name', 'superadmin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $hrRole = Role::where('name', 'hr')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $supervisorRole = Role::where('name', 'supervisor')->first();
        $employeeRole = Role::where('name', 'employee')->first();
        $internRole = Role::where('name', 'intern')->first();
        $contractorRole = Role::where('name', 'contractor')->first();

        // Buat Super Admin User
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'employee_id' => 'SADM001',
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'phone' => '081234567888',
                'address' => 'Jl. Super Admin No. 001, Jakarta',
                'birth_date' => '1985-01-01',
            ]
        );
        if ($superadminRole && ! $superadmin->roles->contains($superadminRole->id)) {
            $superadmin->roles()->attach($superadminRole->id);
        }
        

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

        // Buat HR User
        $hr = User::firstOrCreate(
            ['email' => 'hr@example.com'],
            [
                'employee_id' => 'HR001',
                'name' => 'HR Manager',
                'password' => Hash::make('password123'),
                'phone' => '081234567889',
                'address' => 'Jl. HR No. 100, Jakarta',
                'birth_date' => '1988-03-20',
            ]
        );
        if ($hrRole && ! $hr->roles->contains($hrRole->id)) {
            $hr->roles()->attach($hrRole->id);
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

        // Buat Intern User
        $intern = User::firstOrCreate(
            ['email' => 'intern@example.com'],
            [
                'employee_id' => 'INT001',
                'name' => 'Intern User',
                'password' => Hash::make('password123'),
                'phone' => '081234567895',
                'address' => 'Jl. Magang No. 111, Malang',
                'birth_date' => '2000-06-15',
            ]
        );
        if ($internRole && ! $intern->roles->contains($internRole->id)) {
            $intern->roles()->attach($internRole->id);
        }

        // Buat Contractor User
        $contractor = User::firstOrCreate(
            ['email' => 'contractor@example.com'],
            [
                'employee_id' => 'CTR001',
                'name' => 'Contractor User',
                'password' => Hash::make('password123'),
                'phone' => '081234567896',
                'address' => 'Jl. Kontraktor No. 222, Bali',
                'birth_date' => '1991-09-10',
            ]
        );
        if ($contractorRole && ! $contractor->roles->contains($contractorRole->id)) {
            $contractor->roles()->attach($contractorRole->id);
        }

        $this->command->info('✅ Users with roles created successfully!');
    }
}
