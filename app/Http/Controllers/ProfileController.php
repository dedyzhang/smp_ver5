<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    //Index Halaman Profile
    public function index(): View
    {
        // $user = Auth::user();
        // if ($user->access !== "siswa" && $user->access !== "orangtua") {
        //     $account = Guru::where('id_login', $user->uuid)->get();
        // }
        return view('profile.index');
    }
    //Edit Halaman Profile
    public function edit(): View
    {
        return view('profile.edit');
    }
}
