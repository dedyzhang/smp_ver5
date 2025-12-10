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
        $semester = Semester::first();
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
        $rapor = Rapor::where('semester', $semester->semester)->whereIn('id_ngajar', $id_ngajar)->get();
        foreach ($rapor as $item) {
            $rapor_array[$item->id_ngajar . "." . $item->id_siswa] = array(
                "nilai" => $item->nilai,
                "positif" => $item->deskripsi_positif,
                "negatif" => $item->deskripsi_negatif
            );
        }
        $siswa = Siswa::where('id_kelas', $this->params)->orderBy('nama', 'ASC')->get();
        $semester = Semester::first();

        $rataRataRapor = array();
        
        foreach($siswa as $sis) {
            $raporSiswa = 0;
            $jumlahNilai = 0;
            foreach($ngajar as $item) {
                if($rapor_array[$item->uuid . '.' . $sis->uuid]['nilai'] != null) {
                    $jumlahNilai++;
                    $raporSiswa += $rapor_array[$item->uuid . '.' . $sis->uuid]['nilai'];
                }
            }
            $rataRata = round($raporSiswa / $jumlahNilai,2);
            array_push($rataRataRapor,array(
                'id_siswa' => $sis->uuid,
                'nilai' => $rataRata,
                'ranking' => 0
            ));
        }
        $ordered_rataRata = $rataRataRapor;
        array_multisort(array_column($ordered_rataRata, 'nilai'), SORT_DESC, $ordered_rataRata);

        // dd($ordered_rataRata);
        foreach($rataRataRapor as $key => $value) {
            foreach($ordered_rataRata as $ordered_key => $ordered_value) {
                if($value['nilai'] === $ordered_value['nilai']) {
                    $rataRataRapor[$key]['ranking'] = ((int) $ordered_key + 1);
                    $key = $ordered_key;
                    break;
                }
            }

        }
        
    return view('cetak.rapor.excel', ['semester' => $semester, 'siswa' => $siswa, 'ngajar' => $ngajar, 'kelas' => $kelas, 'rapor_array' => $rapor_array, 'setting' => $setting,'rata_rata_rapor' => $rataRataRapor]);
    }
}