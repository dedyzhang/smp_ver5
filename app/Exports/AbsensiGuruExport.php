<?php

namespace App\Exports;

use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\TanggalAbsensi;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class AbsensiGuruExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $dari, $sampai;

    function __construct($dari, $sampai)
    {
        $this->dari = $dari;
        $this->sampai = $sampai;
    }

    public function view(): View
    {
        $dari = date($this->dari);
        $sampai = date($this->sampai);

        $absensi = TanggalAbsensi::whereBetween('tanggal', [$dari, $sampai])->get();
        $tanggal_array = $absensi->map->only('uuid');
        $guru = Guru::orderBy('nama', 'asc')->get();
        $absensiGuru = AbsensiGuru::whereIn('id_tanggal', $tanggal_array)->get();
        $absen_array = array();
        foreach ($absensiGuru as $item) {
            $absen_array[$item->id_tanggal . "." . $item->id_guru][$item->jenis] = date('H:i', strtotime($item->waktu));
        }
        return View('cetak.absensi.guru.excel', ["tanggal" => $absensi, "absensi" => $absen_array, "guru" => $guru, "dari" => $this->dari, "sampai" => $this->sampai]);
    }
}
