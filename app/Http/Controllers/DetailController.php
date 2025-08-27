<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\Formatif;
use App\Models\Materi;
use App\Models\Ngajar;
use App\Models\Orangtua;
use App\Models\P3Poin;
use App\Models\Poin;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Sumatif;
use App\Models\TanggalAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DetailController extends Controller
{
    /**
     * Tampilkan Absensi di halaman informasi
     */
    public function absensi(): View
    {
        if (Auth::user()->access == "orangtua") {
            $orangtua = Orangtua::where('id_login', Auth::user()->uuid)->first();
            $siswa = Siswa::with('kelas')->where('uuid', $orangtua->id_siswa)->first();
        } else {
            $siswa = Siswa::with('kelas')->where('id_login', Auth::user()->uuid)->first();
        }
        $jumlahHari = TanggalAbsensi::where([['ada_siswa', '=', 1], ['semester', '=', 1]])->get();
        if (isset($jumlahHari)) {
            $jumlah = $jumlahHari->count();
        } else {
            $jumlah = 0;
        }
        $tanggalArray = $jumlahHari->pluck('uuid');
        $absensi = AbsensiSiswa::selectRaw('
            COUNT(CASE WHEN absensi = "sakit" THEN 1 ELSE null END) as "sakit",
            COUNT(CASE WHEN absensi = "izin" THEN 1 ELSE null END) as "izin",
            COUNT(CASE WHEN absensi = "alpa" THEN 1 ELSE null END) as "alpa"
        ')->where('id_siswa', $siswa->uuid)->whereIn('id_tanggal', $tanggalArray)->first();
        $absensiSemua = AbsensiSiswa::with('tanggal')->whereIn('id_tanggal', $tanggalArray)->where('id_siswa', $siswa->uuid)->get()->sortBy('tanggal.tanggal');
        return view('detail.absensi.index', compact('siswa', 'absensi', 'jumlah', 'absensiSemua'));
    }
    /**
     * Tampilkan Poin di halaman informasi
     */
    public function poin(): View
    {
        $semester = Semester::first();
        if (Auth::user()->access == "orangtua") {
            $orangtua = Orangtua::where('id_login', Auth::user()->uuid)->first();
            $siswa = Siswa::with('kelas')->where('uuid', $orangtua->id_siswa)->first();
        } else {
            $siswa = Siswa::with('kelas')->where('id_login', Auth::user()->uuid)->first();
        }
        $poin = Poin::with('aturan')->where('id_siswa', $siswa->uuid)->orderBy(Poin::raw("DATE(tanggal)"), 'ASC')->get();
        $sisa = 100;
        foreach ($poin as $item) {
            $item->aturan->jenis == "kurang" ? $sisa -= $item->aturan->poin : $sisa += $item->aturan->poin;
        }
        return view('detail.poin.index', compact('poin', 'sisa', 'semester'));
    }
    /**
     * Tampilkan Penilaian di halaman informasi
     */
    public function nilai(): View
    {
        if (Auth::user()->access == "orangtua") {
            $orangtua = Orangtua::where('id_login', Auth::user()->uuid)->first();
            $siswa = Siswa::with('kelas')->where('uuid', $orangtua->id_siswa)->first();
        } else {
            $siswa = Siswa::with('kelas')->where('id_login', Auth::user()->uuid)->first();
        }
        $ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where('id_kelas', $siswa->id_kelas)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
            ->orderByRaw('length(kelas.kelas), kelas.kelas')
            ->get();
        return view('detail.nilai.index', compact('ngajar'));
    }
    /**
     * Show Nilai Berdasarkan id ngajar
     */
    public function nilaiShow(String $uuid): View
    {
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;

        if (Auth::user()->access == "orangtua") {
            $orangtua = Orangtua::where('id_login', Auth::user()->uuid)->first();
            $siswa = Siswa::with('kelas')->where('id_login', Auth::user()->uuid)->first();
        } else {
            $siswa = Siswa::with('kelas')->where('id_login', Auth::user()->uuid)->first();
        }

        $materi = Materi::with('tupe')->where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
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
        $formatif = Formatif::where('id_siswa', $siswa->uuid)->whereIn('id_materi', $uuidMateri)->get();
        $formatif_array = array();
        foreach ($formatif as $item) {
            $formatif_array[$item->id_tupe] = array(
                'uuid' => $item->uuid,
                'nilai' => $item->nilai
            );
        }
        $sumatif = Sumatif::where('id_siswa', $siswa->uuid)->whereIn('id_materi', $uuidMateri)->get();
        $sumatif_array = array();
        foreach ($sumatif as $item) {
            $sumatif_array[$item->id_materi] = array(
                'uuid' => $item->uuid,
                'nilai' => $item->nilai
            );
        }
        return view('detail.nilai.show', compact('ngajar', 'materi', 'formatif_array', 'sumatif_array', 'materiArray', 'tupeArray'));
    }
    /**
     * Tampilkan P3 di halaman informasi
     */
    public function p3(): View
    {
        $semester = Semester::first();
        if (Auth::user()->access == "orangtua") {
            $orangtua = Orangtua::where('id_login', Auth::user()->uuid)->first();
            $siswa = Siswa::with('kelas')->where('uuid', $orangtua->id_siswa)->first();
        } else {
            $siswa = Siswa::with('kelas')->where('id_login', Auth::user()->uuid)->first();
        }
        $p3 = P3Poin::where('id_siswa', $siswa->uuid)->orderBy(P3Poin::raw("DATE(tanggal)"), 'ASC')->get();

        return view('detail.p3.index', compact('p3', 'semester'));
    }
}
