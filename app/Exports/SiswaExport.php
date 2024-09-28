<?php

namespace App\Exports;

use App\Models\Setting;
use App\Models\Siswa;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SiswaExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $params;

    function __construct($params)
    {
        $this->params = $params;
    }
    public function view(): View
    {
        $setting = Setting::where('jenis', 'nama_sekolah')->first();
        if ($this->params == "semua") {
            return view('cetak.siswa.excel', ['siswa' => Siswa::with('kelas')->orderBy('nis', 'asc')->get(), 'setting' => $setting]);
        } else {
            return view('cetak.siswa.excel', ['siswa' => Siswa::where('id_kelas', $this->params)->get()->sortBy('nama', SORT_NATURAL), 'setting' => $setting]);
        }
    }
}
