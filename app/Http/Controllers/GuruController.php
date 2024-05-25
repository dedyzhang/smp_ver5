<?php

namespace App\Http\Controllers;

//import model
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Ngajar;
use App\Models\Pelajaran;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class GuruController extends Controller
{
    //index controller
    public function index() : View
    {
        $gurus = Guru::with('users')->orderBy('nama')->get();

        return view('guru.index',compact('gurus'));
    }

    //Halaman Tambah Guru dan Insert Data Guru
    public function create() : View {
        return view('guru.create');
    }
    public function store(Request $request) : RedirectResponse {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required|unique:users,username|numeric',
            'jk' => 'required',
            'role' => 'required',
        ],[
            'nik.required'=> 'NIK Wajib Diisi',
            'nik.unique' => 'NIK '.$request->nik." Sudah dipakai",
        ]);

        //Buat Password Random
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];
        $password = Hash::make($rand);

        $user = User::create([
            'username'=> $request->nik,
            'password'=> $password,
            'access' => $request->role,
            'token' => '1',
        ]);
        Guru::create([
            'id_login' => $user->uuid,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'jk' => $request->jk,
        ]);

        return redirect()->route('guru.index')->with(['success' => 'Data Berhasil Disimpan']);
    }
    //Tampilkan Data Guru
    public function show(string $uuid) {
        $guru = Guru::findOrFail($uuid);

        return compact('guru');
    }
    //Halaman Edit Guru dan Update Data Guru
    public function edit(string $uuid) : View
    {
        $guru = Guru::with('users')->findOrFail($uuid);
        return view('guru.edit',compact('guru'));
    }
    public function update(Request $request,$uuid) : RedirectResponse
    {
        $guru = Guru::with('users')->findOrFail($uuid);

        $guru->update([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'jk' => $request->jk,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'agama' => $request->agama,
            'alamat' => $request->alamat,
            'tingkat_studi' => $request->tingkat_studi,
            'program_studi' => $request->program_studi,
            'universitas' => $request->universitas,
            'tahun_tamat' => $request->tahun_tamat,
            'tmt_ngajar' => $request->tmt_ngajar,
            'tmt_smp' => $request->tmt_smp,
            'no_telp' => $request->no_telp
        ]);
        if($guru->users->access != $request->role && $guru->users->access != "admin") {
            $guru->users->update([
                'access'=> $request->role
            ]);
        }

        return redirect()->back()->with(['success' => 'Data Berhasil Diupdate']);
    }
    //Hapus data PTK
    public function destroy($uuid) {
        $guru = Guru::with(['users','walikelas'])->findOrFail($uuid);
        if($guru->walikelas){
            $guru->walikelas->delete();
        }
        $guru->users->delete();
        $guru->delete();

        return response()->json([
            'success' => true,
            'Message' => "PTK Berhasil Dihapus"
        ]);
    }
    //Reset Password
    public function reset(String $uuid) {
        $guru = Guru::with('users')->findOrFail($uuid);
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];
        $passwordHash = Hash::make($rand);
        $guru->users->update([
            'password' => $passwordHash,
            'token' => 1,
        ]);

        return response()->json([
            'success' => true,
            'password'=> $rand
        ]);
    }
    /**
     * Guru Atur Pelajaran yang dingajar
     */
    public function pelajaran(String $uuid) : View {
        $guru = Guru::findOrFail($uuid);
        $pelajaran = Pelajaran::get()->sortBy('urutan');
        $kelas = Kelas::OrderByRaw('tingkat ASC, kelas ASC')->get();
        $ngajar = Ngajar::with('pelajaran','kelas')->where('id_guru',$uuid)->get();

        return view('guru.pelajaran',compact('pelajaran','kelas','guru','ngajar'));
    }
    /**
     * Masukkan Data Ngajar di data Guru kedalam databases
     */
    public function ngajar(Request $request,String $uuid)
    {
        $datas = $request->dataArray;

        $count = 0;
        foreach($datas as $data) {
            $ngajar = Ngajar::where([
                ['id_guru',$data['id_guru']],
                ['id_pelajaran',$data['id_pelajaran']],
                ['id_kelas',$data['id_kelas']]
            ])->first();

            if($ngajar !== null) {
                $count ++;
            }
        }
        if($count > 0) {
            return response()->json(["success" => false, "message" => "Terdapat data pelajaran dan data Kelas yang duplikat"]);
        } else {
            Ngajar::upsert($datas,'uuid',['id_pelajaran','id_kelas','id_guru']);
            return response()->json(["success" => true]);
        }
    }
    /**
     * Hapus Data Ngajar
     */
    public function hapusNgajar(String $id)
    {
        $hapus = Ngajar::findOrFail($id);

        $hapus->delete();

        return response()->json(["success" => "Berhasil Menghapus Data"]);
    }
}
