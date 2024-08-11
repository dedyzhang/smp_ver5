<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Ruang;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(String $id): View
    {
        $ruang = Ruang::findOrFail($id);
        return view('sapras.barang.create', compact('id', 'ruang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, String $id)
    {
        $request->validate([
            "barang" => "required",
            "merk" => "required",
            "penyedia" => "required",
            "tanggal" => "required",
            "deskripsi" => "required",
            "jumlah" => "required",
        ]);
        Barang::create([
            'id_ruang' => $id,
            "barang" => $request->barang,
            "merk" => $request->merk,
            "penyedia" => $request->penyedia,
            "tanggal" => $request->tanggal,
            "deskripsi" => $request->deskripsi,
            "jumlah" => $request->jumlah,
        ]);
        return redirect()->back()->with(['success' => 'Data Berhasil Disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $uuid, String $uuidBarang)
    {
        $barang = Barang::with('ruang')->findOrFail($uuidBarang);

        return response()->json(['success' => true, 'barang' => $barang]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $uuid, String $uuidBarang): View
    {
        $barang = Barang::findOrFail($uuidBarang);
        return view('sapras.barang.edit', compact('barang', 'uuid'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $uuid, String $uuidBarang)
    {
        $barang = Barang::findOrFail($uuidBarang);
        $edit = $request->validate([
            "barang" => "required",
            "merk" => "required",
            "penyedia" => "required",
            "tanggal" => "required",
            "deskripsi" => "required",
            "jumlah" => "required",
        ]);
        $barang->update($edit);
        return redirect()->back()->with(['success' => 'Data Berhasil Diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $uuid, String $uuidBarang)
    {
        $barang = Barang::findOrFail($uuidBarang);
        $barang->delete();
    }
}
