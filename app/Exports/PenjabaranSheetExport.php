<?php

namespace App\Exports;

use App\Models\JabarInggris;
use App\Models\JabarMandarin;
use App\Models\Kelas;
use App\Models\Ngajar;
use App\Models\Semester;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class PenjabaranSheetExport implements FromView, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $ngajar;
    private $params;

    function __construct($params, $ngajar)
    {
        $this->params = $params;
        $this->ngajar = $ngajar;
    }
    public function view(): View
    {
        $setting = Setting::where('jenis', 'nama_sekolah')->first();
        $kelas = Kelas::findOrFail($this->params);
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($this->ngajar->uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $has_penjabaran = $ngajar->pelajaran->has_penjabaran;

        if ($has_penjabaran == 1) {
            $jabaran = 'inggris';
            $penjabaran = JabarInggris::where([['id_ngajar', '=', $this->ngajar->uuid], ['semester', '=', $sem]])->get();
            $penjabaran_array = array();
            foreach ($penjabaran as $jabar) {
                $penjabaran_array[$jabar->id_ngajar . "." . $jabar->id_siswa] = array(
                    'uuid' => $jabar->uuid,
                    'listening' => $jabar->listening,
                    'speaking' => $jabar->speaking,
                    'writing' => $jabar->writing,
                    'reading' => $jabar->reading,
                    'grammar' => $jabar->grammar,
                    'vocabulary' => $jabar->vocabulary,
                    'singing' => $jabar->singing
                );
            }
        } else if ($has_penjabaran == 2) {
            $jabaran = 'mandarin';
            $penjabaran = JabarMandarin::where([['id_ngajar', '=', $this->ngajar->uuid], ['semester', '=', $sem]])->get();
            $penjabaran_array = array();
            foreach ($penjabaran as $jabar) {
                $penjabaran_array[$jabar->id_ngajar . "." . $jabar->id_siswa] = array(
                    'uuid' => $jabar->uuid,
                    'listening' => $jabar->listening,
                    'speaking' => $jabar->speaking,
                    'writing' => $jabar->writing,
                    'reading' => $jabar->reading,
                    'vocabulary' => $jabar->vocabulary,
                    'singing' => $jabar->singing
                );
            }
        }
        return view('cetak.penjabaran.excel', ['ngajar' => $ngajar, 'penjabaran' => $penjabaran, 'jabaran' => $jabaran, 'penjabaran_array' => $penjabaran_array, 'semester' => $semester, 'setting' => $setting, 'kelas' => $kelas]);
    }
    public function title(): string
    {
        return $this->ngajar->pelajaran_singkat;
    }
}
