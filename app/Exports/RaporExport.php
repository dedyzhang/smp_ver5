<?php

namespace App\Exports;

use App\Models\Kelas;
use App\Models\Ngajar;
use App\Models\Rapor;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RaporExport implements FromView
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
        $kelas = Kelas::with('walikelas')->findOrFail($this->params);
        $ngajar = Ngajar::with('guru')->select(['ngajar.*', 'pelajaran', 'pelajaran_singkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->where('id_kelas', $this->params)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')->get();
        $id_ngajar = array();
        foreach ($ngajar as $item) {
            array_push($id_ngajar, $item->uuid);
        }
        $rapor_array = array();
        $rapor = Rapor::whereIn('id_ngajar', $id_ngajar)->get();
        foreach ($rapor as $item) {
            $rapor_array[$item->id_ngajar . "." . $item->id_siswa] = array(
                "nilai" => $item->nilai,
                "positif" => $item->deskripsi_positif,
                "negatif" => $item->deskripsi_negatif
            );
        }
        $siswa = Siswa::where('id_kelas', $this->params)->orderBy('nama', 'ASC')->get();
        $semester = Semester::first();

        return view('cetak.rapor.excel', ['semester' => $semester, 'siswa' => $siswa, 'ngajar' => $ngajar, 'kelas' => $kelas, 'rapor_array' => $rapor_array, 'setting' => $setting]);
    }
}
