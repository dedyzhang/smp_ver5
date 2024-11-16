<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiGuruExport;
use App\Exports\GuruExport;
use App\Exports\HarianExport;
use App\Exports\PasExport;
use App\Exports\RaporExport;
use App\Exports\SiswaExport;
use App\Models\Kelas;
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
}
