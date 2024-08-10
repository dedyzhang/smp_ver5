<?php

namespace App\Http\Controllers;

use App\Models\Formatif;
use App\Models\Guru;
use App\Models\JabarInggris;
use App\Models\JabarMandarin;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Ngajar;
use App\Models\PAS;
use App\Models\Pelajaran;
use App\Models\PTS;
use App\Models\Rapor;
use App\Models\RaporTemp;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Sumatif;
use App\Models\Tupe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Mavinoo\Batch\BatchFacade as Batch;
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
        $pts = PTS::whereIn('id_ngajar', $id_ngajar)->get();
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
        $pas = PAS::whereIn('id_ngajar', $id_ngajar)->get();
        foreach ($pas as $item) {
            $pas_array[$item->id_ngajar . "." . $item->id_siswa] = $item->nilai;
        }
        $siswa = Siswa::where('id_kelas', $id)->orderBy('nama', 'ASC')->get();
        return view('penilaian.pas.all', compact('ngajar', 'kelas', 'siswa', 'pas_array'));
    }
    /**
     * Penilaian PAS Show All Index
     */
    public function raporIndexAll(): View
    {
        $kelas = Kelas::orderBy('tingkat', 'ASC')->orderBy('kelas', 'ASC')->get();

        return view('penilaian.rapor', compact('kelas'));
    }
    /**
     * Penilaian PAS Show All
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
        $rapor = Rapor::whereIn('id_ngajar', $id_ngajar)->get();
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
        return view("penilaian.materi.show", compact('ngajar', 'materi'));
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
                "semester" => $semester
            ]);
            $tupe_array = array();
            for ($i = 1; $i <= $request->tupe; $i++) {
                array_push($tupe_array, array(
                    "id_materi" => $materi->uuid
                ));
            }
            $tupe = Tupe::upsert($tupe_array, ['uuid']);

            $getTupe = Tupe::where('id_materi', $materi->uuid)->get();
            $formatif_array = array();
            for ($i = 0; $i < $request->tupe; $i++) {
                foreach ($ngajar->siswa as $siswa) {
                    array_push($formatif_array, array(
                        "id_materi" => $materi->uuid,
                        "id_tupe" => $getTupe[$i]->uuid,
                        "id_siswa" => $siswa->uuid,
                        'nilai' => 0
                    ));
                }
            }
            $formatif = Formatif::upsert($formatif_array, ['uuid'], ['id_materi', 'id_tupe', 'id_siswa', 'nilai']);
            $sumatif_array = array();

            foreach ($ngajar->siswa as $siswa) {
                array_push($sumatif_array, array(
                    "id_materi" => $materi->uuid,
                    "id_siswa" => $siswa->uuid,
                    "nilai" => 0
                ));
            }
            $sumatif = Sumatif::upsert($sumatif_array, ['uuid'], ['id_materi', 'id_siswa', 'nilai']);
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
     * Materi - Tambah Tujuan Pembelajaran
     */
    public function materiCreateTupe(Request $request, String $uuid)
    {
        $tupe = Tupe::create([
            "id_materi" => $uuid
        ]);
        $newTupe = $request->tupe + 1;
        $materi = Materi::findOrFail($uuid)->update([
            "tupe" => $newTupe
        ]);
        $formatif_array = array();
        $ngajar = Ngajar::with('siswa')->findOrFail($request->idNgajar)->first();
        foreach ($ngajar->siswa as $siswa) {
            array_push($formatif_array, array(
                "id_materi" => $uuid,
                "id_tupe" => $tupe->uuid,
                "id_siswa" => $siswa->uuid,
                'nilai' => 0
            ));
        }
        $formatif = Formatif::upsert($formatif_array, ['uuid'], ['id_materi', 'id_tupe', 'id_siswa', 'nilai']);
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
     * Formatif - Menampilkan isi dari penilaian Sumatif Per Kelas
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
                "jumlahTupe" => $item->tupe
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

        if ($raporFinal->count() > 0) {
            $sudah_konfirmasi = "sudah";
        } else {
            $sudah_konfirmasi = "belum";
        }

        return View("penilaian.rapor.show", compact('ngajar', 'formatif_array', 'sumatif_array', 'pas_array', 'temp_array', 'tupeArray', 'materiArray', 'semester', 'sudah_konfirmasi'));
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
            ->whereIn('pelajaran.has_penjabaran', array('1', '2'))
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
        }

        return View("penilaian.penjabaran.show", compact('ngajar', 'penjabaran', 'jabaran', 'penjabaran_array'));
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
        } else {
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
        } else {
            Batch::update(new JabarMandarin, $nilai, 'uuid');
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
        } else {
            $jabaran = JabarMandarin::where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]]);
        }
        $jabaran->delete();
    }
}
