<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Orangtua;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        if ($user->access != "siswa" && $user->access != "orangtua") {
            $account = Guru::with('users', 'walikelas')->where('id_login', $user->uuid)->first();
            if ($account->walikelas !== null) {
                $id_kelas = $account->walikelas->id_kelas;
                $jumlah = Siswa::selectRaw('
                    COUNT(CASE WHEN jk = "l" THEN 1 ELSE null END) as "laki",
                    COUNT(CASE WHEN jk = "p" THEN 1 ELSE null END) as "perempuan",
                    COUNT(*) as "all"
                ')->where('id_kelas', $id_kelas)->first();
                $siswa = Siswa::where('id_kelas', $id_kelas)->get();
            } else {
                $jumlah = "";
                $siswa = "";
            }
        } else {
            $jumlah = "";
            $siswa = "";
            if ($user->access == "orangtua") {
                $account = Orangtua::with(['users', 'siswa'])->where('id_login', $user->uuid)->first();
            } else {
                $account = Siswa::with('users')->where('id_login', $user->uuid)->first();
            }
        }
        // $account = Guru::with('users')->where('id_login',"=",$id)->first();
        return view('auth.home', compact('user', 'account', 'jumlah', 'siswa'));
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $remember = $request->remember_me == "on" ? true : false;

        if (Auth::attempt($credentials, $remember)) {

            $session = $request->session()->regenerate();
            $id = Auth::user()->uuid;

            return redirect()->intended('/home');
        }
        return back()->with('LoginError', 'Login Failed!');
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // return redirect('/login');

    }
    public function gantiPassword(Request $request)
    {
        $authId = Auth::user()->uuid;
        $user = User::findOrFail($authId);
        $password = Hash::make($request->password);

        $user->update([
            'password' => $password,
            'token' => 0,
        ]);
        return response()->json([
            'success' => true,
        ]);
    }
}
