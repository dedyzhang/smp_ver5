<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\Aturan;
use App\Models\Barang;
use App\Models\Classroom;
use App\Models\ClassroomJawaban;
use App\Models\ClassroomSiswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Ngajar;
use App\Models\Poin;
use App\Models\PoinTemp;
use App\Models\Ruang;
use App\Models\RuangKelas;
use App\Models\Sekretaris;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TanggalAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class WalikelasController extends Controller
{
    /**
     * Absensi Siswa
     */
    public function absensi(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $dataSekolah = Semester::first();
        $semester = $dataSekolah->semester;
        if ($guru->walikelas == null) {
            $iswalikelas = false;
            $kelas = "";
            $jumlahAbsensi = "";
            $absensi_array = "";
        } else {
            $iswalikelas = true;
            $kelas = Kelas::with('siswa')->findOrFail($guru->walikelas->id_kelas);
            $tanggalAbsensi = TanggalAbsensi::where([
                ['ada_siswa', '=', 1],
                ['semester', '=', $semester]
            ])->get();
            $tanggalID = array();
            foreach ($tanggalAbsensi as $tanggal) {
                array_push($tanggalID, $tanggal->uuid);
            }
            $jumlahAbsensi = $tanggalAbsensi->count();
            $siswaID = array();
            foreach ($kelas->siswa as $siswa) {
                array_push($siswaID, $siswa->uuid);
            };
            $absensi = AbsensiSiswa::whereIn('id_tanggal', $tanggalID)->whereIn('id_siswa', $siswaID)->get();
            $absensi_array = array();
            foreach ($absensi as $item) {
                if (isset($absensi_array[$item->id_siswa][$item->absensi])) {
                    $absensi_array[$item->id_siswa][$item->absensi] += 1;
                } else {
                    $absensi_array[$item->id_siswa][$item->absensi] = 1;
                }
            }
        }
        return view('walikelas.absensi.index', compact('iswalikelas', 'kelas', 'jumlahAbsensi', 'absensi_array'));
    }
    /**
     * Tambah Absensi Siswa
     */
    public function absensiCreate(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $dataSekolah = Semester::first();
        $semester = $dataSekolah->semester;


        return View('walikelas.absensi.create', compact('guru'));
    }
    /**
     * Get - Dapatkan Data Absensi berdasarkan Tanggal
     */
    public function absensiGet(Request $request)
    {
        $tanggal = $request->tanggal;
        $absensiTanggal = TanggalAbsensi::where([['tanggal', '=', $tanggal], ['ada_siswa', '=', 1]])->first();
        if ($absensiTanggal !== null) {
            $kelas = Kelas::with('siswa')->findOrFail($request->kelas);
            $siswaID = array();
            foreach ($kelas->siswa as $siswa) {
                array_push($siswaID, $siswa->uuid);
            };
            $absensi = AbsensiSiswa::where('id_tanggal', $absensiTanggal->uuid)->whereIn('id_siswa', $siswaID)->get();
            return response()->json(['success' => true, 'siswa' => $kelas->siswa, 'absensi' => $absensi, 'id_tanggal' => $absensiTanggal->uuid]);
        } else {
            return response()->json(['success' => false, 'message' => 'tidak ada pembelajaran di tanggal ini']);
        }
    }
    /**
     * Store - Simpan Data Absensi Kedalam Database
     */
    public function absensiStore(Request $request)
    {
        $input_array = $request->input;

        AbsensiSiswa::upsert($input_array, ['uuid'], ['waktu', 'keterangan', 'absensi']);
    }
    /**
     * Siswa - Preview Data Siswa
     */
    public function siswa(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $siswa = Siswa::with('kelas')->where('id_kelas', $guru->walikelas->id_kelas)->orderBy('nis', 'ASC')->get();
        $sekretaris = Sekretaris::where('id_kelas', $guru->walikelas->id_kelas)->first();
        return View('walikelas.siswa.index', compact('siswa', 'sekretaris'));
    }
    /**
     * Siswa - Lihat Data Siswa
     */
    public function siswaShow(String $uuid)
    {
        $siswa = Siswa::findOrFail($uuid);

        return compact('siswa');
    }
    /**
     * Siswa - Reset Password
     */
    public function resetSiswa(String $uuid)
    {
        $siswa = Siswa::with('users')->findOrFail($uuid);
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
            . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            . '0123456789'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];
        $passwordHash = Hash::make($rand);
        $siswa->users->update([
            'password' => $passwordHash,
            'token' => 1,
        ]);

        return response()->json([
            'success' => true,
            'password' => $rand
        ]);
    }
    /**
     * Siswa - Reset Password Orangtua
     */
    public function resetOrangtua(String $uuid)
    {
        $siswa = Siswa::with('orangtua')->findOrFail($uuid);
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
            . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            . '0123456789'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];
        $passwordHash = Hash::make($rand);
        $siswa->orangtua[0]->update([
            'password' => $passwordHash,
            'token' => 1,
        ]);
        return response()->json([
            'success' => true,
            'password' => $rand
        ]);
    }
    /**
     * Siswa - Set Sekretaris
     */
    public function setSekretaris(Request $request)
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();

        $sekretaris = Sekretaris::where('id_kelas', $guru->walikelas->id_kelas)->first();
        if ($sekretaris === null) {
            Sekretaris::create([
                'id_kelas' => $guru->walikelas->id_kelas,
                'sekretaris1' => $request->sekretaris1,
                'sekretaris2' => $request->sekretaris2
            ]);
        } else {
            Sekretaris::where('id_kelas', $guru->walikelas->id_kelas)->update([
                'sekretaris1' => $request->sekretaris1,
                'sekretaris2' => $request->sekretaris2
            ]);
        }
    }
    /**
     * Poin - Index Tampil seluruh data siswa
     */
    public function poinIndex(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $siswa = Siswa::with('kelas')->where('id_kelas', $guru->walikelas->id_kelas)->orderBy('nis', 'ASC')->get();
        $siswa_array = $siswa->pluck('uuid')->toArray();
        $poin = Poin::with('aturan')->whereIn('id_siswa', $siswa_array)->get();
        $array_poin = array();
        foreach ($poin as $item) {
            if (isset($array_poin[$item->id_siswa])) {
                array_push($array_poin[$item->id_siswa], array(
                    "jenis" => $item->aturan->jenis,
                    "poin" => $item->aturan->poin,
                ));
            } else {
                $array_poin[$item->id_siswa] = array();
                array_push($array_poin[$item->id_siswa], array(
                    "jenis" => $item->aturan->jenis,
                    "poin" => $item->aturan->poin,
                ));
            }
        }
        return view('walikelas.poin.index', compact('siswa', 'array_poin'));
    }
    /**
     * Poin - Show Poin Per Siswa
     */
    public function poinShow(String $uuid): View
    {
        $siswa = Siswa::with('kelas')->findOrFail($uuid);
        $poin = Poin::with('aturan')->where('id_siswa', $siswa->uuid)->orderBy(Poin::raw("DATE(tanggal)"), 'ASC')->get();
        return view('walikelas.poin.show', compact('siswa', 'poin'));
    }
    public function poinTempIndex(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $siswa = Siswa::where('id_kelas', $guru->walikelas->id_kelas)->orderBy('nama')->get();
        $guru_all = Guru::get();
        $siswa_all_name = $siswa->pluck('nama', 'uuid')->toArray();
        $guru_all_name = $guru_all->pluck('nama', 'uuid')->toArray();
        $all_name = array_merge($siswa_all_name, $guru_all_name);
        $siswa_array = $siswa->pluck('uuid')->toArray();
        $poin_temp = PoinTemp::with('aturan', 'siswa')->whereIn('id_siswa', $siswa_array)->orderBy('created_at', 'DESC')->get();
        return view('walikelas.poin.temp.index', compact('poin_temp', 'all_name'));
    }
    public function poinTempCreate(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $siswa = Siswa::with('kelas')->where('id_kelas', $guru->walikelas->id_kelas)->orderBy('nis', 'ASC')->get();
        return view('walikelas.poin.temp.create', compact('siswa'));
    }
    public function poinGetAturan(Request $request)
    {
        $aturan = Aturan::where('jenis', $request->jenis)->orderBy('kode', 'asc')->get();

        return response()->json(["aturan" => $aturan]);
    }
    public function poinTempStore(Request $request)
    {
        $request->validate([
            'siswa' => 'required',
            'tanggal' => 'required',
            'jenis' => 'required',
            'aturan' => 'required'
        ]);
        $auth = Auth::user();
        if ($auth->access == "siswa") {
            $siswa = Siswa::where('id_login', $auth->uuid)->first();
            $penginput = "sekretaris";
            $id_penginput = $siswa->uuid;
        } else {
            $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
            $penginput = "guru";
            $id_penginput = $guru->uuid;
        }
        PoinTemp::create([
            'tanggal' => $request->tanggal,
            'id_aturan' => $request->aturan,
            'id_siswa' => $request->siswa,
            'penginput' => $penginput,
            'id_input' => $id_penginput,
            'status' => 'belum'
        ]);
        if ($auth->access != "siswa") {
            return redirect()->route('walikelas.poin.temp')->with(['success' => 'Poin Berhasil Diajukan, Silahkan menunggu update dari kesiswaan']);
        } else {
            return redirect()->route('sekretaris.poin')->with(['success' => 'Poin Berhasil Diajukan, Silahkan menunggu update dari kesiswaan']);
        }
    }
    public function poinTempDelete(Request $request)
    {
        $uuid = $request->uuid;
        $poin = PoinTemp::findOrFail($uuid);
        $poin->delete();
    }
    /**
     * Ruangan - Walikelas mengisi data dalam kelas
     * */
    public function ruang(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $idKelas = $guru->walikelas->id_kelas;
        $ruangan = RuangKelas::with('ruang')->where('id_kelas', $idKelas)->first();
        if ($ruangan !== null) {
            $id = $ruangan->ruang->uuid;
            $ruang = $ruangan->ruang;
        } else {
            $id = "";
            $ruang = array();
        }
        return view('sapras.barang.index', compact('id', 'ruang'));
    }
    /**
     * Ruangan - Create Barang Walikelas
     */
    public function ruangCreate(String $id): View
    {
        $ruang = Ruang::findOrFail($id);
        return view('sapras.barang.create', compact('id', 'ruang'));
    }
    /**
     * Ruangan - Edit Barang Walikelas
     */
    public function ruangEdit(String $uuid, String $uuidBarang): View
    {
        $barang = Barang::findOrFail($uuidBarang);
        return view('sapras.barang.edit', compact('barang', 'uuid'));
    }
    /**
     * Classroom - Tampilkan Classroom Walikelas
     */
    public function classroom(): View
    {
        $id = Auth::user()->uuid;
        $guru = Guru::with('walikelas')->where('id_login', $id)->first();
        $ngajar = Ngajar::select(['ngajar.*', 'pelajaran', 'pelajaran_singkat', 'kelas', 'tingkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->join('kelas', 'id_kelas', '=', 'kelas.uuid')
            ->where('id_kelas', $guru->walikelas->id_kelas)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')
            ->orderByRaw('length(kelas.tingkat), kelas.tingkat')
            ->orderByRaw('length(kelas.kelas), kelas.kelas')
            ->get();
        return view('walikelas.classroom.index', compact('ngajar'));
    }
    /**
     * Classroom - Tampilkan Materi dan Latihan dalam Ngajar
     */
    public function classroomShow(String $uuid): View
    {
        $ngajar = Ngajar::with('kelas', 'pelajaran', 'guru')->findOrFail($uuid);
        $classroom = Classroom::where([['id_ngajar', '=', $uuid], ['status', '=', 'assign']])->orderBy('created_at', 'desc')->get();
        return View('walikelas.classroom.show', compact('classroom', 'ngajar'));
    }
    /**
     * Classroom - Tampilkan Materi dan Latihan yang sudah di archived
     */
    public function classroomArchived(String $uuid): View
    {
        $ngajar = Ngajar::with('kelas', 'pelajaran', 'guru')->findOrFail($uuid);
        $classroom = Classroom::where([['id_ngajar', '=', $uuid], ['status', '=', 'arsip']])->orderBy('created_at', 'desc')->get();
        return View('walikelas.classroom.archived', compact('classroom', 'ngajar'));
    }
    /**
     * Classroom - Tampilkan Data Materi dan Latihan
     */
    public function classroomPreview(String $uuid, String $uuidClassroom): View
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
        $siswa = Siswa::where('id_kelas', $classroom->ngajar->id_kelas)->orderBy('nama', 'asc')->get();
        if ($classroom->file !== "") {
            $file_array = explode(',', $classroom->file);
        } else {
            $file_array = array();
        }

        if ($classroom->jenis == "latihan") {
            $jawabanAll = ClassroomJawaban::where('id_classroom', $classroom->uuid)->get();
            $jawaban_array = array();
            foreach ($jawabanAll as $item) {
                if ($item->selesai == 1) {
                    $jawaban_array[$item->id_siswa] = array(
                        'nilai' => $item->nilai,
                        'status' => $item->status
                    );
                }
            }
        } else {
            $jawaban_array = array();
        }
        return View('walikelas.classroom.preview', compact('classroom', 'file_array', 'status_array', 'jawaban_array', 'siswa', 'uuid'));
    }
}
