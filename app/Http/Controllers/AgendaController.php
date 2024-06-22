<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JadwalHari;
use App\Models\JadwalVer;
use App\Models\Semester;
use App\Models\TanggalAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AgendaController extends Controller
{
    /**
     * Create = Buat agenda baru guru
     */
    public function create() : View {
        return view('agenda.create');
    }
    /**
     * cektanggal - untuk mengecek tanggal
     */
    public function cektanggal(Request $request) {
        $tanggal = $request->tanggal;
        $semester = Semester::first();
        $sem = $semester->semester;
        $cekTanggal = TanggalAbsensi::where([
            ['tanggal','=',$tanggal],
            ['agenda','=',1],
            ['semester','=',$sem]
        ])->get();
        $hariKe = date('N',strtotime($tanggal));
        $hari = JadwalHari::where('no_hari',$hariKe)->first();

        if($cekTanggal->count() >= 1) {
            $jadwalVer = JadwalVer::where('status','active')->first();
            // $account = Auth::user()->uuid;
            $guru = Guru::where('id_login',Auth::user()->uuid)->first();
            $jadwal = Jadwal::with('pelajaran','kelas','waktu')->where([
                ['id_jadwal','=',$jadwalVer->uuid],
                ['id_guru','=',$guru->uuid],
                ['id_hari','=',$hari->uuid]
            ])->groupBy(['id_kelas','id_pelajaran'])->get();
            return response()->json(["success"=> true, "jadwal" => $jadwal]);
        } else {
            return response()->json(["success"=> false, "message" => "tidak ada agenda pada tanggal ini"]);
        }
    }
    /**
     * cekjadwal - untuk mengecek jadwal
     */
    public function cekjadwal(Request $request) {
        $idJadwal = $request->idJadwal;
        $Jadwal = Jadwal::with('siswa')->findOrFail($idJadwal);

        if($Jadwal->siswa->count() > 0) {
            return response()->json(["success"=> true,"siswa" => $Jadwal->siswa]);
        } else {
            return response()->json(["success"=> false,"message" => "tidak ada siswa di kelas ini"]);
        }
    }
}
