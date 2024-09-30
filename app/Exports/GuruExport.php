<?php

namespace App\Exports;

use App\Models\Guru;
use App\Models\Setting;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class GuruExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $setting = Setting::where('jenis', 'nama_sekolah')->first();
        return view('cetak.guru.excel', ['guru' => Guru::orderBy('nama')->get(), 'setting' => $setting]);
    }
}
