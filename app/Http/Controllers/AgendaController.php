<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
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

        if($cekTanggal->count() >= 1) {
            $jadwalVer = JadwalVer::where('status','active')->first();
            // $account = Auth::user()->uuid;
            $guru = Guru::where('id_login',Auth::user()->uuid)->first();
            $jadwal = Jadwal::with('pelajaran','kelas','waktu')->where([
                ['id_jadwal','=',$jadwalVer->uuid],
                ['id_guru','=',$guru->uuid]
            ])->groupBy(['id_kelas','id_pelajaran'])->get();
            return response()->json(["success"=> true, "jadwal" => $jadwal]);
        } else {
            return response()->json(["success"=> false, "message" => "tidak ada agenda pada tanggal ini"]);
        }
    }
}
