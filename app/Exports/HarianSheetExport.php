<?php

namespace App\Exports;

use App\Models\Formatif;
use App\Models\Materi;
use App\Models\Ngajar;
use App\Models\Semester;
use App\Models\Sumatif;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class HarianSheetExport implements FromView, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $params;
    private $ngajar;

    function __construct($params, $ngajar)
    {
        $this->params = $params;
        $this->ngajar = $ngajar;
    }

    public function view(): View
    {
        $semester = Semester::first();
        $sem = $semester->semester;
        $materi = Materi::with('tupe')->where([['id_ngajar', '=', $this->ngajar->uuid], ['semester', '=', $sem]])->get();
        $materiArray = array();
        $tupeArray = array();

        $countFormatif = 0;
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
                $countFormatif++;
            }
            $countFormatif++;
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

        $countSumatif = 0;
        foreach ($materi as $item) {
            $countSumatif++;
        }
        $sumatif = Sumatif::whereIn('id_materi', $uuidMateri)->get();
        $sumatif_array = array();
        foreach ($sumatif as $item) {
            $sumatif_array[$item->id_materi . "." . $item->id_siswa] = array(
                'uuid' => $item->uuid,
                'nilai' => $item->nilai
            );
        }
        return view('cetak.harian.excel', [
            'ngajar' => $this->ngajar,
            'materi' => $materi,
            'countFormatif' => $countFormatif,
            'countSumatif' => $countSumatif,
            'formatif_array' => $formatif_array,
            'sumatif_array' => $sumatif_array,
            'materiArray' => $materiArray,
            'tupeArray' => $tupeArray
        ]);
    }
    public function title(): string
    {
        return $this->ngajar->pelajaran->pelajaran_singkat;
    }
}
