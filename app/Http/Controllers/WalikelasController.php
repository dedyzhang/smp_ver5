<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\TanggalAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WalikelasController extends Controller
{
    /**
     * Absensi Siswa
     */
    public function absensi(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $dataSekolah = Semester::first();
        $semester = $dataSekolah->semester;
        if ($guru->walikelas == null) {
            $iswalikelas = false;
            $kelas = "";
            $jumlahAbsensi = "";
        } else {
            $iswalikelas = true;
            $kelas = Kelas::with('siswa')->findOrFail($guru->walikelas->id_kelas);
            $tanggalAbsensi = TanggalAbsensi::where([
                ['ada_siswa', '=', 1],
                ['semester', '=', $semester]
            ])->get();
            $jumlahAbsensi = $tanggalAbsensi->count();
        }
        return view('walikelas.absensi.index', compact('iswalikelas', 'kelas', 'jumlahAbsensi'));
    }
    /**
     * Tambah Absensi Siswa
     */
    public function absensiCreate(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $dataSekolah = Semester::first();
        $semester = $dataSekolah->semester;


        return View('walikelas.absensi.create', compact('guru'));
    }
    public function absensiGet(Request $request)
    {
        $tanggal = $request->tanggal;
        $absensiTanggal = TanggalAbsensi::where([['tanggal', '=', $tanggal], ['ada_siswa', '=', 1]])->first();
        if ($absensiTanggal !== null) {
            $kelas = Kelas::with('siswa')->findOrFail($request->kelas);
            $siswaID = array();
            foreach ($kelas->siswa as $siswa) {
                array_push($siswaID, $siswa->uuid);
            };
            $absensi = AbsensiSiswa::where('id_tanggal', $absensiTanggal->uuid)->whereIn('id_siswa', $siswaID)->get();
            return response()->json(['success' => true, 'siswa' => $kelas->siswa, 'absensi' => $absensi]);
        } else {
            return response()->json(['success' => false, 'message' => 'tidak ada pembelajaran di tanggal ini']);
        }
    }
}
