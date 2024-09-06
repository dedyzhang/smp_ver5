<?php

namespace Database\Seeders;

use App\Models\TanggalAbsensi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdJadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TanggalAbsensi::query()->update([
            'id_jadwal' => '9c90a48d-b14c-442b-ae83-165f773f95a3',
        ]);
    }
}
