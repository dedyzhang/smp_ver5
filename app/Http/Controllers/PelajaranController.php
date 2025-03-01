<?php

namespace App\Http\Controllers;

use App\Models\Pelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $pelajaran = Pelajaran::get()->sortBy('urutan');
        return view('pelajaran.index', compact('pelajaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pelajaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $pelajaran = $request->validate([
            'pelajaran' => 'required',
            'pelajaran_singkat' => 'required|alpha:ascii',
        ], [
            'pelajaran_singkat.required' => 'Nama singkat pelajaran wajib diisi',
            'pelajran_singkat.alpha:ascii' => 'Nama Singkat pelajaran tidak boleh mengandung simbol1'
        ]);

        $urutan = Pelajaran::get();
        if ($urutan->count() > 0) {
            $urut = 1 + $urutan->count();
        } else {
            $urut = 1;
        };
        $input = Pelajaran::create([
            'pelajaran' => $request->pelajaran,
            'pelajaran_singkat' => $request->pelajaran_singkat,
            'has_penjabaran' => 0,
            'urutan' => $urut
        ]);

        return redirect()->route('pelajaran.index')->with(['success' => 'Pelajaran Berhasil Ditambah']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelajaran $pelajaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id): View
    {
        $pelajaran = Pelajaran::findOrFail($id);

        return view('pelajaran.edit', compact('pelajaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id): RedirectResponse
    {
        $pelajaran = Pelajaran::findOrFail($id);
        $validation = $request->validate([
            'pelajaran' => 'required',
            'pelajaran_singkat' => 'required|alpha:ascii',
        ], [
            'pelajaran_singkat.required' => 'Nama singkat pelajaran wajib diisi',
            'pelajran_singkat.alpha:ascii' => 'Nama Singkat pelajaran tidak boleh mengandung simbol1'
        ]);

        $input = $pelajaran->update([
            'pelajaran' => $request->pelajaran,
            'pelajaran_singkat' => $request->pelajaran_singkat
        ]);

        return redirect()->route('pelajaran.index')->with(['success' => $request->pelajaran . ' Berhasil Diedit']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $pelajaran = Pelajaran::findOrFail($id);
        $pelajaran->delete();

        return response()->json(['success' => true]);
    }
    /**
     * Sort Pelajaran View
     */
    public function sort(): View
    {
        $pelajaran = Pelajaran::get()->sortBy('urutan');

        return view('pelajaran.sort', compact('pelajaran'));
    }
    /**
     * Sorting Function Executed
     */
    public function sorting(Request $request): RedirectResponse
    {
        $pelajaran_array = $request->urutan;

        Pelajaran::upsert($pelajaran_array, 'uuid', ['urutan']);

        return redirect()->route('pelajaran.sort')->with(['success' => "Pelajaran berhasil diurut kembali"]);
    }
    /**
     * Dapatkan Data Penjabaran
     */
    public function getPenjabaran()
    {
        $english = Pelajaran::where('has_penjabaran', 1)->get();
        $mandarin = Pelajaran::where('has_penjabaran', 2)->get();
        $komputer = Pelajaran::where('has_penjabaran', 3)->get();
        if ($english->isEmpty() && $mandarin->isEmpty() && $komputer->isEmpty()) {
            return response()->json(["success" => false]);
        } else {
            return response()->json(["success" => true, "english" => $english, "mandarin" => $mandarin, "komputer" => $komputer]);
        }
    }
    /**
     * Atur Penjabaran
     */
    public function setPenjabaran(Request $request)
    {
        $inggris = $request->english;
        $mandarin = $request->mandarin;
        $komputer = $request->komputer;

        $allPelajaran = Pelajaran::where('has_penjabaran', '>', '0')->update([
            "has_penjabaran" => 0
        ]);

        $english_query = Pelajaran::findOrFail($inggris)->update([
            "has_penjabaran" => 1
        ]);
        $mandarin_query = Pelajaran::findOrFail($mandarin);

        $mandarin_query->update([
            "has_penjabaran" => 2
        ]);

        $komputer_query = Pelajaran::findOrFail($komputer)->update([
            'has_penjabaran' => 3
        ]);
    }
}
