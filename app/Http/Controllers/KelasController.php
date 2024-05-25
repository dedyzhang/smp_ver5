<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Walikelas;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $kelas = Kelas::with('walikelas')->orderBy('tingkat','ASC')->orderBy('kelas','ASC')->get();
        $guru = Guru::orderBy('nama','ASC')->get();
        return View('kelas.index',compact('kelas','guru'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        return View('kelas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $kelas = $request->validate([
            'tingkat' => 'required|numeric',
            'kelas' => 'required'
        ]);

        Kelas::create($kelas);

        return redirect()->route('kelas.index')->with(['success' => 'Data Berhasil Disimpan']);
    }
    /**
     * Tampilkan Walikelas
     */
    public function showWalikelas(string $uuid) {
        $find = Walikelas::with('Guru')->where('id_kelas',$uuid)->first();

        if(!empty($find)) {
            return response()->json([
                'success' => true,
                'data' => $find
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    /**
     * Jadikan Guru Bersangkutan Menjadi Walikelas
     */
    public function walikelas(Request $request)
    {
        $find = Walikelas::where('id_kelas',$request->idKelas);

        if($find->exists()) {
            $find->update([
                'id_kelas' => $request->idKelas,
                'id_guru' => $request->idGuru
            ]);
        } else {
            Walikelas::create([
                'id_kelas' => $request->idKelas,
                'id_guru' => $request->idGuru
            ]);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) : View
    {
        $kelas = Kelas::findOrFail($id);
        return View('kelas.edit',compact('kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $input = $request->validate([
            'tingkat' => 'required|numeric',
            'kelas' => 'required'
        ]);
        $kelas->update($input);
        return redirect()->route('kelas.index')->with(['success' => 'Kelas Berhasil Diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $walikelas = Walikelas::where('id_kelas',$id)->first();
        if($walikelas) {
            $walikelas->delete();
        }
        $kelas->delete();
    }
    /**
     * Set Siswa kedalam Rombelnya
     */
    public function setKelasSiswa() : View
    {
        $siswa = Siswa::where('id_kelas',NULL)->orderBy('nis','ASC')->get();
        $kelas = Kelas::orderBy('tingkat','ASC')->orderBy('kelas','ASC')->get();

        return View('kelas.setKelas',compact('siswa','kelas'));
    }
    /**
     * Save Siswa kedalam Rombel
     */
    public function saveRombel(Request $request,String $uuid)
    {
        $siswa = $request->idSiswa;
        $siswa_array = array();
        foreach($siswa as $item) {
            array_push($siswa_array,array(
                "id_siswa" => $item,
                "id_kelas" => $uuid
            ));
        }
        $kelas = Siswa::whereIn('uuid',$siswa)->update([
            'id_kelas' => $uuid
        ]);
        $histori = Rombel::upsert($siswa_array,['id_siswa'],['id_kelas']);
        return response()->json(['success' => true]);
    }
    /**
     * Histori Rombel
     */
    public function historiRombel() : View
    {
        $rombel = Rombel::with('kelas','siswa')->orderBy('created_at','DESC')->get();

        return view('kelas.historiRombel',compact('rombel'));
    }
    /**
     * Hapus Histori Rombel
     */
    public function historiHapus(String $id)
    {
        $rombel = Rombel::with('siswa')->findOrFail($id);

        $rombel->siswa->update([
            'id_kelas' => NULL
        ]);
        $rombel->delete();

        return response()->json(['success' => true]);
    }
}
