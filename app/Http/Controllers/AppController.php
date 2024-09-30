<?php

namespace App\Http\Controllers;

use App\Models\Guru;
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

        if ($user->access != "siswa" && $user->access != "orangtua") {
            $guru = Guru::where('id_login', $user->uuid)->first();
            if ($user->access == "admin" || $user->access == "kepala") {
                Cookie::queue('admin', $guru->nik, time() + 86400);
            } else {
                Cookie::queue('guru', $guru->nik, time() + 86400);
            }
            $url = env('APP_URL') . "ujian/admin/index.php";
        } else {
            $url = env('APP_URL') . "ujian/index.php";
        }
        return Redirect::to($url);
    }
}
