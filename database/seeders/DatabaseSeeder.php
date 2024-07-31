<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\JadwalHari;
use App\Models\Nis;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
        JadwalHari::upsert([
            ['no_hari' => 1, 'nama_hari' => 'senin'],
            ['no_hari' => 2, 'nama_hari' => 'selasa'],
            ['no_hari' => 3, 'nama_hari' => 'rabu'],
            ['no_hari' => 4, 'nama_hari' => 'kamis'],
            ['no_hari' => 5, 'nama_hari' => 'jumat']
        ], ['uuid']);

        $rand = 'Guru.10090150289';
        $password = Hash::make($rand);

        $user = User::create([
            'username' => '10090150289',
            'password' => $password,
            'access' => 'admin',
            'token' => '1',
        ]);
        Guru::create([
            'id_login' => $user->uuid,
            'nama' => 'Dedy',
            'nik' => '10090150289',
            'jk' => 'l',
        ]);
    }
}
