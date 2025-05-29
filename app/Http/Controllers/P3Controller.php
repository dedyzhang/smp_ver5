<?php

namespace App\Http\Controllers;

use App\Models\P3Kategori;
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
        $p3 = P3Kategori::orderBy('jenis','ASC')->get();
        return view('p3.index',compact('p3'));
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

        return view('p3.edit',compact('p3'));
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

        return redirect()->route('p3.edit',$id)->with(['success' => "Data Berhasil Diedit"]);
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
    public function showSiswa() : View {
        $siswa = Siswa::with('kelas')->get();
        return view('p3.siswa.index',compact('siswa'));
    }
    /**
     * Show Poin Siswa Per Individual
     */
    public function siswaShowP3(String $uuid) : View {
        $siswa = Siswa::with('kelas')->findOrFail($uuid);

        return view('p3.siswa.show',compact('siswa'));
    }
    /**
     * P3 - Halaman Tambah Poin
     */
    public function p3CreatePoin(String $uuid) : View {
        $siswa = Siswa::with('kelas')->findOrFail($uuid);
        return view('p3.siswa.create',compact('siswa'));
    }
    /**
     * P3 - Halaman Dapatkan Kategori Sesuai yang dipilih
     */
    public function p3GetKategori(Request $request) {
        $kategori = P3Kategori::where('jenis',$request->jenis)->get();
        
        return response()->json(['kategori' => $kategori,'message' => 'success']);
    }
}
