<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Guru;
use App\Models\Ngajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    /**
     * Index - Tampilkan classroom dari ID Ngajar
     */
    public function index() : View {
        $id = auth()->user()->uuid;
        $guru = Guru::where('id_login',$id)->first();
        $ngajar = Ngajar::select(['ngajar.*','pelajaran','pelajaran_singkat','kelas','tingkat'])
        ->join('pelajaran','id_pelajaran','=','pelajaran.uuid')
        ->join('kelas','id_kelas','=','kelas.uuid')
        ->where('id_guru',$guru->uuid)
        ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
        ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
        ->orderByRaw('length(kelas.kelas), kelas.kelas')
        ->get();
        return View('classroom.index',compact('ngajar'));
    }

    /**
     * Show - Tampilkan semua classroom berdasarkan ID Ngajar
     */
    public function show(String $uuid) : View {
        $ngajar = Ngajar::with('kelas','pelajaran','guru')->findOrFail($uuid);
        $classroom = Classroom::where('id_ngajar',$uuid)->orderBy('created_at','desc')->get();
        return View('classroom.show',compact('classroom','ngajar'));
    }
    /**
     * Create - Tambahkan Data Materi Pembelajaran
     */
    public function create(String $uuid, String $jenis) : View {
        return View('classroom.create',compact('jenis'));
    }
}
