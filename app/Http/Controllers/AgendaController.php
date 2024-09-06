<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\AgendaAbsensi;
use App\Models\AgendaPancasila;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JadwalHari;
use App\Models\JadwalVer;
use App\Models\Semester;
use App\Models\TanggalAbsensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AgendaController extends Controller
{
    /**
     * Index - Halaman Pertama Agenda
     */
    public function index(): View
    {
        // $tanggal = '2024-06-17';
        $tanggal = date('Y-m-d');
        $semester = Semester::first();
        $sem = $semester->semester;
        $cekTanggal = TanggalAbsensi::where([
            ['tanggal', '=', $tanggal],
            ['agenda', '=', 1],
            ['semester', '=', $sem]
        ])->first();
        $hariKe = date('N', strtotime($tanggal));
        $hari = JadwalHari::where('no_hari', $hariKe)->first();
        $jadwal = "";
        $array_agenda = array();
        if ($hariKe <= 5) {
            if ($cekTanggal !== null) {
                $jadwalVer = JadwalVer::findOrFail($cekTanggal->id_jadwal);
                // $account = Auth::user()->uuid;
                $guru = Guru::where('id_login', Auth::user()->uuid)->first();
                $jadwal = Jadwal::with('pelajaran', 'kelas', 'waktu')->where([
                    ['id_jadwal', '=', $jadwalVer->uuid],
                    ['id_guru', '=', $guru->uuid],
                    ['id_hari', '=', $hari->uuid]
                ])->groupBy(['id_kelas', 'id_pelajaran'])->get();

                $cekAgenda = Agenda::where([
                    ['id_versi', '=', $jadwalVer->uuid],
                    ['tanggal', '=', $tanggal],
                    ['id_guru', '=', $guru->uuid]
                ])->get();


                foreach ($cekAgenda as $agenda) {
                    $array_agenda[$agenda->uuid] = $agenda->id_jadwal;
                }
            }
        } else {
            $jadwal = array();
        }
        return view('agenda.index', compact('cekTanggal', 'jadwal', 'array_agenda'));
    }
    /**
     * Create = Buat agenda baru guru
     */
    public function create(): View
    {
        return view('agenda.create');
    }
    /**
     * Create = Buat agenda baru guru
     */
    public function createWithID(): View
    {
        return view('agenda.create');
    }
    /**
     * Create - Buat agenda baru dengan cara copy agenda dari rekap
     */
    public function createWithCopy(String $uuid): View
    {
        $agenda = Agenda::findOrFail($uuid);
        return view('agenda.create', compact('agenda'));
    }

    /**
     * Show = Preview agenda by uuid
     */
    public function show(String $uuid)
    {
        $agenda = Agenda::with('absensi.siswa', 'pancasila.siswa', 'jadwal', 'jadwal.hari', 'jadwal.kelas', 'jadwal.pelajaran', 'jadwal.waktu')->findOrFail($uuid);

        return response()->json(['jadwal' => $agenda->jadwal, 'agenda' => $agenda]);
    }
    /**
     * Edit - Edit Agenda
     */
    public function edit(String $uuid): View
    {
        $agenda = Agenda::with('absensi.siswa', 'pancasila.siswa', 'jadwal.kelas', 'jadwal.pelajaran', 'jadwal.waktu', 'jadwal.kelas.siswa')->findOrFail($uuid);
        return View('agenda.edit', compact('agenda'));
    }
    /**
     * Update - Eksekusi edit agenda
     */
    public function update(Request $request, String $uuid)
    {

        $agenda = Agenda::findOrFail($uuid);
        $agenda->update([
            'pembahasan' => $request->pembahasan,
            'metode' => $request->metode,
            'proses' => $request->proses,
            'kegiatan' => $request->kegiatan,
            'kendala' => $request->kendala,
        ]);
    }
    /**
     * cektanggal - untuk mengecek tanggal
     */
    public function cektanggal(Request $request)
    {
        $tanggal = $request->tanggal;
        $semester = Semester::first();
        $sem = $semester->semester;
        $cekTanggal = TanggalAbsensi::where([
            ['tanggal', '=', $tanggal],
            ['agenda', '=', 1],
            ['semester', '=', $sem]
        ])->first();
        $hariKe = date('N', strtotime($tanggal));
        $hari = JadwalHari::where('no_hari', $hariKe)->first();

        if ($cekTanggal !== null) {
            $jadwalVer = JadwalVer::findOrFail($cekTanggal->id_jadwal);
            // $account = Auth::user()->uuid;
            $guru = Guru::where('id_login', Auth::user()->uuid)->first();
            $jadwal = Jadwal::with('pelajaran', 'kelas', 'waktu')->where([
                ['id_jadwal', '=', $jadwalVer->uuid],
                ['id_guru', '=', $guru->uuid],
                ['id_hari', '=', $hari->uuid]
            ])->groupBy(['id_kelas', 'id_pelajaran'])->get();
            return response()->json(["success" => true, "jadwal" => $jadwal]);
        } else {
            return response()->json(["success" => false, "message" => "tidak ada agenda pada tanggal ini"]);
        }
    }
    /**
     * cekjadwal - untuk mengecek jadwal
     */
    public function cekjadwal(Request $request)
    {
        $idJadwal = $request->idJadwal;
        $Jadwal = Jadwal::with('siswa')->findOrFail($idJadwal);

        if ($Jadwal->siswa->count() > 0) {
            return response()->json(["success" => true, "siswa" => $Jadwal->siswa]);
        } else {
            return response()->json(["success" => false, "message" => "tidak ada siswa di kelas ini"]);
        }
    }
    /**
     * Store - Untuk Menyimpan Data
     */
    public function store(Request $request)
    {
        $jadwal = Jadwal::findOrFail($request->jadwal);
        $semester = Semester::first();

        $agenda = Agenda::where([
            ['tanggal', '=', $request->tanggal],
            ['id_jadwal', '=', $jadwal->uuid],
        ])->get();
        if ($agenda->count() > 0) {
            return response()->json(["success" => false, "message" => "Tanggal dan jadwal ini sudah diisi"]);
        } else {
            $guru = Guru::where('id_login', Auth::user()->uuid)->first();
            $agenda = Agenda::create([
                'tanggal' => $request->tanggal,
                'id_versi' => $jadwal->id_jadwal,
                'id_jadwal' => $jadwal->uuid,
                'id_guru' => $guru->uuid,
                'pembahasan' => $request->pembahasan,
                'metode' => $request->metode,
                'proses' => $request->proses,
                'kegiatan' => $request->kegiatan,
                'kendala' => $request->kendala,
                'validasi' => "belum",
                'catatan_kepsek' => "",
                'semester' => $semester->semester
            ]);
            if (isset($request->absensi)) {
                $absensi_array = json_decode($request->absensi);
                $absensi_input = array();
                foreach ($absensi_array as $element) {
                    array_push($absensi_input, array(
                        'id_agenda' => $agenda->uuid,
                        'id_siswa' => $element->siswa,
                        'absensi' => $element->absensi,
                        'keterangan' => $element->keterangan
                    ));
                }
                AgendaAbsensi::upsert($absensi_input, ['uuid']);
            }
            if (isset($request->pancasila)) {
                $pancasila_array = json_decode($request->pancasila);
                $pancasila_input = array();
                foreach ($pancasila_array as $element) {
                    array_push($pancasila_input, array(
                        'id_agenda' => $agenda->uuid,
                        'id_guru' => $guru->uuid,
                        'tanggal' => $request->tanggal,
                        'id_siswa' => $element->siswa,
                        'dimensi' => $element->dimensi,
                        'keterangan' => $element->keterangan,
                        'semester' => $semester->semester
                    ));
                }
                AgendaPancasila::upsert($pancasila_input, ['uuid']);
            }
            return response()->json(["success" => true]);
        }
    }
    /**
     * store Absensi - tambah absensi di menu Edit
     */
    public function storeAbsensi(Request $request, String $uuid)
    {
        $agenda = Agenda::findOrFail($uuid);
        $cekAbsensi = AgendaAbsensi::where([
            ['id_agenda', '=', $agenda->uuid],
            ['id_siswa', '=', $request->siswa]
        ])->first();

        if ($cekAbsensi === null) {
            AgendaAbsensi::create([
                'id_agenda' => $agenda->uuid,
                'id_siswa' => $request->siswa,
                'absensi' => $request->absensi,
                'keterangan' => $request->keterangan
            ]);
            return response()->json(["success" => true]);
        } else {
            return response()->json(["success" => false, "message" => "sudah ada absensi siswa bersangkutan di agenda ini"]);
        }
    }
    /**
     * store Absensi - tambah absensi di menu Edit
     */
    public function storePancasila(Request $request, String $uuid)
    {
        $agenda = Agenda::findOrFail($uuid);
        $semester = Semester::first();
        $sem = $semester->semester;
        $cekPancasila = AgendaPancasila::where([
            ['id_agenda', '=', $agenda->uuid],
            ['id_siswa', '=', $request->siswa],
            ['dimensi', '=', $request->pancasila]
        ])->first();

        if ($cekPancasila === null) {
            AgendaPancasila::create([
                'id_agenda' => $agenda->uuid,
                'id_guru' => $agenda->id_guru,
                'tanggal' => $agenda->tanggal,
                'id_siswa' => $request->siswa,
                'dimensi' => $request->pancasila,
                'keterangan' => $request->keterangan,
                'semester' => $sem,
            ]);
            return response()->json(["success" => true]);
        } else {
            return response()->json(["success" => false, "message" => "sudah ada Penilaian profil pancasila siswa bersangkutan di agenda ini"]);
        }
    }
    /**
     * Delete Absensi
     */
    public function deleteAbsensi(String $uuid)
    {
        $absensi = AgendaAbsensi::findOrFail($uuid);
        $absensi->delete();
    }
    /**
     * Delete Pancasila
     */
    public function deletePancasila(String $uuid)
    {
        $pancasila = AgendaPancasila::findOrFail($uuid);
        $pancasila->delete();
    }

    public function history(): View
    {
        $tanggal = TanggalAbsensi::orderBy('tanggal')->get()->groupBy(function ($date) {
            return Carbon::parse($date->tanggal)->format('W Y');
        });
        return view('agenda.histori', compact('tanggal'));
    }
    /**
     * Guru Melihat Rekap Agenda Per minggunya
     */
    public function historyRekapAgenda(Request $request)
    {
        $guru = Guru::where('id_login', Auth::user()->uuid)->first();
        $minggu = explode(',', $request->minggu);
        $tanggal = TanggalAbsensi::whereIn('uuid', $minggu)->where('agenda', 1)->orderBy('tanggal', 'ASC')->get();
        $tanggal_array = $tanggal->pluck('tanggal');
        $tanggal_jadwal_array = $tanggal->pluck('id_jadwal', 'tanggal');
        $jadwal_array = array();
        $hari = JadwalHari::all()->pluck('uuid', 'no_hari');
        foreach ($tanggal_jadwal_array as $key => $value) {
            $hariKe = date('N', strtotime($key));
            $noHari = $hari[$hariKe];
            $jadwal = Jadwal::where([
                ['id_guru', '=', $guru->uuid],
                ['id_jadwal', '=', $value],
                ['id_hari', '=', $noHari]
            ])->groupBy(['id_kelas', 'id_pelajaran'])->get()->sortBy('waktu.waktu_mulai')->sortBy('hari.no_hari');

            foreach ($jadwal as $item) {
                array_push($jadwal_array, array(
                    'tanggal' => $key,
                    'uuid' => $item->uuid,
                    'versi' => $item->versi->versi,
                    'hari' => $item->hari->no_hari,
                    'waktu' => $item->waktu->waktu_mulai,
                    'pelajaran' => $item->pelajaran->pelajaran,
                    'kelas' => $item->kelas->tingkat . $item->kelas->kelas
                ));
            }
        }
        $agenda_array = Agenda::with('absensi.siswa', 'pancasila.siswa', 'jadwal')->whereIn('tanggal', $tanggal_array)->where('id_guru', $guru->uuid)->get()->sortBy('jadwal.waktu.waktu_mulai')->sortBy('tanggal')->toArray();
        // dd($agenda_array);
        return response()->json(['guru' => $guru, 'tanggal' => $tanggal, 'agenda_array' => $agenda_array, 'jadwal_array' => $jadwal_array]);
    }
    /**
     * -----------------------------------------------------
     * Untuk Admin dan Kurikulum, Kepala Sekolah
     * -----------------------------------------------------
     */
    public function rekap(): View
    {
        $tanggal = TanggalAbsensi::orderBy('tanggal')->get()->groupBy(function ($date) {
            return Carbon::parse($date->tanggal)->format('W Y');
        });
        return view('agenda.rekap.index', compact('tanggal'));
    }
    public function getTanggal(Request $request)
    {
        $minggu = explode(',', $request->minggu);
        $tanggal = TanggalAbsensi::whereIn('uuid', $minggu)->where('agenda', 1)->orderBy('tanggal', 'ASC')->get();
        $tanggal_array = $tanggal->pluck('tanggal');
        $agenda_array = array();
        $agenda = Agenda::with('jadwal', 'absensi', 'pancasila')->whereIn('tanggal', $tanggal_array)->get();
        foreach ($agenda as $element) {
            if (isset($agenda_array[$element->tanggal . "." . $element->id_guru])) {
                array_push($agenda_array[$element->tanggal . "." . $element->id_guru], array(
                    "tanggal" => $element->tanggal,
                    "id_versi" => $element->id_versi,
                    "id_jadwal" => $element->id_jadwal,
                    "id_guru" => $element->id_guru,
                    "pembahasan" => $element->pembahasan,
                    "metode" => $element->metode,
                    "proses" => $element->proses,
                    "kegiatan" => $element->kegiatan,
                    "kendala" => $element->kendala,
                    "semester" => $element->semester,
                    "absensi" => $element->absensi,
                    "pancasila" => $element->pancasila
                ));
            } else {
                $agenda_array[$element->tanggal . "." . $element->id_guru] = array();
                array_push($agenda_array[$element->tanggal . "." . $element->id_guru], array(
                    "tanggal" => $element->tanggal,
                    "id_versi" => $element->id_versi,
                    "id_jadwal" => $element->id_jadwal,
                    "id_guru" => $element->id_guru,
                    "pembahasan" => $element->pembahasan,
                    "metode" => $element->metode,
                    "proses" => $element->proses,
                    "kegiatan" => $element->kegiatan,
                    "kendala" => $element->kendala,
                    "semester" => $element->semester,
                    "absensi" => $element->absensi,
                    "pancasila" => $element->pancasila
                ));
            }
        }
        $access_array = array('guru', 'kurikulum', 'kesiswaan', 'sapras');
        $guru = User::with('guru')->whereIn('access', $access_array)->orderBy(Guru::select('nama')->whereColumn('gurus.id_login', 'users.uuid'))->get();
        return response()->json(['guru' => $guru, 'tanggal' => $tanggal, 'agenda' => $agenda_array]);
    }
    public function rekapGuru(String $idGuru, String $idMinggu)
    {
        $minggu = explode(',', $idMinggu);
        $tanggal = TanggalAbsensi::whereIn('uuid', $minggu)->where('agenda', 1)->orderBy('tanggal', 'ASC')->get();
        $tanggal_array = $tanggal->pluck('tanggal');
        $tanggal_jadwal_array = $tanggal->pluck('id_jadwal', 'tanggal');
        $guru = Guru::findOrFail($idGuru);
        $jadwal_array = array();
        $hari = JadwalHari::all()->pluck('uuid', 'no_hari');
        foreach ($tanggal_jadwal_array as $key => $value) {
            $hariKe = date('N', strtotime($key));
            $noHari = $hari[$hariKe];
            $jadwal = Jadwal::where([
                ['id_guru', '=', $guru->uuid],
                ['id_jadwal', '=', $value],
                ['id_hari', '=', $noHari]
            ])->groupBy(['id_kelas', 'id_pelajaran'])->get()->sortBy('waktu.waktu_mulai')->sortBy('hari.no_hari');

            foreach ($jadwal as $item) {
                $jadwal_array[$item->uuid] = array(
                    'tanggal' => $key,
                    'uuid' => $item->uuid,
                    'versi' => $item->versi->versi,
                    'hari' => $item->hari->no_hari,
                    'waktu' => $item->waktu->waktu_mulai,
                    'pelajaran' => $item->pelajaran->pelajaran,
                    'kelas' => $item->kelas->tingkat . $item->kelas->kelas
                );
            }
        }
        $agenda_array = Agenda::with('absensi.siswa', 'pancasila.siswa', 'jadwal')->whereIn('tanggal', $tanggal_array)->where('id_guru', $guru->uuid)->get()->sortBy('jadwal.waktu.waktu_mulai')->sortBy('tanggal')->toArray();
        // dd($agenda_array);
        return view('agenda.rekap.guru', compact('guru', 'tanggal', 'agenda_array', 'jadwal_array', 'idMinggu'));
    }
}
