<?php

namespace App\Exports;

use App\Models\Kelas;
use App\Models\P5Deskripsi;
use App\Models\P5Fasilitator;
use App\Models\P5Nilai;
use App\Models\P5Proyek;
use App\Models\P5ProyekDetail;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProyekExport implements FromView
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
        //Loop Nama Nama Siswa
        $siswa = Siswa::where('id_kelas', $this->params)->get();
        $kelas = Kelas::findOrFail($this->params);
        $proyek = P5Fasilitator::with('proyek')->where('id_kelas', $this->params)->get();
        $id_proyek = $proyek->pluck('id_proyek')->toArray();
        $proyek_detail = P5ProyekDetail::with('proyek', 'dimensi', 'elemen', 'subelemen')->whereIn('id_proyek', $id_proyek)->get();
        $proyek_detail_uuid = $proyek_detail->pluck('uuid')->toArray();
        $array_proyek_detail = array();
        foreach ($proyek_detail as $detail) {
            if (empty($array_proyek_detail[$detail->id_proyek])) {
                $array_proyek_detail[$detail->id_proyek] = array();
                array_push($array_proyek_detail[$detail->id_proyek], array(
                    'id_detail' => $detail->uuid,
                    'dimensi' => $detail->dimensi->dimensi,
                    'capaian' => $detail->subelemen->capaian
                ));
            } else {
                array_push($array_proyek_detail[$detail->id_proyek], array(
                    'id_detail' => $detail->uuid,
                    'dimensi' => $detail->dimensi->dimensi,
                    'capaian' => $detail->subelemen->capaian
                ));
            }
        }
        $nilai_proyek = P5Nilai::whereIn('id_detail', $proyek_detail_uuid)->get();
        $array_nilai = array();
        foreach ($nilai_proyek as $nilai) {
            $array_nilai[$nilai->id_detail . "." . $nilai->id_siswa] = $nilai->nilai;
        }
        $deskripsi_proyek = P5Deskripsi::whereIn('id_proyek', $id_proyek)->get();
        $array_deskripsi = array();
        foreach ($deskripsi_proyek as $nilai) {
            $array_deskripsi[$nilai->id_proyek . "." . $nilai->id_siswa] = $nilai->deskripsi;
        }
        $semester = Semester::first();
        return view('cetak.proyek.excel', ['siswa' => $siswa, 'proyek' => $proyek, 'semester' => $semester, 'kelas' => $kelas, 'detail' => $array_proyek_detail, 'nilai' => $array_nilai, 'deskripsi' => $array_deskripsi]);
    }
}
