<?php

namespace App\Http\Controllers;

use App\Models\Formatif;
use App\Models\Guru;
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
use Illuminate\View\View;
use Mavinoo\Batch\BatchFacade as Batch;
use PhpParser\Node\Expr\Cast\String_;

class PenilaianController extends Controller
{
    /**
     * Penilain Index
     */
    public function index() : View {
        $pelajaran = Pelajaran::get()->sortBy('urutan');

        return view('penilaian.index',compact('pelajaran'));
    }
    /**
     * Penilaian Index Get Pelajaran
     */
    public function get(String $uuid) {
        $ngajar = Ngajar::with('kelas','pelajaran','guru')->where('id_pelajaran',$uuid)->get();
        return response()->json(["success" => true,"data" => $ngajar]);
    }
    /**
     * Penilaian PTS Show All Index
     */
    public function ptsIndexAll() : View {
        $kelas = Kelas::orderBy('tingkat','ASC')->orderBy('kelas','ASC')->get();

        return view('penilaian.pts',compact('kelas'));
    }
    /**
     * Penilaian PTS Show All
     */
    public function ptsShowAll(String $id) : View {
        $kelas = Kelas::findOrFail($id);
        $ngajar = Ngajar::select(['ngajar.*','pelajaran','pelajaran_singkat'])->join('pelajaran','id_pelajaran','=','pelajaran.uuid')->where('id_kelas',$id)->orderBy('pelajaran.urutan','ASC')->get();
        $siswa = Siswa::where('id_kelas',$id)->get();
        return view('penilaian.pts.all',compact('ngajar','kelas','siswa'));
    }
    /**
     * KKTP - Show Index
     */
    public function kktpIndex() : View {
        $id = auth()->user()->uuid;
        $guru = Guru::where('id_login',$id)->first();
        $ngajar = Ngajar::with('pelajaran','kelas')->where('id_guru',$guru->uuid)->get()->sortBy('urutan',SORT_NATURAL,true);
        return view('penilaian.kktp.index',compact('ngajar'));
    }
    /**
     * KKTP - Edit KKTP
     */
    public function kktpEdit(Request $request) {
        $kktp = $request->kktp;

        Batch::update(new Ngajar,$kktp,'uuid');
    }
    /**
     * Materi - Show Index
     */
    public function materiIndex() : View {
        $id = auth()->user()->uuid;
        $guru = Guru::where('id_login',$id)->first();
        $ngajar = Ngajar::with('pelajaran','kelas')->where('id_guru',$guru->uuid)->get()->sortBy('urutan',SORT_NATURAL,true);
        return view("penilaian.materi.index",compact('ngajar'));
    }
    /**
     * Materi - Lihat isi dari Materi
     */
    public function materiShow(String $uuid) : View {
        $ngajar = Ngajar::with('pelajaran','kelas','guru','siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $materi = Materi::with('tupe')->where([['id_ngajar','=',$uuid],['semester','=',$sem]])->get();
        return view("penilaian.materi.show",compact('ngajar','materi'));
    }
    /**
     * Materi - Tambah Materi
     */
    public function materiCreate(Request $request,String $uuid) {
        $smt = Semester::first();
        $ngajar = Ngajar::with('siswa')->find($uuid);
        $semester = $smt->semester;
        if($ngajar->siswa->count() === 0) {
            return response()->json(["success" => false,"message"=> 'Data Ngajar belum memiliki siswa']);
        } else {
            $materi = Materi::create([
                "id_ngajar" => $uuid,
                "materi" => $request->materi,
                "tupe" => $request->tupe,
                "semester"=> $semester
            ]);
            $tupe_array = array();
            for($i = 1; $i <= $request->tupe; $i++) {
                array_push($tupe_array,array(
                    "id_materi" => $materi->uuid
                ));
            }
            $tupe = Tupe::upsert($tupe_array,['uuid']);

            $getTupe = Tupe::where('id_materi',$materi->uuid)->get();
            $formatif_array = array();
            for($i = 0; $i < $request->tupe; $i++) {
                foreach($ngajar->siswa as $siswa) {
                    array_push($formatif_array,array(
                        "id_materi" => $materi->uuid,
                        "id_tupe" => $getTupe[$i]->uuid,
                        "id_siswa" => $siswa->uuid,
                        'nilai' => 0
                    ));
                }
            }
            $formatif = Formatif::upsert($formatif_array,['uuid'],['id_materi','id_tupe','id_siswa','nilai']);
            $sumatif_array = array();

            foreach($ngajar->siswa as $siswa) {
                array_push($sumatif_array,array(
                    "id_materi" => $materi->uuid,
                    "id_siswa" => $siswa->uuid,
                    "nilai" => 0
                ));
            }
            $sumatif = Sumatif::upsert($sumatif_array,['uuid'],['id_materi','id_siswa','nilai']);
            return response()->json(["success" => true]);
        }
    }
    /**
     * Materi - Edit Materi
     */
    public function materiUpdate(Request $request,String $uuid) {
        $materi = Materi::findorFail($uuid);

        $materi->update([
            "materi" => $request->materi
        ]);

        return response()->json(["success" => true]);
    }
    /**
     * Materi - Delete Materi
     */
    public function materiDelete(String $uuid) {
        $formatif = Formatif::where('id_materi',$uuid)->delete();
        $sumatif = Sumatif::where('id_materi',$uuid)->delete();
        $tupe = Tupe::where('id_materi',$uuid)->delete();
        $materi = Materi::findOrFail($uuid)->delete();

        return response()->json(["success" => true]);
    }
    /**
     * Materi - Tambah Tujuan Pembelajaran
     */
    public function materiCreateTupe(Request $request,String $uuid) {
        $tupe = Tupe::create([
            "id_materi" => $uuid
        ]);
        $newTupe = $request->tupe + 1;
        $materi = Materi::findOrFail($uuid)->update([
            "tupe" => $newTupe
        ]);
        $formatif_array = array();
        $ngajar = Ngajar::with('siswa')->findOrFail($request->idNgajar)->first();
        foreach($ngajar->siswa as $siswa) {
            array_push($formatif_array,array(
                "id_materi" => $uuid,
                "id_tupe" => $tupe->uuid,
                "id_siswa" => $siswa->uuid,
                'nilai' => 0
            ));
        }
        $formatif = Formatif::upsert($formatif_array,['uuid'],['id_materi','id_tupe','id_siswa','nilai']);
        return response()->json(["success" => true]);
    }
    /**
     * Materi - Update Tujuan Pembelajaran
     */
    public function materiUpdateTupe(Request $request,String $uuid) {
        $tupe = Tupe::findOrFail($uuid)->update([
            'tupe' => $request->tupe
        ]);

        return response()->json(['success'=> true]);
    }
    /**
     * Materi - Delete Tujuan Pembelajaran
     */
    public function materiDeleteTupe(String $uuid) {
        $tupe = Tupe::findOrFail($uuid);
        $materi = Materi::findOrFail($tupe->id_materi);
        $jumlahTupe = $materi->tupe;
        if($jumlahTupe == 1) {
            return response()->json(["success" => false,"message"=> "1 Materi Harus mempunyai minimal 1 tujuan pembelajaran"]);
        } else {
            $jumlahTupe = $jumlahTupe - 1;
            $materi->update([
                "tupe" => $jumlahTupe
            ]);
            $tupe->delete();
            $formatif = Formatif::where('id_tupe',$uuid)->delete();

            return response()->json(["success" => true]);
        }
    }
    /**
     * Formatif - Show Index Formatif
     */
    public function formatifIndex() : View {
        $id = auth()->user()->uuid;
        $guru = Guru::where('id_login',$id)->first();
        $ngajar = Ngajar::with('pelajaran','kelas')->where('id_guru',$guru->uuid)->get()->sortBy('urutan',SORT_NATURAL,true);
        return view("penilaian.formatif.index",compact('ngajar'));
    }
    /**
     * Formatif - Menampilkan isi dari penilaian formatif Per Kelas
     */
    public function formatifShow(String $uuid) : View {
        $ngajar = Ngajar::with('pelajaran','kelas','guru','siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $materi = Materi::with('tupe')->where([['id_ngajar','=',$uuid],['semester','=',$sem]])->get();
        $materiArray = array();
        $tupeArray = array();

        $count = 0;
        foreach($materi as $item) {
            array_push($materiArray,array(
                "uuid" => $item->uuid,
                "materi" => $item->materi,
                "jumlahTupe" => $item->tupe
            ));
            foreach($item->tupe()->get() as $tupe) {
                array_push($tupeArray,array(
                    "uuid" => $tupe->uuid,
                    "id_materi" => $tupe->id_materi,
                    "tupe" => $tupe->tupe
                ));
                $count++;
            }
            $count++;
        }
        $uuidMateri = array();
        foreach($materiArray as $item) {
            array_push($uuidMateri,$item["uuid"]);
        }
        $formatif = Formatif::whereIn('id_materi',$uuidMateri)->get();
        $formatif_array = array();
        foreach($formatif as $item) {
           $formatif_array[$item->id_tupe.".".$item->id_siswa] = array(
            'uuid' => $item->uuid,
            'nilai' => $item->nilai
           );
        }
        return view("penilaian.formatif.show",compact('ngajar','materi','count','formatif_array','materiArray','tupeArray'));
    }
    /**
     * Formatif - Simpan Nilai
     */
    public function formatifEdit(Request $request) {
        $nilai = $request->nilai;

        Batch::update(new Formatif,$nilai,'uuid');
    }
    /**
     * Sumatif = Show Index Sumatif
     */
    public function sumatifIndex() : View {
        $id = auth()->user()->uuid;
        $guru = Guru::where('id_login',$id)->first();
        $ngajar = Ngajar::with('pelajaran','kelas')->where('id_guru',$guru->uuid)->get()->sortBy('urutan',SORT_NATURAL,true);
        return view("penilaian.sumatif.index",compact('ngajar'));
    }
    /**
     * Formatif - Menampilkan isi dari penilaian Sumatif Per Kelas
     */
    public function sumatifShow(String $uuid) : View {
        $ngajar = Ngajar::with('pelajaran','kelas','guru','siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $materi = Materi::with('tupe')->where([['id_ngajar','=',$uuid],['semester','=',$sem]])->get();
        $materiArray = array();
        $tupeArray = array();

        $count = 0;
        foreach($materi as $item) {
            array_push($materiArray,array(
                "uuid" => $item->uuid,
                "materi" => $item->materi,
                "jumlahTupe" => $item->tupe
            ));
            $count++;
        }
        $uuidMateri = array();
        foreach($materiArray as $item) {
            array_push($uuidMateri,$item["uuid"]);
        }
        $sumatif = Sumatif::whereIn('id_materi',$uuidMateri)->get();
        $sumatif_array = array();
        foreach($sumatif as $item) {
           $sumatif_array[$item->id_materi.".".$item->id_siswa] = array(
            'uuid' => $item->uuid,
            'nilai' => $item->nilai
           );
        }
        return view("penilaian.sumatif.show",compact('ngajar','materi','count','sumatif_array','materiArray'));
    }
    /**
     * Sumatif - Simpan Nilai
     */
    public function sumatifEdit(Request $request) {
        $nilai = $request->nilai;

        Batch::update(new Sumatif,$nilai,'uuid');
    }
    /**
     * PTS - Show Index PTS
     */
    public function ptsIndex() : View {
        $id = auth()->user()->uuid;
        $guru = Guru::where('id_login',$id)->first();
        $ngajar = Ngajar::with('pelajaran','kelas')->where('id_guru',$guru->uuid)->get()->sortBy('urutan',SORT_NATURAL,true);
        return view("penilaian.pts.index",compact('ngajar'));
    }
    /**
     * PTS - Menampilkan isi dari penilaian pts per kelas
     */
    public function ptsShow(String $uuid) {
        $ngajar = Ngajar::with('pelajaran','kelas','guru','siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $pts = PTS::where([['id_ngajar','=',$uuid],['semester','=',$sem]])->get();
        $pts_array = array();

        foreach($pts as $nilai) {
            $pts_array[$nilai->id_ngajar.".".$nilai->id_siswa] = array(
                "uuid" => $nilai->uuid,
                "nilai" => $nilai->nilai
            );
        }
        return View("penilaian.pts.show",compact('ngajar','pts_array','pts'));
    }
    /**
     * PTS - PTS Store
     */
    public function ptsStore(String $uuid) {
        $ngajar = Ngajar::with('pelajaran','kelas','guru','siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $pts = PTS::where([['id_ngajar','=',$uuid],['semester','=',$sem]])->get();

        if($pts->count() === 0) {
            $smt = Semester::first();
            $semester = $smt->semester;

            $nilai_array = array();
            foreach($ngajar->siswa as $siswa) {
                array_push($nilai_array,array(
                    'id_ngajar' => $ngajar->uuid,
                    'id_siswa' => $siswa->uuid,
                    'nilai' => 0,
                    'semester' => $semester
                ));
            }
            PTS::upsert($nilai_array,['uuid'],['id_ngajar','id_siswa','nilai','semester']);
        }

    }
    /**
     * PTS - PTS Destroy
     */
    public function ptsDestroy(String $uuid) {
        $semester = Semester::first();
        $sem = $semester->semester;
        $pts = PTS::where([['id_ngajar','=',$uuid],['semester','=',$sem]]);

        $pts->delete();
    }
    /**
     * PTS - Simpan Nilai PTS
     */
    public function ptsEdit(Request $request) {
        $nilai = $request->nilai;

        Batch::update(new PTS,$nilai,'uuid');
    }
    /**
     * PAS - Show Index PAS
     */
    public function pasIndex() : View {
        $id = auth()->user()->uuid;
        $guru = Guru::where('id_login',$id)->first();
        $ngajar = Ngajar::with('pelajaran','kelas')->where('id_guru',$guru->uuid)->get()->sortBy('urutan',SORT_NATURAL,true);
        return view("penilaian.pas.index",compact('ngajar'));
    }
    /**
     * PAS - Menampilkan isi dari penilaian pas per kelas
     */
    public function pasShow(String $uuid) {
        $ngajar = Ngajar::with('pelajaran','kelas','guru','siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $pas = PAS::where([['id_ngajar','=',$uuid],['semester','=',$sem]])->get();
        $pas_array = array();

        foreach($pas as $nilai) {
            $pas_array[$nilai->id_ngajar.".".$nilai->id_siswa] = array(
                "uuid" => $nilai->uuid,
                "nilai" => $nilai->nilai
            );
        }
        return View("penilaian.pas.show",compact('ngajar','pas_array','pas'));
    }
    /**
     * PAS - PAS Store
     */
    public function pasStore(String $uuid) {
        $ngajar = Ngajar::with('pelajaran','kelas','guru','siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $pas = PAS::where([['id_ngajar','=',$uuid],['semester','=',$sem]])->get();

        if($pas->count() === 0) {
            $smt = Semester::first();
            $semester = $smt->semester;

            $nilai_array = array();
            foreach($ngajar->siswa as $siswa) {
                array_push($nilai_array,array(
                    'id_ngajar' => $ngajar->uuid,
                    'id_siswa' => $siswa->uuid,
                    'nilai' => 0,
                    'semester' => $semester
                ));
            }
            PAS::upsert($nilai_array,['uuid'],['id_ngajar','id_siswa','nilai','semester']);
        }

    }
    /**
     * PAS - PAS Destroy
     */
    public function pasDestroy(String $uuid) {
        $semester = Semester::first();
        $sem = $semester->semester;
        $pas = PAS::where([['id_ngajar','=',$uuid],['semester','=',$sem]]);

        $pas->delete();
    }
    /**
     * PAS - Simpan Nilai PAS
     */
    public function pasEdit(Request $request) {
        $nilai = $request->nilai;

        Batch::update(new PAS,$nilai,'uuid');
    }
    /**
     * Rapor - Index Show View
     */
    public function raporIndex(Request $request) {
        $id = auth()->user()->uuid;
        $guru = Guru::where('id_login',$id)->first();
        $ngajar = Ngajar::with('pelajaran','kelas')->where('id_guru',$guru->uuid)->get()->sortBy('urutan',SORT_NATURAL,true);
        return view("penilaian.rapor.index",compact('ngajar'));
    }
    /**
     * Rapor - Menampilkan isi dari penilaian rapor per kelas
     */
    public function raporShow(String $uuid) {
        $ngajar = Ngajar::with('pelajaran','kelas','guru','siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $materi = Materi::with('tupe')->where([['id_ngajar','=',$uuid],['semester','=',$sem]])->get();
        $rapor_temp = RaporTemp::where([['id_ngajar','=',$uuid],['semester','=',$sem]])->get();
        $raporFinal = Rapor::where([['id_ngajar','=',$uuid],['semester','=',$sem]])->get();
        $materiArray = array();
        $tupeArray = array();

        $count = 0;
        foreach($materi as $item) {
            array_push($materiArray,array(
                "uuid" => $item->uuid,
                "materi" => $item->materi,
                "jumlahTupe" => $item->tupe
            ));
            foreach($item->tupe()->get() as $tupe) {
                array_push($tupeArray,array(
                    "uuid" => $tupe->uuid,
                    "id_materi" => $tupe->id_materi,
                    "tupe" => $tupe->tupe
                ));
                $count++;
            }
            $count++;
        }
        $uuidMateri = array();
        foreach($materiArray as $item) {
            array_push($uuidMateri,$item["uuid"]);
        }
        $formatif = Formatif::whereIn('id_materi',$uuidMateri)->get();
        $formatif_array = array();
        foreach($formatif as $item) {
           $formatif_array[$item->id_tupe.".".$item->id_siswa] = array(
            'uuid' => $item->uuid,
            'nilai' => $item->nilai
           );
        }
        $sumatif = Sumatif::whereIn('id_materi',$uuidMateri)->get();
        $sumatif_array = array();
        foreach($sumatif as $item) {
           $sumatif_array[$item->id_materi.".".$item->id_siswa] = array(
            'uuid' => $item->uuid,
            'nilai' => $item->nilai
           );
        }
        $pas = PAS::where([['id_ngajar','=',$uuid],['semester','=',$sem]])->get();
        $pas_array = array();

        foreach($pas as $nilai) {
            $pas_array[$nilai->id_ngajar.".".$nilai->id_siswa] = array(
                "uuid" => $nilai->uuid,
                "nilai" => $nilai->nilai
            );
        }

        $temp_array = array();
        foreach($rapor_temp as $rapor) {
            array_push($temp_array,array(
                "id_siswa" => $rapor->id_siswa,
                "jenis" => $rapor->jenis,
                "perubahan" => $rapor->perubahan
            ));
        }

        if($raporFinal->count() > 0) {
            $sudah_konfirmasi = "sudah";
        } else {
            $sudah_konfirmasi = "belum";
        }

        return View("penilaian.rapor.show",compact('ngajar','formatif_array','sumatif_array','pas_array','temp_array','tupeArray','materiArray','semester','sudah_konfirmasi'));
    }
    /**
     * Rapor Temp- Edit Rapor Temp Nilai
     */
    public function raporEdit(Request $request,String $uuid) {
        $semester = Semester::first();
        $sem = $semester->semester;
        $raporTemp = RaporTemp::updateOrCreate([
            'id_ngajar' => $request->idNgajar,
            'id_siswa' => $request->idSiswa,
            'jenis' => $request->jenis
        ],[
            'perubahan' => $request->perubahan,
            'semester' => $sem
        ]);
    }
    /**
     * Rapor Temp - Hapus Temp Nilai Rapor
     */
    public function raporDelete(Request $request,String $id) {
        $semester = Semester::first();
        $sem = $semester->semester;
        $raporTemp = RaporTemp::where([
            ['id_ngajar','=',$id],
            ['id_siswa','=',$request->idSiswa],
            ['jenis','=',$request->jenis],
            ['semester','=',$sem]
        ]);
        $raporTemp->delete();

    }
    /**
     * Rapor - Konfirmasi Rapor
     */
    public function raporKonfirmasi(Request $request,String $id) {
        $nilai = $request->nilai;

        $rapor = Rapor::upsert($nilai,'uuid');
    }
    /**
     * Rapor - Hapus Konfirmasi Rapor
     */
    public function hapusRaporKonfirmasi(String $id) {
        $semester = Semester::first();
        $sem = $semester->semester;
        $rapor = Rapor::where([['id_ngajar','=',$id],['semester','=',$sem]]);
        $rapor->delete();
    }
}
