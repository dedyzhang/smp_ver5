<?php

namespace App\Http\Controllers;

use App\Models\Nis;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $semester = Semester::first();
        $nis = Nis::first();
        return view('setting.index', compact('semester', 'nis'));
    }
    public function updateSemester(Request $request)
    {
        $semester = $request->semester;
        $tp = $request->tp;

        Semester::first()->update([
            'tp' => $tp,
            'semester' => $semester
        ]);
    }
    public function updatenis(Request $request)
    {
        $first_nis = $request->first_nis;
        $second_nis = $request->second_nis;
        $third_nis = $request->third_nis;


        Nis::query()->update([
            'first_nis' => $first_nis,
            'second_nis' => $second_nis,
            'third_nis' => $third_nis
        ]);
    }
}
