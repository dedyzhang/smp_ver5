<?php

use App\Models\Agenda;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JadwalVer;
use App\Models\JadwalWaktu;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Ngajar;
use App\Models\Pelajaran;
use App\Models\Siswa;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbsTrail;

Breadcrumbs::for('home',function(BreadcrumbsTrail $trail){
    $trail->push('Home',route('auth.home'));
});
// Guru.Breadcrumbs
Breadcrumbs::for('guru',function(BreadcrumbsTrail $trail){ $trail->push('Guru',route('guru.index')); });
Breadcrumbs::for('guru-create',function(BreadcrumbsTrail $trail){ $trail->parent('guru'); $trail->push('Create',route('guru.create')); });
Breadcrumbs::for('guru-edit',function(BreadcrumbsTrail $trail, Guru $guru){
    $trail->parent('guru');
    $trail->push($guru->nama,route('guru.edit',$guru));
});
Breadcrumbs::for('guru-pelajaran',function(BreadcrumbsTrail $trail, Guru $guru){
    $trail->parent('guru');
    $trail->push($guru->nama,route('guru.pelajaran',$guru));
});
// Kelas.Breadcrumbs
Breadcrumbs::for('kelas',function(BreadcrumbsTrail $trail){ $trail->push('Kelas',route('kelas.index'));});
Breadcrumbs::for('kelas-create',function(BreadcrumbsTrail $trail){ $trail->parent('kelas'); $trail->push('Create',route('kelas.create'));});
Breadcrumbs::for('kelas-set',function(BreadcrumbsTrail $trail){ $trail->parent('kelas'); $trail->push('Atur Kelas',route('kelas.setKelas'));});
Breadcrumbs::for('kelas-histori',function(BreadcrumbsTrail $trail){ $trail->parent('kelas-set'); $trail->push('Histori',route('kelas.historiRombel'));});
Breadcrumbs::for('kelas-edit',function(BreadcrumbsTrail $trail, Kelas $kelas){
    $trail->parent('kelas');
    $trail->push($kelas->tingkat.$kelas->kelas,route('kelas.edit',$kelas));
});
//Siswa.Breadcrumbs
Breadcrumbs::for('siswa',function(BreadcrumbsTrail $trail){ $trail->push('Siswa',route('siswa.index'));});
Breadcrumbs::for('siswa-create',function(BreadcrumbsTrail $trail){ $trail->parent('siswa'); $trail->push('Create',route('siswa.create'));});
Breadcrumbs::for('siswa-edit',function(BreadcrumbsTrail $trail, Siswa $siswa){
    $trail->parent('siswa');
    $trail->push($siswa->nama,route('siswa.edit',$siswa));
});
//Pelajaran.Breadcrumbs
Breadcrumbs::for('pelajaran',function(BreadcrumbsTrail $trail){ $trail->push('Pelajaran',route('pelajaran.index'));});
Breadcrumbs::for('pelajaran-create',function(BreadcrumbsTrail $trail){ $trail->parent('pelajaran'); $trail->push('Create',route('pelajaran.create'));});
Breadcrumbs::for('pelajaran-edit',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran){
    $trail->parent('pelajaran');
    $trail->push($pelajaran->pelajaran_singkat,route('pelajaran.edit',$pelajaran));
});
//Penilaian.kktp.Breadcrumbs
Breadcrumbs::for('penilaian-kktp',function(BreadcrumbsTrail $trail){ $trail->push('KKTP',route('penilaian.kktp.index'));});
//Penilaian.materi.Breadcrumbs
Breadcrumbs::for('penilaian-materi',function(BreadcrumbsTrail $trail){ $trail->push('Materi',route('penilaian.materi.index'));});
Breadcrumbs::for('penilaian-materi-show',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar){
    $trail->parent('penilaian-materi');
    $trail->push($pelajaran->pelajaran_singkat." ".$kelas->tingkat.$kelas->kelas,route('penilaian.materi.show',$ngajar));
});
//Penilaian.formatif.Breadcrumbs
Breadcrumbs::for('penilaian-formatif',function(BreadcrumbsTrail $trail){ $trail->push('Formatif',route('penilaian.formatif.index'));});
Breadcrumbs::for('penilaian-formatif-show',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar){
    $trail->parent('penilaian-formatif');
    $trail->push($pelajaran->pelajaran_singkat." ".$kelas->tingkat.$kelas->kelas,route('penilaian.formatif.show',$ngajar));
});
//Penilaian.Sumatif.Breadcrumbs
Breadcrumbs::for('penilaian-sumatif',function(BreadcrumbsTrail $trail){ $trail->push('Sumatif',route('penilaian.sumatif.index'));});
Breadcrumbs::for('penilaian-sumatif-show',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar){
    $trail->parent('penilaian-sumatif');
    $trail->push($pelajaran->pelajaran_singkat." ".$kelas->tingkat.$kelas->kelas,route('penilaian.sumatif.show',$ngajar));
});
//Penilaian.Pts.Breadcrumbs
Breadcrumbs::for('penilaian-pts',function(BreadcrumbsTrail $trail){ $trail->push('PTS',route('penilaian.pts.index'));});
Breadcrumbs::for('penilaian-pts-show',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar){
    $trail->parent('penilaian-pts');
    $trail->push($pelajaran->pelajaran_singkat." ".$kelas->tingkat.$kelas->kelas,route('penilaian.pts.show',$ngajar));
});
//Penilaian.PAS.Breadcrumbs
Breadcrumbs::for('penilaian-pas',function(BreadcrumbsTrail $trail){ $trail->push('PAS',route('penilaian.pas.index'));});
Breadcrumbs::for('penilaian-pas-show',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar){
    $trail->parent('penilaian-pas');
    $trail->push($pelajaran->pelajaran_singkat." ".$kelas->tingkat.$kelas->kelas,route('penilaian.pas.show',$ngajar));
});
//Penilaian.rapor.Breadcrumbs
Breadcrumbs::for('penilaian-rapor',function(BreadcrumbsTrail $trail){ $trail->push('Rapor',route('penilaian.rapor.index'));});
Breadcrumbs::for('penilaian-rapor-show',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar){
    $trail->parent('penilaian-rapor');
    $trail->push($pelajaran->pelajaran_singkat." ".$kelas->tingkat.$kelas->kelas,route('penilaian.rapor.show',$ngajar));
});
//Penilaian.Penjabaran.Breadcrumbs
Breadcrumbs::for('penilaian-penjabaran',function(BreadcrumbsTrail $trail){ $trail->push('Penjabaran',route('penilaian.penjabaran.index'));});
Breadcrumbs::for('penilaian-penjabaran-show',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar){
    $trail->parent('penilaian-penjabaran');
    $trail->push($pelajaran->pelajaran_singkat." ".$kelas->tingkat.$kelas->kelas,route('penilaian.penjabaran.show',$ngajar));
});


//Penilaian.Admin.Breadcrumbs
Breadcrumbs::for('penilaian',function(BreadcrumbsTrail $trail){ $trail->push('Penilaian',route('penilaian.admin.index'));});
Breadcrumbs::for('penilaian-admin-pts',function(BreadcrumbsTrail $trail){ $trail->push('PTS',route('penilaian.admin.pts'));});
Breadcrumbs::for('penilaian-admin-pts-showAll',function(BreadcrumbsTrail $trail, Kelas $kelas){
    $trail->parent('penilaian-admin-pts');
    $trail->push($kelas->tingkat.$kelas->kelas,route('penilaian.admin.pts.showAll',$kelas));
});
Breadcrumbs::for('penilaian-admin-pas',function(BreadcrumbsTrail $trail){ $trail->push('PAS',route('penilaian.admin.pas'));});
Breadcrumbs::for('penilaian-admin-pas-showAll',function(BreadcrumbsTrail $trail, Kelas $kelas){
    $trail->parent('penilaian-admin-pas');
    $trail->push($kelas->tingkat.$kelas->kelas,route('penilaian.admin.pas.showAll',$kelas));
});
Breadcrumbs::for('penilaian-admin-rapor',function(BreadcrumbsTrail $trail){ $trail->push('Rapor',route('penilaian.admin.rapor'));});
Breadcrumbs::for('penilaian-admin-rapor-showAll',function(BreadcrumbsTrail $trail, Kelas $kelas){
    $trail->parent('penilaian-admin-rapor');
    $trail->push($kelas->tingkat.$kelas->kelas,route('penilaian.admin.rapor.showAll',$kelas));
});

Breadcrumbs::for('penilaian-admin-materi-show',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar){
    $trail->parent('penilaian');
    $trail->push($pelajaran->pelajaran_singkat." ".$kelas->tingkat.$kelas->kelas,route('penilaian.admin.materi.show',$ngajar));
});
Breadcrumbs::for('penilaian-admin-formatif-show',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar){
    $trail->parent('penilaian');
    $trail->push($pelajaran->pelajaran_singkat." ".$kelas->tingkat.$kelas->kelas,route('penilaian.admin.formatif.show',$ngajar));
});
Breadcrumbs::for('penilaian-admin-sumatif-show',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar){
    $trail->parent('penilaian');
    $trail->push($pelajaran->pelajaran_singkat." ".$kelas->tingkat.$kelas->kelas,route('penilaian.admin.sumatif.show',$ngajar));
});
Breadcrumbs::for('penilaian-admin-pts-show',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar){
    $trail->parent('penilaian');
    $trail->push($pelajaran->pelajaran_singkat." ".$kelas->tingkat.$kelas->kelas,route('penilaian.admin.pts.show',$ngajar));
});
Breadcrumbs::for('penilaian-admin-pas-show',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar){
    $trail->parent('penilaian');
    $trail->push($pelajaran->pelajaran_singkat." ".$kelas->tingkat.$kelas->kelas,route('penilaian.admin.pas.show',$ngajar));
});
Breadcrumbs::for('penilaian-admin-rapor-show',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar){
    $trail->parent('penilaian');
    $trail->push($pelajaran->pelajaran_singkat." ".$kelas->tingkat.$kelas->kelas,route('penilaian.admin.rapor.show',$ngajar));
});
Breadcrumbs::for('penilaian-admin-penjabaran-show',function(BreadcrumbsTrail $trail, Pelajaran $pelajaran, Kelas $kelas, Ngajar $ngajar){
    $trail->parent('penilaian');
    $trail->push($pelajaran->pelajaran_singkat." ".$kelas->tingkat.$kelas->kelas,route('penilaian.admin.penjabaran.show',$ngajar));
});


//Absensi.Breadcrumbs
Breadcrumbs::for('absensi',function(BreadcrumbsTrail $trail){ $trail->push('Absensi',route('absensi.index'));});
Breadcrumbs::for('absensi-create',function(BreadcrumbsTrail $trail){
    $trail->parent('absensi');
    $trail->push('Tambah',route('absensi.create'));
});

//Jadwal.Breadcrumbs
Breadcrumbs::for('jadwal',function(BreadcrumbsTrail $trail){ $trail->push('Jadwal',route('jadwal.index'));});
Breadcrumbs::for('jadwal-create',function(BreadcrumbsTrail $trail){
    $trail->parent('jadwal');
    $trail->push('Create',route('jadwal.create'));
});
Breadcrumbs::for('jadwal-versi-show',function(BreadcrumbsTrail $trail,JadwalVer $versi){
    $trail->parent('jadwal');
    $trail->push('Ver. '.$versi->versi,route('jadwal.show',$versi));
});
Breadcrumbs::for('jadwal-versi-waktu',function(BreadcrumbsTrail $trail,JadwalVer $versi){
    $trail->parent('jadwal-versi-show',$versi);
    $trail->push('Waktu',route('jadwal.waktu.index',$versi));
});
Breadcrumbs::for('jadwal-versi-waktu-create',function(BreadcrumbsTrail $trail,JadwalVer $versi){
    $trail->parent('jadwal-versi-waktu',$versi);
    $trail->push('Create',route('jadwal.waktu.create',$versi));
});
Breadcrumbs::for('jadwal-versi-waktu-edit',function(BreadcrumbsTrail $trail,String $uuid,JadwalWaktu $waktu){
    $versi = JadwalVer::findOrFail($uuid);
    $trail->parent('jadwal-versi-waktu',$versi);
    $trail->push('Edit',route('jadwal.waktu.edit',['uuid' => $uuid,'waktuUUID' => $waktu->uuid]));
});

//Agenda.Breadcrumbs
Breadcrumbs::for('agenda',function(BreadcrumbsTrail $trail){ $trail->push('Agenda',route('agenda.index'));});
Breadcrumbs::for('agenda-create',function(BreadcrumbsTrail $trail){
    $trail->parent('agenda');
    $trail->push('Create',route('agenda.create'));
});
Breadcrumbs::for('agenda-edit',function(BreadcrumbsTrail $trail,Agenda $agenda, Kelas $kelas, Pelajaran $pelajaran, JadwalWaktu $waktu){
    $trail->parent('agenda');
    $trail->push($kelas->tingkat.$kelas->kelas." - ".$pelajaran->pelajaran_singkat."( ".$waktu->waktu_mulai." )",route('agenda.edit',$agenda->uuid));
});
