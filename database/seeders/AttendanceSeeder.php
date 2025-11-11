<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            // Create attendance records for the last 10 days
            for ($i = 0; $i < 10; $i++) {
                $date = Carbon::now()->subDays($i);
                
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }
                
                $checkIn = $date->copy()->setHour(8)->setMinute(rand(0, 15));
                $checkOut = $date->copy()->setHour(17)->setMinute(rand(0, 30));
                
                // Determine status based on check-in time
                $status = 'present';
                if ($checkIn->minute > 10) {
                    $status = 'late';
                }
                
                // Random chance for other statuses
                $rand = rand(1, 100);
                if ($rand <= 5) {
                    $status = 'sick';
                    $checkIn = null;
                    $checkOut = null;
                } elseif ($rand <= 10) {
                    $status = 'leave';
                    $checkIn = null;
                    $checkOut = null;
                }
                
                // Calculate work hours
                $workHours = 0;
                if ($checkIn && $checkOut) {
                    $workHours = $checkOut->diffInHours($checkIn);
                }
                
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date->format('Y-m-d'),
                    'check_in' => $checkIn?->format('H:i:s'),
                    'check_out' => $checkOut?->format('H:i:s'),
                    'status' => $status,
                    'work_hours' => $workHours,
                    'check_in_location' => 'Kantor Pusat',
                    'check_out_location' => $checkOut ? 'Kantor Pusat' : null,
                    'notes' => $status === 'late' ? 'Traffic jam' : null,
                ]);
            }
        }
        
        $this->command->info('Attendance data created successfully!');
    }
}