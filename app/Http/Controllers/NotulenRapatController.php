<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\NotulenRapat;
use App\Models\Semester;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotulenRapatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $account = Guru::with('users')->where('id_login', Auth::user()->uuid)->first();
        $notulen = NotulenRapat::orderBy('tanggal_rapat', 'desc')->get();
        return view('notulen.index', compact('notulen','user','account'));
    }

    public function create()
    {
        return view('notulen.create');
    }
    public function store(Request $request)
    {
        NotulenRapat::create([
            'tanggal_rapat' => date('Y-m-d'),
            'pokok_permasalahan' => $request->pokok_pembahasan,
            'hasil_rapat' => $request->hasil_rapat,
        ]);

        return response()->json(['success' => true]);
    }
    public function show(String $uuid)
    {
        $notulen = NotulenRapat::findOrFail($uuid);
        $notulen_guru = $notulen->guru_hadir ? unserialize($notulen->guru_hadir) : [];
        $guru = Guru::whereIn('uuid', $notulen_guru)->orderBy('nama', 'asc')->get();
        return response()->json(['success' => true, 'data' => $notulen, 'guru' => $guru]);
    }
    public function edit(String $uuid)
    {
        $notulen = NotulenRapat::where('uuid', $uuid)->first();
        return view('notulen.edit', compact('notulen'));
    }
    public function update(Request $request, String $uuid)
    {
        $notulen = NotulenRapat::findOrFail($uuid);
        $notulen->update([
            'pokok_permasalahan' => $request->pokok_pembahasan,
            'hasil_rapat' => $request->hasil_rapat,
        ]);
        return response()->json(['success' => true, 'data' => $request->pokok_pembahasan]);
    }
    public function absensi(String $uuid)
    {
        $notulen = NotulenRapat::findOrFail($uuid);
        $guru = Guru::orderBy('nama', 'asc')->get();
        $notulen_guru = $notulen->guru_hadir ? unserialize($notulen->guru_hadir) : [];
        return view('notulen.absensi', compact('notulen', 'guru', 'notulen_guru'));
    }
    public function storeAbsensi(Request $request)
    {
        $notulen = NotulenRapat::findOrFail($request->id_notulen);
        $notulen->update([
            'guru_hadir' => serialize($request->array_guru)
        ]);
        return response()->json(['success' => true]);
    }
    public function dokumentasi(String $uuid) {
        $notulen = NotulenRapat::findOrFail($uuid);
        $gambar = array();
        if($notulen->dokumentasi != null) {
            $gambar = explode(",", $notulen->dokumentasi);
        }
        return view('notulen.dokumentasi', compact('notulen','gambar'));
    }
    public function storeDokumentasi(Request $request, String $uuid) {
        $notulen = NotulenRapat::findOrFail($uuid);
        $path = 'notulen/' . date('d M Y', strtotime($notulen->tanggal_rapat));
        $file_path = storage_path('app/public/' . $path);
        
        if($notulen->dokumentasi != null) {
            $namafile = $notulen->dokumentasi.",";
        } else {
            $namafile = "";
        }

        foreach ($request->file('files') as $file) {
            $ext = $file->extension();
            $filename = $file->hashName();
            $file->storeAs('public/'.$path, $filename);
            $namafile .= $filename . ",";
        }
        chmod($file_path, 0755);
        $namafile = substr($namafile, 0, -1);

        $notulen->update([
            'dokumentasi' => $namafile
        ]);
        return response()->json(['status' => 'success']);
    }
    public function dokumentasiDestroy(Request $request, String $uuid) {
        $notulen = NotulenRapat::findOrFail($uuid);
        $path = 'notulen/' . date('d M Y', strtotime($notulen->tanggal_rapat));
        $file_path = storage_path('app/public/' . $path);

        if($notulen->dokumentasi != null) {
            $gambar = explode(",", $notulen->dokumentasi);
            $gambar_baru = "";
            foreach ($gambar as $item) {
                $file_didalam_folder = $file_path . '/' . $item;
                $file_to_delete = $file_path.'/'.$request->gambar;
                if ($file_to_delete == $file_didalam_folder) {
                    unlink($file_to_delete);
                } else {
                    $gambar_baru .= $item . ",";
                }
            }
            $gambar_baru = substr($gambar_baru, 0, -1);
            $notulen->update([
                'dokumentasi' => $gambar_baru
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    public function printNotulen(String $uuid) {
        $notulen = NotulenRapat::findOrFail($uuid);
        $notulen_guru = $notulen->guru_hadir ? unserialize($notulen->guru_hadir) : [];
        $guru = Guru::whereIn('uuid', $notulen_guru)->orderBy('nama', 'asc')->get();
        $setting = Setting::all();
        $semester = Semester::first();
        $kepalaSekolah = $setting->first(function ($elem) {
            return $elem->jenis == 'kepala_sekolah';
        });

        if ($kepalaSekolah) {
            $kepala_sekolah = Guru::findOrFail($kepalaSekolah->nilai);
        } else {
            $kepala_sekolah = "";
        }
        $path = 'notulen/' . date('d M Y', strtotime($notulen->tanggal_rapat));
        $dokumentasi = explode(",", $notulen->dokumentasi);
        return view('notulen.cetak', compact('notulen', 'guru','setting','semester','kepala_sekolah','path','dokumentasi'));
    }
}
