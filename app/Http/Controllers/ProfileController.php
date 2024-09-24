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
        $user = Auth::user();
        if ($user->access !== "siswa" && $user->access !== "orangtua") {
            $account = Guru::with('users')->where('id_login', $user->uuid)->first();
        }
        return view('profile.index', compact('account'));
    }
    //Edit Halaman Profile
    public function edit(): View
    {
        $user = Auth::user();
        $account = Guru::with('users')->where('id_login', $user->uuid)->first();
        return view('profile.edit', compact('account'));
    }
}
