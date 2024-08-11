<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PoinTemp;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TanggalAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SekretarisController extends Controller
{
    /**
     * Sekretaris Halaman Absensi
     */
    public function absensi(): View
    {
        $auth = Auth::user();
        $siswa = Siswa::where('id_login', $auth->uuid)->first();
        $id_kelas = $siswa->id_kelas;
        $guru = Guru::with('walikelas')->whereRelation('walikelas', 'id_kelas', '=', $id_kelas)->first();

        return View('walikelas.absensi.create', compact('guru'));
    }
    /**
     * Sekretaris Halaman Poin
     */
    public function poin(): View
    {
        $auth = Auth::user();
        $siswa = Siswa::where('id_login', $auth->uuid)->first();
        $id_kelas = $siswa->id_kelas;
        $guru = Guru::with('walikelas')->whereRelation('walikelas', 'id_kelas', '=', $id_kelas)->first();
        $guru_all = Guru::get();
        $siswa_all_name = $siswa->pluck('nama', 'uuid')->toArray();
        $guru_all_name = $guru_all->pluck('nama', 'uuid')->toArray();
        $all_name = array_merge($siswa_all_name, $guru_all_name);
        $siswa_array = $siswa->pluck('uuid')->toArray();
        $poin_temp = PoinTemp::with('aturan', 'siswa')->whereIn('id_siswa', $siswa_array)->orderBy('created_at', 'DESC')->get();
        return view('walikelas.poin.temp.index', compact('poin_temp', 'all_name'));
    }
    /**
     * Sekretaris - Halaman Tambah Poin Temp
     */
    public function poinCreate()
    {
        $auth = Auth::user();
        $siswa_login = Siswa::where('id_login', $auth->uuid)->first();
        $id_kelas = $siswa_login->id_kelas;
        $guru = Guru::with('walikelas')->whereRelation('walikelas', 'id_kelas', '=', $id_kelas)->first();
        $siswa = Siswa::with('kelas')->where('id_kelas', $guru->walikelas->id_kelas)->orderBy('nis', 'ASC')->get();

        return view('walikelas.poin.temp.create', compact('siswa'));
    }
}
