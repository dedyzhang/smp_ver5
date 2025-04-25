<?php

use App\Models\Agenda;
use App\Models\Aturan;
use App\Models\Barang;
use App\Models\Classroom;
use App\Models\Ekskul;
use App\Models\Event;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JadwalVer;
use App\Models\JadwalWaktu;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Ngajar;
use App\Models\P5Fasilitator;
use App\Models\P5Proyek;
use App\Models\Pelajaran;
use App\Models\PerangkatAjar;
use App\Models\Ruang;
use App\Models\Siswa;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbsTrail;
use Illuminate\Support\Str;

Breadcrumbs::for('home', function (BreadcrumbsTrail $trail) {
    $trail->push('Home', route('auth.home'));
});
Breadcrumbs::for('ganti-password', function (BreadcrumbsTrail $trail) {
    $trail->push('Ganti Password', route('ganti.password'));
});
Breadcrumbs::for('profile', function (BreadcrumbsTrail $trail) {
    $trail->push('Profile', route('profile.index'));
});
Breadcrumbs::for('profile-edit', function (BreadcrumbsTrail $trail) {
    $trail->parent('profile');
    $trail->push('Edit', route('profile.edit'));
});
// Guru.Breadcrumbs
Breadcrumbs::for('guru', function (BreadcrumbsTrail $trail) {
    $trail->push('Guru', route('guru.index'));
});
Breadcrumbs::for('guru-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('guru');
    $trail->push('Create', route('guru.create'));
});
Breadcrumbs::for('guru-edit', function (BreadcrumbsTrail $trail, Guru $guru) {
    $trail->parent('guru');
    $trail->push($guru->nama, route('guru.edit', $guru));
});
Breadcrumbs::for('guru-pelajaran', function (BreadcrumbsTrail $trail, Guru $guru) {
    $trail->parent('guru');
    $trail->push($guru->nama, route('guru.pelajaran', $guru));
});
// Kelas.Breadcrumbs
Breadcrumbs::for('kelas', function (BreadcrumbsTrail $trail) {
    $trail->push('Kelas', route('kelas.index'));
});
Breadcrumbs::for('kelas-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('kelas');
    $trail->push('Create', route('kelas.create'));
});
Breadcrumbs::for('kelas-set', function (BreadcrumbsTrail $trail) {
    $trail->parent('kelas');
    $trail->push('Atur Kelas', route('kelas.setKelas'));
});
Breadcrumbs::for('kelas-histori', function (BreadcrumbsTrail $trail) {
    $trail->parent('kelas-set');
    $trail->push('Histori', route('kelas.historiRombel'));
});
Breadcrumbs::for('kelas-edit', function (BreadcrumbsTrail $trail, Kelas $kelas) {
    $trail->parent('kelas');
    $trail->push($kelas->tingkat . $kelas->kelas, route('kelas.edit', $kelas));
});
//Siswa.Breadcrumbs
Breadcrumbs::for('siswa', function (BreadcrumbsTrail $trail) {
    $trail->push('Siswa', route('siswa.index'));
});
Breadcrumbs::for('siswa-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('siswa');
    $trail->push('Create', route('siswa.create'));
});
Breadcrumbs::for('siswa-edit', function (BreadcrumbsTrail $trail, Siswa $siswa) {
    $trail->parent('siswa');
    $trail->push($siswa->nama, route('siswa.edit', $siswa));
});
//Pelajaran.Breadcrumbs
Breadcrumbs::for('pelajaran', function (BreadcrumbsTrail $trail) {
    $trail->push('Pelajaran', route('pelajaran.index'));
});
Breadcrumbs::for('pelajaran-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('pelajaran');
    $trail->push('Create', route('pelajaran.create'));
});
Breadcrumbs::for('pelajaran-edit', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran) {
    $trail->parent('pelajaran');
    $trail->push($pelajaran->pelajaran_singkat, route('pelajaran.edit', $pelajaran));
});

//Cetak.excel.Breadcrumbs
Breadcrumbs::for('cetak-siswa', function (BreadcrumbsTrail $trail) {
    $trail->push('Data Siswa', route('cetak.siswa.index'));
});
Breadcrumbs::for('cetak-absensi-guru', function (BreadcrumbsTrail $trail) {
    $trail->push('Absensi Guru', route('cetak.absensi.guru.index'));
});
Breadcrumbs::for('cetak-rapor', function (BreadcrumbsTrail $trail) {
    $trail->push('Rapor', route('cetak.rapor.index'));
});
Breadcrumbs::for('cetak-olahan', function (BreadcrumbsTrail $trail) {
    $trail->push('Olahan', route('cetak.olahan.index'));
});
Breadcrumbs::for('cetak-harian', function (BreadcrumbsTrail $trail) {
    $trail->push('Harian', route('cetak.harian.index'));
});
Breadcrumbs::for('cetak-pas', function (BreadcrumbsTrail $trail) {
    $trail->push('PAS', route('cetak.pas.index'));
});
Breadcrumbs::for('cetak-pts', function (BreadcrumbsTrail $trail) {
    $trail->push('PTS', route('cetak.pts.index'));
});
Breadcrumbs::for('cetak-penjabaran', function (BreadcrumbsTrail $trail) {
    $trail->push('Penjabaran', route('cetak.penjabaran.index'));
});
//Penilaian.kktp.Breadcrumbs
Breadcrumbs::for('penilaian-kktp', function (BreadcrumbsTrail $trail) {
    $trail->push('KKTP', route('penilaian.kktp.index'));
});
//Penilaian.materi.Breadcrumbs
Breadcrumbs::for('penilaian-materi', function (BreadcrumbsTrail $trail) {
    $trail->push('Materi', route('penilaian.materi.index'));
});
Breadcrumbs::for('penilaian-materi-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('penilaian-materi');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('penilaian.materi.show', $ngajar));
});
//Penilaian.formatif.Breadcrumbs
Breadcrumbs::for('penilaian-formatif', function (BreadcrumbsTrail $trail) {
    $trail->push('Formatif', route('penilaian.formatif.index'));
});
Breadcrumbs::for('penilaian-formatif-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('penilaian-formatif');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('penilaian.formatif.show', $ngajar));
});
//Penilaian.Sumatif.Breadcrumbs
Breadcrumbs::for('penilaian-sumatif', function (BreadcrumbsTrail $trail) {
    $trail->push('Sumatif', route('penilaian.sumatif.index'));
});
Breadcrumbs::for('penilaian-sumatif-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('penilaian-sumatif');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('penilaian.sumatif.show', $ngajar));
});
//Penilaian.Pts.Breadcrumbs
Breadcrumbs::for('penilaian-pts', function (BreadcrumbsTrail $trail) {
    $trail->push('PTS', route('penilaian.pts.index'));
});
Breadcrumbs::for('penilaian-pts-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('penilaian-pts');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('penilaian.pts.show', $ngajar));
});
//Penilaian.PAS.Breadcrumbs
Breadcrumbs::for('penilaian-pas', function (BreadcrumbsTrail $trail) {
    $trail->push('PAS', route('penilaian.pas.index'));
});
Breadcrumbs::for('penilaian-pas-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('penilaian-pas');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('penilaian.pas.show', $ngajar));
});
//Penilaian.rapor.Breadcrumbs
Breadcrumbs::for('penilaian-rapor', function (BreadcrumbsTrail $trail) {
    $trail->push('Rapor', route('penilaian.rapor.index'));
});
Breadcrumbs::for('penilaian-rapor-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('penilaian-rapor');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('penilaian.rapor.show', $ngajar));
});
//Penilaian.Penjabaran.Breadcrumbs
Breadcrumbs::for('penilaian-penjabaran', function (BreadcrumbsTrail $trail) {
    $trail->push('Penjabaran', route('penilaian.penjabaran.index'));
});
Breadcrumbs::for('penilaian-penjabaran-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('penilaian-penjabaran');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('penilaian.penjabaran.show', $ngajar));
});
//Penilaian.Poin.Breadcrumbs
Breadcrumbs::for('poin-guru', function (BreadcrumbsTrail $trail) {
    $trail->push('Poin', route('poin.guru.index'));
});
Breadcrumbs::for('poin-guru-tambah', function (BreadcrumbsTrail $trail) {
    $trail->parent('poin-guru');
    $trail->push('Create', route('poin.guru.create'));
});


//Penilaian.Admin.Breadcrumbs
Breadcrumbs::for('penilaian', function (BreadcrumbsTrail $trail) {
    $trail->push('Penilaian', route('penilaian.admin.index'));
});
Breadcrumbs::for('penilaian-admin-pts', function (BreadcrumbsTrail $trail) {
    $trail->push('PTS', route('penilaian.admin.pts'));
});
Breadcrumbs::for('penilaian-admin-pts-showAll', function (BreadcrumbsTrail $trail, Kelas $kelas) {
    $trail->parent('penilaian-admin-pts');
    $trail->push($kelas->tingkat . $kelas->kelas, route('penilaian.admin.pts.showAll', $kelas));
});
Breadcrumbs::for('penilaian-admin-pas', function (BreadcrumbsTrail $trail) {
    $trail->push('PAS', route('penilaian.admin.pas'));
});
Breadcrumbs::for('penilaian-admin-pas-showAll', function (BreadcrumbsTrail $trail, Kelas $kelas) {
    $trail->parent('penilaian-admin-pas');
    $trail->push($kelas->tingkat . $kelas->kelas, route('penilaian.admin.pas.showAll', $kelas));
});
Breadcrumbs::for('penilaian-admin-rapor', function (BreadcrumbsTrail $trail) {
    $trail->push('Rapor', route('penilaian.admin.rapor'));
});
Breadcrumbs::for('penilaian-admin-rapor-showAll', function (BreadcrumbsTrail $trail, Kelas $kelas) {
    $trail->parent('penilaian-admin-rapor');
    $trail->push($kelas->tingkat . $kelas->kelas, route('penilaian.admin.rapor.showAll', $kelas));
});
Breadcrumbs::for('penilaian-admin-rapor-individu', function (BreadcrumbsTrail $trail, Siswa $siswa) {
    $trail->parent('penilaian-admin-rapor-showAll', $siswa->kelas);
    $trail->push($siswa->nama, route('penilaian.admin.rapor.individu', $siswa->uuid));
});

Breadcrumbs::for('penilaian-admin-materi-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('penilaian');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('penilaian.admin.materi.show', $ngajar));
});
Breadcrumbs::for('penilaian-admin-formatif-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('penilaian');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('penilaian.admin.formatif.show', $ngajar));
});
Breadcrumbs::for('penilaian-admin-sumatif-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('penilaian');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('penilaian.admin.sumatif.show', $ngajar));
});
Breadcrumbs::for('penilaian-admin-pts-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('penilaian');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('penilaian.admin.pts.show', $ngajar));
});
Breadcrumbs::for('penilaian-admin-pas-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('penilaian');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('penilaian.admin.pas.show', $ngajar));
});
Breadcrumbs::for('penilaian-admin-rapor-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('penilaian');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('penilaian.admin.rapor.show', $ngajar));
});
Breadcrumbs::for('penilaian-admin-penjabaran-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('penilaian');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('penilaian.admin.penjabaran.show', $ngajar));
});


//Absensi.Breadcrumbs
Breadcrumbs::for('absensi', function (BreadcrumbsTrail $trail) {
    $trail->push('Absensi', route('absensi.index'));
});
Breadcrumbs::for('absensi-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('absensi');
    $trail->push('Tambah', route('absensi.create'));
});
Breadcrumbs::for('absensi-kehadiran', function (BreadcrumbsTrail $trail) {
    $trail->push('Kehadiran', route('absensi.kehadiran'));
});
Breadcrumbs::for('absensi-kehadiran-hadir', function (BreadcrumbsTrail $trail, String $jenis) {
    $trail->parent('absensi-kehadiran');
    $trail->push('Scan ' . $jenis, route('absensi.kehadiran.hadir', $jenis));
});
Breadcrumbs::for('absensi-histori', function (BreadcrumbsTrail $trail) {
    $trail->push('History', route('absensi.kehadiran.histori'));
});
Breadcrumbs::for('absensi-kehadiran-siswa', function (BreadcrumbsTrail $trail) {
    $trail->push('Kehadiran', route('absensi.siswa.kehadiran'));
});
Breadcrumbs::for('absensi-kehadiran-hadir-siswa', function (BreadcrumbsTrail $trail, String $jenis) {
    $trail->parent('absensi-kehadiran-siswa');
    $trail->push('Scan ' . $jenis, route('absensi.kehadiran.siswa.hadir', $jenis));
});
Breadcrumbs::for('absensi-histori-siswa', function (BreadcrumbsTrail $trail) {
    $trail->push('History', route('absensi.siswa.kehadiran.histori'));
});

//Jadwal.Breadcrumbs
Breadcrumbs::for('jadwal', function (BreadcrumbsTrail $trail) {
    $trail->push('Jadwal', route('jadwal.index'));
});
Breadcrumbs::for('jadwal-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('jadwal');
    $trail->push('Create', route('jadwal.create'));
});
Breadcrumbs::for('jadwal-versi-show', function (BreadcrumbsTrail $trail, JadwalVer $versi) {
    $trail->parent('jadwal');
    $trail->push('Ver. ' . $versi->versi, route('jadwal.show', $versi));
});
Breadcrumbs::for('jadwal-versi-waktu', function (BreadcrumbsTrail $trail, JadwalVer $versi) {
    $trail->parent('jadwal-versi-show', $versi);
    $trail->push('Waktu', route('jadwal.waktu.index', $versi));
});
Breadcrumbs::for('jadwal-versi-waktu-create', function (BreadcrumbsTrail $trail, JadwalVer $versi) {
    $trail->parent('jadwal-versi-waktu', $versi);
    $trail->push('Create', route('jadwal.waktu.create', $versi));
});
Breadcrumbs::for('jadwal-versi-waktu-edit', function (BreadcrumbsTrail $trail, String $uuid, JadwalWaktu $waktu) {
    $versi = JadwalVer::findOrFail($uuid);
    $trail->parent('jadwal-versi-waktu', $versi);
    $trail->push('Edit', route('jadwal.waktu.edit', ['uuid' => $uuid, 'waktuUUID' => $waktu->uuid]));
});

//Agenda.Breadcrumbs
Breadcrumbs::for('agenda', function (BreadcrumbsTrail $trail) {
    $trail->push('Agenda', route('agenda.index'));
});
Breadcrumbs::for('agenda-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('agenda');
    $trail->push('Create', route('agenda.create'));
});
Breadcrumbs::for('agenda-edit', function (BreadcrumbsTrail $trail, Agenda $agenda, Kelas $kelas, Pelajaran $pelajaran, JadwalWaktu $waktu) {
    $trail->parent('agenda');
    $trail->push($kelas->tingkat . $kelas->kelas . " - " . $pelajaran->pelajaran_singkat . "( " . $waktu->waktu_mulai . " )", route('agenda.edit', $agenda->uuid));
});
Breadcrumbs::for('agenda-history', function (BreadcrumbsTrail $trail) {
    $trail->push('Rekap', route('agenda.history'));
});
Breadcrumbs::for('agenda-rekap', function (BreadcrumbsTrail $trail) {
    $trail->push('Rekap', route('agenda.rekap'));
});
Breadcrumbs::for('agenda-rekap-guru', function (BreadcrumbsTrail $trail, Guru $guru, String $idMinggu) {
    $trail->parent('agenda-rekap');
    $trail->push($guru->nama, route('agenda.rekap.guru', ['idGuru' => $guru->uuid, 'idMinggu' => $idMinggu]));
});
//Classroom.Breadcrumbs
Breadcrumbs::for('classroom', function (BreadcrumbsTrail $trail) {
    $trail->push('Classroom', route('classroom.index'));
});
Breadcrumbs::for('classroom-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('classroom');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('classroom.show', $ngajar->uuid));
});
Breadcrumbs::for('classroom-arsip', function (BreadcrumbsTrail $trail, Ngajar $ngajar) {
    $trail->parent('classroom-show', $ngajar->pelajaran, $ngajar->kelas, $ngajar);
    $trail->push('Arsip', route('classroom.show', $ngajar->uuid));
});
Breadcrumbs::for('classroom-preview', function (BreadcrumbsTrail $trail, String $uuid, Classroom $classroom, Ngajar $ngajar) {
    $trail->parent('classroom-show', $ngajar->pelajaran, $ngajar->kelas, $ngajar);
    $trail->push("Preview", route('classroom.preview', ['uuid' => $uuid, 'uuidClassroom' => $classroom->uuid]));
});
Breadcrumbs::for('classroom-create', function (BreadcrumbsTrail $trail, Ngajar $ngajar, String $jenis) {
    $trail->parent('classroom-show', $ngajar->pelajaran, $ngajar->kelas, $ngajar);
    $trail->push("Create", route('classroom.create', ['uuid' => $ngajar->uuid, 'jenis' => $jenis]));
});
Breadcrumbs::for('classroom-edit', function (BreadcrumbsTrail $trail, Classroom $classroom, Ngajar $ngajar) {
    $trail->parent('classroom-show', $ngajar->pelajaran, $ngajar->kelas, $ngajar);
    $trail->push("Edit", route('classroom.edit', ['uuid' => $ngajar->uuid, 'uuidClassroom' => $classroom->uuid]));
});
Breadcrumbs::for('classroom-siswa', function (BreadcrumbsTrail $trail) {
    $trail->push('Classroom', route('classroom.siswa.index'));
});
Breadcrumbs::for('classroom-siswa-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('classroom-siswa');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('classroom.siswa.show', $ngajar->uuid));
});
Breadcrumbs::for('classroom-siswa-preview', function (BreadcrumbsTrail $trail, Classroom $classroom, Ngajar $ngajar) {
    $trail->parent('classroom-siswa-show', $ngajar->pelajaran, $ngajar->kelas, $ngajar);
    $trail->push("Preview", route('classroom.siswa.preview', ['uuid' => $classroom->uuid, 'uuidClassroom' => $classroom->uuid]));
});
//Classroom.admin.Breadcrumbs
Breadcrumbs::for('classroom-admin', function (BreadcrumbsTrail $trail) {
    $trail->push('Classroom', route('penilaian.classroom.index'));
});
Breadcrumbs::for('classroom-admin-show', function (BreadcrumbsTrail $trail, Kelas $kelas) {
    $trail->parent('classroom-admin');
    $trail->push($kelas->tingkat . $kelas->kelas, route('penilaian.classroom.show', $kelas->uuid));
});
Breadcrumbs::for('classroom-admin-class', function (BreadcrumbsTrail $trail, Ngajar $ngajar) {
    $trail->parent('classroom-admin-show', $ngajar->kelas);
    $trail->push($ngajar->pelajaran->pelajaran_singkat, route('penilaian.classroom.class', $ngajar->uuid));
});
Breadcrumbs::for('classroom-admin-preview', function (BreadcrumbsTrail $trail, String $uuid, Ngajar $ngajar, Classroom $classroom) {
    $trail->parent('classroom-admin-class', $ngajar);
    $trail->push('Preview', route('penilaian.classroom.preview', ['uuid' => $uuid, 'uuidClassroom' => $classroom->uuid]));
});
Breadcrumbs::for('classroom-admin-archived', function (BreadcrumbsTrail $trail, Ngajar $ngajar) {
    $trail->parent('classroom-admin-show', $ngajar->kelas);
    $trail->push('Archived', route('penilaian.classroom.archived', $ngajar->uuid));
});
//Poin.Breadcrumbs
Breadcrumbs::for('aturan', function (BreadcrumbsTrail $trail) {
    $trail->push('Aturan', route('aturan.index'));
});
Breadcrumbs::for('aturan-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('aturan');
    $trail->push('Create', route('aturan.create'));
});
Breadcrumbs::for('aturan-edit', function (BreadcrumbsTrail $trail, Aturan $aturan) {
    $trail->parent('aturan');
    $trail->push('Edit', route('aturan.edit', $aturan->uuid));
});
Breadcrumbs::for('poin', function (BreadcrumbsTrail $trail) {
    $trail->push('Poin', route('poin.index'));
});
Breadcrumbs::for('poin-show', function (BreadcrumbsTrail $trail, Siswa $siswa) {
    $trail->parent('poin');
    $trail->push($siswa->nama, route('poin.show', $siswa->uuid));
});
Breadcrumbs::for('poin-create', function (BreadcrumbsTrail $trail, Siswa $siswa) {
    $trail->parent('poin-show', $siswa);
    $trail->push('Create', route('poin.create', $siswa->uuid));
});
Breadcrumbs::for('pengajuan-poin', function (BreadcrumbsTrail $trail) {
    $trail->push('Pengajuan', route('temp.index'));
});
Breadcrumbs::for('pengajuan-poin-approve', function (BreadcrumbsTrail $trail) {
    $trail->parent('pengajuan-poin');
    $trail->push('Approve', route('temp.approve'));
});
Breadcrumbs::for('pengajuan-poin-disapprove', function (BreadcrumbsTrail $trail) {
    $trail->parent('pengajuan-poin');
    $trail->push('Disapprove', route('temp.disapprove'));
});
//Walikelas.Breadcrumbs
Breadcrumbs::for('walikelas-siswa', function (BreadcrumbsTrail $trail) {
    $trail->push('Data Siswa', route('walikelas.siswa'));
});
Breadcrumbs::for('walikelas-absensi', function (BreadcrumbsTrail $trail) {
    $trail->push('Absensi', route('walikelas.absensi'));
});
Breadcrumbs::for('walikelas-absensi-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('walikelas-absensi');
    $trail->push('Create', route('walikelas.absensi.create'));
});
Breadcrumbs::for('walikelas-poin', function (BreadcrumbsTrail $trail) {
    $trail->push('Poin', route('walikelas.poin'));
});
Breadcrumbs::for('walikelas-poin-show', function (BreadcrumbsTrail $trail, Siswa $siswa) {
    $trail->parent('walikelas-poin');
    $trail->push($siswa->nama, route('walikelas.poin.show', $siswa->uuid));
});
Breadcrumbs::for('walikelas-poin-temp', function (BreadcrumbsTrail $trail) {
    $trail->parent('walikelas-poin');
    $trail->push('Temp', route('walikelas.poin.temp'));
});
Breadcrumbs::for('walikelas-poin-temp-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('walikelas-poin-temp');
    $trail->push('Create', route('walikelas.poin.temp.create'));
});
Breadcrumbs::for('walikelas-classroom', function (BreadcrumbsTrail $trail) {
    $trail->push('Classroom', route('classroom.index'));
});
Breadcrumbs::for('walikelas-classroom-show', function (BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar) {
    $trail->parent('walikelas-classroom');
    $trail->push($pelajaran->pelajaran_singkat . " " . $kelas->tingkat . $kelas->kelas, route('classroom.show', $ngajar->uuid));
});
Breadcrumbs::for('walikelas-classroom-arsip', function (BreadcrumbsTrail $trail, Ngajar $ngajar) {
    $trail->parent('classroom-show', $ngajar->pelajaran, $ngajar->kelas, $ngajar);
    $trail->push('Arsip', route('classroom.show', $ngajar->uuid));
});
Breadcrumbs::for('walikelas-classroom-preview', function (BreadcrumbsTrail $trail, String $uuid, Classroom $classroom, Ngajar $ngajar) {
    $trail->parent('classroom-show', $ngajar->pelajaran, $ngajar->kelas, $ngajar);
    $trail->push("Preview", route('classroom.preview', ['uuid' => $uuid, 'uuidClassroom' => $classroom->uuid]));
});
Breadcrumbs::for('walikelas-rapor', function (BreadcrumbsTrail $trail) {
    $trail->push('Rapor', route('walikelas.rapor'));
});
Breadcrumbs::for('walikelas-rapor-show', function (BreadcrumbsTrail $trail, Siswa $siswa) {
    $trail->parent('walikelas-rapor');
    $trail->push($siswa->nama, route('walikelas.rapor.show', $siswa->uuid));
});
Breadcrumbs::for('walikelas-nilai', function (BreadcrumbsTrail $trail) {
    $trail->push('Nilai', route('walikelas.nilai'));
});
Breadcrumbs::for('walikelas-nilai-pts', function (BreadcrumbsTrail $trail) {
    $trail->parent('walikelas-nilai');
    $trail->push('PTS', route('walikelas.nilai.pts'));
});
Breadcrumbs::for('walikelas-nilai-pas', function (BreadcrumbsTrail $trail) {
    $trail->parent('walikelas-nilai');
    $trail->push('PAS', route('walikelas.nilai.pas'));
});
Breadcrumbs::for('walikelas-nilai-olahan', function (BreadcrumbsTrail $trail) {
    $trail->parent('walikelas-nilai');
    $trail->push('Olahan', route('walikelas.nilai.olahan'));
});
Breadcrumbs::for('walikelas-nilai-harian', function (BreadcrumbsTrail $trail) {
    $trail->parent('walikelas-nilai');
    $trail->push('Harian', route('walikelas.nilai.harian'));
});
Breadcrumbs::for('walikelas-nilai-harian-materi', function (BreadcrumbsTrail $trail, Ngajar $ngajar) {
    $trail->parent('walikelas-nilai-harian');
    $trail->push('Materi', route('walikelas.nilai.materi', $ngajar->uuid));
});
Breadcrumbs::for('walikelas-nilai-harian-formatif', function (BreadcrumbsTrail $trail, Ngajar $ngajar) {
    $trail->parent('walikelas-nilai-harian');
    $trail->push('Formatif', route('walikelas.nilai.formatif', $ngajar->uuid));
});
Breadcrumbs::for('walikelas-nilai-harian-sumatif', function (BreadcrumbsTrail $trail, Ngajar $ngajar) {
    $trail->parent('walikelas-nilai-harian');
    $trail->push('Sumatif', route('walikelas.nilai.sumatif', $ngajar->uuid));
});
Breadcrumbs::for('walikelas-nilai-harian-penjabaran', function (BreadcrumbsTrail $trail, Ngajar $ngajar) {
    $trail->parent('walikelas-nilai-harian');
    $trail->push('Penjabaran', route('walikelas.nilai.penjabaran', $ngajar->uuid));
});
Breadcrumbs::for('walikelas-nilai-proyek', function (BreadcrumbsTrail $trail) {
    $trail->parent('walikelas-nilai');
    $trail->push('P5', route('walikelas.nilai.proyek'));
});
Breadcrumbs::for('walikelas-nilai-proyek-print', function (BreadcrumbsTrail $trail, Siswa $siswa) {
    $trail->parent('walikelas-nilai-proyek');
    $trail->push($siswa->nama, route('walikelas.nilai.proyek.show', $siswa->uuid));
});
Breadcrumbs::for('walikelas-ruang', function (BreadcrumbsTrail $trail) {
    $trail->push('Ruangan', route('walikelas.ruang'));
});
Breadcrumbs::for('walikelas-ruang-create', function (BreadcrumbsTrail $trail, Ruang $ruang) {
    $trail->parent('walikelas-ruang');
    $trail->push('Create', route('walikelas.ruang.create', $ruang));
});
Breadcrumbs::for('walikelas-ruang-edit', function (BreadcrumbsTrail $trail, String $uuid, Barang $barang) {
    $trail->parent('walikelas-ruang');
    $trail->push($barang->barang, route('barang.edit', ['uuid' => $uuid, 'uuidBarang' => $barang]));
});
// Sekretaris.Breadcrumbs
Breadcrumbs::for('sekretaris-absensi', function (BreadcrumbsTrail $trail) {
    $trail->push('Absensi', route('sekretaris.absensi'));
});
Breadcrumbs::for('sekretaris-poin', function (BreadcrumbsTrail $trail) {
    $trail->push('Poin', route('sekretaris.poin'));
});
Breadcrumbs::for('sekretaris-poin-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('sekretaris-poin');
    $trail->push('Create', route('sekretaris.poin.create'));
});
//Sapras.Breadcrumbs
Breadcrumbs::for('ruang', function (BreadcrumbsTrail $trail) {
    $trail->push('Ruangan', route('ruang.index'));
});
Breadcrumbs::for('ruang-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('ruang');
    $trail->push('Create', route('ruang.create'));
});
Breadcrumbs::for('ruang-show', function (BreadcrumbsTrail $trail, Ruang $ruang) {
    $trail->parent('ruang');
    $trail->push($ruang->nama, route('ruang.show', $ruang->uuid));
});
Breadcrumbs::for('ruang-edit', function (BreadcrumbsTrail $trail, Ruang $ruang) {
    $trail->parent('ruang');
    $trail->push($ruang->nama, route('ruang.edit', $ruang));
});
Breadcrumbs::for('barang-edit', function (BreadcrumbsTrail $trail, String $uuid, Barang $barang) {
    $trail->parent('ruang-show', $barang->ruang);
    $trail->push($barang->barang, route('barang.edit', ['uuid' => $uuid, 'uuidBarang' => $barang]));
});
Breadcrumbs::for('barang-create', function (BreadcrumbsTrail $trail, Ruang $ruang) {
    $trail->parent('ruang-show', $ruang);
    $trail->push('Create', route('barang.create', $ruang));
});
//Penggunaan Sapras.Breadcrumbs
Breadcrumbs::for('penggunaan', function (BreadcrumbsTrail $trail) {
    $trail->push('Penggunaan Ruang', route('penggunaan.index'));
});
Breadcrumbs::for('penggunaan-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('penggunaan');
    $trail->push('Create', route('penggunaan.create'));
});
//Perangkat Ajar.Breadcrumbs
Breadcrumbs::for('perangkat', function (BreadcrumbsTrail $trail) {
    $trail->push('Perangkat', route('perangkat.index'));
});
Breadcrumbs::for('perangkat-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('perangkat');
    $trail->push('Create', route('perangkat.create'));
});
Breadcrumbs::for('perangkat-edit', function (BreadcrumbsTrail $trail, PerangkatAjar $perangkatAjar) {
    $trail->parent('perangkat');
    $trail->push('Edit', route('perangkat.edit', $perangkatAjar));
});
Breadcrumbs::for('perangkat-show', function (BreadcrumbsTrail $trail, Guru $guru) {
    $trail->parent('perangkat');
    $trail->push($guru->nama, route('perangkat.show', $guru));
});
Breadcrumbs::for('perangkat-guru', function (BreadcrumbsTrail $trail) {
    $trail->push('Perangkat', route('penilaian.perangkat.index'));
});
//Detail.Breadcrumbs
Breadcrumbs::for('detail-absensi', function (BreadcrumbsTrail $trail) {
    $trail->push('Info Absensi', route('detail.absensi.index'));
});
Breadcrumbs::for('detail-poin', function (BreadcrumbsTrail $trail) {
    $trail->push('Info Poin', route('detail.poin.index'));
});
Breadcrumbs::for('detail-nilai', function (BreadcrumbsTrail $trail) {
    $trail->push('Info Nilai', route('detail.nilai.index'));
});
Breadcrumbs::for('detail-nilai-show', function (BreadcrumbsTrail $trail, Ngajar $ngajar) {
    $trail->parent('detail-nilai');
    $trail->push($ngajar->pelajaran->pelajaran_singkat, route('detail.nilai.show', $ngajar->uuid));
});
//event.Breadcrumbs
Breadcrumbs::for('event-index', function (BreadcrumbsTrail $trail) {
    $trail->push('Event', route('event.index'));
});
Breadcrumbs::for('event-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('event-index');
    $trail->push('Create', route('event.create'));
});
Breadcrumbs::for('event-show', function (BreadcrumbsTrail $trail, Event $event) {
    $trail->parent('event-index');
    $trail->push(Str::limit($event->judul, 15, '...'), route('event.show', $event->uuid));
});
Breadcrumbs::for('event-edit', function (BreadcrumbsTrail $trail, Event $event) {
    $trail->parent('event-show', $event);
    $trail->push('Edit', route('event.edit', $event->uuid));
});
//Ekskul.Breadcrumbs
Breadcrumbs::for('ekskul-index', function (BreadcrumbsTrail $trail) {
    $trail->push('Ekskul', route('ekskul.index'));
});
Breadcrumbs::for('ekskul-create', function (BreadcrumbsTrail $trail) {
    $trail->parent('ekskul-index');
    $trail->push('Create', route('ekskul.create'));
});
Breadcrumbs::for('ekskul-edit', function (BreadcrumbsTrail $trail, Ekskul $ekskul) {
    $trail->parent('ekskul-index');
    $trail->push($ekskul->ekskul, route('ekskul.edit', $ekskul->uuid));
});
Breadcrumbs::for('ekskul-sort', function (BreadcrumbsTrail $trail) {
    $trail->parent('ekskul-index');
    $trail->push('Sort', route('ekskul.sort'));
});
Breadcrumbs::for('ekskul-nilai', function (BreadcrumbsTrail $trail) {
    $trail->push('Nilai Ekskul', route('ekskul.nilai.index'));
});
Breadcrumbs::for('ekskul-nilai-show', function (BreadcrumbsTrail $trail, Ekskul $ekskul) {
    $trail->parent('ekskul-nilai');
    $trail->push($ekskul->ekskul, route('ekskul.nilai.show', $ekskul->uuid));
});
//Proyek.Breadcrumbs
Breadcrumbs::for('proyek-index', function (BreadcrumbsTrail $trail) {
    $trail->push('Proyek', route('penilaian.p5.index'));
});
Breadcrumbs::for('proyek-atur-dimensi', function (BreadcrumbsTrail $trail) {
    $trail->parent('proyek-index');
    $trail->push('Atur Dimensi', route('penilaian.p5.atur'));
});
Breadcrumbs::for('proyek-tambah', function (BreadcrumbsTrail $trail) {
    $trail->parent('proyek-index');
    $trail->push('Atur Dimensi', route('penilaian.p5.create'));
});
Breadcrumbs::for('proyek-edit', function (BreadcrumbsTrail $trail, P5Proyek $p5Proyek) {
    $trail->parent('proyek-index');
    $trail->push($p5Proyek->judul, route('penilaian.p5.edit', $p5Proyek->uuid));
});
Breadcrumbs::for('proyek-edit-fasilitator', function (BreadcrumbsTrail $trail, P5Proyek $p5Proyek) {
    $trail->parent('proyek-index');
    $trail->push("Fasilitator " . $p5Proyek->judul, route('penilaian.p5.fasilitator', $p5Proyek->uuid));
});
Breadcrumbs::for('proyek-edit-dimensi', function (BreadcrumbsTrail $trail, P5Proyek $p5Proyek) {
    $trail->parent('proyek-index');
    $trail->push("Dimensi " . $p5Proyek->judul, route('penilaian.p5.config', $p5Proyek->uuid));
});
Breadcrumbs::for('proyek-nilai', function (BreadcrumbsTrail $trail, P5Proyek $p5Proyek, P5Fasilitator $p5Fasilitator) {
    $trail->parent('proyek-index');
    $trail->push("Proyek " . $p5Proyek->judul . " " . $p5Fasilitator->kelas->tingkat . $p5Fasilitator->kelas->kelas, route('penilaian.p5.nilai', $p5Fasilitator->uuid));
});
Breadcrumbs::for('proyek-index-guru', function (BreadcrumbsTrail $trail) {
    $trail->push('Proyek', route('penilaian.guru.proyek.index'));
});
Breadcrumbs::for('proyek-nilai-guru', function (BreadcrumbsTrail $trail, P5Proyek $p5Proyek, P5Fasilitator $p5Fasilitator) {
    $trail->parent('proyek-index-guru');
    $trail->push("Proyek " . $p5Proyek->judul . " " . $p5Fasilitator->kelas->tingkat . $p5Fasilitator->kelas->kelas, route('penilaian.guru.proyek.nilai', $p5Fasilitator->uuid));
});
Breadcrumbs::for('proyek-rapor', function (BreadcrumbsTrail $trail) {
    $trail->parent('proyek-index');
    $trail->push('Rapor', route('penilaian.p5.rapor'));
});
Breadcrumbs::for('proyek-rapor-kelas', function (BreadcrumbsTrail $trail, Kelas $kelas) {
    $trail->parent('proyek-rapor');
    $trail->push($kelas->tingkat . $kelas->kelas, route('penilaian.p5.rapor.show', $kelas->uuid));
});
Breadcrumbs::for('proyek-rapor-show', function (BreadcrumbsTrail $trail, Siswa $siswa) {
    $trail->parent('proyek-rapor-kelas', $siswa->kelas);
    $trail->push($siswa->nama, route('penilaian.p5.rapor.print', $siswa->uuid));
});
