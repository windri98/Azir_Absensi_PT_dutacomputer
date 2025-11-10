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
        // Seed roles dan users with roles
        $this->call([
            RoleSeeder::class,
            ShiftSeeder::class,
            UserWithRoleSeeder::class,
        ]);
    }
}
