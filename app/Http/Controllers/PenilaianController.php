<?php

namespace App\Http\Controllers;

use App\Models\Formatif;
use App\Models\Guru;
use App\Models\Materi;
use App\Models\Ngajar;
use App\Models\Semester;
use App\Models\Sumatif;
use App\Models\Tupe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Mavinoo\Batch\BatchFacade as Batch;

class PenilaianController extends Controller
{
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
        $materi = Materi::with('tupe')->where('id_ngajar',$uuid)->get();
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
    public function formatifShow(String $uuid) {
        $ngajar = Ngajar::with('pelajaran','kelas','guru','siswa')->findOrFail($uuid);
        $materi = Materi::with('tupe')->where('id_ngajar',$uuid)->get();
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
           $formatif_array[$item->id_tupe.".".$item->id_siswa] = $item->nilai;
        }
        return view("penilaian.formatif.show",compact('ngajar','materi','count','formatif_array','materiArray','tupeArray'));
    }
}
