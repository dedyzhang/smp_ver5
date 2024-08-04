<?php

namespace App\Http\Controllers;

use App\Models\Barang;
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
        return view('sapras.barang.create', compact('id'));
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
    public function show(Barang $barang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        //
    }
}
