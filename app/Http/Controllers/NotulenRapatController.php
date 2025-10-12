<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\NotulenRapat;
use Illuminate\Http\Request;

class NotulenRapatController extends Controller
{
    public function index()
    {
        $notulen = NotulenRapat::orderBy('tanggal_rapat', 'desc')->get();
        return view('notulen.index', compact('notulen'));
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
            'guru_hadir'
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
}
