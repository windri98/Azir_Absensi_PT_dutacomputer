<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'superadmin',
                'display_name' => 'Super Administrator',
                'description' => 'Super Admin dengan akses penuh ke seluruh sistem termasuk konfigurasi',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrator dengan akses penuh ke seluruh sistem',
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Manager yang dapat melihat dan mengelola data karyawan',
            ],
            [
                'name' => 'supervisor',
                'display_name' => 'Supervisor',
                'description' => 'Supervisor yang dapat melihat data tim',
            ],
            [
                'name' => 'hr',
                'display_name' => 'Human Resource',
                'description' => 'HR yang mengelola kepegawaian dan absensi',
            ],
            [
                'name' => 'employee',
                'display_name' => 'Employee',
                'description' => 'Karyawan dengan akses terbatas hanya untuk absensi pribadi',
            ],
            [
                'name' => 'intern',
                'display_name' => 'Intern / Magang',
                'description' => 'Karyawan magang dengan akses sangat terbatas',
            ],
            [
                'name' => 'contractor',
                'display_name' => 'Contractor',
                'description' => 'Kontraktor atau freelancer dengan akses terbatas',
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                [
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description']
                ]
            );
            
            // Update existing roles that might not have display_name or description
            if (!$role->display_name || !$role->description) {
                $role->update([
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description']
                ]);
            }
        }

        $this->command->info('Roles created successfully!');
        $this->command->info('');
        $this->command->info('Available roles:');
        foreach ($roles as $role) {
            $this->command->info("- {$role['name']}: {$role['display_name']}");
        }
    }
}
