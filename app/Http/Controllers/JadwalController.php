<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JadwalHari;
use App\Models\JadwalVer;
use App\Models\JadwalWaktu;
use App\Models\Kelas;
use App\Models\Ngajar;
use App\Models\Pelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalController extends Controller
{
    /**
     * Index - Menampilkan Halaman Jadwal
     */
    function index(): View
    {
        $jadwalVer = JadwalVer::orderBy('created_at', 'desc')->get();
        $jadwalSekarang = JadwalVer::where('status', 'active')->first();
        $array_jadwal = array();
        if ($jadwalSekarang !== null) {
            $kelas = Kelas::orderBy('tingkat', 'ASC')->orderBy('kelas', 'ASC')->get();
            $waktu = JadwalWaktu::where('id_jadwal', $jadwalSekarang->uuid)->orderBy('waktu_mulai', 'ASC')->get();
            $hari = JadwalHari::orderBy('no_hari', 'ASC')->get();
            $jadwal = Jadwal::with('kelas', 'pelajaran', 'guru', 'ngajar')->where('id_jadwal', $jadwalSekarang->uuid)->get();
            $pelajaran = Pelajaran::get()->sortBy('urutan');
            $guruAll = Guru::get()->sortBy('nama');

            foreach ($jadwal as $item) {
                if (isset($item->pelajaran)) {
                    $pljran = $item->pelajaran->pelajaran;
                    $pljran_singkat = $item->pelajaran->pelajaran_singkat;
                } else {
                    $pljran = "";
                    $pljran_singkat = "";
                }
                if (isset($item->guru)) {
                    $guru = $item->guru->nama;
                } else {
                    $guru = "";
                }
                $array_jadwal[$item->id_hari . "." . $item->id_waktu . "." . $item->id_kelas] = array(
                    "uuid" => $item->uuid,
                    "id_hari" => $item->id_hari,
                    "id_waktu" => $item->id_waktu,
                    "id_kelas" => $item->id_kelas,
                    "kelas" => $item->kelas->tingkat . $item->kelas->kelas,
                    "id_pelajaran" => $item->id_pelajaran,
                    "pelajaran" => $pljran,
                    "pelajaran_singkat" => $pljran_singkat,
                    "id_guru" => $item->id_guru,
                    "guru" => $guru,
                    "id_ngajar" => $item->id_ngajar,
                    "jenis" => $item->jenis,
                    "spesial" => $item->spesial
                );
            }
            return View('jadwal.index', compact('jadwalVer', 'array_jadwal', 'kelas', 'waktu', 'hari', 'jadwalSekarang', 'guruAll'));
        } else {
            return View('jadwal.index', compact('jadwalVer', 'array_jadwal'));
        }
    }
    /**
     * Create - Menampilkan Halaman Tambah Jadwal
     */
    function create(): View
    {

        $jadwalVer = JadwalVer::orderBy('created_at', 'desc')->first();
        if ($jadwalVer === null) {
            $versiTerbaru = 1;
        } else {
            // dd($jadwalVer);
            $versiTerbaru = $jadwalVer->versi + 1;
        }
        return View('jadwal.create', compact('versiTerbaru'));
    }
    /**
     * Store - Eksekusi Penambahan Jadwal
     */
    function store(Request $request): RedirectResponse
    {
        $request->validate([
            'deskripsi' => 'required'
        ]);

        $insert = JadwalVer::create([
            'versi' => $request->versi,
            'deskripsi' => $request->deskripsi,
            'status' => "standby"
        ]);
        return redirect()->route('jadwal.index')->with(['success' => 'Data Berhasil Disimpan']);
    }
    /**
     * Edit - Set Aktif Jadwal
     */
    function edit(Request $request)
    {
        if ($request->purpose == "aktif") {
            $updateStandby = JadwalVer::where('uuid', '!=', $request->uuid)->update([
                'status' => "standby"
            ]);
            $activeStandby = JadwalVer::where('uuid', '=', $request->uuid)->update([
                'status' => "active"
            ]);
        } else {
            $standby = JadwalVer::findOrFail($request->uuid)->update([
                'status' => "standby"
            ]);
        }
    }
    /**
     * Show - Menampilkan Jadwal
     */
    function show(String $uuid): View
    {
        $versi = JadwalVer::findOrFail($uuid);
        $kelas = Kelas::orderBy('tingkat', 'ASC')->orderBy('kelas', 'ASC')->get();
        $waktu = JadwalWaktu::where('id_jadwal', $uuid)->orderBy('waktu_mulai', 'ASC')->get();
        $hari = JadwalHari::orderBy('no_hari', 'ASC')->get();
        $jadwal = Jadwal::with('kelas', 'pelajaran', 'guru', 'ngajar')->where('id_jadwal', $uuid)->get();
        $pelajaran = Pelajaran::get()->sortBy('urutan');

        $array_jadwal = array();

        foreach ($jadwal as $item) {
            if (isset($item->pelajaran)) {
                $pljran = $item->pelajaran->pelajaran;
                $pljran_singkat = $item->pelajaran->pelajaran_singkat;
            } else {
                $pljran = "";
                $pljran_singkat = "";
            }
            if (isset($item->guru)) {
                $guru = $item->guru->nama;
            } else {
                $guru = "";
            }
            $array_jadwal[$item->id_hari . "." . $item->id_waktu . "." . $item->id_kelas] = array(
                "uuid" => $item->uuid,
                "id_hari" => $item->id_hari,
                "id_waktu" => $item->id_waktu,
                "id_kelas" => $item->id_kelas,
                "kelas" => $item->kelas->tingkat . $item->kelas->kelas,
                "id_pelajaran" => $item->id_pelajaran,
                "pelajaran" => $pljran,
                "pelajaran_singkat" => $pljran_singkat,
                "id_guru" => $item->id_guru,
                "guru" => $guru,
                "id_ngajar" => $item->id_ngajar,
                "jenis" => $item->jenis,
                "spesial" => $item->spesial
            );
        }
        return View('jadwal.show', compact('versi', 'array_jadwal', 'kelas', 'waktu', 'hari', 'pelajaran'));
    }
    /**
     * Update - Update data didalam jadwal
     */
    function update(Request $request, String $uuid)
    {
        $versiID = $uuid;
        $jadwalID = $request->uuid;
        $pelajaranID = $request->pelajaran;

        $jadwal = Jadwal::with('kelas', 'pelajaran', 'guru', 'waktu', 'hari', 'ngajar')->where([
            ['id_jadwal', '=', $versiID],
            ['uuid', '=', $jadwalID]
        ])->first();
        $pelajaran = Pelajaran::where('pelajaran_singkat', $pelajaranID)->first();

        if ($pelajaranID == "") {
            if ($jadwal->jenis != "spesial") {
                $jadwal->update([
                    "id_pelajaran" => "",
                    "id_guru" => "",
                    "id_ngajar" => "",
                    "jenis" => ""
                ]);
                return response()->json(["success" => true]);
            } else {
                $oldValue = $request->old;
                $jadwal2 = Jadwal::with('kelas', 'pelajaran', 'guru', 'waktu', 'hari', 'ngajar')->where([
                    ['id_jadwal', '=', $versiID],
                    ['id_hari', '=', $request->hari],
                    ['spesial', '=', $oldValue]
                ]);
                $jadwal2->update([
                    'jenis' => "",
                    "spesial" => ""
                ]);
                return response()->json(["success" => true, "reload" => true]);
            }
        } else if ($pelajaran === null) {
            if (str_starts_with($pelajaranID, 'S.')) {
                $split = explode('.', $pelajaranID);
                $spesialString = end($split);
                $jadwal->update([
                    "id_pelajaran" => "",
                    "id_guru" => "",
                    "id_ngajar" => "",
                    "jenis" => "spesial",
                    "spesial" => $spesialString
                ]);
                return response()->json(["success" => true, "spesial" => $spesialString]);
            } else {
                if ($jadwal->jenis == "spesial") {
                    $oldValue = $request->old;
                    $jadwal2 = Jadwal::with('kelas', 'pelajaran', 'guru', 'waktu', 'hari', 'ngajar')->where([
                        ['id_jadwal', '=', $versiID],
                        ['id_hari', '=', $request->hari],
                        ['spesial', '=', $oldValue]
                    ]);
                    $jadwal2->update([
                        "spesial" => $pelajaranID
                    ]);
                    return response()->json(["success" => true, "spesial" => $pelajaranID]);
                } else {
                    return response()->json(["success" => false, "message" => "Kode Pelajaran tidak ditemukan"]);
                }
            }
        } else {
            //cek apakah ada di data ngajar
            $ngajar = Ngajar::where([
                ['id_kelas', '=', $jadwal->kelas->uuid],
                ['id_pelajaran', '=', $pelajaran->uuid]
            ])->first();
            if ($ngajar === null) {
                return response()->json(["success" => false, "message" => "tidak ada Jam Mengajar untuk mata pelajaran ini"]);
            } else {
                $bentrok = Jadwal::with('kelas', 'pelajaran', 'guru', 'waktu', 'hari', 'ngajar')->where([
                    ['id_jadwal', '=', $versiID],
                    ['id_hari', "=", $jadwal->id_hari],
                    ['id_waktu', '=', $jadwal->id_waktu],
                    ['id_guru', '=', $ngajar->id_guru],
                ])->get();
                $jumlahBentrok = $bentrok->count();
                foreach ($bentrok as $item) {
                    if ($item->id_ngajar != $ngajar->uuid) {
                        $jumlahBentrok += 1;
                    }
                }
                if ($jumlahBentrok > 1) {
                    return response()->json(["success" => false, "message" => "Sudah ada jam mengajar di jam yang sama"]);
                } else {
                    $jadwal->update([
                        "id_pelajaran" => $pelajaran->uuid,
                        "id_guru" => $ngajar->id_guru,
                        "id_ngajar" => $ngajar->uuid,
                        "jenis" => "mapel"
                    ]);
                    $jadwal = Jadwal::with('kelas', 'pelajaran', 'guru', 'waktu', 'hari', 'ngajar')->where([
                        ['id_jadwal', '=', $versiID],
                        ['uuid', '=', $jadwalID]
                    ])->first();
                    return response()->json(["success" => true, "guru" => $jadwal->guru->nama]);
                }
            }
        }
    }
    /**
     * Generate - Generate Jadwal
     */
    function generate(String $uuid)
    {
        $id_jadwal = $uuid;
        $kelas = Kelas::orderBy('tingkat', 'ASC')->orderBy('kelas', 'ASC')->get();
        $waktu = JadwalWaktu::where('id_jadwal', $id_jadwal)->orderBy('waktu_mulai', 'ASC')->get();
        $hari = JadwalHari::orderBy('no_hari', 'ASC')->get();

        $jadwal = Jadwal::where('id_jadwal', $id_jadwal);

        if ($jadwal->count() > 0) {
            $jadwal->delete();
        }

        $array_insert = array();
        foreach ($hari as $hari) {
            $id_hari = $hari->uuid;
            foreach ($waktu as $wkt) {
                $id_waktu = $wkt->uuid;
                foreach ($kelas as $kls) {
                    $id_kelas = $kls->uuid;
                    array_push($array_insert, array(
                        "id_jadwal" => $id_jadwal,
                        "id_hari" => $id_hari,
                        "id_waktu" => $id_waktu,
                        "id_kelas" => $id_kelas,
                        'id_ngajar' => "",
                        "id_pelajaran" => "",
                        "id_guru" => "",
                        "jenis" => "",
                        "spesial" => ""
                    ));
                }
            }
        }
        $jadwalInsert = Jadwal::upsert($array_insert, ['uuid']);
    }
    /**
     * Waktu - Menampilkan Waktu didalam Jadwal
     */
    function waktuIndex(String $uuid): View
    {
        $versi = JadwalVer::findOrFail($uuid);
        $waktu = JadwalWaktu::where('id_jadwal', $uuid)->orderBy('waktu_mulai', 'ASC')->get();
        return View('jadwal.waktu.index', compact('versi', 'waktu'));
    }
    /**
     * Waktu - Menampilkan halaman tambah waktu didalam Jadwal
     */
    function waktuCreate(String $uuid): View
    {
        $versi = JadwalVer::findOrFail($uuid);
        return View('jadwal.waktu.create', compact('versi'));
    }
    /**
     * Waktu - Store Data Waktu ke dalam Database
     */
    function waktuStore(Request $request, String $uuid): RedirectResponse
    {
        $request->validate([
            'waktu_mulai' => 'required',
            'waktu_akhir' => 'required'
        ]);
        $jadwalWaktu = JadwalWaktu::where([['waktu_mulai', '=', $request->waktu_mulai], ['id_jadwal', '=', $uuid]])->first();

        if ($jadwalWaktu === null) {
            JadwalWaktu::create([
                'id_jadwal' => $uuid,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_akhir' => $request->waktu_akhir
            ]);
            return redirect()->route('jadwal.waktu.create', $uuid)->with(["success" => "Sukses Menambahkan Waktu"]);
        } else {
            return back()->with(['error' => "Sudah Ada waktu dengan waktu mulai yang sama"]);
        }
    }
    /**
     * Waktu - Menampilkan halaman edit waktu didalam Jadwal
     */
    function waktuEdit(String $uuid, String $waktuUUID): View
    {
        $waktuJadwal = JadwalWaktu::findOrFail($waktuUUID);
        $uuid = $uuid;
        return View('jadwal.waktu.edit', compact('waktuJadwal', 'uuid'));
    }
    /**
     * Waktu - Edit Waktu
     */
    function waktuUpdate(Request $request, String $uuid, String $waktuUUID): RedirectResponse
    {
        $request->validate([
            'waktu_mulai' => 'required',
            'waktu_akhir' => 'required'
        ]);
        $waktu = JadwalWaktu::findOrFail($waktuUUID);

        $waktu->update([
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_akhir' => $request->waktu_akhir
        ]);
        return redirect()->route('jadwal.waktu.edit', ['uuid' => $uuid, 'waktuUUID' => $waktu->uuid])->with(["success" => "success Mengedit Waktu"]);
    }
    /**
     * Waktu - Hapus Waktu
     */
    function waktuDelete(String $uuid, String $waktuUUID)
    {
        JadwalWaktu::findOrFail($waktuUUID)->delete();

        return response()->json([
            "success" => true,
            "message" => "Berhasil detele"
        ]);
    }
}
