<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Nis;
use App\Models\Orangtua;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $kelas = Kelas::get()->sortBy('tingkat')->sortBy('kelas');
        $siswa = Siswa::with('kelas')->orderBy('nis','ASC')->get();

        return View('siswa.index',compact('siswa','kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $nis = Nis::get()->first();
        return View('siswa.create',compact('nis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'nama' => 'required',
            'jk' => 'required'
        ]);
        $password = Hash::make("pass.".$request->nis);

        $user = User::create([
            'username' => $request->nis,
            'password' => $password,
            'access' => 'siswa',
            'token' => '1'
        ]);

        $siswa = Siswa::create([
            'id_login' => $user->uuid,
            'nis' => $request->nis,
            'nama' => $request->nama,
            'jk' => $request->jk
        ]);
        $usernameOrtu = "P.".$request->nis;
        $passwordOrtu = Hash::make("passortu.".$request->nis);

        $orangtuaUser = User::create([
            'username' => $usernameOrtu,
            'password' => $passwordOrtu,
            'access' => 'orangtua',
            'token' => '1',
        ]);

        $orangtua = Orangtua::create([
            'id_login' => $orangtuaUser->uuid,
            'id_siswa' => $siswa->uuid,
        ]);
        $nis = explode('.',$request->nis);
        $nis = end($nis);
        $number = intval($nis) + 1;
        $nis = sprintf('%04d', $number);

        $newNis = Nis::query()->update([
            'third_nis' => $nis,
        ]);

        return redirect()->route('siswa.create')->with(['success' => 'Data '.$request->nama.' Berhasil Disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::findOrFail($id);

        return compact('siswa');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) : View
    {
        $siswa = Siswa::findOrFail($id);

        return View('siswa.edit',compact('siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) : RedirectResponse
    {
        $siswa = Siswa::findOrFail($id);

        $siswa->update($request->all());

        return redirect()->back()->with(['success' => 'Data '.$request->nama.' Berhasil Disimpan']);
    }
    /**
     * Reset Password Siswa
    */
    public function resetSiswa(string $id) {
        $siswa = Siswa::with('users')->findOrFail($id);
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];
        $passwordHash = Hash::make($rand);
        $siswa->users->update([
            'password' => $passwordHash,
            'token' => 1,
        ]);

        return response()->json([
            'success' => true,
            'password' => $rand
        ]);
    }

    /**
     * Reset Password Orangtua
     */
    public function resetOrangtua(string $id) {
        $siswa = Siswa::with('orangtua')->findOrFail($id);
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];
        $passwordHash = Hash::make($rand);
        $siswa->orangtua[0]->update([
            'password' => $passwordHash,
            'token' => 1,
        ]);
        return response()->json([
            'success' => true,
            'password' => $rand
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::with(['users','orangtua','ortu'])->findOrFail($id);

        $siswa->orangtua[0]->delete();
        $siswa->ortu->delete();
        $siswa->users->delete();
        $siswa->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
