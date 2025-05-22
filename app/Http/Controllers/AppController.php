<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use Gemini\Laravel\Facades\Gemini as FacadesGemini;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AppController extends Controller
{
    public function redirect(Request $request): View
    {
        return view('redirect');
    }
    public function redirectUpdate(Request $request)
    {
        $link = $request->link;
        Cookie::queue('link', $link, 10080);
    }
    public function qrcode(): View
    {
        return view('qrcode');
    }
    public function ujian()
    {
        $user = Auth::user();
        $website = "." . env('APP_WEB');
        if ($user->access != "siswa" && $user->access != "orangtua") {
            $guru = Guru::where('id_login', $user->uuid)->first();
            if ($user->access == "admin" || $user->access == "kepala") {
                setCookie('guru', '', -1, '/', $website);
                setCookie('admin', $guru->nik, time() + 86400, '/', $website);
            } else {
                setCookie('admin', '', -1, '/', $website);
                setCookie('guru', $guru->nik, time() + 86400, '/', $website);
            }
            $url = env('APP_URL') . "ujian/admin/index.php";
        } else {
            $siswa = Siswa::where('id_login', $user->uuid)->first();
            setCookie('siswa', $siswa->nis, time() + 86400, '/', $website);
            $url = env('APP_URL') . "ujian/index.php";
        }
        return Redirect::to($url);
    }
    public function getGemini(Request $request)
    {
        $result = FacadesGemini::generativeModel(model: 'models/gemini-1.5-flash-001')->generateContent($request->text);
        return $result->text();
    }
    public function ppdb()
    {
        $url = env('APP_URL') . "ppdb";

        return Redirect::to($url);
    }
}
