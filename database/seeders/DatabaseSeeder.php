<?php

namespace Database\Seeders;

use App\Models\Nis;
use App\Models\Semester;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Nis::create([
            'first_nis' => '04',
            'second_nis' => '23',
            'third_nis' => '0001',
        ]);

        Semester::create([
            'semester' => '1',
            'tp' => '2023/2024'
        ]);
    }
}
