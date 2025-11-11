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
                'name' => 'employee',
                'display_name' => 'Employee',
                'description' => 'Karyawan dengan akses terbatas hanya untuk absensi pribadi',
            ],
            [
                'name' => 'supervisor',
                'display_name' => 'Supervisor',
                'description' => 'Supervisor yang dapat melihat data tim',
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
            
            // Update existing roles that might not have display_name
            if (!$role->display_name) {
                $role->update(['display_name' => $roleData['display_name']]);
            }
        }
    }
}
