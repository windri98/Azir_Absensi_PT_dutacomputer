<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Human Resources',
                'code' => 'HR',
                'description' => 'Departemen Sumber Daya Manusia',
            ],
            [
                'name' => 'Information Technology',
                'code' => 'IT',
                'description' => 'Departemen Teknologi Informasi',
            ],
            [
                'name' => 'Finance',
                'code' => 'FIN',
                'description' => 'Departemen Keuangan',
            ],
            [
                'name' => 'Operations',
                'code' => 'OPS',
                'description' => 'Departemen Operasional',
            ],
            [
                'name' => 'Sales',
                'code' => 'SALES',
                'description' => 'Departemen Penjualan',
            ],
            [
                'name' => 'Marketing',
                'code' => 'MKT',
                'description' => 'Departemen Pemasaran',
            ],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(
                ['code' => $department['code']],
                $department
            );
        }
    }
}
