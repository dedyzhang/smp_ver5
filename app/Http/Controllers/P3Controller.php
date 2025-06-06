<?php

namespace App\Http\Controllers;

use App\Models\P3Kategori;
use App\Models\P3Poin;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class P3Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $p3 = P3Kategori::orderBy('jenis', 'ASC')->get();
        return view('p3.index', compact('p3'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('p3.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required',
            'deskripsi' => 'required'
        ]);

        P3Kategori::create([
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('p3.create')->with(['success' => 'Data Berhasil Disimpan']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $p3 = P3Kategori::findOrFail($id);

        return view('p3.edit', compact('p3'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $p3 = P3Kategori::findOrFail($id);

        $request->validate([
            'jenis' => 'required',
            'deskripsi' => 'required'
        ]);

        $p3->update([
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('p3.edit', $id)->with(['success' => "Data Berhasil Diedit"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $p3 = P3Kategori::findOrFail($id);

        $p3->delete();
    }

    /**
     * Show Data Siswa dan jumlah p3 nya
     */
    public function showSiswa(): View
    {
        $siswa = Siswa::with('kelas')->get()->sortBy('nama')->sortBy('kelas.kelas')->sortBy('kelas.tingkat');
        $p3 = P3Poin::with('siswa')->get();
        $array_p3 = array();

        foreach($p3 as $item) {
            if(empty($array_p3[$item->id_siswa])) {
                $array_p3[$item->id_siswa] = array(
                    'pelanggaran' => 0,
                    'prestasi' => 0,
                    'partisipasi' => 0
                );
                $array_p3[$item->id_siswa][$item->jenis] += 1;
            } else {
                $array_p3[$item->id_siswa][$item->jenis] += 1;
            } 
        }
        // dd($array_p3);
        return view('p3.siswa.index', compact('siswa','array_p3'));
    }
    /**
     * Show Poin Siswa Per Individual
     */
    public function siswaShowP3(String $uuid): View
    {
        $siswa = Siswa::with('kelas')->findOrFail($uuid);
        $p3 = P3Poin::where('id_siswa',$siswa->uuid)->orderBy('tanggal')->get();

        return view('p3.siswa.show', compact('siswa','p3'));
    }
    /**
     * P3 - Halaman Tambah Poin
     */
    public function p3CreatePoin(String $uuid): View
    {
        $siswa = Siswa::with('kelas')->findOrFail($uuid);
        return view('p3.siswa.create', compact('siswa'));
    }
    /**
     * P3 - Halaman Dapatkan Kategori Sesuai yang dipilih
     */
    public function p3GetKategori(Request $request)
    {
        $kategori = P3Kategori::where('jenis', $request->jenis)->get();

        return response()->json(['kategori' => $kategori, 'message' => 'success']);
    }
    /**
     * P3 - Simpan Poin Siswa
     */
    public function p3StorePoin(Request $request, String $uuid)
    {
        $siswa = Siswa::with('kelas')->findOrFail($uuid);
        $semester = Semester::first();

        $request->validate([
            'tanggal' => 'required',
            'jenis' => 'required',
            'deskripsi' => 'required',
        ]);

        P3Poin::create([
            'id_siswa' => $siswa->uuid,
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi,
            'semester' => $semester->semester,
        ]);

        return redirect()->route('p3.siswa.show', $uuid)->with(['success' => 'Poin Berhasil Ditambahkan']);
    }
    /**
     * P3 Edit Poin yang sudah dibuat
     */
    public function p3EditPoin(String $uuid): View {
        $p3 = P3Poin::with('siswa','kategori')->findOrFail($uuid);
        $siswa = $p3->siswa;

        return view('p3.siswa.edit', compact('siswa','p3'));
    }
    /**
     * P3 Update Poin
     */
    public function p3UpdatePoin(Request $request, String $uuid) {
        $p3 = P3Poin::findOrFail($uuid);

        $request->validate([
            'tanggal' => 'required',
            'jenis' => 'required',
            'deskripsi' => 'required',
        ]);

        $p3->update([
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi
        ]);
        return redirect()->route('p3.siswa.show', $p3->id_siswa)->with(['success' => 'Poin Berhasil Diedit']);
    }
    /**
     * P3 Poin Delete
     */
    public function p3DeletePoin(String $uuid) {
        $p3 = P3Poin::findOrFail($uuid);
        $p3->delete();

        return response()->json(['success' => true]);
    }
}
