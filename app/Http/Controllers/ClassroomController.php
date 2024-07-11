<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\ClassroomSiswa;
use App\Models\Guru;
use App\Models\Ngajar;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ClassroomController extends Controller
{
    /**
     * Index - Tampilkan classroom dari ID Ngajar
     */
    public function index(): View
    {
        $id = auth()->user()->uuid;
        $guru = Guru::where('id_login', $id)->first();
        $ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where('id_guru', $guru->uuid)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
            ->orderByRaw('length(kelas.kelas), kelas.kelas')
            ->get();
        return View('classroom.index', compact('ngajar'));
    }

    /**
     * Show - Tampilkan semua classroom berdasarkan ID Ngajar
     */
    public function show(String $uuid): View
    {
        $ngajar = Ngajar::with('kelas', 'pelajaran', 'guru')->findOrFail($uuid);
        $classroom = Classroom::where('id_ngajar', $uuid)->orderBy('created_at', 'desc')->get();
        return View('classroom.show', compact('classroom', 'ngajar'));
    }
    /**
     * Create - Tambahkan Data Materi Pembelajaran
     */
    public function create(String $uuid, String $jenis)
    {
        $ngajar = Ngajar::with('kelas', 'pelajaran', 'guru')->findOrFail($uuid);
        $list_ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where([
                ['id_guru', '=', $ngajar->guru->uuid],
                ['id_pelajaran', '=', $ngajar->pelajaran->uuid],
            ])
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
            ->orderByRaw('length(kelas.kelas), kelas.kelas')
            ->get();
        return View('classroom.create', compact('jenis', 'ngajar', 'list_ngajar'));
    }
    /**
     * Store - Masukkan Data Materi Pembelajaran kedalam database
     */
    public function store(Request $request, String $uuid, String $jenis)
    {
        if ($jenis == "materi") {
            $ngajar = Ngajar::findOrFail($uuid);
            $idBahan = "H" . rand(100000, 999999);
            $judul = $request->judul;
            $deskripsi = $request->deskripsi;
            $link = $request->link;
            $isi = $request->isi;
            $adaToken = $request->token;
            $tanggalPost = date('m/d/Y h:i:s a', time());
            $status = $request->status;
            if ($adaToken == "tidak") {
                $token = "XXXX";
            } else {
                $token = rand(1000, 9999);
            }

            //Cek Ada File
            $adafile = $request->adafile;
            $namafile = "";

            if ($adafile == "ada") {
                foreach ($request->file('files') as $file) {
                    $ext = $file->extension();
                    $filename = $file->hashName();
                    $file->storeAs('public/classroom/teacher', $filename);
                    $namafile .= $filename . ",";
                }
                $namafile = substr($namafile, 0, -1);
            }

            $input_array = array();
            $kelas = explode(',', $request->kelas);
            foreach ($kelas as $item) {
                array_push($input_array, array(
                    "id_bahan" => $idBahan,
                    "id_ngajar" => $item,
                    "jenis" => "materi",
                    "judul" => $judul,
                    "tanggal_post" => $tanggalPost,
                    "deskripsi" => $deskripsi,
                    "file" => $namafile,
                    "link" => $link,
                    "isi" => $isi,
                    "show_nilai" => false,
                    "status" => $status,
                    "token" => $token
                ));
            }
            Classroom::upsert($input_array, ['uuid']);
            return response()->json(["success" => true]);
        }
    }
    /**
     * Edit - Edit Classroom
     */
    public function edit(String $uuid, String $uuidClassroom)
    {
        $ngajar = Ngajar::with('kelas', 'pelajaran', 'guru')->findOrFail($uuid);
        $list_ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where([
                ['id_guru', '=', $ngajar->guru->uuid],
                ['id_pelajaran', '=', $ngajar->pelajaran->uuid],
            ])
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
            ->orderByRaw('length(kelas.kelas), kelas.kelas')
            ->get();
        $classroom = Classroom::findOrFail($uuidClassroom);
        $array_ngajar = array();
        $classroomNgajar = Classroom::where('id_bahan', $classroom->id_bahan)->get();
        foreach ($classroomNgajar as $item) {
            array_push($array_ngajar, $item->id_ngajar);
        }
        $files = explode(',', $classroom->file);

        return View('classroom.edit', compact('ngajar', 'list_ngajar', 'classroom', 'array_ngajar', 'files'));
    }
    /**
     * Update - Update Classroom
     */
    public function update(Request $request, String $uuid, String $uuidClassroom)
    {
        if ($request->jenis == "materi") {
            $ngajar = Ngajar::findOrFail($uuid);
            $classroom = Classroom::findOrFail($uuidClassroom);
            $classroomAll = Classroom::where('id_bahan', $classroom->id_bahan);

            $judul = $request->judul;
            $deskripsi = $request->deskripsi;
            $link = $request->link;
            $isi = $request->isi;
            $adaToken = $request->token;
            $tanggalPost = date('m/d/Y h:i:s a', time());
            $status = $request->status;
            if ($adaToken == "tidak") {
                $token = "XXXX";
            } else {
                $token = rand(1000, 9999);
            }

            //Cek Ada File
            $adafile = $request->adafile;
            $namafile = $classroom->file;

            if ($adafile == "ada") {
                if ($namafile !== "") {
                    $namafile = $namafile . ",";
                }
                foreach ($request->file('files') as $file) {
                    $ext = $file->extension();
                    $filename = $file->hashName();
                    $file->storeAs('public/classroom/teacher', $filename);
                    $namafile .= $filename . ",";
                }
                $namafile = substr($namafile, 0, -1);
            }
            $classroomAll->update([
                'judul' => $judul,
                'deskripsi' => $deskripsi,
                'file' => $namafile,
                'link' => $link,
                'isi' => $isi,
                'token' => $token
            ]);

            // $input_array = array();
            $classroomAll2 = $classroomAll->get();
            $oldKelas = array();
            foreach ($classroomAll2 as $allClass) {
                array_push($oldKelas, $allClass->id_ngajar);
            }
            $kelas = explode(',', $request->kelas);
            $newKelas = array();
            foreach ($oldKelas as $item) {
                if (in_array($item, $kelas) === false) {
                    Classroom::where([
                        ['id_bahan', '=', $classroom->id_bahan],
                        ['id_ngajar', '=', $item]
                    ])->delete();
                } else {
                    array_push($newKelas, $item);
                }
            }
            $newArrayKelas = array_diff($kelas, $newKelas);
            $input_array = array();
            foreach ($newArrayKelas as $item) {
                array_push($input_array, array(
                    "id_bahan" => $classroom->id_bahan,
                    "id_ngajar" => $item,
                    "jenis" => "materi",
                    "judul" => $judul,
                    "tanggal_post" => $tanggalPost,
                    "deskripsi" => $deskripsi,
                    "file" => $namafile,
                    "link" => $link,
                    "isi" => $isi,
                    "show_nilai" => false,
                    "status" => $status,
                    "token" => $token
                ));
            }
            Classroom::upsert($input_array, ['uuid']);
            return response()->json(["success" => true]);
        }
    }
    /**
     * Delete File - Delete File dalam Classroom
     */
    public function deleteFile(Request $request)
    {
        $classroom = Classroom::where('id_bahan', $request->idBahan)->first();
        $files = explode(',', $classroom->file);
        $newArray = array_diff($files, [$request->oldPath]);
        Storage::delete('public/classroom/teacher/' . $request->oldPath);
        $newFile = implode(',', $newArray);
        $classroom = Classroom::where('id_bahan', $request->idBahan)->update([
            'file' => $newFile,
        ]);
    }
    /**
     * Asign Classroom - Asign Classroom
     */
    public function assign(String $uuid)
    {
        $classroom = Classroom::findOrFail($uuid);
        Classroom::where('id_bahan', $classroom->id_bahan)->update([
            'status' => 'assign',
        ]);
        return response()->json(["success" => true]);
    }
    /**
     * Delete Classroom
     */
    public function delete(Request $request, String $uuid)
    {
        $classroom = Classroom::findOrFail($uuid);
        if ($request->deleteAll === "true") {
            Classroom::where('id_bahan', $classroom->id_bahan)->delete();
            $files = explode(',', $classroom->file);
            foreach ($files as $item) {
                Storage::delete('public/classroom/teacher/' . $item);
            }
            return response()->json(["success" => true]);
        } else {
            $all = Classroom::where('id_bahan', $classroom->id_bahan)->get();
            if (count($all) == 1) {
                $files = explode(',', $classroom->file);
                foreach ($files as $item) {
                    Storage::delete('public/classroom/teacher/' . $item);
                }
            }
            $classroom->delete();
            return response()->json(["success" => true]);
        }
    }
    /**
     * Preview Classroom
     */
    public function preview(String $uuid, String $uuidClassroom): View
    {
        $classroom = Classroom::with('ngajar')->findOrFail($uuidClassroom);
        $classroomSiswa = ClassroomSiswa::where('id_classroom', $classroom->uuid)->get();
        $status_array = array();
        foreach ($classroomSiswa as $item) {
            $status_array[$item->id_siswa] = array(
                'status' => $item->status,
                'last_seen' => $item->last_seen
            );
        }
        $siswa = Siswa::where('id_kelas', $classroom->ngajar->id_kelas)->get();
        if ($classroom->file !== "") {
            $file_array = explode(',', $classroom->file);
        } else {
            $file_array = array();
        }
        return View('classroom.preview', compact('classroom', 'file_array', 'status_array', 'siswa', 'uuid'));
    }
    /**
     * Reset Siswa
     */
    public function resetSiswa(Request $request, String $uuid, String $uuidClassroom)
    {
        $idSiswa = $request->uuid;
        $idClassroom = $uuidClassroom;

        $classroomSiswa = ClassroomSiswa::where([['id_classroom', '=', $idClassroom], ['id_siswa', '=', $idSiswa]])->first();

        $classroomSiswa->update([
            'status' => 'reset'
        ]);
    }
    /**
     * -----------Siswa----------------
     */

    /**
     * Index - Preview Data Ngajar Siswa
     */
    public function siswaIndex(): View
    {
        $id = auth()->user()->uuid;
        $siswa = Siswa::where('id_login', $id)->first();
        $ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where('id_kelas', $siswa->id_kelas)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
            ->orderByRaw('length(kelas.kelas), kelas.kelas')
            ->get();
        return View('classroom.siswa.index', compact('ngajar'));
    }
    /**
     * Show - Preview Classroom Siswa
     */
    public function siswaShow(String $uuid): View
    {
        $ngajar = Ngajar::with('kelas', 'pelajaran', 'guru')->findOrFail($uuid);
        $classroom = Classroom::where([
            ['id_ngajar', '=', $uuid],
            ['status', '=', 'assign']
        ])->orderBy('created_at', 'desc')->get();
        return View('classroom.siswa.show', compact('classroom', 'ngajar'));
    }
    /**
     * Cek Token - Siswa Cek Token
     */
    public function siswaCekToken(Request $request)
    {
        $classroom = Classroom::findOrFail($request->uuid);
        if ($classroom->token === $request->token) {
            $id = auth()->user()->uuid;
            $siswa = Siswa::where('id_login', $id)->first();
            $cekSiswa = ClassroomSiswa::where([
                ['id_classroom', '=', $classroom->uuid],
                ['id_siswa', '=', $siswa->uuid]
            ])->first();
            if ($cekSiswa !== null) {
                if ($cekSiswa->status == "reset") {
                    $now = date('d M Y H:i:s');
                    $cekSiswa->update([
                        'status' => "online",
                        'last_seen' => $now
                    ]);
                    return response()->json(["success" => true]);
                } else {
                    return response()->json(["success" => false, "message" => 'Anda Terdeteksi Keluar, Hubungi Guru Mengajar untuk mereset Password']);
                }
            } else {
                $now = date('d M Y H:i:s');
                ClassroomSiswa::create([
                    'id_classroom' => $classroom->uuid,
                    'id_siswa' => $siswa->uuid,
                    'status' => 'online',
                    'last_seen' => $now
                ]);
                return response()->json(["success" => true]);
            }
        } else {
            return response()->json(["success" => false, "message" => 'Token Salah ! Cek kembali token yang dimasukkan dengan guru yang mengajar']);
        }
    }
    public function siswaPreview(String $uuid, String $uuidClassroom): View
    {
        $ngajar = Ngajar::with('pelajaran', 'kelas')->findOrFail($uuid);
        $classroom = Classroom::findOrFail($uuidClassroom);
        $id = auth()->user()->uuid;
        $siswa = Siswa::where('id_login', $id)->first();
        $siswa_aktif = ClassroomSiswa::where([
            ['id_classroom', '=', $classroom->uuid],
            ['id_siswa', '=', $siswa->uuid]
        ])->first();
        if ($classroom->file !== "") {
            $file_array = explode(',', $classroom->file);
        } else {
            $file_array = array();
        }
        return View('classroom.siswa.preview', compact('classroom', 'file_array', 'siswa_aktif', 'ngajar'));
    }
    public function siswaDetectOut(String $uuid)
    {
        $classroomSiswa = ClassroomSiswa::findOrFail($uuid);
        $now = date('d M Y H:i:s');
        $classroomSiswa->update([
            'status' => "out",
            'last_seen' => $now
        ]);
    }
}
