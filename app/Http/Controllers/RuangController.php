<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RuangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $ruang = Ruang::orderBy('kode', 'ASC')->get();
        return view('sapras.ruang.index', compact('ruang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sapras.ruang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:sapras_ruang,kode',
            'nama' => 'required',
            'warna' => 'required',
            'umum' => 'required'
        ], [
            'kode.unique' => 'Kode ' . $request->kode . " Sudah Pernah dipakai",
        ]);

        Ruang::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'warna' => $request->warna,
            'umum' => $request->umum
        ]);

        return redirect()->route('ruang.index')->with(['success' => 'Sukses Menambahkan Ruangan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $ruang = Ruang::with('barang')->findOrFail($id);
        return view('sapras.barang.index', compact('id', 'ruang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $ruang = Ruang::findOrFail($id);
        return view('sapras.ruang.edit', compact('ruang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ruang = Ruang::findOrFail($id);
        $edit = $request->validate([
            'kode' => 'required|unique:sapras_ruang,kode,' . $id . ',uuid',
            'nama' => 'required',
            'warna' => 'required',
            'umum' => 'required'
        ], [
            'kode.unique' => 'Kode ' . $request->kode . " Sudah Pernah dipakai",
        ]);
        $ruang->update($edit);

        return redirect()->back()->with(['success' => 'Ruangan Berhasil Diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ruang = Ruang::findOrFail($id);

        $ruang->delete();
    }
}
