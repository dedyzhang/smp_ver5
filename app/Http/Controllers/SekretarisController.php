<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TanggalAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SekretarisController extends Controller
{
    /**
     * Sekretaris Halaman Absensi
     */
    public function absensi()
    {
        $auth = Auth::user();
        $siswa = Siswa::where('id_login', $auth->uuid)->first();
        $id_kelas = $siswa->id_kelas;
        $guru = Guru::with('walikelas')->whereRelation('walikelas', 'id_kelas', '=', $id_kelas)->first();

        return View('walikelas.absensi.create', compact('guru'));
    }
    public function poin()
    {
        // $auth = Auth::user();
        // $siswa = Siswa::where('id_login', $auth->uuid)->first();
        // $kelas = Kelas::with('siswa')->findOrFail($siswa->id_kelas);
        // return View('poin.sekretaris.index', compact('kelas'));
    }
}
