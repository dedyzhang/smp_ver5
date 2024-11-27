<?php

namespace App\Exports;

use App\Models\Formatif;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Ngajar;
use App\Models\PAS;
use App\Models\Rapor;
use App\Models\RaporManual;
use App\Models\RaporTemp;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\Sumatif;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class OlahanSheetExport implements FromView, WithTitle
{
    private $params;
    private $ngajar;

    function __construct($params, $ngajar)
    {
        $this->params = $params;
        $this->ngajar = $ngajar;
    }
    public function view(): View
    {
        $setting = Setting::where('jenis', 'nama_sekolah')->first();
        $kelas = Kelas::findOrFail($this->params);
        $uuid = $this->ngajar->uuid;
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $materi = Materi::with('tupe')->where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
        $rapor_temp = RaporTemp::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
        $raporFinal = Rapor::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
        $raporManual = RaporManual::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
        $materiArray = array();
        $tupeArray = array();

        $count = 0;
        foreach ($materi as $item) {
            array_push($materiArray, array(
                "uuid" => $item->uuid,
                "materi" => $item->materi,
                "jumlahTupe" => $item->tupe
            ));
            foreach ($item->tupe()->get() as $tupe) {
                array_push($tupeArray, array(
                    "uuid" => $tupe->uuid,
                    "id_materi" => $tupe->id_materi,
                    "tupe" => $tupe->tupe
                ));
                $count++;
            }
            $count++;
        }
        $uuidMateri = array();
        foreach ($materiArray as $item) {
            array_push($uuidMateri, $item["uuid"]);
        }
        $formatif = Formatif::whereIn('id_materi', $uuidMateri)->get();
        $formatif_array = array();
        foreach ($formatif as $item) {
            $formatif_array[$item->id_tupe . "." . $item->id_siswa] = array(
                'uuid' => $item->uuid,
                'nilai' => $item->nilai
            );
        }
        $sumatif = Sumatif::whereIn('id_materi', $uuidMateri)->get();
        $sumatif_array = array();
        foreach ($sumatif as $item) {
            $sumatif_array[$item->id_materi . "." . $item->id_siswa] = array(
                'uuid' => $item->uuid,
                'nilai' => $item->nilai
            );
        }
        $pas = PAS::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
        $pas_array = array();

        foreach ($pas as $nilai) {
            $pas_array[$nilai->id_ngajar . "." . $nilai->id_siswa] = array(
                "uuid" => $nilai->uuid,
                "nilai" => $nilai->nilai
            );
        }

        $temp_array = array();
        foreach ($rapor_temp as $rapor) {
            array_push($temp_array, array(
                "id_siswa" => $rapor->id_siswa,
                "jenis" => $rapor->jenis,
                "perubahan" => $rapor->perubahan
            ));
        }

        $manual_array = array();
        foreach ($raporManual as $item) {
            array_push($manual_array, array(
                "id_siswa" => $item->id_siswa,
                "nilai" => $item->nilai,
                "positif" => $item->deskripsi_positif,
                "negatif" => $item->deskripsi_negatif
            ));
        }
        return view('cetak.olahan.excel', [
            'ngajar' => $ngajar,
            'formatif_array' => $formatif_array,
            'sumatif_array' => $sumatif_array,
            'pas_array' => $pas_array,
            'temp_array' => $temp_array,
            'tupeArray' => $tupeArray,
            'materiArray' => $materiArray,
            'semester' => $semester,
            'manual_array' => $manual_array,
            'setting' => $setting,
            'kelas' => $kelas
        ]);
    }
    public function title(): string
    {
        return $this->ngajar->pelajaran->pelajaran_singkat;
    }
}
