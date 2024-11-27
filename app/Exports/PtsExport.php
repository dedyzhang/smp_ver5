<?php

namespace App\Exports;

use App\Models\Kelas;
use App\Models\Ngajar;
use App\Models\PTS;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PtsExport implements FromView
{
    /**
     * @return \Illuminate\Support\View
     */
    protected $params;
    function __construct($params)
    {
        $this->params = $params;
    }
    public function view(): View
    {
        $setting = Setting::where('jenis', 'nama_sekolah')->first();
        $kelas = Kelas::findOrFail($this->params);
        $ngajar = Ngajar::with('guru')->select(['ngajar.*', 'pelajaran', 'pelajaran_singkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->where('id_kelas', $this->params)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')->get();
        $id_ngajar = array();
        foreach ($ngajar as $item) {
            array_push($id_ngajar, $item->uuid);
        }
        $pts_array = array();
        $pts = PTS::whereIn('id_ngajar', $id_ngajar)->get();
        foreach ($pts as $item) {
            $pts_array[$item->id_ngajar . "." . $item->id_siswa] = $item->nilai;
        }
        $siswa = Siswa::where('id_kelas', $this->params)->orderBy('nama', 'ASC')->get();
        $semester = Semester::first();

        return view('cetak.pts.excel', ['semester' => $semester, 'siswa' => $siswa, 'ngajar' => $ngajar, 'kelas' => $kelas, 'pts_array' => $pts_array, 'setting' => $setting]);
    }
}
