<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\Aturan;
use App\Models\Barang;
use App\Models\Classroom;
use App\Models\ClassroomJawaban;
use App\Models\ClassroomSiswa;
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
use App\Models\PAS;
use App\Models\Pelajaran;
use App\Models\Poin;
use App\Models\PoinTemp;
use App\Models\PTS;
use App\Models\Rapor;
use App\Models\RaporManual;
use App\Models\Ruang;
use App\Models\RuangKelas;
use App\Models\Sekretaris;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\Sumatif;
use App\Models\TanggalAbsensi;
use App\Models\Walikelas;
use App\Models\P3Poin;
use App\Models\P3Temp;
use App\Models\P3Kategori;
use App\Models\P5Fasilitator;
use App\Models\P5ProyekDetail;
use App\Models\P5Nilai;
use App\Models\P5Deskripsi;
use Carbon\Carbon;
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
     * Get Absensi By Date
     */
    public function absensiGetByDate(Request $request)
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $dataSekolah = Semester::first();
        $semester = $dataSekolah->semester;

        $kelas = Kelas::with('siswa')->findOrFail($guru->walikelas->id_kelas);
        $tanggalAbsensi = TanggalAbsensi::where([
            ['ada_siswa', '=', 1],
            ['semester', '=', $semester]
        ])->whereBetween('tanggal', [$request->mulai, $request->akhir])->get();
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
        return response()->json([
            'success' => true,
            'absensi' => $absensi_array,
            'siswa' => $kelas->siswa,
            'jumlahAbsensi' => $jumlahAbsensi
        ]);
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
            return redirect()->back()->with(['success' => 'Poin Berhasil Diajukan, Silahkan menunggu update dari kesiswaan']);
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
    /**
     * Laporan Walikelas
     */
    public function laporanIndex(): View
    {
        return view('walikelas.laporan.index');
    }
    /**
     * Laporan Walikelas - Get Data
     */
    public function laporanGet(Request $request)
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $idKelas = $guru->walikelas->id_kelas;
        $kelas = Kelas::with('siswa')->findOrFail($idKelas);

        //Setting Awal
        $sem = Semester::first();
        $tp = explode('/', $sem->tp);


        //Get Absensi Siswa
        $bulan = $request->bulan;
        $year = $bulan >= 6 ? $tp[0] : $tp[1];

        $dari = Carbon::createFromFormat('n Y', $bulan . ' ' . $year)->startOfMonth()->toDateString();
        $sampai = Carbon::createFromFormat('n Y', $bulan . ' ' . $year)->endOfMonth()->toDateString();

        $absensi = TanggalAbsensi::whereBetween('tanggal', [$dari, $sampai])->where('ada_siswa', 1)->get();
        $absensi_array = $absensi->pluck('uuid')->toArray();
        $siswa_id_array = $kelas->siswa->pluck('uuid')->toArray();

        $absensi_siswa = AbsensiSiswa::selectRaw('
            COUNT(CASE WHEN absensi = "sakit" THEN 1 ELSE null END) as "sakit",
            COUNT(CASE WHEN absensi = "izin" THEN 1 ELSE null END) as "izin",
            COUNT(CASE WHEN absensi = "alpa" THEN 1 ELSE null END) as "alpa"
        ')->whereIn('id_tanggal', $absensi_array)->whereIn('id_siswa', $siswa_id_array)->first();
    }

    /**
     * Rapor Kelas
     */
    public function raporIndex(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $idKelas = $guru->walikelas->id_kelas;
        $kelas = Kelas::with('siswa')->findOrFail($idKelas);
        $semester = Semester::first();

        return view('walikelas.rapor.index', compact('kelas', 'guru', 'semester'));
    }
    /**
     * Rapor Kelas - Tampilan Halaman rapor
     */
    public function raporshow(String $uuid): View
    {
        $siswa = Siswa::with('kelas')->findOrFail($uuid);
        $setting = Setting::all();
        $semester = Semester::first();
        $walikelas = Walikelas::with('Guru')->where('id_kelas', $siswa->kelas->uuid)->first();

        $ngajar = Ngajar::with('pelajaran')->where('id_kelas', $siswa->kelas->uuid)->get()->sortBy('pelajaran.urutan', SORT_NATURAL);
        $raporSiswa = Rapor::where([['id_siswa', '=', $uuid], ['semester', '=', $semester->semester]])->get();
        $ekskul = Ekskul::all()->sortBy('urutan', SORT_NATURAL);
        $ekskulSiswa = EkskulSiswa::with('ekskul')->where([['id_siswa', '=', $uuid], ['semester', '=', $semester->semester]])->get();

        $jumlahHari = TanggalAbsensi::where([['ada_siswa', '=', 1], ['semester', '=', $semester->semester]])->get();
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
     * Tampilkan Halaman Nilai PAS - PTS dan Olahan Rekap Seluruh Kelas
     */

    /**
     * Halaman Nilai Utama
     */
    public function nilaiIndex(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $idKelas = $guru->walikelas->id_kelas;
        $kelas = Kelas::with('siswa')->findOrFail($idKelas);
        $setting = Setting::all();
        return view('walikelas.nilai.index', compact('kelas', 'setting'));
    }
    /**
     * Menampilkan Halaman Nilai PTS
     */
    public function nilaiPTS(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $idKelas = $guru->walikelas->id_kelas;
        $kelas = Kelas::with('siswa')->findOrFail($idKelas);
        $semester = Semester::first();
        $ngajar = Ngajar::with('guru')->select(['ngajar.*', 'pelajaran', 'pelajaran_singkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->where('id_kelas', $kelas->uuid)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')->get();
        $id_ngajar = array();
        foreach ($ngajar as $item) {
            array_push($id_ngajar, $item->uuid);
        }
        $pts_array = array();
        $pts = PTS::whereIn('id_ngajar', $id_ngajar)->where('semester', $semester->semester)->get();
        foreach ($pts as $item) {
            $pts_array[$item->id_ngajar . "." . $item->id_siswa] = $item->nilai;
        }
        $siswa = Siswa::where('id_kelas', $kelas->uuid)->orderBy('nama', 'ASC')->get();
        return view('penilaian.pts.all', compact('ngajar', 'kelas', 'siswa', 'pts_array'));
    }
    /**
     * Menampilkan Halaman Nilai PAS
     */
    public function nilaiPAS(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $idKelas = $guru->walikelas->id_kelas;
        $kelas = Kelas::with('siswa')->findOrFail($idKelas);
        $semester = Semester::first();
        $ngajar = Ngajar::with('guru')->select(['ngajar.*', 'pelajaran', 'pelajaran_singkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->where('id_kelas', $kelas->uuid)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')->get();
        $id_ngajar = array();
        foreach ($ngajar as $item) {
            array_push($id_ngajar, $item->uuid);
        }
        $pas_array = array();
        $pas = PAS::whereIn('id_ngajar', $id_ngajar)->where('semester', $semester->semester)->get();
        foreach ($pas as $item) {
            $pas_array[$item->id_ngajar . "." . $item->id_siswa] = $item->nilai;
        }
        $siswa = Siswa::where('id_kelas', $kelas->uuid)->orderBy('nama', 'ASC')->get();
        return view('penilaian.pas.all', compact('ngajar', 'kelas', 'siswa', 'pas_array'));
    }
    /**
     * Menampilkan Nilai Olahan
     */
    public function nilaiOlahan(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $idKelas = $guru->walikelas->id_kelas;
        $kelas = Kelas::with('siswa')->findOrFail($idKelas);
        $semester = Semester::first();
        $ngajar = Ngajar::with('guru')->select(['ngajar.*', 'pelajaran', 'pelajaran_singkat'])
            ->join('pelajaran', 'id_pelajaran', '=', 'pelajaran.uuid')
            ->where('id_kelas', $kelas->uuid)
            ->orderByRaw('length(pelajaran.urutan), pelajaran.urutan')->get();
        $id_ngajar = array();
        foreach ($ngajar as $item) {
            array_push($id_ngajar, $item->uuid);
        }
        $rapor_array = array();
        $rapor = Rapor::whereIn('id_ngajar', $id_ngajar)->where('semester', $semester->semester)->get();
        foreach ($rapor as $item) {
            $rapor_array[$item->id_ngajar . "." . $item->id_siswa] = array(
                "nilai" => $item->nilai,
                "positif" => $item->deskripsi_positif,
                "negatif" => $item->deskripsi_negatif
            );
        }
        $siswa = Siswa::where('id_kelas', $kelas->uuid)->orderBy('nama', 'ASC')->get();
        return view('penilaian.rapor.all', compact('ngajar', 'kelas', 'siswa', 'rapor_array'));
    }
    /**
     * Menampilkan Nilai Olahan
     */
    public function nilaiHarian(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $idKelas = $guru->walikelas->id_kelas;
        $kelas = Kelas::with('siswa')->findOrFail($idKelas);
        $semester = Semester::first();
        $pelajaran = Pelajaran::get()->sortBy('urutan');
        $setting = Setting::all();
        $akses_nilai = $setting->first(function ($elem) {
            return $elem->jenis == "akses_harian_walikelas";
        });

        if ($akses_nilai && $akses_nilai->nilai == 1) {
            return view('walikelas.nilai.harian.index', compact('pelajaran'));
        } else {
            return view('home.index');
        }
    }
    /**
     * Mengambil data nilai Ngajar Guru
     */
    public function nilaiHarianGet(String $uuid)
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $idKelas = $guru->walikelas->id_kelas;
        $kelas = Kelas::with('siswa')->findOrFail($idKelas);
        $semester = Semester::first();
        $ngajar = Ngajar::with('kelas', 'pelajaran', 'guru')->where([
            ['id_pelajaran', '=', $uuid],
            ['id_kelas', '=', $kelas->uuid]
        ])->get();
        return response()->json(["success" => true, "data" => $ngajar]);
    }
    /**
     * Menampilkan Materi
     */
    public function nilaiMateriShow(String $uuid)
    {
        $ngajar = Ngajar::with('pelajaran', 'kelas', 'guru', 'siswa')->findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $materi = Materi::with('tupe')->where([['id_ngajar', '=', $uuid], ['semester', '=', $sem]])->get();
        $setting = Setting::all();
        $akses_nilai = $setting->first(function ($elem) {
            return $elem->jenis == "akses_harian_walikelas";
        });

        if ($akses_nilai && $akses_nilai->nilai == 1) {
            return view("walikelas.nilai.harian.materi", compact('ngajar', 'materi'));
        } else {
            return view('home.index');
        }
    }
    /**
     * Menampilkan Formatif
     */
    public function nilaiFormatifShow(String $uuid)
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
        $setting = Setting::all();
        $akses_nilai = $setting->first(function ($elem) {
            return $elem->jenis == "akses_harian_walikelas";
        });

        if ($akses_nilai && $akses_nilai->nilai == 1) {
            return view("walikelas.nilai.harian.formatif", compact('ngajar', 'materi', 'count', 'formatif_array', 'materiArray', 'tupeArray'));
        } else {
            return view('home.index');
        }
    }
    /**
     * Menampilkan Sumatif
     */
    public function nilaiSumatifShow(String $uuid)
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
        $setting = Setting::all();
        $akses_nilai = $setting->first(function ($elem) {
            return $elem->jenis == "akses_harian_walikelas";
        });

        if ($akses_nilai && $akses_nilai->nilai == 1) {
            return view("walikelas.nilai.harian.sumatif", compact('ngajar', 'materi', 'count', 'sumatif_array', 'materiArray'));
        } else {
            return view('home.index');
        }
    }
    /**
     * Menampilkan Nilai Penjabaran
     */
    public function nilaiPenjabaranShow(String $uuid)
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

        $setting = Setting::all();
        $akses_nilai = $setting->first(function ($elem) {
            return $elem->jenis == "akses_harian_walikelas";
        });

        if ($akses_nilai && $akses_nilai->nilai == 1) {
            return View("walikelas.nilai.harian.penjabaran", compact('ngajar', 'penjabaran', 'jabaran', 'penjabaran_array', 'rata2Penjabaran'));
        } else {
            return view('home.index');
        }
    }
    /**
     * Tampilkan Nilai Proyek Kelas Walikelas Bersangkutan
     */
    public function nilaiProyek(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $idKelas = $guru->walikelas->id_kelas;
        $kelas = Kelas::with('siswa')->findOrFail($idKelas);
        $setting = Setting::all();
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
     * Tampilkan Nilai Proyek Siswa Walikelas Bersangkutan
     */
    public function nilaiProyekShow(String $uuid): View
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
     * P3 - PELANGGARAN,PRESTASI DAN PARTISIPASI
     */

    /**
     * P3 - Show Poin siswa per kelas
     */
    public function p3Index(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $idKelas = $guru->walikelas->id_kelas;

        $siswa = Siswa::with('kelas')->where('id_kelas', $idKelas)->get()->sortBy('nama')->sortBy('kelas.kelas')->sortBy('kelas.tingkat');
        $p3 = P3Poin::with('siswa')->get();
        $array_p3 = array();

        foreach ($p3 as $item) {
            if (empty($array_p3[$item->id_siswa])) {
                $array_p3[$item->id_siswa] = array(
                    'pelanggaran' => 0,
                    'prestasi' => 0,
                    'partisipasi' => 0
                );
                $array_p3[$item->id_siswa][$item->jenis] += 1;
            } else {
                $array_p3[$item->id_siswa][$item->jenis] += 1;
            }
        }
        // dd($array_p3);
        return view('walikelas.p3.index', compact('siswa', 'array_p3'));
    }
    /**
     * P3 - Tampilkan Poin P3 Per siswa
     */
    public function p3Show(String $uuid): View
    {
        $siswa = Siswa::with('kelas')->findOrFail($uuid);
        $p3 = P3Poin::where('id_siswa', $siswa->uuid)->orderBy('tanggal')->get();

        return view('walikelas.p3.show', compact('siswa', 'p3'));
    }
    /**
     * P3 Temp - P3 Temporary Index
     */
    public function p3TempIndex(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $idKelas = $guru->walikelas->id_kelas;

        $siswa = Siswa::with('kelas')->where('id_kelas', $idKelas)->get()->sortBy('nama')->sortBy('kelas.kelas')->sortBy('kelas.tingkat');
        $id_siswa = $siswa->pluck('uuid');
        $p3_temp = P3Temp::whereIn('id_siswa', $id_siswa)->orderBy('created_at', 'DESC')->get();

        $all_siswa = Siswa::all();
        $all_guru = Guru::all();


        return view('walikelas.p3.temp.index', compact('siswa', 'p3_temp', 'all_siswa', 'all_guru'));
    }
    /**
     * P3 Temp - Get Bahasa Dari Temp
     */
    public function p3GetKategori(Request $request)
    {
        $kategori = P3Kategori::where('jenis', $request->jenis)->get();

        return response()->json(['kategori' => $kategori, 'message' => 'success']);
    }
    /**
     * P3 Temp - Create P3 Temporary Page
     */
    public function p3TempCreate(): View
    {
        $auth = Auth::user();
        $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
        $idKelas = $guru->walikelas->id_kelas;

        $siswa = Siswa::with('kelas')->where('id_kelas', $idKelas)->get()->sortBy('nama')->sortBy('kelas.kelas')->sortBy('kelas.tingkat');

        return view('walikelas.p3.temp.create', compact('siswa'));
    }
    /**
     * P3 Temp - Store P3 Temporary
     */
    public function p3TempStore(Request $request)
    {

        $semester = Semester::first();

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

        $request->validate([
            'nama' => 'required',
            'tanggal' => 'required',
            'jenis' => 'required',
            'deskripsi' => 'required',
        ]);

        P3Temp::create([
            'id_siswa' => $request->nama,
            'yang_mengajukan' => $penginput,
            'id_pengajuan' => $id_penginput,
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi,
            'status' => 'belum',
            'semester' => $semester->semester,
        ]);
        if ($auth->access != "siswa") {
            return redirect()->back()->with(['success' => 'P3 Berhasil Ditambahkan, Silahkan menunggu update dari kesiswaan']);
        } else {
            return redirect()->route('sekretaris.p3')->with(['success' => 'Poin Berhasil Diajukan, Silahkan menunggu update dari kesiswaan']);
        }
    }
    /**
     * P3 Temp - Delete P3 Temporary
     */
    public function p3TempDelete(String $uuid)
    {
        $p3Temp = P3Temp::findOrFail($uuid);
        $p3Temp->delete();
        return response()->json(['success' => true]);
    }
}
