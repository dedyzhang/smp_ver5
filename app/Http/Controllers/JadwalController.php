<?php

namespace App\Http\Controllers;

use App\Models\JadwalVer;
use App\Models\JadwalWaktu;
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
        return View('jadwal.show',compact('versi'));
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
}
