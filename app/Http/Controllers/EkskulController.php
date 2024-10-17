<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use App\Models\EkskulSiswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Pelajaran;
use App\Models\Semester;
use App\Models\Siswa;
use Mavinoo\Batch\BatchFacade as Batch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EkskulController extends Controller
{
    /**
     * Ekskul - Index
     */
    public function index(): View
    {
        $ekskul = Ekskul::all()->sortBy('urutan', SORT_NATURAL);
        return view('ekskul.index', compact('ekskul'));
    }
    /**
     * Ekskul - Masuk ke halaman create
     */
    public function create(): View
    {
        $guru = Guru::orderBy('nama', 'ASC')->get();
        $pelajaran = Pelajaran::all()->sortBy('urutan', SORT_NATURAL);
        $kelas = Kelas::all()->sortBy('kelas')->sortBy('tingkat');
        return view('ekskul.create', compact('guru', 'pelajaran', 'kelas'));
    }
    /**
     * Store - Halaman Proses Penambahan Ekskul
     */
    public function store(Request $request)
    {
        $request->validate([
            'ekskul' => 'required',
            'guru' => 'required'
        ]);
        $urutan = Ekskul::get();
        if ($urutan->count() > 0) {
            $urut = 1 + $urutan->count();
        } else {
            $urut = 1;
        };
        Ekskul::create([
            'ekskul' => $request->ekskul,
            'id_guru' => $request->guru,
            'id_pelajaran' => $request->pelajaran,
            'urutan' => $urut
        ]);
        return redirect()->route('ekskul.index')->with(['success' => 'Ekskul Berhasil Ditambah']);
    }
    /**
     * Edit = Halaman Edit Ekskul
     */
    public function edit(String $uuid): View
    {
        $ekskul = Ekskul::with('guru', 'pelajaran')->findOrFail($uuid);
        $guru = Guru::orderBy('nama', 'ASC')->get();
        $pelajaran = Pelajaran::all()->sortBy('urutan', SORT_NATURAL);
        $kelas = Kelas::all()->sortBy('kelas')->sortBy('tingkat');
        return view('ekskul.edit', compact('ekskul', 'guru', 'pelajaran', 'kelas'));
    }
    /**
     * Store - Halaman Proses Penambahan Ekskul
     */
    public function update(Request $request, String $uuid)
    {
        $request->validate([
            'ekskul' => 'required',
            'guru' => 'required'
        ]);
        $ekskul = Ekskul::findOrFail($uuid);
        $ekskul->update([
            'ekskul' => $request->ekskul,
            'id_guru' => $request->guru,
            'id_pelajaran' => $request->pelajaran
        ]);
        return redirect()->route('ekskul.index')->with(['success' => 'Ekskul Berhasil Diedit']);
    }
    /**
     * Destroy - Hapus Ekskul Yang sudah ditambah
     */
    public function destroy(String $uuid)
    {
        $ekskul = Ekskul::findOrFail($uuid);
        $ekskul->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Nilai - Ekskul Lihat Nilai Ekskul
     */
    public function nilai(): View
    {
        $auth = Auth::user();
        $access = $auth->access;
        $allowAccess = array('admin', 'kepala', 'kurikulum');
        if (in_array($access, $allowAccess)) {
            $ekskul = Ekskul::all()->sortBy('urutan', SORT_NATURAL);
        } else {
            $guru = Guru::where('id_login', $auth->uuid)->first();
            $ekskul = Ekskul::where('id_guru', $guru->uuid)->get()->sortBy('urutan', SORT_NATURAL);
        }
        return view('ekskul.nilai.index', compact('ekskul'));
    }
    /**
     * Nilai Show - Tampilkan Halaman Lihat Nilai Ekskul
     */
    public function showNilai(String $uuid): View
    {
        $ekskul = Ekskul::findOrFail($uuid);
        $kelas = Kelas::all()->sortBy('kelas')->sortBy('tingkat');
        $semester = Semester::first();
        return view('ekskul.nilai.show', compact('ekskul', 'kelas', 'semester'));
    }
    /**
     * Get Nilai - Ambil data Nilai Ekskul
     */
    public function getNilai(Request $request)
    {
        $id_ekskul = $request->ekskul;
        $siswa = Siswa::where('id_kelas', $request->kelas)->orderBy('nama', 'ASC')->get();
        $idSiswa = $siswa->pluck('uuid')->toArray();
        $semester = Semester::first();
        $nilai = EkskulSiswa::where([['id_ekskul', "=", $id_ekskul], ['semester', '=', $semester->semester]])->whereIn('id_siswa', $idSiswa)->get();

        return response()->json(['success' => true, 'siswa' => $siswa, 'nilai' => $nilai]);
    }
    /**
     * Create Nilai - Simpan Nilai Ekskul
     */
    public function createNilai(Request $request)
    {
        $arrayNilai = $request->nilai;
        EkskulSiswa::upsert($arrayNilai, ['uuid'], ['deskripsi']);

        $uuidHapus = array();
        foreach ($arrayNilai as $nilai) {
            if ($nilai['deskripsi'] == "") {
                array_push($uuidHapus, $nilai['uuid']);
            }
        }
        EkskulSiswa::whereIn('uuid', $uuidHapus)->delete();

        return response()->json(['success' => true]);
    }
    /**
     * Sort Ekskul View
     */
    public function sort(): View
    {
        $ekskul = Ekskul::get()->sortBy('urutan');

        return view('ekskul.sort', compact('ekskul'));
    }
    /**
     * Sorting Function Executed
     */
    public function sorting(Request $request): RedirectResponse
    {
        $ekskul_array = $request->urutan;

        Batch::update(new Ekskul, $ekskul_array, 'uuid');

        return redirect()->route('ekskul.sort')->with(['success' => "Ekskul berhasil diurut kembali"]);
    }
}
