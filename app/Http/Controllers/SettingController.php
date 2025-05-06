<?php

namespace App\Http\Controllers;

use App\Models\Aturan;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Nis;
use App\Models\Pelajaran;
use App\Models\Semester;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $semester = Semester::first();
        $nis = Nis::first();
        $aturan = Aturan::where('jenis', 'kurang')->orderBy('kode')->get();
        $setting = Setting::all();
        $guru = Guru::with('users')->orderBy('nama')->get();
        $pelajaran = Pelajaran::all()->sortBy('urutan');
        $kelas = Kelas::groupBy('tingkat')->get();
        return view('setting.index', compact('semester', 'nis', 'aturan', 'setting', 'guru', 'pelajaran', 'kelas'));
    }
    public function updateSemester(Request $request)
    {
        $semester = $request->semester;
        $tp = $request->tp;

        Semester::first()->update([
            'tp' => $tp,
            'semester' => $semester
        ]);
    }
    public function updatenis(Request $request)
    {
        $first_nis = $request->first_nis;
        $second_nis = $request->second_nis;
        $third_nis = $request->third_nis;


        Nis::query()->update([
            'first_nis' => $first_nis,
            'second_nis' => $second_nis,
            'third_nis' => $third_nis
        ]);
    }
    public function setPoinTerlambat(Request $request)
    {
        $poin = $request->poin;
        $settingPoin = Setting::where('jenis', 'poin_terlambat')->first();

        if ($settingPoin !== null) {
            $settingPoin->update([
                'nilai' => $poin
            ]);
        } else {
            Setting::create([
                'jenis' => 'poin_terlambat',
                'nilai' => $poin
            ]);
        }
    }
    public function setWaktuTerlambat(Request $request)
    {
        $waktu = $request->waktu;
        $settingWaktu = Setting::where('jenis', 'waktu_terlambat_siswa')->first();

        if ($settingWaktu !== null) {
            $settingWaktu->update([
                'nilai' => $waktu
            ]);
        } else {
            Setting::create([
                'jenis' => 'waktu_terlambat_siswa',
                'nilai' => $waktu
            ]);
        }
    }
    public function setIdentitasSekolah(Request $request)
    {
        $sekolah = $request->sekolah;
        $settingSekolah = Setting::where('jenis', 'nama_sekolah')->first();

        if ($settingSekolah !== null) {
            $settingSekolah->update([
                'nilai' => $sekolah
            ]);
        } else {
            Setting::create([
                'jenis' => 'nama_sekolah',
                'nilai' => $sekolah
            ]);
        }

        $kepala = $request->kepala;
        $settingKepala = Setting::where('jenis', 'kepala_sekolah')->first();

        if ($settingKepala !== null) {
            $settingKepala->update([
                'nilai' => $kepala
            ]);
        } else {
            Setting::create([
                'jenis' => 'kepala_sekolah',
                'nilai' => $kepala
            ]);
        }
    }

    /**
     * Rapor - mata pelajaran yang tampil
     */
    public function setMapelRapor(Request $request)
    {
        $pelajaran = $request->pelajaran;
        $pelajaran = implode(',', $pelajaran);
        $settingPelajaran = Setting::where('jenis', 'pelajaran_rapor')->first();

        if ($settingPelajaran !== null) {
            $settingPelajaran->update([
                'nilai' => $pelajaran
            ]);
        } else {
            Setting::create([
                'jenis' => 'pelajaran_rapor',
                'nilai' => $pelajaran
            ]);
        }
    }
    /**
     * Rapor - Tanggal Rapor
     */
    public function setTanggalRapor(Request $request)
    {
        $tanggal = $request->tanggal;
        $settingPelajaran = Setting::where('jenis', 'tanggal_rapor')->first();

        if ($settingPelajaran !== null) {
            $settingPelajaran->update([
                'nilai' => $tanggal
            ]);
        } else {
            Setting::create([
                'jenis' => 'tanggal_rapor',
                'nilai' => $tanggal
            ]);
        }
    }
    /**
     * Rapor - Setting Kop Rapor
     */
    public function setKopRapor(Request $request)
    {
        $kop = $request->kop;
        $settingPelajaran = Setting::where('jenis', 'kop_rapor')->first();

        if ($settingPelajaran !== null) {
            $settingPelajaran->update([
                'nilai' => $kop
            ]);
        } else {
            Setting::create([
                'jenis' => 'kop_rapor',
                'nilai' => $kop
            ]);
        }
    }
    /**
     * Rapor - Setting Kop Rapor
     */
    public function setFaseRapor(Request $request)
    {
        $fase = serialize($request->fase);
        $settingPelajaran = Setting::where('jenis', 'fase_rapor')->first();

        if ($settingPelajaran !== null) {
            $settingPelajaran->update([
                'nilai' => $fase
            ]);
        } else {
            Setting::create([
                'jenis' => 'fase_rapor',
                'nilai' => $fase
            ]);
        }
    }
    /**
     * Absensi - Atur Cara Absensi
     */
    public function setCaraAbsensi(Request $request)
    {
        $settingPelajaran = Setting::where('jenis', 'absensi_guru')->first();
        $pilihan = $request->method;
        if ($settingPelajaran !== null) {
            $settingPelajaran->update([
                'nilai' => $pilihan
            ]);
        } else {
            Setting::create([
                'jenis' => 'absensi_guru',
                'nilai' => $pilihan
            ]);
        }
    }
    /**
     * Absensi - Generate Ulang Barcode Absensi
     */
    public function setBarcodeAbsensi()
    {
        $settingPelajaran = Setting::where('jenis', 'absensi_token')->first();
        $datang = fake()->regexify('[A-Za-z0-9]{50}');
        $pulang = fake()->regexify('[A-Za-z0-9]{50}');
        $token = $datang . "|" . $pulang;
        if ($settingPelajaran !== null) {
            $settingPelajaran->update([
                'nilai' => $token
            ]);
        } else {
            Setting::create([
                'jenis' => 'absensi_token',
                'nilai' => $token
            ]);
        }
        return response()->json(['datang' => $datang, 'pulang' => $pulang]);
    }
    public function setAksesHarianWalikelas(Request $request)
    {
        $akses = $request->akses;
        $settingPelajaran = Setting::where('jenis', 'akses_harian_walikelas')->first();
        if ($settingPelajaran !== null) {
            $settingPelajaran->update([
                'nilai' => $akses
            ]);
        } else {
            Setting::create([
                'jenis' => 'akses_harian_walikelas',
                'nilai' => $akses
            ]);
        }
    }
    /**
     * Setting - Penjabaran set Rata-rata
     */
    public function setPenjabaran(Request $request)
    {
        $inggris = $request->inggris;
        $mandarin = $request->mandarin;
        $komputer = $request->komputer;

        $penjabaran = array(
            'inggris' => $inggris,
            'mandarin' => $mandarin,
            'komputer' => $komputer
        );
        $rerataPenjabaran = serialize($penjabaran);

        $settingPelajaran = Setting::where('jenis', 'penjabaran_rata')->first();
        if ($settingPelajaran !== null) {
            $settingPelajaran->update([
                'nilai' => $rerataPenjabaran
            ]);
        } else {
            Setting::create([
                'jenis' => 'penjabaran_rata',
                'nilai' => $rerataPenjabaran
            ]);
        }
    }
    public function setRumusRapor(Request $request)
    {
        $rumus = $request->rumus;
        $settingPelajaran = Setting::where('jenis', 'rumus_rapor')->first();
        if ($settingPelajaran !== null) {
            $settingPelajaran->update([
                'nilai' => $rumus
            ]);
        } else {
            Setting::create([
                'jenis' => 'rumus_rapor',
                'nilai' => $rumus
            ]);
        }
    }
    public function setRentangPenilaianProyek(Request $request)
    {
        $rentang1 = $request->rentang1;
        $rentang2 = $request->rentang2;
        $rentang3 = $request->rentang3;
        $rentang4 = $request->rentang4;

        $singkat1 = $request->singkat1;
        $singkat2 = $request->singkat2;
        $singkat3 = $request->singkat3;
        $singkat4 = $request->singkat4;

        $deskripsi1 = $request->deskripsi1;
        $deskripsi2 = $request->deskripsi2;
        $deskripsi3 = $request->deskripsi3;
        $deskripsi4 = $request->deskripsi4;


        $rentang = array(
            '1' => array(
                'rentang' => $rentang1,
                'singkat' => $singkat1,
                'deskripsi' => $deskripsi1
            ),
            '2' => array(
                'rentang' => $rentang2,
                'singkat' => $singkat2,
                'deskripsi' => $deskripsi2
            ),
            '3' => array(
                'rentang' => $rentang3,
                'singkat' => $singkat3,
                'deskripsi' => $deskripsi3
            ),
            '4' => array(
                'rentang' => $rentang4,
                'singkat' => $singkat4,
                'deskripsi' => $deskripsi4
            )
        );
        $rentangSerialize = serialize($rentang);

        $settingRentang = Setting::where('jenis', 'rentang_penilaian_proyek')->first();
        if ($settingRentang !== null) {
            $settingRentang->update([
                'nilai' => $rentangSerialize
            ]);
        } else {
            Setting::create([
                'jenis' => 'rentang_penilaian_proyek',
                'nilai' => $rentangSerialize
            ]);
        }
    }
    /**
     * Pemilihan Aturan P3 atau pemakaian Poin
     */
    public function setPemilihanAturan(Request $request)
    {
        $aturan = $request->peraturan;
        $settingAturan = Setting::where('jenis', 'jenis_aturan')->first();

        if ($settingAturan !== null) {
            $settingAturan->update([
                'nilai' => $aturan
            ]);
        } else {
            Setting::create([
                'jenis' => 'jenis_aturan',
                'nilai' => $aturan
            ]);
        }
    }
    /**
     * Kelulusan - Set Tanggal Kelulusan
     */
    public function setTanggalKelulusan(Request $request) {
        $kelulusan = $request->kelulusan;
        $rapat = $request->rapat;

        $tanggalArray = array(
            'kelulusan' => $kelulusan,
            'rapat' => $rapat
        );

        $value = serialize($tanggalArray);
        $settingTanggalKelulusan = Setting::where('jenis','tanggal_kelulusan')->first();

        if ($settingTanggalKelulusan !== null) {
            $settingTanggalKelulusan->update([
                'nilai' => $value
            ]);
        } else {
            Setting::create([
                'jenis' => 'tanggal_kelulusan',
                'nilai' => $value
            ]);
        }
    }
    /**
     * Kelulusan - mata pelajaran yang ditampilkan
     */
    public function setMapelkelulusan(Request $request)
    {
        $pelajaran = $request->pelajaran;
        if($pelajaran == "") {
            $settingPelajaran = Setting::where('jenis', 'pelajaran_kelulusan')->first();

            $settingPelajaran->delete();
        } else {
            $pelajaran = implode(',', $pelajaran);
            $settingPelajaran = Setting::where('jenis', 'pelajaran_kelulusan')->first();

            if ($settingPelajaran !== null) {
                $settingPelajaran->update([
                    'nilai' => $pelajaran
                ]);
            } else {
                Setting::create([
                    'jenis' => 'pelajaran_kelulusan',
                    'nilai' => $pelajaran
                ]);
            }
        }
    }
}
