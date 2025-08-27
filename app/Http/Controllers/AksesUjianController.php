<?php

namespace App\Http\Controllers;

use App\Models\AksesUjian;
use App\Models\Guru;
use App\Models\Semester;
use Illuminate\Http\Request;

class AksesUjianController extends Controller
{
    public function index()
    {
        $guru = Guru::orderBy('nama', 'ASC')->get();
        $akses = AksesUjian::where('semester', Semester::first()->semester)->get();
        $aksesGuru = $akses->pluck('id_guru')->toArray();
        return view('akses.ujian.index', compact('guru', 'aksesGuru'));
    }
    public function buka(String $uuid)
    {
        $semseter = Semester::first();
        $akses = AksesUjian::where('id_guru', $uuid)->first();
        if ($akses != null) {
            if ($akses->semester == $semseter->semester) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akses Ujian Sudah Dibuka Sebelumnya'
                ]);
            } else {
                $akses->update([
                    'semester' => $semseter->semester,
                ]);
                return response()->json([
                    'status' => 'success',
                ]);
            }
        } else {
            AksesUjian::create([
                'id_guru' => $uuid,
                'semester' => $semseter->semester,
            ]);
            return response()->json([
                'status' => 'success',
                'data' => $akses
            ]);
        }
    }
    public function tutup(String $uuid)
    {
        $akses = AksesUjian::where('id_guru', $uuid)->first();
        if ($akses == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ujian Belum Dibuka'
            ]);
        } else {
            $akses->delete();
            return response()->json([
                'status' => 'success',
            ]);
        }
    }
    public function bukaSemua()
    {
        $semester = Semester::first();
        $guru = Guru::all()->pluck('uuid')->toArray();
        $arrayAdd = array();
        foreach ($guru as $g) {
            array_push($arrayAdd, array(
                'id_guru' => $g,
                'semester' => $semester->semester
            ));
        }
        $aksesUjian = AksesUjian::upsert($arrayAdd, ['id_guru'], ['semester']);
        return response()->json([
            'status' => 'success'
        ]);
    }
    public function tutupSemua()
    {
        $akses = AksesUjian::where('semester', Semester::first()->semester)->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
