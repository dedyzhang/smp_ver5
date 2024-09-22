<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\AbsensiSiswa;
use App\Models\Agenda;
use App\Models\Aturan;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JadwalHari;
use App\Models\JadwalVer;
use App\Models\Poin;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Setting;
use App\Models\TanggalAbsensi;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    public function index(): View
    {
        $sem = Semester::first();
        $semester = $sem->semester;
        $tanggal = TanggalAbsensi::with('jadwal')->get();
        $event = [];

        foreach ($tanggal as $tgl) {

            $agenda = $tgl->agenda == 1 ? "- agenda" : "";
            $siswa = $tgl->ada_siswa == 1 ? "- siswa" : "";

            if ($tgl->agenda == 1 && $tgl->ada_siswa == 1) {
                $colors = '#FFC470';
            } else if ($tgl->agenda == 1) {
                $colors = '#FA7070';
            } else if ($tgl->ada_siswa == 1) {
                $colors = "#7EA1FF";
            } else {
                $colors = '#007F73';
            }

            $event[] = [
                'title' => "S" . $tgl->semester . "- V" . $tgl->jadwal->versi . $agenda . $siswa,
                'start' => $tgl->tanggal,
                'end' => $tgl->tanggal,
                'color' => $colors,
            ];
        }
        return View('absensi.index', compact('event'));
    }
    public function get(Request $request)
    {
        $tanggal = array();
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $absensiTanggal = TanggalAbsensi::whereYear("tanggal", "=", $tahun)->whereMonth("tanggal", "=", $bulan)->get();

        return $absensiTanggal;
    }
    public function create(): View
    {
        $sem = Semester::first();
        $semester = $sem->semester;
        $jadwalVersi = JadwalVer::orderBy('versi')->get();
        return View('absensi.create', compact('semester', 'jadwalVersi'));
    }
    public function store(Request $request)
    {
        $tanggal = $request->tanggal;

        TanggalAbsensi::upsert($tanggal, ['tanggal'], ['agenda', 'ada_siswa']);
    }
    public function destroy(Request $request)
    {
        $tanggal = TanggalAbsensi::where('tanggal', $request->tanggal)->first();
        $tanggal->delete();
    }
    /**
     * Absensi Kehadiran
     */
    public function kehadiranIndex(): View
    {
        $auth = Auth::user();
        if ($auth->access !== "siswa" && $auth->access !== "orangtua") {
            $account = Guru::where('id_login', $auth->uuid)->first();
            $today = date('Y-m-d');
            $kehadiran_array = array();
            $array_agenda = array();
            $jadwal = "";
            $tanggal = TanggalAbsensi::where('tanggal', $today)->first();
            if ($tanggal !== null) {
                $adaTanggal = "ada";
                $kehadiran = AbsensiGuru::where([
                    ['id_tanggal', '=', $tanggal->uuid],
                    ['id_guru', '=', $account->uuid],
                ])->get();
                foreach ($kehadiran as $element) {
                    $kehadiran_array[$element->jenis] = $element->waktu;
                }
                if ($tanggal->agenda == 1) {
                    if ($auth->access == "kurikulum" || $auth->access == "kesiswaan" || $auth->access == "guru" || $auth->access == "sapras") {
                        $jadwalVer = JadwalVer::where('status', 'active')->first();
                        $hariKe = date('N', strtotime($tanggal->tanggal));
                        $hari = JadwalHari::where('no_hari', $hariKe)->first();
                        $jadwal = Jadwal::with('pelajaran', 'kelas', 'waktu')->where([
                            ['id_jadwal', '=', $jadwalVer->uuid],
                            ['id_guru', '=', $account->uuid],
                            ['id_hari', '=', $hari->uuid]
                        ])->groupBy(['id_kelas', 'id_pelajaran'])->get();

                        $cekAgenda = Agenda::where([
                            ['id_versi', '=', $jadwalVer->uuid],
                            ['tanggal', '=', $today],
                            ['id_guru', '=', $account->uuid]
                        ])->get();

                        foreach ($cekAgenda as $agenda) {
                            $array_agenda[$agenda->uuid] = $agenda->id_jadwal;
                        }
                    }
                }
            } else {
                $adaTanggal = "tidak";
            }
            return View('absensi.kehadiran.index', compact('account', 'adaTanggal', 'kehadiran_array', 'jadwal', 'array_agenda', 'auth'));
        }
    }

    /**
     * Absensi Kehadiran - Absen Hadir
     */
    public function kehadiranHadir(String $jenis): View
    {
        return View('absensi.kehadiran.hadir', compact('jenis'));
    }
    /**
     * Absensi Kehadiran - Store Absen Hadir
     */
    public function kehadiranStoreHadir(Request $request, String $jenis)
    {
        $auth = Auth::user();
        if ($auth->access !== "siswa" && $auth->access !== "orangtua") {
            $guru = Guru::where('id_login', $auth->uuid)->first();
            $today = date('Y-m-d');
            $tanggal = TanggalAbsensi::where('tanggal', $today)->first();

            if ($tanggal !== null) {
                if ($jenis == "datang") {
                    $token = '42630aad83430ada2c8178afb9720a11';
                } else {
                    $token = '2e2dffe1521f8199ff389060f563ad45';
                }
                if ($request->message == $token) {
                    $kehadiran = AbsensiGuru::where([
                        ['id_tanggal', '=', $tanggal->uuid],
                        ['id_guru', '=', $guru->uuid],
                        ['jenis', '=', $jenis]
                    ])->first();
                    if ($kehadiran === null) {
                        $waktu = date('H:i:s');
                        AbsensiGuru::create([
                            'id_tanggal' => $tanggal->uuid,
                            'id_guru' => $guru->uuid,
                            'jenis' => $jenis,
                            'waktu' => $waktu,
                        ]);
                        return response()->json(["success" => true, "data" => $guru->nama]);
                    } else {
                        return response()->json(["success" => false, "message" => "Sudah melakukan absensi hari ini"]);
                    }
                } else {
                    return response()->json(["success" => false, "message" => "Barcode tidak diketahui"]);
                }
            } else {
                return response()->json(["success" => false, "message" => "Tidak ada jadwal masuk hari ini"]);
            }
        }
    }
    /**
     * Absensi - Melihat histori dari absensi
     */
    public function kehadiranHistori(): View
    {
        $auth = Auth::user();
        $guru = Guru::where('id_login', $auth->uuid)->first();
        $sem = Semester::first();
        $semester = $sem->semester;

        $absensi = AbsensiGuru::where('id_guru', $guru->uuid)->get();
        $absensi_array = array();
        foreach ($absensi as $item) {
            if ($item->jenis == "datang") {
                $absensi_array['datang-' . $item->id_tanggal] = $item->waktu;
            } else {
                $absensi_array['pulang-' . $item->id_tanggal] = $item->waktu;
            }
        }
        $tanggal = TanggalAbsensi::where('semester', $semester)->orderBy('tanggal', 'ASC')->get();
        return View('absensi.kehadiran.histori', compact('absensi_array', 'tanggal'));
    }

    public function rekapAbsensi(Request $request)
    {
        $dari = date($request->dari);
        $sampai = date($request->sampai);

        $absensi = TanggalAbsensi::whereBetween('tanggal', [$dari, $sampai])->get();
        $tanggal_array = $absensi->map->only('uuid');
        $guru = Guru::orderBy('nama', 'asc')->get();
        $absensiGuru = AbsensiGuru::whereIn('id_tanggal', $tanggal_array)->get();
        $absen_array = array();
        foreach ($absensiGuru as $item) {
            $absen_array[$item->id_tanggal . "." . $item->id_guru][$item->jenis] = date('H:i', strtotime($item->waktu));
        }

        return response()->json(["tanggal" => $absensi, "absensi" => $absen_array, "guru" => $guru]);
    }

    /**
     * --------------------Siswa-----------------------
     */
    /**
     * Absensi Kehadiran
     */
    public function kehadiranSiswaIndex(): View
    {
        $auth = Auth::user();
        $account = Siswa::where('id_login', $auth->uuid)->first();
        $today = date('Y-m-d');
        $kehadiran_array = array();
        $jadwal = "";
        $tanggal = TanggalAbsensi::where('tanggal', $today)->first();
        if ($tanggal !== null && $tanggal->ada_siswa == 1) {
            $adaTanggal = "ada";
            $kehadiran = AbsensiSiswa::where([
                ['id_tanggal', '=', $tanggal->uuid],
                ['id_siswa', '=', $account->uuid],
            ])->first();
        } else {
            $kehadiran = "";
            $adaTanggal = "tidak";
        }
        return View('absensi.kehadiran.siswa.index', compact('account', 'adaTanggal', 'kehadiran', 'auth'));
    }
    /**
     * Absensi Kehadiran - Absen Hadir
     */
    public function kehadiranSiswaHadir(String $jenis): View
    {
        return View('absensi.kehadiran.siswa.hadir', compact('jenis'));
    }
    public function kehadiranSiswaStoreHadir(Request $request, String $jenis)
    {
        $auth = Auth::user();
        $siswa = Siswa::where('id_login', $auth->uuid)->first();
        $today = date('Y-m-d');
        $tanggal = TanggalAbsensi::where('tanggal', $today)->first();

        $tanggalqrcode = date('d/m/Y');
        $generatemd5 = md5($tanggalqrcode);

        if ($tanggal !== null) {
            // if ($jenis == "datang") {
            //     $token = 'f9e2221247d19748b0335821f5d48a1e';
            // } else {
            //     $token = '2e2dffe1521f8199ff389060f563ad45';
            // }
            if ($request->message == $generatemd5) {
                $kehadiran = AbsensiSiswa::where([
                    ['id_tanggal', '=', $tanggal->uuid],
                    ['id_siswa', '=', $siswa->uuid]
                ])->first();
                if ($kehadiran === null) {
                    $waktu = date('H:i:s');
                    AbsensiSiswa::create([
                        'id_tanggal' => $tanggal->uuid,
                        'id_siswa' => $siswa->uuid,
                        'waktu' => $waktu,
                        'absensi' => 'hadir',
                    ]);
                    //Kenakan Poin jika terlambat
                    $waktuTerlambat = Setting::where('jenis', 'waktu_terlambat_siswa')->first();
                    if ($waktuTerlambat && strtotime($waktu) >= strtotime($waktuTerlambat->nilai)) {
                        $poinTerlambat = Setting::where('jenis', 'poin_terlambat')->first();
                        if ($poinTerlambat != null && $poinTerlambat->nilai != null) {
                            $poin = Aturan::findOrFail($poinTerlambat->nilai);
                            $auth = Auth::user();
                            $siswa = Siswa::where('id_login', $auth->uuid)->first();
                            $tanggal = date('Y-m-d');
                            Poin::create([
                                'tanggal' => $tanggal,
                                'id_aturan' => $poin->uuid,
                                'id_siswa' => $siswa->uuid
                            ]);
                        }
                    }
                    return response()->json(["success" => true, "data" => $siswa->nama]);
                } else {
                    return response()->json(["success" => false, "message" => "Sudah melakukan absensi hari ini"]);
                }
            } else {
                return response()->json(["success" => false, "message" => "Barcode tidak diketahui"]);
            }
        } else {
            return response()->json(["success" => false, "message" => "Tidak ada jadwal masuk hari ini"]);
        }
    }
    /**
     * Absensi - Melihat histori dari absensi
     */
    public function kehadiranSiswaHistori(): View
    {
        $auth = Auth::user();
        $siswa = Siswa::where('id_login', $auth->uuid)->first();
        $sem = Semester::first();
        $semester = $sem->semester;

        $absensi = AbsensiSiswa::where('id_siswa', $siswa->uuid)->get();
        $absensi_array = array();
        foreach ($absensi as $item) {
            $absensi_array[$item->id_tanggal] = array(
                "waktu" => $item->waktu,
                "absensi" => $item->absensi,
                "keterangan" => $item->keterangan
            );
        }
        $tanggal = TanggalAbsensi::where('semester', $semester)->orderBy('tanggal', 'ASC')->get();
        return View('absensi.kehadiran.siswa.histori', compact('absensi_array', 'tanggal'));
    }
}
