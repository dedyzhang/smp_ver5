<?php

namespace App\Http\Controllers;

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
    function index() : View {
        $jadwalVer = JadwalVer::orderBy('created_at','desc')->get();
        return View('jadwal.index',compact('jadwalVer'));
    }
    /**
     * Create - Menampilkan Halaman Tambah Jadwal
     */
    function create() : View {

        $jadwalVer = JadwalVer::orderBy('created_at','desc')->first();
        if($jadwalVer->count() === 0) {
            $versiTerbaru = 1;
        } else {
            // dd($jadwalVer);
            $versiTerbaru = $jadwalVer->versi + 1;
        }
        return View('jadwal.create',compact('versiTerbaru'));
    }
    /**
     * Store - Eksekusi Penambahan Jadwal
     */
    function store(Request $request) : RedirectResponse {
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
    function edit(Request $request) {
        if($request->purpose == "aktif") {
            $updateStandby = JadwalVer::where('uuid','!=',$request->uuid)->update([
                'status' => "standby"
            ]);
            $activeStandby = JadwalVer::where('uuid','=',$request->uuid)->update([
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
    function show(String $uuid) : View {
        $versi = JadwalVer::findOrFail($uuid);
        $kelas = Kelas::orderBy('tingkat','ASC')->orderBy('kelas','ASC')->get();
        $waktu = JadwalWaktu::where('id_jadwal',$uuid)->orderBy('waktu_mulai','ASC')->get();
        $hari = JadwalHari::orderBy('no_hari','ASC')->get();
        $jadwal = Jadwal::where('id_jadwal',$uuid)->get();
        $pelajaran = Pelajaran::get()->sortBy('urutan');

        $array_jadwal = array();

        foreach($jadwal as $item) {
            $array_jadwal[$item->id_hari.".".$item->id_waktu.".".$item->id_kelas] = array(
                "uuid" => $item->uuid,
                "id_hari"=> $item->id_hari,
                "id_waktu" => $item->id_waktu,
                "id_kelas" => $item->id_kelas,
                "id_pelajaran" => $item->id_pelajaran,
                "id_guru" => $item->id_guru,
                "id_ngajar" => $item->id_ngajar,
                "jenis" => $item->jenis,
                "spesial" => $item->spesial
            );
        }
        return View('jadwal.show',compact('versi','array_jadwal','kelas','waktu','hari','pelajaran'));
    }
    /**
     * Generate - Generate Jadwal
     */
    function generate(String $uuid) {
        $id_jadwal = $uuid;
        $kelas = Kelas::orderBy('tingkat','ASC')->orderBy('kelas','ASC')->get();
        $waktu = JadwalWaktu::where('id_jadwal',$id_jadwal)->orderBy('waktu_mulai','ASC')->get();
        $hari = JadwalHari::orderBy('no_hari','ASC')->get();

        $array_insert = array();
        foreach($hari as $hari) {
            $id_hari = $hari->uuid;
            foreach($waktu as $wkt) {
                $id_waktu = $wkt->uuid;
                foreach($kelas as $kls) {
                    $id_kelas = $kls->uuid;
                    array_push($array_insert,array(
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
        $jadwalInsert = Jadwal::upsert($array_insert,['uuid']);

    }
    /**
     * Waktu - Menampilkan Waktu didalam Jadwal
     */
    function waktuIndex(String $uuid) : View {
        $versi = JadwalVer::findOrFail($uuid);
        $waktu = JadwalWaktu::where('id_jadwal',$uuid)->orderBy('waktu_mulai','ASC')->get();
        return View('jadwal.waktu.index',compact('versi','waktu'));
    }
    /**
     * Waktu - Menampilkan halaman tambah waktu didalam Jadwal
     */
    function waktuCreate(String $uuid) : View {
        $versi = JadwalVer::findOrFail($uuid);
        return View('jadwal.waktu.create',compact('versi'));
    }
    /**
     * Waktu - Store Data Waktu ke dalam Database
     */
    function waktuStore(Request $request,String $uuid) : RedirectResponse {
        $request->validate([
            'waktu_mulai' => 'required',
            'waktu_akhir' => 'required'
        ]);
        $jadwalWaktu = JadwalWaktu::where([['waktu_mulai','=',$request->waktu_mulai],['id_jadwal','=',$uuid]])->first();

        if($jadwalWaktu === null) {
            JadwalWaktu::create([
                'id_jadwal' => $uuid,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_akhir' => $request->waktu_akhir
            ]);
            return redirect()->route('jadwal.waktu.create',$uuid)->with(["success" => "Sukses Menambahkan Waktu"]);
        } else {
            return back()->with(['error' => "Sudah Ada waktu dengan waktu mulai yang sama"]);
        }
    }
    /**
     * Waktu - Menampilkan halaman edit waktu didalam Jadwal
     */
    function waktuEdit(String $uuid,String $waktuUUID) : View {
        $waktuJadwal = JadwalWaktu::findOrFail($waktuUUID);
        $uuid = $uuid;
        return View('jadwal.waktu.edit',compact('waktuJadwal','uuid'));
    }
    /**
     * Waktu - Edit Waktu
     */
    function waktuUpdate(Request $request,String $uuid, String $waktuUUID) : RedirectResponse{
        $request->validate([
            'waktu_mulai' => 'required',
            'waktu_akhir' => 'required'
        ]);
        $waktu = JadwalWaktu::findOrFail($waktuUUID);

        $waktu->update([
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_akhir' => $request->waktu_akhir
        ]);
        return redirect()->route('jadwal.waktu.edit',['uuid' => $uuid,'waktuUUID' => $waktu->uuid])->with(["success" => "success Mengedit Waktu"]);
    }
    /**
     * Waktu - Hapus Waktu
     */
    function waktuDelete(String $uuid, String $waktuUUID) {
        JadwalWaktu::findOrFail($waktuUUID)->delete();

        return response()->json([
            "success" => true,
            "message" => "Berhasil detele"
        ]);
    }
}
