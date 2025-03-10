<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\Event;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JadwalHari;
use App\Models\JadwalVer;
use App\Models\JadwalWaktu;
use App\Models\Kelas;
use App\Models\Orangtua;
use App\Models\PerangkatAjar;
use App\Models\PerangkatAjarGuru;
use App\Models\Siswa;
use App\Models\TanggalAbsensi;
use App\Models\User;
use App\Models\Poin;
use App\Models\Ruang;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
            $now = date('Y-m-d');
            $futureDate = Carbon::parse($now)->addWeek()->format('Y-m-d');
            $event = Event::whereBetween('waktu_mulai', [$now, $futureDate])->get();

            if ($user->access == "admin" || $user->access == "kepala") {
                //TotalSiswa
                $siswa = Siswa::selectRaw('
                    COUNT(CASE WHEN jk = "l" THEN 1 ELSE null END) as "laki",
                    COUNT(CASE WHEN jk = "p" THEN 1 ELSE null END) as "perempuan",
                    COUNT(*) as "all"
                ')->first();
                //Siswa Per Kelas
                $siswaPKelas = Siswa::selectRaw('
                    COUNT(CASE WHEN jk = "l" THEN 1 ELSE null END) as "laki",
                    COUNT(CASE WHEN jk = "p" THEN 1 ELSE null END) as "perempuan",
                    COUNT(*) as "all",
                    id_kelas
                ')->groupBy('id_kelas')->get();
                //TotalGuru
                $guru = Guru::selectRaw('
                    COUNT(CASE WHEN jk = "l" THEN 1 ELSE null END) as "laki",
                    COUNT(CASE WHEN jk = "p" THEN 1 ELSE null END) as "perempuan",
                    COUNT(*) as "all"
                ')->first();
                //TotalKelas
                $kelas = Kelas::orderBy('tingkat', 'ASC')->orderBy('kelas', 'ASC')->get();
                if (isset($kelas)) {
                    $jumlahRombel = $kelas->count();
                } else {
                    $jumlahRombel = 0;
                }
                //TotalRuang
                $ruang = Ruang::all();
                if (isset($ruang)) {
                    $jumlahRuang = $ruang->count();
                } else {
                    $jumlahRuang = 0;
                }
                return view('auth.home', compact('user', 'account', 'event', 'siswa', 'siswaPKelas', 'guru', 'kelas', 'jumlahRombel', 'jumlahRuang'));
            } else {
                $listPerangkat = PerangkatAjar::orderBy('perangkat')->get();
                $UploadPerangkat = PerangkatAjarGuru::where('id_guru', $user->guru->uuid)->get();
                $arrayUpload = $UploadPerangkat->pluck('id_list')->toArray();
                if ($account->walikelas !== null) {
                    $id_kelas = $account->walikelas->id_kelas;
                    $jumlah = Siswa::selectRaw('
                        COUNT(CASE WHEN jk = "l" THEN 1 ELSE null END) as "laki",
                        COUNT(CASE WHEN jk = "p" THEN 1 ELSE null END) as "perempuan",
                        COUNT(*) as "all"
                    ')->where('id_kelas', $id_kelas)->first();
                    $siswa = Siswa::where('id_kelas', $id_kelas)->get();
                    return view('auth.home', compact('user', 'event', 'account', 'jumlah', 'siswa', 'listPerangkat', 'arrayUpload'));
                } else {
                    return view('auth.home', compact('user', 'event', 'account', 'listPerangkat', 'arrayUpload'));
                }
            }
        } else {
            if ($user->access == "orangtua") {
                $account = Orangtua::with(['users', 'siswa'])->where('id_login', $user->uuid)->first();
                $siswa = $account->siswa;
            } else {
                $account = Siswa::with('users')->where('id_login', $user->uuid)->first();
                $siswa = $account;
            }
            $versi = JadwalVer::where('status', 'active')->first();
            $today = date('N');
            $hari = JadwalHari::where('no_hari', $today)->first();
            if (isset($hari)) {
                $jadwal = Jadwal::with('kelas', 'pelajaran', 'guru', 'ngajar', 'waktu')->where([
                    ['id_jadwal', '=', $versi->uuid],
                    ['id_kelas', '=', $siswa->id_kelas],
                    ['id_hari', '=', $hari->uuid],
                ])->get()->sortBy('waktu.waktu_mulai');
            } else {
                $jadwal = array();
            }
            $jumlahHari = TanggalAbsensi::where([['ada_siswa', '=', 1], ['semester', '=', 1]])->get();
            if (isset($jumlahHari)) {
                $jumlah = $jumlahHari->count();
            } else {
                $jumlah = 0;
            }
            $tanggalArray = $jumlahHari->pluck('uuid');
            $absensi = AbsensiSiswa::selectRaw('
                COUNT(CASE WHEN absensi = "sakit" THEN 1 ELSE null END) as "sakit",
                COUNT(CASE WHEN absensi = "izin" THEN 1 ELSE null END) as "izin",
                COUNT(CASE WHEN absensi = "alpa" THEN 1 ELSE null END) as "alpa"
            ')->where('id_siswa', $siswa->uuid)->whereIn('id_tanggal', $tanggalArray)->first();

            //Poin Siswa
            $poin = Poin::with('aturan')->where('id_siswa', $siswa->uuid)->orderBy(Poin::raw("DATE(tanggal)"), 'ASC')->get();
            $sisa = 100;
            foreach ($poin as $item) {
                $item->aturan->jenis == "kurang" ? $sisa -= $item->aturan->poin : $sisa += $item->aturan->poin;
            }
            return view('auth.home', compact('user', 'account', 'siswa', 'jadwal', 'jumlah', 'absensi', 'sisa'));
        }
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
    public function changePassword(): View
    {
        return view('auth.password');
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
    public function gantiPasswordRequest(Request $request)
    {
        $authId = Auth::user()->uuid;
        $user = User::findOrFail($authId);
        if (Hash::check($request->passwordLama, $user->password)) {
            $password = Hash::make($request->password);
            $user->update([
                'password' => $password,
                'token' => 0,
            ]);
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Password Lama Yang dimasukkan tidak sesuai'
            ]);
        }
    }
}
