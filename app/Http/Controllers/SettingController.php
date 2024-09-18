<?php

namespace App\Http\Controllers;

use App\Models\Aturan;
use App\Models\Nis;
use App\Models\Semester;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $semester = Semester::first();
        $nis = Nis::first();
        $aturan = Aturan::where('jenis', 'kurang')->orderBy('kode')->get();
        $setting = Setting::all();
        return view('setting.index', compact('semester', 'nis', 'aturan', 'setting'));
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
    public function setPoinTerlambat(Request $request)
    {
        $poin = $request->poin;
        $settingPoin = Setting::where('jenis', 'poin_terlambat')->first();

        if ($settingPoin !== null) {
            $settingPoin->update([
                'nilai' => $poin
            ]);
        } else {
            Setting::create([
                'jenis' => 'poin_terlambat',
                'nilai' => $poin
            ]);
        }
    }
    public function setWaktuTerlambat(Request $request)
    {
        $waktu = $request->waktu;
        $settingWaktu = Setting::where('jenis', 'waktu_terlambat_siswa')->first();

        if ($settingWaktu !== null) {
            $settingWaktu->update([
                'nilai' => $waktu
            ]);
        } else {
            Setting::create([
                'jenis' => 'waktu_terlambat_siswa',
                'nilai' => $waktu
            ]);
        }
    }
}
