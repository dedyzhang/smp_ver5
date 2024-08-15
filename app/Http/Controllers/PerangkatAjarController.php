<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\PerangkatAjar;
use App\Models\PerangkatAjarGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use ZipArchive;

class PerangkatAjarController extends Controller
{
    /**
     * Index - Halaman Guru Perangkat Ajar
     */
    public function index(): View
    {
        $perangkat_list = PerangkatAjar::orderBy('perangkat')->get();
        $guru = Guru::orderBy('nama')->get();
        $perangkat_guru = PerangkatAjarGuru::get();
        $perangkat_array = array();

        foreach ($perangkat_guru as $item) {
            if (isset($perangkat_array[$item->id_guru])) {
                $perangkat_array[$item->id_guru] += 1;
            } else {
                $perangkat_array[$item->id_guru] = 1;
            }
        }
        return view('perangkat.index', compact('perangkat_list', 'guru', 'perangkat_array'));
    }
    public function show(String $uuid): View
    {
        $guru = Guru::findOrFail($uuid);
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
     * Create - Menampilkan halaman data list Perangkat
     */
    public function create(): View
    {
        return view('perangkat.create');
    }
    /**
     * Store - Proses simpan data list perangkat
     */
    public function store(Request $request)
    {
        $request->validate([
            'perangkat' => 'required'
        ]);

        PerangkatAjar::create([
            'perangkat' => $request->perangkat
        ]);

        return redirect()->route('perangkat.index')->with(['success' => 'Perangkat Berhasil Disimpan']);
    }
    /**
     * Edit - Tampil Halaman edit list perangkat
     */
    public function edit(String $uuid)
    {
        $perangkat = PerangkatAjar::findOrFail($uuid);

        return view('perangkat.edit', compact('perangkat'));
    }
    /**
     * Update - Update Data List Perangkat
     */
    public function update(Request $request, String $uuid)
    {
        $edit = $request->validate([
            'perangkat' => 'required'
        ]);
        $perangkat = PerangkatAjar::findOrFail($uuid);
        $perangkat->update($edit);

        return redirect()->route('perangkat.index')->with(['success' => "Data Berhasil Diupdate"]);
    }
    /**
     * Delete - Delete Data List Perangkat
     */
    public function delete(String $uuid)
    {
        $perangkat = PerangkatAjar::findOrFail($uuid);

        $perangkat->delete();

        return response()->json(['success' => true]);
    }
    /**
     * Upload - Upload Perangkat Berdasarkan Jenis Perangkat
     */
    public function upload(String $uuid, String $uuidPerangkat, Request $request)
    {
        $request->validate([
            'perangkat' => 'required'
        ]);
        $perangkat = PerangkatAjar::findOrFail($uuidPerangkat);
        $guru = Guru::findOrFail($uuid);
        $file = $request->file('perangkat');
        $ext = $file->extension();
        $OriginalName = explode('.', $file->getClientOriginalName())[0];
        $filename = $perangkat->perangkat . "_" . $OriginalName . "." . $ext;
        $path = 'perangkat/' . $guru->nik;
        $file->storeAs('public/' . $path, $filename);

        $realPath = $path . "/" . $filename;
        PerangkatAjarGuru::create([
            'id_guru' => $uuid,
            'id_list' => $uuidPerangkat,
            'file' => $realPath
        ]);

        return redirect()->back()->with(['success' => 'File Berhasil Diupload']);
    }
    /**
     * Download Perangkat Yang diupload
     */
    public function download(String $uuid)
    {
        $file = PerangkatAjarGuru::findOrFail($uuid);
        $file_path = storage_path('app/public/' . $file->file);
        $file_name_split = explode('/', $file->file);
        $file_name = end($file_name_split);

        if (file_exists($file_path)) {
            return response()->download($file_path, $file_name);
        } else {
            abort(404, 'File not found');
        }
    }
    /**
     * Delete Perangkat yang diupload
     */
    public function deletePerangkat(String $uuid)
    {
        $file = PerangkatAjarGuru::findOrFail($uuid);

        Storage::delete('public/' . $file->file);
        $file->delete();

        return response()->json(["success" => true]);
    }
    /**
     * Download Zip Perangkat
     */
    public function downloadZip(String $uuid)
    {
        $guru = Guru::findOrFail($uuid);
        $perangkat = PerangkatAjarGuru::where('id_guru', $guru->uuid)->get();
        $zip = new ZipArchive;
        $zipFileName = "app/public/perangkat/" . 'Perangkat-' . $guru->nik . '.zip';

        if ($zip->open(storage_path($zipFileName), ZipArchive::CREATE) === TRUE) {
            foreach ($perangkat as $key => $value) {
                $filePath = storage_path('app/public/' . $value->file);

                $filesToZip[] = $filePath;
            }
            foreach ($filesToZip as $file) {
                $zip->addFile($file, basename($file));
            }

            $zip->close();

            return response()->download(storage_path($zipFileName))->deleteFileAfterSend(true);
        }
    }
}
