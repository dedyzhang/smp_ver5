<?php

namespace App\Exports;

use App\Models\Kelas;
use App\Models\Ngajar;
use App\Models\PAS;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PasExport implements FromView
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
        $semester = Semester::first();
        $kelas = Kelas::findOrFail($this->params);
        $ngajar = Ngajar::with('guru')->select(['ngajar.*', 'pelajaran', 'pelajaran_singkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->where('id_kelas', $this->params)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')->get();
        $id_ngajar = array();
        foreach ($ngajar as $item) {
            array_push($id_ngajar, $item->uuid);
        }
        $pas_array = array();
        $pas = PAS::where('semester', $semester->semester)->whereIn('id_ngajar', $id_ngajar)->get();
        foreach ($pas as $item) {
            $pas_array[$item->id_ngajar . "." . $item->id_siswa] = $item->nilai;
        }
        $siswa = Siswa::where('id_kelas', $this->params)->orderBy('nama', 'ASC')->get();
        $semester = Semester::first();

        return view('cetak.pas.excel', ['semester' => $semester, 'siswa' => $siswa, 'ngajar' => $ngajar, 'kelas' => $kelas, 'pas_array' => $pas_array, 'setting' => $setting]);
    }
}
