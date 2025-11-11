<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Pagi', 'start_time' => '08:00', 'end_time' => '16:00', 'description' => 'Shift kerja pagi reguler'],
            ['name' => 'Siang', 'start_time' => '12:00', 'end_time' => '20:00', 'description' => 'Shift kerja siang'],
            ['name' => 'Malam', 'start_time' => '20:00', 'end_time' => '04:00', 'description' => 'Shift kerja malam'],
        ];
        foreach ($data as $row) {
            Shift::firstOrCreate(['name' => $row['name']], $row);
        }
    }
}
