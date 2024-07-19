<?php

namespace App\Exports;

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
        if ($this->params == "semua") {
            return view('cetak.siswa.excel', ['siswa' => Siswa::with('kelas')->orderBy('nis', 'asc')->get()]);
        } else {
            return view('cetak.siswa.excel', ['siswa' => Siswa::where('id_kelas', $this->params)->get()->sortBy('nama', SORT_NATURAL)]);
        }
    }
}
