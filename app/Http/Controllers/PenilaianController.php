<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\Ekskul;
use App\Models\EkskulSiswa;
use App\Models\Formatif;
use App\Models\Guru;
use App\Models\JabarInggris;
use App\Models\JabarKomputer;
use App\Models\JabarMandarin;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Ngajar;
use App\Models\P5Deskripsi;
use App\Models\P5Dimensi;
use App\Models\P5Elemen;
use App\Models\P5Fasilitator;
use App\Models\P5Nilai;
use App\Models\P5Proyek;
use App\Models\P5ProyekDetail;
use App\Models\P5Subelemen;
use App\Models\PAS;
use App\Models\Pelajaran;
use App\Models\PerangkatAjar;
use App\Models\PerangkatAjarGuru;
use App\Models\PTS;
use App\Models\Rapor;
use App\Models\RaporManual;
use App\Models\RaporTemp;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\Sumatif;
use App\Models\TanggalAbsensi;
use App\Models\Tupe;
use App\Models\Walikelas;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Mavinoo\Batch\BatchFacade as Batch;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use PhpParser\Node\Expr\Cast\String_;

class PenilaianController extends Controller
{
    /**
     * Penilain Index
     */
    public function index(): View
    {
        $pelajaran = Pelajaran::get()->sortBy('urutan');

        return view('penilaian.index', compact('pelajaran'));
    }
    /**
     * Penilaian Index Get Pelajaran
     */
    public function get(String $uuid)
    {
        $ngajar = Ngajar::with('kelas', 'pelajaran', 'guru')->where('id_pelajaran', $uuid)->get();
        return response()->json(["success" => true, "data" => $ngajar]);
    }
    /**
     * Penilaian PTS Show All Index
     */
    public function ptsIndexAll(): View
    {
        $kelas = Kelas::orderBy('tingkat', 'ASC')->orderBy('kelas', 'ASC')->get();

        return view('penilaian.pts', compact('kelas'));
    }
    /**
     * Penilaian PTS Show All
     */
    public function ptsShowAll(String $id): View
    {
        $kelas = Kelas::findOrFail($id);
        $ngajar = Ngajar::with('guru')->select(['ngajar.*', 'pelajaran', 'pelajaran_singkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->where('id_kelas', $id)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')->get();
        $id_ngajar = array();
        foreach ($ngajar as $item) {
            array_push($id_ngajar, $item->uuid);
        }
        $pts_array = array();
        $semester = Semester::first();
        $sem = $semester->semester;
        $pts = PTS::whereIn('id_ngajar', $id_ngajar)->where('semester', $sem)->get();
        foreach ($pts as $item) {
            $pts_array[$item->id_ngajar . "." . $item->id_siswa] = $item->nilai;
        }
        $siswa = Siswa::where('id_kelas', $id)->orderBy('nama', 'ASC')->get();
        return view('penilaian.pts.all', compact('ngajar', 'kelas', 'siswa', 'pts_array'));
    }
    /**
     * Penilaian PAS Show All Index
     */
    public function pasIndexAll(): View
    {
        $kelas = Kelas::orderBy('tingkat', 'ASC')->orderBy('kelas', 'ASC')->get();

        return view('penilaian.pas', compact('kelas'));
    }
    /**
     * Penilaian PAS Show All
     */
    public function pasShowAll(String $id): View
    {
        $kelas = Kelas::findOrFail($id);
        $ngajar = Ngajar::with('guru')->select(['ngajar.*', 'pelajaran', 'pelajaran_singkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->where('id_kelas', $id)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')->get();
        $id_ngajar = array();
        foreach ($ngajar as $item) {
            array_push($id_ngajar, $item->uuid);
        }
        $pas_array = array();
        $semester = Semester::first();
        $sem = $semester->semester;
        $pas = PAS::whereIn('id_ngajar', $id_ngajar)->where('semester', $sem)->get();
        foreach ($pas as $item) {
            $pas_array[$item->id_ngajar . "." . $item->id_siswa] = $item->nilai;
        }
        $siswa = Siswa::where('id_kelas', $id)->orderBy('nama', 'ASC')->get();
        return view('penilaian.pas.all', compact('ngajar', 'kelas', 'siswa', 'pas_array'));
    }
    /**
     * Penilaian Rapor Show All Index
     */
    public function raporIndexAll(): View
    {
        $kelas = Kelas::orderBy('tingkat', 'ASC')->orderBy('kelas', 'ASC')->get();

        return view('penilaian.rapor', compact('kelas'));
    }
    /**
     * Penilaian rapor Show All
     */
    public function raporShowAll(String $id): View
    {
        $kelas = Kelas::findOrFail($id);
        $ngajar = Ngajar::with('guru')->select(['ngajar.*', 'pelajaran', 'pelajaran_singkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->where('id_kelas', $id)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')->get();
        $id_ngajar = array();
        foreach ($ngajar as $item) {
            array_push($id_ngajar, $item->uuid);
        }
        $rapor_array = array();
        $semester = Semester::first();
        $sem = $semester->semester;
        $rapor = Rapor::whereIn('id_ngajar', $id_ngajar)->where('semester', $sem)->get();
        foreach ($rapor as $item) {
            $rapor_array[$item->id_ngajar . "." . $item->id_siswa] = array(
                "nilai" => $item->nilai,
                "positif" => $item->deskripsi_positif,
                "negatif" => $item->deskripsi_negatif
            );
        }
        $siswa = Siswa::where('id_kelas', $id)->orderBy('nama', 'ASC')->get();
        return view('penilaian.rapor.all', compact('ngajar', 'kelas', 'siswa', 'rapor_array'));
    }
    /**
     * Tampilan Rapor Untuk Dicetak
     */
    public function raporIndividu(String $uuid)
    {
        $siswa = Siswa::with('kelas')->findOrFail($uuid);
        $setting = Setting::all();
        $semester = Semester::first();
        $walikelas = Walikelas::with('Guru')->where('id_kelas', $siswa->kelas->uuid)->first();

        $ngajar = Ngajar::with('pelajaran')->where('id_kelas', $siswa->kelas->uuid)->get()->sortBy('pelajaran.urutan', SORT_NATURAL);
        $raporSiswa = Rapor::where([['id_siswa', '=', $uuid], ['semester', '=', $semester->semester]])->get();
        $ekskul = Ekskul::all()->sortBy('urutan', SORT_NATURAL);
        $ekskulSiswa = EkskulSiswa::with('ekskul')->where([['id_siswa', '=', $uuid], ['semester', '=', $semester->semester]])->get();

        $jumlahHari = TanggalAbsensi::where([['ada_siswa', '=', 1], ['semester', '=', 1]])->get();
        $tanggalArray = $jumlahHari->pluck('uuid');
        $absensi = AbsensiSiswa::selectRaw('
            COUNT(CASE WHEN absensi = "sakit" THEN 1 ELSE null END) as "sakit",
            COUNT(CASE WHEN absensi = "izin" THEN 1 ELSE null END) as "izin",
            COUNT(CASE WHEN absensi = "alpa" THEN 1 ELSE null END) as "alpa"
        ')->where('id_siswa', $uuid)->whereIn('id_tanggal', $tanggalArray)->first();

        $pInggris = $ngajar->first(function ($elem) {
            return $elem->pelajaran->has_penjabaran == 1;
        });
        $jabarInggris = JabarInggris::where([['id_ngajar', '=', $pInggris->uuid], ['id_siswa', '=', $siswa->uuid], ['semester', '=', $semester->semester]])->first();

        $pMandarin = $ngajar->first(function ($elem) {
            return $elem->pelajaran->has_penjabaran == 2;
        });
        $jabarMandarin = JabarMandarin::where([['id_ngajar', '=', $pMandarin->uuid], ['id_siswa', '=', $siswa->uuid], ['semester', '=', $semester->semester]])->first();

        $pKomputer = $ngajar->first(function ($elem) {
            return $elem->pelajaran->has_penjabaran == 3;
        });
        if ($pKomputer) {
            $jabarKomputer = JabarKomputer::where([['id_ngajar', '=', $pKomputer->uuid], ['id_siswa', '=', $siswa->uuid], ['semester', '=', $semester->semester]])->first();
        } else {
            $jabarKomputer = array();
        }

        $kepalaSekolah = $setting->first(function ($elem) {
            return $elem->jenis == 'kepala_sekolah';
        });

        if ($kepalaSekolah) {
            $kepala_sekolah = Guru::findOrFail($kepalaSekolah->nilai);
        } else {
            $kepala_sekolah = "";
        }

        $tanggal_rapor = $setting->first(function ($item) {
            return $item->jenis == 'tanggal_rapor';
        });
        if ($tanggal_rapor != null) {
            $tanggal = Carbon::parse($tanggal_rapor->nilai)->isoFormat('D MMMM Y');
        } else {
            $tanggal = "";
        }
        return view('walikelas.rapor.show', compact('siswa', 'semester', 'setting', 'ngajar', 'raporSiswa', 'ekskulSiswa', 'ekskul', 'absensi', 'walikelas', 'kepala_sekolah', 'jabarInggris', 'jabarMandarin', 'jabarKomputer', 'tanggal'));
    }


    /**
     * KKTP - Show Index
     */
    public function kktpIndex(): View
    {
        $id = Auth::user()->uuid;
        $guru = Guru::where('id_login', $id)->first();
        $ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where('id_guru', $guru->uuid)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
            ->orderByRaw('length(kelas.kelas), kelas.kelas')
            ->get();
        return view('penilaian.kktp.index', compact('ngajar'));
    }
    /**
     * KKTP - Edit KKTP
     */
    public function kktpEdit(Request $request)
    {
        $kktp = $request->kktp;

        Batch::update(new Ngajar, $kktp, 'uuid');
    }
    /**
     * Materi - Show Index
     */
    public function materiIndex(): View
    {
        $id = Auth::user()->uuid;
        $guru = Guru::where('id_login', $id)->first();
        $ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where('id_guru', $guru->uuid)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
            ->orderByRaw('length(kelas.kelas), kelas.kelas')
            ->get();
        return view("penilaian.materi.index", compact('ngajar'));
    }
    /**
     * Materi - Lihat isi dari Materi
     */
    public function materiShow(String $uuid): View
    {
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $materi = Materi::with('tupe')->where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
        $listNgajar = Ngajar::with('pelajaran', 'kelas')->where([
            ['id_pelajaran', '=', $ngajar->id_pelajaran],
            ['id_guru', '=', $ngajar->id_guru]
        ])->get();
        return view("penilaian.materi.show", compact('ngajar', 'materi', 'listNgajar'));
    }
    /**
     * Materi - Tambah Materi
     */
    public function materiCreate(Request $request, String $uuid)
    {
        $smt = Semester::first();
        $ngajar = Ngajar::with('siswa')->find($uuid);
        $semester = $smt->semester;
        if ($ngajar->siswa->count() === 0) {
            return response()->json(["success" => false, "message" => 'Data Ngajar belum memiliki siswa']);
        } else {
            $materi = Materi::create([
                "id_ngajar" => $uuid,
                "materi" => $request->materi,
                "tupe" => $request->tupe,
                "semester" => $semester,
                "show" => 0,
            ]);
            $tupe_array = array();
            for ($i = 1; $i <= $request->tupe; $i++) {
                array_push($tupe_array, array(
                    "id_materi" => $materi->uuid,
                    "show" => 0,
                ));
            }
            $tupe = Tupe::upsert($tupe_array, ['uuid']);
            return response()->json(["success" => true]);
        }
    }
    /**
     * Materi - Edit Materi
     */
    public function materiUpdate(Request $request, String $uuid)
    {
        $materi = Materi::findorFail($uuid);

        $materi->update([
            "materi" => $request->materi
        ]);

        return response()->json(["success" => true]);
    }
    /**
     * Materi - Delete Materi
     */
    public function materiDelete(String $uuid)
    {
        $formatif = Formatif::where('id_materi', $uuid)->delete();
        $sumatif = Sumatif::where('id_materi', $uuid)->delete();
        $tupe = Tupe::where('id_materi', $uuid)->delete();
        $materi = Materi::findOrFail($uuid)->delete();

        return response()->json(["success" => true]);
    }
    /**
     * Materi - Aktifkan Materi dan tambahkan sumatif
     */
    public function materiAktifkan(String $uuid)
    {
        $materi = Materi::findOrFail($uuid);
        $ngajar = Ngajar::with('siswa')->findOrFail($materi->id_ngajar);
        $array_insert = array();
        foreach ($ngajar->siswa as $siswa) {
            array_push($array_insert, array(
                "id_materi" => $uuid,
                "id_siswa" => $siswa->uuid,
                "nilai" => 0
            ));
        }
        $sumatif = Sumatif::upsert($array_insert, ['uuid'], ['id_materi', 'id_siswa', 'nilai']);
        $materi->update([
            "show" => 1
        ]);

        return response()->json(["success" => true]);
    }
    /**
     * Materi - Nonaktifkan Materi dan hapus sumatif
     */
    public function materiNonaktifkan(String $uuid)
    {
        $materi = Materi::findOrFail($uuid);
        $sumatif = Sumatif::where('id_materi', $uuid)->delete();
        $formatif = Formatif::where('id_materi', $uuid)->delete();
        $materi->update([
            "show" => 0
        ]);

        return response()->json(["success" => true]);
    }

    /**
     * Materi - Tambah Tujuan Pembelajaran
     */
    public function materiCreateTupe(Request $request, String $uuid)
    {
        $tupe = Tupe::create([
            "id_materi" => $uuid,
            "show" => 0,
        ]);
        $newTupe = $request->tupe + 1;
        $materi = Materi::findOrFail($uuid)->update([
            "tupe" => $newTupe
        ]);
        return response()->json(["success" => true]);
    }
    /**
     * Materi - Update Tujuan Pembelajaran
     */
    public function materiUpdateTupe(Request $request, String $uuid)
    {
        $tupe = Tupe::findOrFail($uuid)->update([
            'tupe' => $request->tupe
        ]);

        return response()->json(['success' => true]);
    }
    /**
     * Materi - Delete Tujuan Pembelajaran
     */
    public function materiDeleteTupe(String $uuid)
    {
        $tupe = Tupe::findOrFail($uuid);
        $materi = Materi::findOrFail($tupe->id_materi);
        $jumlahTupe = $materi->tupe;
        if ($jumlahTupe == 1) {
            return response()->json(["success" => false, "message" => "1 Materi Harus mempunyai minimal 1 tujuan pembelajaran"]);
        } else {
            $jumlahTupe = $jumlahTupe - 1;
            $materi->update([
                "tupe" => $jumlahTupe
            ]);
            $tupe->delete();
            $formatif = Formatif::where('id_tupe', $uuid)->delete();

            return response()->json(["success" => true]);
        }
    }
    /**
     * Materi - Update Nilai Formatif pada Materi
     */
    public function materiTambahkanFormatif(Request $request)
    {
        $id_materi = $request->idMateri;
        $id_tupe = $request->idTupe;
        $id_ngajar = $request->idNgajar;

        $ngajar = Ngajar::with('siswa')->findOrFail($id_ngajar);
        $array_insert = array();
        foreach ($ngajar->siswa as $siswa) {
            array_push($array_insert, array(
                "id_materi" => $id_materi,
                "id_tupe" => $id_tupe,
                "id_siswa" => $siswa->uuid,
                'nilai' => 0
            ));
        }
        $formatif = Formatif::upsert($array_insert, ['uuid'], ['id_materi', 'id_tupe', 'id_siswa', 'nilai']);

        $tupe = Tupe::findOrFail($id_tupe)->update([
            'show' => 1
        ]);

        return response()->json(['success' => true]);
    }
    /**
     * Materi - Hapus Tujuan Pembelajaran pada halaman materi
     */
    public function materiHapusFormatif(Request $request)
    {
        $id_materi = $request->idMateri;
        $id_tupe = $request->idTupe;

        $formatif = Formatif::where([
            ['id_materi', '=', $id_materi],
            ['id_tupe', '=', $id_tupe]
        ]);

        $formatif->delete();

        $tupe = Tupe::findOrFail($id_tupe)->update([
            'show' => 0
        ]);
        return response()->json(['success' => true]);
    }
    /**
     * Materi - Duplikat Materi Ke Kelas Lain
     */
    public function materiDuplikatMateri(Request $request)
    {
        $smt = Semester::first();
        $ngajar = Ngajar::with('siswa')->find($request->ngajar);
        $materi = Materi::with('Tupe')->findOrFail($request->materi);
        $semester = $smt->semester;
        if ($ngajar->siswa->count() === 0) {
            return response()->json(["success" => false, "message" => 'Data Ngajar belum memiliki siswa']);
        } else {
            $materi_insert = Materi::create([
                "id_ngajar" => $ngajar->uuid,
                "materi" => $materi->materi,
                "tupe" => $materi->tupe,
                "show" => 0,
                "semester" => $semester
            ]);
            $tupe_array = array();
            foreach ($materi->Tupe as $tupe) {
                array_push($tupe_array, array(
                    "id_materi" => $materi_insert->uuid,
                    'tupe' => $tupe->tupe,
                    "show" => 0,
                ));
            }
            $tupe = Tupe::upsert($tupe_array, ['uuid']);

            return response()->json(["success" => true]);
        }
    }
    /**
     * Formatif - Show Index Formatif
     */
    public function formatifIndex(): View
    {
        $id = Auth::user()->uuid;
        $guru = Guru::where('id_login', $id)->first();
        $ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where('id_guru', $guru->uuid)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
            ->orderByRaw('length(kelas.kelas), kelas.kelas')
            ->get();
        return view("penilaian.formatif.index", compact('ngajar'));
    }
    /**
     * Formatif - Menampilkan isi dari penilaian formatif Per Kelas
     */
    public function formatifShow(String $uuid): View
    {
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
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
                    "tupe" => $tupe->tupe,
                    "show" => $tupe->show
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
        return view("penilaian.formatif.show", compact('ngajar', 'materi', 'count', 'formatif_array', 'materiArray', 'tupeArray'));
    }
    /**
     * Formatif - Simpan Nilai
     */
    public function formatifEdit(Request $request)
    {
        $nilai = $request->nilai;
        Batch::update(new Formatif, $nilai, 'uuid');
    }
    /**
     * Formatif - Tambah Nilai Yang Ketinggalan
     */
    public function formatifTambah(Request $request)
    {
        Formatif::create([
            'id_materi' => $request->materi,
            'id_tupe' => $request->tupe,
            'id_siswa' => $request->siswa,
            'nilai' => 0
        ]);

        return response()->json(['success' => true]);
    }
    /**
     * Sumatif = Show Index Sumatif
     */
    public function sumatifIndex(): View
    {
        $id = Auth::user()->uuid;
        $guru = Guru::where('id_login', $id)->first();
        $ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where('id_guru', $guru->uuid)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
            ->orderByRaw('length(kelas.kelas), kelas.kelas')
            ->get();
        return view("penilaian.sumatif.index", compact('ngajar'));
    }
    /**
     * Sumatif - Menampilkan isi dari penilaian Sumatif Per Kelas
     */
    public function sumatifShow(String $uuid): View
    {
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $materi = Materi::with('tupe')->where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
        $materiArray = array();
        $tupeArray = array();

        $count = 0;
        foreach ($materi as $item) {
            array_push($materiArray, array(
                "uuid" => $item->uuid,
                "materi" => $item->materi,
                "jumlahTupe" => $item->tupe,
                "show" => $item->show
            ));
            $count++;
        }
        $uuidMateri = array();
        foreach ($materiArray as $item) {
            array_push($uuidMateri, $item["uuid"]);
        }
        $sumatif = Sumatif::whereIn('id_materi', $uuidMateri)->get();
        $sumatif_array = array();
        foreach ($sumatif as $item) {
            $sumatif_array[$item->id_materi . "." . $item->id_siswa] = array(
                'uuid' => $item->uuid,
                'nilai' => $item->nilai
            );
        }
        return view("penilaian.sumatif.show", compact('ngajar', 'materi', 'count', 'sumatif_array', 'materiArray'));
    }
    /**
     * Sumatif - Simpan Nilai
     */
    public function sumatifEdit(Request $request)
    {
        $nilai = $request->nilai;

        Batch::update(new Sumatif, $nilai, 'uuid');
    }
    public function sumatifTambah(Request $request)
    {
        Sumatif::create([
            'id_materi' => $request->materi,
            'id_siswa' => $request->siswa,
            'nilai' => 0
        ]);

        return response()->json(['success' => true]);
    }
    /**
     * PTS - Show Index PTS
     */
    public function ptsIndex(): View
    {
        $id = Auth::user()->uuid;
        $guru = Guru::where('id_login', $id)->first();
        $ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where('id_guru', $guru->uuid)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
            ->orderByRaw('length(kelas.kelas), kelas.kelas')
            ->get();
        return view("penilaian.pts.index", compact('ngajar'));
    }
    /**
     * PTS - Menampilkan isi dari penilaian pts per kelas
     */
    public function ptsShow(String $uuid)
    {
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $pts = PTS::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
        $pts_array = array();

        foreach ($pts as $nilai) {
            $pts_array[$nilai->id_ngajar . "." . $nilai->id_siswa] = array(
                "uuid" => $nilai->uuid,
                "nilai" => $nilai->nilai
            );
        }
        return View("penilaian.pts.show", compact('ngajar', 'pts_array', 'pts'));
    }
    /**
     * PTS - PTS Store
     */
    public function ptsStore(String $uuid)
    {
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $pts = PTS::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();

        if ($pts->count() === 0) {
            $smt = Semester::first();
            $semester = $smt->semester;

            $nilai_array = array();
            foreach ($ngajar->siswa as $siswa) {
                array_push($nilai_array, array(
                    'id_ngajar' => $ngajar->uuid,
                    'id_siswa' => $siswa->uuid,
                    'nilai' => 0,
                    'semester' => $semester
                ));
            }
            PTS::upsert($nilai_array, ['uuid'], ['id_ngajar', 'id_siswa', 'nilai', 'semester']);
        }
    }
    /**
     * PTS - PTS Destroy
     */
    public function ptsDestroy(String $uuid)
    {
        $semester = Semester::first();
        $sem = $semester->semester;
        $pts = PTS::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]]);

        $pts->delete();
    }
    /**
     * PTS - Simpan Nilai PTS
     */
    public function ptsEdit(Request $request)
    {
        $nilai = $request->nilai;

        Batch::update(new PTS, $nilai, 'uuid');
    }
    /**
     * PAS - Show Index PAS
     */
    public function pasIndex(): View
    {
        $id = Auth::user()->uuid;
        $guru = Guru::where('id_login', $id)->first();
        $ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where('id_guru', $guru->uuid)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
            ->orderByRaw('length(kelas.kelas), kelas.kelas')
            ->get();
        return view("penilaian.pas.index", compact('ngajar'));
    }
    /**
     * PAS - Menampilkan isi dari penilaian pas per kelas
     */
    public function pasShow(String $uuid)
    {
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $pas = PAS::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
        $pas_array = array();

        foreach ($pas as $nilai) {
            $pas_array[$nilai->id_ngajar . "." . $nilai->id_siswa] = array(
                "uuid" => $nilai->uuid,
                "nilai" => $nilai->nilai
            );
        }
        return View("penilaian.pas.show", compact('ngajar', 'pas_array', 'pas'));
    }
    /**
     * PAS - PAS Store
     */
    public function pasStore(String $uuid)
    {
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $pas = PAS::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();

        if ($pas->count() === 0) {
            $smt = Semester::first();
            $semester = $smt->semester;

            $nilai_array = array();
            foreach ($ngajar->siswa as $siswa) {
                array_push($nilai_array, array(
                    'id_ngajar' => $ngajar->uuid,
                    'id_siswa' => $siswa->uuid,
                    'nilai' => 0,
                    'semester' => $semester
                ));
            }
            PAS::upsert($nilai_array, ['uuid'], ['id_ngajar', 'id_siswa', 'nilai', 'semester']);
        }
    }
    /**
     * PAS - PAS Destroy
     */
    public function pasDestroy(String $uuid)
    {
        $semester = Semester::first();
        $sem = $semester->semester;
        $pas = PAS::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]]);

        $pas->delete();
    }
    /**
     * PAS - Simpan Nilai PAS
     */
    public function pasEdit(Request $request)
    {
        $nilai = $request->nilai;

        Batch::update(new PAS, $nilai, 'uuid');
    }
    /**
     * Rapor - Index Show View
     */
    public function raporIndex(Request $request)
    {
        $id = Auth::user()->uuid;
        $guru = Guru::where('id_login', $id)->first();
        $ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where('id_guru', $guru->uuid)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
            ->orderByRaw('length(kelas.kelas), kelas.kelas')
            ->get();
        return view("penilaian.rapor.index", compact('ngajar'));
    }
    /**
     * Rapor - Menampilkan isi dari penilaian rapor per kelas
     */
    public function raporShow(String $uuid)
    {
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $materi = Materi::with('tupe')->where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
        $rapor_temp = RaporTemp::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
        $raporFinal = Rapor::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
        $raporManual = RaporManual::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
        $materiArray = array();
        $tupeArray = array();
        $rumus_rapor = Setting::where('jenis', 'rumus_rapor')->first();
        if ($rumus_rapor) {
            $rumus = $rumus_rapor->nilai;
        } else {
            $rumus = "bagi4";
        }

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
                    "tupe" => $tupe->tupe,
                    "show" => $tupe->show
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

        if ($raporFinal->count() > 0) {
            $sudah_konfirmasi = "sudah";
        } else {
            $sudah_konfirmasi = "belum";
        }

        return View("penilaian.rapor.show", compact('ngajar', 'formatif_array', 'sumatif_array', 'pas_array', 'temp_array', 'tupeArray', 'materiArray', 'semester', 'sudah_konfirmasi', 'manual_array', 'rumus'));
    }
    /**
     * Rapor Temp- Edit Rapor Temp Nilai
     */
    public function raporEdit(Request $request, String $uuid)
    {
        $semester = Semester::first();
        $sem = $semester->semester;
        $raporTemp = RaporTemp::updateOrCreate([
            'id_ngajar' => $request->idNgajar,
            'id_siswa' => $request->idSiswa,
            'jenis' => $request->jenis
        ], [
            'perubahan' => $request->perubahan,
            'semester' => $sem
        ]);
    }
    /**
     * Rapor Temp - Hapus Temp Nilai Rapor
     */
    public function raporDelete(Request $request, String $id)
    {
        $semester = Semester::first();
        $sem = $semester->semester;
        $raporTemp = RaporTemp::where([
            ['id_ngajar', '=', $id],
            ['id_siswa', '=', $request->idSiswa],
            ['jenis', '=', $request->jenis],
            ['semester', '=', $sem]
        ]);
        $raporTemp->delete();
    }
    /**
     * Rapor - Konfirmasi Rapor
     */
    public function raporKonfirmasi(Request $request, String $id)
    {
        $nilai = $request->nilai;

        $rapor = Rapor::upsert($nilai, 'uuid');
    }
    /**
     * Rapor - Hapus Konfirmasi Rapor
     */
    public function hapusRaporKonfirmasi(String $id)
    {
        $semester = Semester::first();
        $sem = $semester->semester;
        $rapor = Rapor::where([['id_ngajar', '=', $id], ['semester', '=', $sem]]);
        $rapor->delete();
    }
    /**
     * Penjabaran - Index Show View
     */
    public function penjabaranIndex()
    {
        $id = Auth::user()->uuid;
        $guru = Guru::where('id_login', $id)->first();
        $ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where('id_guru', $guru->uuid)
            ->whereIn('pelajaran.has_penjabaran', array('1', '2', '3'))
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
            ->orderByRaw('length(kelas.kelas), kelas.kelas')
            ->get();
        return view("penilaian.penjabaran.index", compact('ngajar'));
    }
    /**
     * Penjabaran - Show Isi dari Penjabaran
     */
    public function penjabaranShow(String $uuid)
    {
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $has_penjabaran = $ngajar->pelajaran->has_penjabaran;
        $setting = Setting::where('jenis', 'penjabaran_rata')->first();

        if ($setting) {
            $rata2Penjabaran = unserialize($setting->nilai);
        } else {
            $rata2Penjabaran = array(
                'inggris' => array(),
                'mandarin' => array(),
                'komputer' => array()
            );
        }

        if ($has_penjabaran == 1) {
            $jabaran = 'inggris';
            $penjabaran = JabarInggris::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
            $penjabaran_array = array();
            foreach ($penjabaran as $jabar) {
                $penjabaran_array[$jabar->id_ngajar . "." . $jabar->id_siswa] = array(
                    'uuid' => $jabar->uuid,
                    'listening' => $jabar->listening,
                    'speaking' => $jabar->speaking,
                    'writing' => $jabar->writing,
                    'reading' => $jabar->reading,
                    'grammar' => $jabar->grammar,
                    'vocabulary' => $jabar->vocabulary,
                    'singing' => $jabar->singing
                );
            }
        } else if ($has_penjabaran == 2) {
            $jabaran = 'mandarin';
            $penjabaran = JabarMandarin::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
            $penjabaran_array = array();
            foreach ($penjabaran as $jabar) {
                $penjabaran_array[$jabar->id_ngajar . "." . $jabar->id_siswa] = array(
                    'uuid' => $jabar->uuid,
                    'listening' => $jabar->listening,
                    'speaking' => $jabar->speaking,
                    'writing' => $jabar->writing,
                    'reading' => $jabar->reading,
                    'vocabulary' => $jabar->vocabulary,
                    'singing' => $jabar->singing
                );
            }
        } else if ($has_penjabaran == 3) {
            $jabaran = 'komputer';
            $penjabaran = JabarKomputer::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
            $penjabaran_array = array();
            foreach ($penjabaran as $jabar) {
                $penjabaran_array[$jabar->id_ngajar . "." . $jabar->id_siswa] = array(
                    'uuid' => $jabar->uuid,
                    'pengetahuan' => $jabar->pengetahuan,
                    'keterampilan' => $jabar->keterampilan,
                );
            }
        }
        return View("penilaian.penjabaran.show", compact('ngajar', 'penjabaran', 'jabaran', 'penjabaran_array', 'rata2Penjabaran'));
    }
    /**
     * Penjabaran - Penjabaran Store
     */
    public function penjabaranStore(Request $request, String $uuid)
    {
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $jabaran = $request->penjabaran;
        if ($jabaran == 'inggris') {
            $penjabaran = JabarInggris::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
            if ($penjabaran->count() === 0) {

                $nilai_array = array();
                foreach ($ngajar->siswa as $siswa) {
                    array_push($nilai_array, array(
                        'id_ngajar' => $ngajar->uuid,
                        'id_siswa' => $siswa->uuid,
                        'semester' => $sem,
                        'listening' => 0,
                        'speaking' => 0,
                        'writing' => 0,
                        'reading' => 0,
                        'grammar' => 0,
                        'vocabulary' => 0,
                        'singing' => 0
                    ));
                }
                JabarInggris::upsert($nilai_array, ['uuid'], ['id_ngajar', 'id_siswa', 'semester', 'listening', 'speaking', 'writing', 'reading', 'grammar', 'vocabulary', 'singing']);
            }
        } else if ($jabaran == "mandarin") {
            $penjabaran = JabarMandarin::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
            if ($penjabaran->count() === 0) {

                $nilai_array = array();
                foreach ($ngajar->siswa as $siswa) {
                    array_push($nilai_array, array(
                        'id_ngajar' => $ngajar->uuid,
                        'id_siswa' => $siswa->uuid,
                        'semester' => $sem,
                        'listening' => 0,
                        'speaking' => 0,
                        'writing' => 0,
                        'reading' => 0,
                        'vocabulary' => 0,
                        'singing' => 0
                    ));
                }
                JabarMandarin::upsert($nilai_array, ['uuid'], ['id_ngajar', 'id_siswa', 'semester', 'listening', 'speaking', 'writing', 'reading', 'vocabulary', 'singing']);
            }
        } else {
            $penjabaran = JabarKomputer::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
            if ($penjabaran->count() === 0) {

                $nilai_array = array();
                foreach ($ngajar->siswa as $siswa) {
                    array_push($nilai_array, array(
                        'id_ngajar' => $ngajar->uuid,
                        'id_siswa' => $siswa->uuid,
                        'semester' => $sem,
                        'pengetahuan' => 0,
                        'keterampilan' => 0
                    ));
                }
                JabarKomputer::upsert($nilai_array, ['uuid'], ['id_ngajar', 'id_siswa', 'semester', 'pengetahuan', 'keterampilan']);
            }
        }
    }
    /**
     * Penjabaran - Penambahan Penjabaran Per Invidual Murid
     */
    public function penjabaranInvidualStore(Request $request, String $uuid)
    {
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $jabaran = $request->penjabaran;
        if ($jabaran == 'inggris') {
            $penjabaran = JabarInggris::where([['id_ngajar', '=', $uuid], ['id_siswa', '=', $request->siswa], ['semester', '=', $sem]])->get();
            if ($penjabaran->count() === 0) {

                $nilai_array = array();
                array_push($nilai_array, array(
                    'id_ngajar' => $ngajar->uuid,
                    'id_siswa' => $request->siswa,
                    'semester' => $sem,
                    'listening' => 0,
                    'speaking' => 0,
                    'writing' => 0,
                    'reading' => 0,
                    'grammar' => 0,
                    'vocabulary' => 0,
                    'singing' => 0
                ));
                JabarInggris::upsert($nilai_array, ['uuid'], ['id_ngajar', 'id_siswa', 'semester', 'listening', 'speaking', 'writing', 'reading', 'grammar', 'vocabulary', 'singing']);
            }
        } else if ($jabaran == "mandarin") {
            $penjabaran = JabarMandarin::where([['id_ngajar', '=', $uuid], ['id_siswa', '=', $request->siswa], ['semester', '=', $sem]])->get();
            if ($penjabaran->count() === 0) {

                $nilai_array = array();
                array_push($nilai_array, array(
                    'id_ngajar' => $ngajar->uuid,
                    'id_siswa' => $request->siswa,
                    'semester' => $sem,
                    'listening' => 0,
                    'speaking' => 0,
                    'writing' => 0,
                    'reading' => 0,
                    'vocabulary' => 0,
                    'singing' => 0
                ));
                JabarMandarin::upsert($nilai_array, ['uuid'], ['id_ngajar', 'id_siswa', 'semester', 'listening', 'speaking', 'writing', 'reading', 'vocabulary', 'singing']);
            }
        } else {
            $penjabaran = JabarKomputer::where([['id_ngajar', '=', $uuid], ['id_siswa', '=', $request->siswa], ['semester', '=', $sem]])->get();
            if ($penjabaran->count() === 0) {

                $nilai_array = array();
                array_push($nilai_array, array(
                    'id_ngajar' => $ngajar->uuid,
                    'id_siswa' => $request->siswa,
                    'semester' => $sem,
                    'pengetahuan' => 0,
                    'keterampilan' => 0
                ));
                JabarKomputer::upsert($nilai_array, ['uuid'], ['id_ngajar', 'id_siswa', 'semester', 'pengetahuan', 'keterampilan']);
            }
        }
    }
    /**
     * Penjabaran - Simpan Nilai Penjabaran
     */
    public function penjabaranEdit(Request $request)
    {
        $nilai = $request->nilai;
        $penjabaran = $request->penjabaran;

        if ($penjabaran == "inggris") {
            Batch::update(new JabarInggris, $nilai, 'uuid');
        } else if ($penjabaran == "mandarin") {
            Batch::update(new JabarMandarin, $nilai, 'uuid');
        } else {
            Batch::update(new JabarKomputer, $nilai, 'uuid');
        }
    }
    /**
     * Penjabaran - Hapus Nilai Penjabaran
     */
    public function penjabaranDestroy(Request $request, String $uuid)
    {
        $semester = Semester::first();
        $sem = $semester->semester;
        $penjabaran = $request->penjabaran;

        if ($penjabaran == "inggris") {
            $jabaran = JabarInggris::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]]);
        } else if ($penjabaran == "mandarin") {
            $jabaran = JabarMandarin::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]]);
        } else {
            $jabaran = JabarKomputer::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]]);
        }
        $jabaran->delete();
    }
    /**
     * Rapor Manual
     */
    public function manual(): View
    {
        $pelajaran = Pelajaran::all()->sortBy('urutan', SORT_NATURAL);
        $kelas = Kelas::all()->sortBy('kelas')->sortBy('tingkat');
        return view('penilaian.rapor.manual.index', compact('pelajaran', 'kelas'));
    }
    /**
     * Rapor Manual - Ambil Data Ngajar
     */
    public function manualGetNilai(Request $request)
    {
        $pelajaran_uuid = $request->pelajaran;
        $kelas_uuid = $request->kelas;

        $ngajar = Ngajar::with('siswa', 'pelajaran', 'kelas', 'guru')->where([['id_pelajaran', '=', $pelajaran_uuid], ['id_kelas', '=', $kelas_uuid]])->first();

        return response()->json(['ngajar' => $ngajar]);
    }
    /**
     * Rapor Manual - Tambahkan Nilai Manual
     */
    public function manualCreate(String $uuid, Request $request)
    {
        $semester = Semester::first();
        $manual = RaporManual::where([
            ['id_ngajar', '=', $uuid],
            ['id_siswa', '=', $request->siswa]
        ])->first();
        if ($manual === null) {
            RaporManual::create([
                'id_ngajar' => $uuid,
                'id_siswa' => $request->siswa,
                'nilai' => $request->nilai,
                'deskripsi_positif' => $request->positif,
                'deskripsi_negatif' => $request->negatif,
                'semester' => $semester->semester
            ]);
            return response()->json(["success" => true]);
        } else {
            return response()->json(["success" => false]);
        }
    }
    /**
     * Rapor Manual - History Rapor Manual
     */
    public function manualHistory(): View
    {
        $semester = Semester::first();
        $manual = RaporManual::with('pelajaran', 'siswa')->where('semester', $semester->semester)->get();
        return view('penilaian.rapor.manual.history', compact('manual'));
    }
    /**
     * Rapor Manual - Halaman Edit Rapor Manual
     */
    public function manualEdit(String $uuid): View
    {
        $semester = Semester::first();
        $manual = RaporManual::with('pelajaran', 'siswa')->findOrFail($uuid);
        return view('penilaian.rapor.manual.edit', compact('manual'));
    }
    /**
     * Rapor Manual - Proses Update Rapor Manual
     */
    public function manualUpdate(Request $request, String $uuid)
    {
        $request->validate([
            'nilai' => 'required',
            'positif' => 'required',
            'negatif' => 'required'
        ]);

        $manual = RaporManual::findOrFail($uuid)->update([
            'nilai' => $request->nilai,
            'deskripsi_positif' => $request->positif,
            'deskripsi_negatif' => $request->negatif
        ]);

        return redirect()->route('penilaian.admin.manual.history')->with(['success' => 'Nilai Berhasil Diedit']);
    }
    /**
     * Rapor Manual - Delete nilai manual
     */
    public function manualDelete(String $uuid)
    {
        $manual = RaporManual::findOrFail($uuid)->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Perangkat Ajar
     */
    public function perangkat()
    {
        $id = Auth::user()->uuid;
        $guru = Guru::where('id_login', $id)->first();
        $perangkat = PerangkatAjar::orderBy('perangkat')->get();
        $perangkatGuru = PerangkatAjarGuru::get();
        $perangkat_array = array();
        foreach ($perangkatGuru as $item) {
            if (isset($perangkat_array[$item->id_list . "." . $item->id_guru])) {
                array_push($perangkat_array[$item->id_list . "." . $item->id_guru], array(
                    "uuid" => $item->uuid,
                    "file" => $item->file
                ));
            } else {
                $perangkat_array[$item->id_list . "." . $item->id_guru] = array();
                array_push($perangkat_array[$item->id_list . "." . $item->id_guru], array(
                    "uuid" => $item->uuid,
                    "file" => $item->file
                ));
            }
        }
        return view('perangkat.guru.index', compact('guru', 'perangkat', 'perangkat_array'));
    }
    /**
     * Projek - Halaman Pertama Projek P5
     */
    public function projekIndex()
    {
        $proyek = P5Proyek::all()->sortBy('tingkat')->sortBy('created_at');
        $ifGuru = "no";
        return view('penilaian.projek.index', compact('proyek', 'ifGuru'));
    }
    /**
     * Projek - Tambah Projek P5
     */
    public function projekCreate(): View
    {
        $tingkat = Kelas::all()->groupBy('tingkat')->sortBy('tingkat');
        return view('penilaian.projek.create', compact('tingkat'));
    }
    public function projekStore(Request $request)
    {
        $request->validate([
            'tingkat' => 'required',
            'judul' => 'required',
            'deskripsi' => 'required'
        ]);

        $proyek = P5Proyek::create([
            'tingkat' => $request->tingkat,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('penilaian.p5.index')->with(['success' => 'Proyek Berhasil Ditambah, Silahkan atur dimensi dalam proyek bersangkutan dengan menekan tombol "Atur Dimensi Proyek"']);
    }
    /**
     * Projek - Edit Projek P5
     */
    public function projekEdit(String $uuid): View
    {
        $tingkat = Kelas::all()->groupBy('tingkat')->sortBy('tingkat');
        $proyek = P5Proyek::findOrFail($uuid);
        return view('penilaian.projek.edit', compact('proyek', 'tingkat'));
    }
    /**
     * Proyek - Update Proses Projek P5
     */
    public function projekUpdate(Request $request, String $uuid)
    {
        $request->validate([
            'tingkat' => 'required',
            'judul' => 'required',
            'deskripsi' => 'required'
        ]);

        $proyek = P5Proyek::findOrFail($uuid)->update([
            'tingkat' => $request->tingkat,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('penilaian.p5.index')->with(['success' => 'Proyek Berhasil Diedit']);
    }
    /**
     * Projek - Halaman Atur Dimensi, Elemen dan Subelemen Projek P5
     */
    public function projekAtur()
    {
        $dimensi = P5Dimensi::all()->sortBy('created_by');
        $elemen = P5Elemen::all()->sortBy('created_by');
        $subelemen = P5Subelemen::with('elemen')->get()->sortBy('created_at')->sortBy('elemen.created_at')->sortBy('elemen.dimensi.created_at');
        return view('penilaian.projek.atur', compact('dimensi', 'elemen', 'subelemen'));
    }
    /**
     * Projek - Proses Tambah Dimensi Projek P5
     */
    public function projekTambahDimensi(Request $request)
    {

        P5Dimensi::create([
            'dimensi' => $request->dimensi
        ]);

        return response()->json(['success' => true]);
    }
    /**
     * Projek - Projek Hapus dimensi Projek P5
     */
    public function projekDeleteDimensi(String $uuid)
    {
        $p5dimensi = P5Dimensi::findOrFail($uuid);
        $p5dimensi->delete();

        return response()->json(['success' => true]);
    }
    /**
     * Projek - Proses Tambah Elemen Projek P5
     */
    public function projekTambahElemen(Request $request)
    {

        P5Elemen::create([
            'id_dimensi' => $request->dimensi,
            'elemen' => $request->elemen
        ]);

        return response()->json(['success' => true]);
    }
    /**
     * Projek - Projek Hapus elemen Projek P5
     */
    public function projekDeleteElemen(String $uuid)
    {
        $p5elemen = P5Elemen::findOrFail($uuid);
        $p5elemen->delete();

        return response()->json(['success' => true]);
    }
    /**
     * Projek - Projek Lihat Elemen dari dimensi yang dipilih
     */
    public function projekGetElemen(String $uuid)
    {
        $elemen = P5Elemen::where('id_dimensi', $uuid)->get();
        return response()->json(['elemen' => $elemen]);
    }
    /**
     * Projek - Projek Lihat Subelemen dari elemen yang
     */
    public function projekGetSubElemen(String $uuid)
    {
        $subelemen = P5Subelemen::where('id_elemen', $uuid)->get();
        return response()->json(['subelemen' => $subelemen]);
    }
    /**
     * Projek - Projek tambah subelemen
     */
    public function projekTambahSubElemen(Request $request)
    {
        P5Subelemen::create([
            'id_elemen' => $request->elemen,
            'subelemen' => $request->subelemen,
            'capaian' => $request->capaian
        ]);

        return response()->json(['success' => true]);
    }
    /**
     * Projek - Atur Dimensi dalam setiap projek
     */
    public function projekConfig(String $uuid)
    {
        $proyek = P5Proyek::findOrFail($uuid);
        $dimensi = P5Dimensi::all()->sortBy('created_by');
        $proyekDetail = P5ProyekDetail::where('id_proyek', $uuid)->get();

        return view('penilaian.projek.config', compact('proyek', 'dimensi', 'proyekDetail'));
    }
    /**
     * Projek - Tambahkan Dimensi, elemen dan Subelemen pada setiap projek
     */
    public function projekConfigStore(Request $request, String $uuid)
    {
        P5ProyekDetail::create([
            'id_proyek' => $uuid,
            'id_dimensi' => $request->dimensi,
            'id_elemen' => $request->elemen,
            'id_subelemen' => $request->subelemen
        ]);

        return response()->json(['success' => true]);
    }
    /**
     * Projek - Hapus Dimensi, elemen dan Subelemen pada setiap projek
     */
    public function projekConfigDelete(String $uuid)
    {
        $proyekDetail = P5ProyekDetail::findOrFail($uuid);
        $proyekDetail->delete();

        return response()->json(['success' => true]);
    }
    /**
     * Projek - Halaman Fasilitator
     */
    public function projekFasilitator(String $uuid): View
    {
        $proyek = P5Proyek::findOrFail($uuid);
        $guru = Guru::all()->sortBy('nama');
        $kelas = Kelas::where('tingkat', $proyek->tingkat)->get();
        $fasilitator = P5Fasilitator::where('id_proyek', $uuid)->get()->sortBy('kelas.kelas');

        return view('penilaian.projek.fasilitator', compact('proyek', 'guru', 'kelas', 'fasilitator'));
    }
    /**
     * Projek - Tambahkan Fasilitator
     */
    public function projekFasilitatorStore(Request $request, String $uuid)
    {
        $find = P5Fasilitator::where([
            ['id_proyek', '=', $uuid],
            ['id_kelas', '=', $request->kelas]
        ])->first();

        if ($find !== null) {
            return response()->json(['success' => false, 'message' => 'Fasilitator Untuk kelas ini sudah ada']);
        } else {
            P5Fasilitator::create([
                'id_proyek' => $uuid,
                'id_guru' => $request->fasilitator,
                'id_kelas' => $request->kelas
            ]);

            return response()->json(['success' => true]);
        }
    }
    /**
     * Projek - Hapus Fasilitator
     */
    public function projekFasilitatorDelete(String $uuid)
    {
        $fasilitator = P5Fasilitator::findOrFail($uuid);
        $fasilitator->delete();

        return response()->json(['success' => true]);
    }
    /**
     * P5 - Get Fasilitator Data
     */
    public function projekFasilitatorGet(String $uuid)
    {
        $fasilitator = P5Fasilitator::with('kelas', 'guru')->where('id_proyek', $uuid)->get();
        return response()->json(['fasilitator' => $fasilitator]);
    }
    /**
     * P5 - Halaman Nilai P5
     */
    public function projekNilai(String $uuid): View
    {
        $fasilitator = P5Fasilitator::findOrFail($uuid);
        $proyek = P5Proyek::findOrFail($fasilitator->id_proyek);
        $siswa = Siswa::where('id_kelas', $fasilitator->id_kelas)->get();
        $id_siswa = $siswa->pluck('uuid');
        $proyekDetail = P5ProyekDetail::with('subelemen')->where('id_proyek', $fasilitator->id_proyek)->get();
        $id_detail = $proyekDetail->pluck('uuid');
        $countDetail = $proyekDetail->count();

        $nilai = P5Nilai::whereIn('id_siswa', $id_siswa)->whereIn('id_detail', $id_detail)->get();
        $deskripsi = P5Deskripsi::whereIn('id_siswa', $id_siswa)->where('id_proyek', $fasilitator->id_proyek)->get();
        $arrayNilai = array();
        $arrayDeskripsi = array();
        foreach ($nilai as $item) {
            $arrayNilai[$item->id_siswa . "." . $item->id_detail] = array(
                'uuid' => $item->uuid,
                'nilai' => $item->nilai
            );
        }
        foreach ($deskripsi as $item) {
            $arrayDeskripsi[$item->id_siswa] = array(
                'uuid' => $item->uuid,
                'deskripsi' => $item->deskripsi
            );
        }
        $settingRentang = Setting::where('jenis', 'rentang_penilaian_proyek')->first();
        if ($settingRentang) {
            $rentang = unserialize($settingRentang->nilai);
        } else {
            $rentang = array();
        }
        return view('penilaian.projek.nilai', compact('fasilitator', 'proyek', 'siswa', 'countDetail', 'proyekDetail', 'arrayNilai', 'arrayDeskripsi', 'rentang'));
    }
    /**
     * P5 - Tambah Nilai P5
     */
    public function projekNilaiTambah(Request $request)
    {
        $siswa = Siswa::where('id_kelas', $request->idKelas)->get();
        $proyek = P5Proyek::findOrFail($request->idProyek);
        $proyekDetail = P5ProyekDetail::with('subelemen')->where('id_proyek', $request->idProyek)->get();
        $arrayNilai = array();
        $arrayDeskripsi = array();
        foreach ($siswa as $item) {
            foreach ($proyekDetail as $detail) {
                $arrayNilai[] = array(
                    'id_siswa' => $item->uuid,
                    'id_detail' => $detail->uuid,
                    'nilai' => 0
                );
            }
            $arrayDeskripsi[] = array(
                'id_proyek' => $proyek->uuid,
                'id_siswa' => $item->uuid,
                'deskripsi' => ''
            );
        }
        P5Nilai::upsert($arrayNilai, ['uuid'], ['nilai']);
        P5Deskripsi::upsert($arrayDeskripsi, ['uuid'], ['deskripsi']);
        return response()->json(['success' => 'data berhasil ditambahkan']);
    }
    /**
     * P5 - Simpan Nilai P5
     */
    public function projekNilaiStore(Request $request)
    {
        $nilai = $request->nilai;
        $deskripsi = $request->deskripsi;

        Batch::update(new P5Nilai, $nilai, 'uuid');
        Batch::update(new P5Deskripsi, $deskripsi, 'uuid');
    }
    /**
     * P5 - Hapus Nilai P5
     */
    public function projekNilaiHapus(Request $request)
    {
        $siswa = Siswa::where('id_kelas', $request->idKelas)->get();
        $id_siswa = $siswa->pluck('uuid');
        $proyek = P5Proyek::findOrFail($request->idProyek);
        $proyekDetail = P5ProyekDetail::with('subelemen')->where('id_proyek', $request->idProyek)->get();
        $id_detail = $proyekDetail->pluck('uuid');

        $deleteNilai = P5Nilai::whereIn('id_siswa', $id_siswa)->whereIn('id_detail', $id_detail)->delete();
        $deleteDeskripsi = P5Deskripsi::whereIn('id_siswa', $id_siswa)->where('id_proyek', $request->idProyek)->delete();
        return response()->json(['success' => 'data berhasil dihapus']);
    }
    /**
     * P5 - Projek Rapor
     */
    public function projekRapor(): View
    {
        $kelas = Kelas::orderBy('tingkat', 'ASC')->orderBy('kelas', 'ASC')->get();
        return view('penilaian.projek.rapor.index', compact('kelas'));
    }
    /**
     * P5 - Projek Rapor Show Per Kelas
     */
    public function projekRaporShow(String $uuid): View
    {
        $setting = Setting::all();
        $kelas = Kelas::with('siswa')->findOrFail($uuid);
        $siswa = $kelas->siswa;
        $proyek = P5Fasilitator::with('proyek')->where('id_kelas', $kelas->uuid)->get();
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
        $settingRentang = Setting::where('jenis', 'rentang_penilaian_proyek')->first();
        if ($settingRentang) {
            $rentang = unserialize($settingRentang->nilai);
        } else {
            $rentang = array();
        }
        // $siswa = Siswa::where('id_kelas', $uuid)->get();
        return view('penilaian.projek.rapor.show', compact('kelas', 'siswa', 'proyek', 'array_proyek_detail', 'array_nilai', 'array_deskripsi'));
    }
    /**
     * P5 - Projek Rapor Print Per Siswa
     */
    public function projekRaporPrint(String $uuid): View
    {
        $setting = Setting::all();
        $siswa = Siswa::with('kelas')->findOrFail($uuid);
        $semester = Semester::first();
        $walikelas = Walikelas::with('Guru')->where('id_kelas', $siswa->kelas->uuid)->first();

        $tanggal_rapor = $setting->first(function ($item) {
            return $item->jenis == 'tanggal_rapor';
        });
        $kepalaSekolah = $setting->first(function ($elem) {
            return $elem->jenis == 'kepala_sekolah';
        });

        if ($kepalaSekolah) {
            $kepala_sekolah = Guru::findOrFail($kepalaSekolah->nilai);
        } else {
            $kepala_sekolah = "";
        }

        if ($tanggal_rapor != null) {
            $tanggal = Carbon::parse($tanggal_rapor->nilai)->isoFormat('D MMMM Y');
        } else {
            $tanggal = "";
        }
        $proyek = P5Fasilitator::with('proyek')->where('id_kelas', $siswa->kelas->uuid)->get();
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
        $nilai_proyek = P5Nilai::where('id_siswa', $siswa->uuid)->whereIn('id_detail', $proyek_detail_uuid)->get();
        $array_nilai = array();
        foreach ($nilai_proyek as $nilai) {
            $array_nilai[$nilai->id_detail . "." . $nilai->id_siswa] = $nilai->nilai;
        }
        $deskripsi_proyek = P5Deskripsi::whereIn('id_proyek', $id_proyek)->get();
        $array_deskripsi = array();
        foreach ($deskripsi_proyek as $nilai) {
            $array_deskripsi[$nilai->id_proyek . "." . $nilai->id_siswa] = $nilai->deskripsi;
        }
        $settingRentang = Setting::where('jenis', 'rentang_penilaian_proyek')->first();
        if ($settingRentang) {
            $rentang = unserialize($settingRentang->nilai);
        } else {
            $rentang = array();
        }
        return view('penilaian.projek.rapor.print', compact('setting', 'siswa', 'semester', 'tanggal', 'walikelas', 'kepala_sekolah', 'proyek', 'rentang', 'array_proyek_detail', 'array_nilai', 'array_deskripsi'));
    }
    /**
     * ---------------P5 Guru ----------------
     */

    /**
     * P5 Guru - Index
     */
    public function guruProyekIndex(): View
    {
        $id = Auth::user()->uuid;
        $guru = Guru::where('id_login', $id)->first();
        $fasilitator = P5Fasilitator::with('kelas')->where('id_guru', $guru->uuid)->get()->sortBy('kelas.kelas')->sortBy('proyek.tingkat');
        $fasilitator_proyek_id = $fasilitator->pluck('id_proyek');
        $proyek = P5Proyek::whereIn('uuid', $fasilitator_proyek_id)->get();
        $ifGuru = "yes";
        return view('penilaian.projek.index', compact('proyek', 'ifGuru', 'fasilitator'));
    }
    public function guruProjekNilai(String $uuid): View
    {
        $fasilitator = P5Fasilitator::findOrFail($uuid);
        $proyek = P5Proyek::findOrFail($fasilitator->id_proyek);
        $siswa = Siswa::where('id_kelas', $fasilitator->id_kelas)->get();
        $id_siswa = $siswa->pluck('uuid');
        $proyekDetail = P5ProyekDetail::with('subelemen')->where('id_proyek', $fasilitator->id_proyek)->get();
        $id_detail = $proyekDetail->pluck('uuid');
        $countDetail = $proyekDetail->count();

        $nilai = P5Nilai::whereIn('id_siswa', $id_siswa)->whereIn('id_detail', $id_detail)->get();
        $deskripsi = P5Deskripsi::whereIn('id_siswa', $id_siswa)->where('id_proyek', $fasilitator->id_proyek)->get();
        $arrayNilai = array();
        $arrayDeskripsi = array();
        foreach ($nilai as $item) {
            $arrayNilai[$item->id_siswa . "." . $item->id_detail] = array(
                'uuid' => $item->uuid,
                'nilai' => $item->nilai
            );
        }
        foreach ($deskripsi as $item) {
            $arrayDeskripsi[$item->id_siswa] = array(
                'uuid' => $item->uuid,
                'deskripsi' => $item->deskripsi
            );
        }
        return view('penilaian.projek.nilai', compact('fasilitator', 'proyek', 'siswa', 'countDetail', 'proyekDetail', 'arrayNilai', 'arrayDeskripsi'));
    }
}
