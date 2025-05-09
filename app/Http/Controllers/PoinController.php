<?php

namespace App\Http\Controllers;

use App\Models\Aturan;
use App\Models\Guru;
use App\Models\Poin;
use App\Models\PoinTemp;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PoinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $aturan = Aturan::orderBy('kode', 'asc')->get();
        return view('poin.index', compact('aturan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('poin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $aturan = $request->validate([
            'jenis' => 'required',
            'kode' => 'required|unique:aturan,kode',
            'aturan' => 'required',
            'poin' => 'required'
        ], [
            'kode.unique' => 'Kode ' . $request->kode . " Sudah Pernah dipakai",
        ]);

        Aturan::create($aturan);

        return redirect()->route('aturan.index')->with(['success' => 'Data Aturan Berhasil Disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Aturan $aturan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $uuid): View
    {
        $aturan = Aturan::findOrFail($uuid);

        return view('poin.edit', compact('aturan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $uuid)
    {
        $aturan = Aturan::findOrFail($uuid);
        $edit = $request->validate([
            'jenis' => 'required',
            'kode' => 'required|unique:aturan,kode,' . $uuid . ',uuid',
            'aturan' => 'required',
            'poin' => 'required'
        ], [
            'kode.unique' => 'Kode ' . $request->kode . " Sudah Pernah dipakai",
        ]);
        $aturan->update($edit);

        return redirect()->back()->with(['success' => 'Poin Berhasil Diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $uuid)
    {
        $aturan = Aturan::findOrFail($uuid);
        $aturan->delete();

        return response()->json(["success" => true]);
    }

    // Poin Siswa

    /**
     * Poin Index - Untuk menampilkan Poin semua siswa
     */
    public function poinIndex(): View
    {
        $siswa = Siswa::with('kelas')->orderBy('nis', 'asc')->get();
        $poin = Poin::with('aturan')->get();
        $array_poin = array();
        foreach ($poin as $item) {
            if (isset($array_poin[$item->id_siswa])) {
                array_push($array_poin[$item->id_siswa], array(
                    "jenis" => $item->aturan->jenis,
                    "poin" => $item->aturan->poin,
                ));
            } else {
                $array_poin[$item->id_siswa] = array();
                array_push($array_poin[$item->id_siswa], array(
                    "jenis" => $item->aturan->jenis,
                    "poin" => $item->aturan->poin,
                ));
            }
        }
        return view('poin.siswa.index', compact('siswa', 'array_poin'));
    }

    /**
     * Poin Show - Tampilkan Poin Siswa Bersangkutan
     */
    public function poinShow(String $uuid): View
    {
        $siswa = Siswa::with('kelas')->findOrFail($uuid);
        $poin = Poin::with('aturan')->where('id_siswa', $siswa->uuid)->orderBy(Poin::raw("DATE(tanggal)"), 'ASC')->get();
        return view('poin.siswa.show', compact('siswa', 'poin'));
    }
    /**
     * Poin Create - Tampilkan Halaman Create Poin
     */
    public function poinCreate(String $uuid): View
    {
        $siswa = Siswa::with('kelas')->findOrFail($uuid);
        return view('poin.siswa.create', compact('uuid', 'siswa'));
    }
    /**
     * Poin Get Aturan - Ambil Data Aturan didalam select create
     */
    public function poinGetAturan(Request $request)
    {
        $aturan = Aturan::where('jenis', $request->jenis)->orderBy('kode', 'asc')->get();

        return response()->json(["aturan" => $aturan]);
    }
    /**
     * Poin Store Aturan - Simpan Data Aturan
     */
    public function poinStore(Request $request, String $uuid)
    {
        $request->validate([
            'jenis' => 'required',
            'tanggal' => 'required',
            'aturan' => 'required'
        ]);
        Poin::create([
            'tanggal' => $request->tanggal,
            'id_aturan' => $request->aturan,
            'id_siswa' => $uuid
        ]);

        return redirect()->route('poin.show', $uuid)->with(['success' => 'Data Aturan Berhasil Disimpan']);
    }
    /**
     * Poin Delete - Hapus Poin
     */
    public function poinDelete(String $uuid)
    {
        Poin::findOrFail($uuid)->delete();
    }
    /**
     * Poin Temp - Temporary Poin
     */
    public function tempIndex(): View
    {
        $temp = PoinTemp::with('siswa')->where('status', 'belum')->orderBy(PoinTemp::raw("DATE(tanggal)"), 'DESC')->get();
        $siswa = Siswa::with('kelas')->get();
        $guru_all = Guru::get();
        $siswa_all_name = $siswa->pluck('nama', 'uuid')->toArray();
        $guru_all_name = $guru_all->pluck('nama', 'uuid')->toArray();
        $all_name = array_merge($siswa_all_name, $guru_all_name);
        return view('poin.temp.index', compact('temp', 'all_name'));
    }
    /**
     * Poin Update - Update Poin
     */
    public function tempUpdate(Request $request, String $uuid)
    {
        $temp = PoinTemp::findOrFail($uuid);
        $temp->update([
            'status' => $request->jenis,
        ]);
        if ($request->jenis == "approve") {
            Poin::create([
                'tanggal' => $temp->tanggal,
                'id_aturan' => $temp->id_aturan,
                'id_siswa' => $temp->id_siswa
            ]);
        }
        return response()->json(['jenis' => $request->jenis]);
    }
    /**
     * Poin - Show Approve Temp Poin
     */
    public function tempApprove(): View
    {
        $temp = PoinTemp::with('siswa')->where('status', 'approve')->orderBy('updated_at', 'DESC')->get();
        $siswa = Siswa::with('kelas')->get();
        $guru_all = Guru::get();
        $siswa_all_name = $siswa->pluck('nama', 'uuid')->toArray();
        $guru_all_name = $guru_all->pluck('nama', 'uuid')->toArray();
        $all_name = array_merge($siswa_all_name, $guru_all_name);
        return view('poin.temp.approve', compact('temp', 'all_name'));
    }
    /**
     * Poin - Show DisApprove Temp Poin
     */
    public function tempDisapprove(): View
    {
        $temp = PoinTemp::with('siswa')->where('status', 'disapprove')->orderBy('updated_at', 'DESC')->get();
        $siswa = Siswa::with('kelas')->get();
        $guru_all = Guru::get();
        $siswa_all_name = $siswa->pluck('nama', 'uuid')->toArray();
        $guru_all_name = $guru_all->pluck('nama', 'uuid')->toArray();
        $all_name = array_merge($siswa_all_name, $guru_all_name);
        return view('poin.temp.approve', compact('temp', 'all_name'));
    }
    public function guruPoinIndex(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $siswa = Siswa::all();
        $guru_all = Guru::get();
        $guru_all_name = $guru_all->pluck('nama', 'uuid')->toArray();
        $all_name = $guru_all_name;
        $poin_temp = PoinTemp::with('aturan', 'siswa')->where('id_input', $guru->uuid)->orderBy('created_at', 'DESC')->get();
        return view('walikelas.poin.temp.index', compact('poin_temp', 'all_name'));
    }
    public function guruPoinCreate(): View
    {
        $auth = Auth::user();
        $guru = Guru::where('id_login', $auth->uuid)->first();
        $siswa = Siswa::with('kelas')->get()->sortBy('nama')->sortBy('kelas.kelas')->sortBy('kelas.tingkat');
        return view('walikelas.poin.temp.create', compact('siswa'));
    }
}
