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
                'description' => 'Administrator dengan akses penuh ke seluruh sistem',
            ],
            [
                'name' => 'manager',
                'description' => 'Manager yang dapat melihat dan mengelola data karyawan',
            ],
            [
                'name' => 'employee',
                'description' => 'Karyawan dengan akses terbatas hanya untuk absensi pribadi',
            ],
            [
                'name' => 'supervisor',
                'description' => 'Supervisor yang dapat melihat data tim',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}
