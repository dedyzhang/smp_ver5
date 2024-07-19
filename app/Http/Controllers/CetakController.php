<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiGuruExport;
use App\Exports\GuruExport;
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
}
