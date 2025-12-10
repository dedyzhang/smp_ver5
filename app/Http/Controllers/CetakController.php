<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiGuruExport;
use App\Exports\GuruExport;
use App\Exports\HarianExport;
use App\Exports\OlahanExport;
use App\Exports\PasExport;
use App\Exports\PenjabaranExport;
use App\Exports\ProyekExport;
use App\Exports\PtsExport;
use App\Exports\RaporExport;
use App\Exports\SiswaExport;
use App\Models\Guru;
use App\Models\Kelas;
use Google\Service\CertificateAuthorityService\PublicKey;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class CetakController extends Controller
{
    /**
     * Siswa - Tampilan Halaman Cetak
     */
    public function siswa(): View
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('kelas')->get();
        return View('cetak.siswa.index', compact('kelas'));
    }
    /**
     * Siswa - Cetak Data Siswa
     */
    public function cetakSiswa(String $params)
    {
        if ($params == 'semua') {
            $namaFile = 'Data Siswa Semua Kelas.xlsx';
        } else {
            $kelas = Kelas::findOrFail($params);
            $namaFile = 'Data Siswa Kelas ' . $kelas->tingkat . $kelas->kelas . '.xlsx';
        }
        return Excel::download(new SiswaExport($params), $namaFile);
    }

    /**
     * Guru - Cetak Data Guru
     */
    public function guru()
    {
        return Excel::download(new GuruExport, 'Data Guru.xlsx');
    }

    /**
     * Cetak Absensi Guru View
     */
    public function absensiGuru(): View
    {
        return View('cetak.absensi.guru.index');
    }

    /**
     * Cetak Absensi Guru
     */
    public function cetakAbsensiGuru(Request $request)
    {
        $dari = $request->dari;
        $sampai = $request->sampai;

        return Excel::download(new AbsensiGuruExport($dari, $sampai), 'Absensi Guru.xlsx');
    }

    /**
     * Cetak Rapor
     */
    public function rapor(): View
    {
        $kelas = Kelas::get()->sortBy('kelas')->sortBy('tingkat');
        return view('cetak.rapor.index', compact('kelas'));
    }
    //Cetak Rapor - Proses
    public function cetakRapor(String $params)
    {
        $kelas = Kelas::findOrFail($params);
        $namaFile = 'Nilai Rapor Kelas ' . $kelas->tingkat . $kelas->kelas . '.xlsx';
        return Excel::download(new RaporExport($params), $namaFile);
    }
    /**
     * Cetak Nilai Penilaian Akhir Semester
     */
    public function pas(): View
    {
        $kelas = Kelas::get()->sortBy('kelas')->sortBy('tingkat');
        return view('cetak.pas.index', compact('kelas'));
    }
    //Cetak Nilai Penilaian Akhir Semester - Proses
    public function cetakPas(String $params)
    {
        $kelas = Kelas::findOrFail($params);
        $namaFile = 'Nilai SAS Kelas ' . $kelas->tingkat . $kelas->kelas . '.xlsx';
        return Excel::download(new PasExport($params), $namaFile);
    }
    /**
     * Cetak Nilai Penilaian Akhir Semester
     */
    public function pts(): View
    {
        $kelas = Kelas::get()->sortBy('kelas')->sortBy('tingkat');
        return view('cetak.pts.index', compact('kelas'));
    }
    //Cetak Nilai Penilaian Akhir Semester - Proses
    public function cetakPts(String $params)
    {
        $kelas = Kelas::findOrFail($params);
        $namaFile = 'Nilai PTS Kelas ' . $kelas->tingkat . $kelas->kelas . '.xlsx';
        return Excel::download(new PtsExport($params), $namaFile);
    }
    /**
     * Cetak Nilai Harian Per Mapel
     */
    public function harian(): View
    {
        $kelas = Kelas::get()->sortBy('kelas')->sortBy('tingkat');
        return view('cetak.harian.index', compact('kelas'));
    }
    //Cetak Nilai Harian Per Mapel - Proses
    public function cetakHarian(String $params)
    {
        $kelas = Kelas::findOrFail($params);
        $namaFile = 'Nilai Harian Kelas ' . $kelas->tingkat . $kelas->kelas . '.xlsx';
        return Excel::download(new HarianExport($params), $namaFile);
    }
    /**
     * Cetak Nilai Harian Per Mapel
     */
    public function olahan(): View
    {
        $kelas = Kelas::get()->sortBy('kelas')->sortBy('tingkat');
        return view('cetak.olahan.index', compact('kelas'));
    }
    //Cetak Nilai Harian Per Mapel - Proses
    public function cetakOlahan(String $params)
    {
        $kelas = Kelas::findOrFail($params);
        $namaFile = 'Nilai Olahan Kelas ' . $kelas->tingkat . $kelas->kelas . '.xlsx';
        return Excel::download(new OlahanExport($params), $namaFile);
    }
    /**
     * Cetak Penjabaran Per Mapel
     */
    public function penjabaran(): View
    {
        $kelas = Kelas::get()->sortBy('kelas')->sortBy('tingkat');
        return view('cetak.penjabaran.index', compact('kelas'));
    }
    //Cetak Nilai Harian Per Mapel - Proses
    public function cetakPenjabaran(String $params)
    {
        $kelas = Kelas::findOrFail($params);
        $namaFile = 'Nilai Penjabaran Kelas ' . $kelas->tingkat . $kelas->kelas . '.xlsx';
        return Excel::download(new PenjabaranExport($params), $namaFile);
    }
    /**
     * Cetak Nilai Proyek
     */
    public function proyek(): View
    {
        $kelas = Kelas::get()->sortBy('kelas')->sortBy('tingkat');
        return view('cetak.proyek.index', compact('kelas'));
    }
    /**
     * Cetak Excel Proyek
     */
    public function cetakProyek(String $params)
    {
        $kelas = Kelas::findOrFail($params);
        $namaFile = 'Nilai Proyek Kelas ' . $kelas->tingkat . $kelas->kelas . '.xlsx';
        return Excel::download(new ProyekExport($params), $namaFile);
    }
    /**
     * Cetak Agenda Guru
     */
    public function agendaIndex(): View {
        $kelas = Kelas::get()->sortBy('kelas')->sortBy('tingkat');
        $guru = Guru::get()->sortBy('nama');
        return view('cetak.agenda.index',compact('kelas','guru'));
    }
}
