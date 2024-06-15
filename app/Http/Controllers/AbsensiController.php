<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\TanggalAbsensi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    public function index() : View {
        $sem = Semester::first();
        $semester = $sem->semester;
        $tanggal = TanggalAbsensi::get();
        $event = [];

        foreach($tanggal as $tgl) {

            $agenda = $tgl->agenda == 1 ? "- agenda" : "";
            $siswa = $tgl->ada_siswa == 1 ? "- siswa": "";

            if($tgl->agenda == 1 && $tgl->ada_siswa == 1) {
                $colors = '#FFC470';
            } else if($tgl->agenda == 1) {
                $colors = '#FA7070';
            } else if($tgl->ada_siswa == 1) {
                $colors = "#7EA1FF";
            } else {
                $colors = '#007F73';
            }

            $event[] = [
                'title' => "S".$semester.$agenda.$siswa,
                'start' => $tgl->tanggal,
                'end' => $tgl->tanggal,
                'color' => $colors,
            ];
        }
        return View('absensi.index',compact('event'));
    }
    public function get(Request $request) {
        $tanggal = array();
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $absensiTanggal = TanggalAbsensi::whereYear("tanggal","=",$tahun)->whereMonth("tanggal","=",$bulan)->get();

        return $absensiTanggal;
    }
    public function create() : View {
        $sem = Semester::first();
        $semester = $sem->semester;
        return View('absensi.create',compact('semester'));
    }
    public function store(Request $request) {
        $tanggal = $request->tanggal;

        TanggalAbsensi::upsert($tanggal,['tanggal'],['agenda','ada_siswa']);
    }
    public function destroy(Request $request) {
        $tanggal = TanggalAbsensi::where('tanggal',$request->tanggal)->first();
        $tanggal->delete();
    }
}
