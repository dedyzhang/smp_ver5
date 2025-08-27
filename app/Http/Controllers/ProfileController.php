<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Orangtua;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    //Index Halaman Profile
    public function index(): View
    {
        $user = Auth::user();
        if ($user->access !== "siswa" && $user->access !== "orangtua") {
            $account = Guru::with('users')->where('id_login', $user->uuid)->first();
        } else {
            if (Auth::user()->access == "orangtua") {
                $orangtua = Orangtua::where('id_login', Auth::user()->uuid)->first();
                $account = Siswa::with('kelas')->where('uuid', $orangtua->id_siswa)->first();
            } else {
                $account = Siswa::with('kelas')->where('id_login', Auth::user()->uuid)->first();
            }
        }
        return view('profile.index', compact('account', 'user'));
    }
    //Edit Halaman Profile
    public function edit(): View
    {
        $user = Auth::user();
        $guru = Guru::with('users')->where('id_login', $user->uuid)->first();
        return view('profile.edit', compact('guru'));
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::with('users')->where('id_login', $user->uuid)->first();

        $guru->update([
            'nama' => $request->nama,
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

        return redirect()->back()->with(['success' => 'Data Berhasil Diupdate']);
    }
}
