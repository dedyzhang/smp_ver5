<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\JadwalHari;
use App\Models\JadwalVer;
use App\Models\JadwalWaktu;
use App\Models\Kelas;
use App\Models\PengRuang;
use App\Models\Ruang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PengRuangController extends Controller
{
    /**
     * Index - Halaman Pertama pengajuan penggunaan ruang
     */
    public function index(): View
    {
        $tanggal = date('Y-m-d');
        $user = Auth::user();
        $ruang = Ruang::where('umum', 'iya')->orderBy('nama')->get();
        $pengRuangAll = PengRuang::with('pelajaran', 'kelas', 'guru', 'ruang', 'waktu')->where('tanggal', $tanggal)->get();
        $ruang_array = array();
        foreach ($pengRuangAll as $item) {
            $ruang_array[$item->id_ruang . "." . $item->id_waktu] = array(
                "guru" => $item->guru->nama,
                "kelas" => $item->kelas->tingkat . $item->kelas->kelas,
                "pelajaran" => $item->pelajaran->pelajaran_singkat,
                "ruang" => $item->ruang
            );
        }
        if ($user->access == "admin" || $user->access == 'sapras') {
            $pengRuang = PengRuang::with('pelajaran', 'kelas', 'guru', 'ruang', 'waktu')->where('tanggal', $tanggal)->get();
        } else {
            $guruID = $user->guru->uuid;
            $pengRuang = PengRuang::with('pelajaran', 'kelas', 'guru', 'ruang', 'waktu')->where([['tanggal', '=', $tanggal], ['id_guru', '=', $guruID]])->get();
        }
        $jadwalver = JadwalVer::where('status', 'active')->first();
        $jadwalWaktu = JadwalWaktu::where('id_jadwal', $jadwalver->uuid)->orderBy('waktu_mulai')->get();
        return view('sapras.penggunaan.index', compact('ruang', 'jadwalWaktu', 'ruang_array', 'pengRuang'));
    }
    /**
     * Create - Masuk ke halaman Create Pengajuan
     */
    public function create(): View
    {
        $user = Auth::user();
        $ruang = Ruang::where('umum', 'iya')->orderBy('nama')->get();
        $kelas = Kelas::orderBy('tingkat', 'ASC')->orderBy('kelas', 'ASC')->get();
        $user_array = array(
            "access" => $user->access,
            "uuid" => $user->guru->uuid
        );
        $date = date('m/d/Y');
        $penggunaan = PengRuang::where('tanggal', $date)->get();

        return view('sapras.penggunaan.create', compact('ruang', 'kelas', 'user_array'));
    }
    /**
     * GetJAdwal - Dapatkan Jadwal di hari ini berdasarkan kelas
     */
    public function getJadwal(String $uuid)
    {
        $kelas = Kelas::findOrFail($uuid);
        $jadwalver = JadwalVer::where('status', 'active')->first();
        $tanggal = date('Y-m-d');
        $hariKe = date('N', strtotime($tanggal));
        $hari = JadwalHari::where('no_hari', $hariKe)->first();


        if ($jadwalver !== null) {
            $jadwal = Jadwal::with('pelajaran', 'guru', 'waktu')->where([
                ['id_jadwal', '=', $jadwalver->uuid],
                ['id_hari', '=', $hari->uuid],
                ['id_kelas', '=', $kelas->uuid]
            ])->get()->sortBy('waktu_mulai');
        } else {
            $jadwal = array();
        }
        return response()->json(['success' => true, 'jadwal' => $jadwal]);
    }
    /**
     * Store - Simpan pengajuan Ruangan
     */
    public function store(Request $request)
    {
        $tanggal = date('Y-m-d');
        $jadwalver = JadwalVer::where('status', 'active')->first();
        $pengRuang = PengRuang::where('tanggal', $tanggal)->get();
        $ruang_array = array();
        foreach ($pengRuang as $item) {
            if (!isset($ruang_array[$item->id_ruang])) {
                $ruang_array[$item->id_ruang] = array($item->id_waktu);
            } else {
                array_push($ruang_array[$item->id_ruang], $item->id_waktu);
            }
        }
        $jadwal = $request->jadwal;
        $input_array = array();
        $error = 0;
        foreach ($jadwal as $item) {
            if (empty($ruang_array[$request->idRuang])) {
                array_push($input_array, array(
                    "tanggal" => $tanggal,
                    "id_ruang" => $request->idRuang,
                    "id_kelas" => $request->idKelas,
                    "id_jadwal" => $jadwalver->uuid,
                    "id_guru" => $item['id_guru'],
                    "id_pelajaran" => $item['id_pelajaran'],
                    "id_waktu" => $item['waktu']
                ));
            } else {
                if (in_array($item['waktu'], $ruang_array[$request->idRuang])) {
                    $error++;
                } else {
                    array_push($input_array, array(
                        "tanggal" => $tanggal,
                        "id_ruang" => $request->idRuang,
                        "id_kelas" => $request->idKelas,
                        "id_jadwal" => $jadwalver->uuid,
                        "id_guru" => $item['id_guru'],
                        "id_pelajaran" => $item['id_pelajaran'],
                        "id_waktu" => $item['waktu']
                    ));
                }
            }
        }
        if ($error > 0) {
            return response()->json(['success' => false, 'message' => 'Ruangan di jam ini sudah dipakai']);
        } else {
            PengRuang::upsert($input_array, 'uuid');
            return response()->json(['success' => true]);
        }
    }
    /**
     * Delete - Hapus Pengajuan Ruangan
     */
    public function destroy(String $uuid, Request $request)
    {
        if ($request->deleteAll === "true") {
            $pengRuang = PengRuang::findOrFail($uuid);
            $allPengRuang = PengRuang::where([
                'tanggal' => $pengRuang->tanggal,
                'id_ruang' => $pengRuang->id_ruang,
                'id_guru' => $pengRuang->id_guru,
                'id_pelajaran' => $pengRuang->id_pelajaran,
                'id_kelas' => $pengRuang->id_kelas
            ]);

            $allPengRuang->delete();
        } else {
            $pengRuang = PengRuang::findOrFail($uuid)->delete();
        }
    }
}
