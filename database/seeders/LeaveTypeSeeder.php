<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            [
                'name' => 'Cuti Tahunan',
                'code' => 'ANNUAL',
                'description' => 'Cuti tahunan yang diberikan kepada setiap karyawan',
                'default_quota' => 12,
                'requires_document' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Cuti Sakit',
                'code' => 'SICK',
                'description' => 'Cuti untuk keperluan kesehatan',
                'default_quota' => 12,
                'requires_document' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Cuti Khusus',
                'code' => 'SPECIAL',
                'description' => 'Cuti untuk keperluan khusus',
                'default_quota' => 3,
                'requires_document' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Cuti Haid',
                'code' => 'MENSTRUAL',
                'description' => 'Cuti untuk karyawan perempuan',
                'default_quota' => 12,
                'requires_document' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Cuti Melahirkan',
                'code' => 'MATERNITY',
                'description' => 'Cuti untuk karyawan yang melahirkan',
                'default_quota' => 90,
                'requires_document' => true,
                'is_active' => true,
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::firstOrCreate(
                ['code' => $leaveType['code']],
                $leaveType
            );
        }
    }
}
