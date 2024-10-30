<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CetakController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\EkskulController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PelajaranController;
use App\Http\Controllers\PengRuangController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PerangkatAjarController;
use App\Http\Controllers\PoinController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SekretarisController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\WalikelasController;
use Illuminate\Support\Facades\Route;

//Middleware
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsAdminKesiswaan;
use App\Http\Middleware\IsAdminKurikulum;
use App\Http\Middleware\IsAdminKurikulumKepala;
use App\Http\Middleware\IsAdminSapras;
use App\Http\Middleware\IsGuru;
use App\Http\Middleware\IsKurikulum;
use App\Http\Middleware\isNgajar;
use App\Http\Middleware\isPenilaianController;
use App\Http\Middleware\IsSekretaris;
use App\Http\Middleware\IsSiswa;
use App\Http\Middleware\IsSiswaOrangtua;
use App\Http\Middleware\IsWalidanSekre;
use App\Http\Middleware\IsWalikelas;
use App\Http\Middleware\IsWaliSekredanGuru;

Route::get('/', function () {
    return view('home.index');
});

//Route Login
Route::get('/signin', function () {
    return view('auth.login');
})->name('login')->middleware('guest');
Route::get('/privacy-policy', function () {
    return view('privacypolicy');
});
Route::get('/redirect', [AppController::class, 'redirect']);
Route::post('/redirect', [AppController::class, 'redirectUpdate'])->name('redirect.update');
Route::get('/qrcode', [AppController::class, 'qrcode']);
Route::post('/gemini', [AppController::class, 'getGemini'])->name('gemini.get')->middleware('auth');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/ganti-password', [LoginController::class, 'changePassword'])->name('ganti.password')->middleware('auth');
Route::post('/ganti-password', [LoginController::class, 'gantiPassword']);
Route::post('/ganti-password-request', [LoginController::class, 'gantiPasswordRequest']);
Route::get('/home', [LoginController::class, 'index'])->name('auth.home')->middleware('auth');
Route::middleware('auth')->controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'index')->name('profile.index');
    Route::get('/profile/edit', 'edit')->name('profile.edit');
    Route::put('/profile/update', 'update')->name('profile.update');
});
//Ujian App
Route::get('/redirectUjian', [AppController::class, 'ujian'])->name('ujian.index')->middleware('auth');

//Admin - Halaman Data Guru
Route::resource('/guru', \App\Http\Controllers\GuruController::class)->middleware(IsAdmin::class);
Route::middleware(IsAdmin::class)->controller(GuruController::class)->group(function () {
    Route::post('/guru/{uuid}/reset', 'reset')->name('guru.reset');
    Route::get('/guru/{uuid}/pelajaran', 'pelajaran')->name('guru.pelajaran');
    Route::post('/guru/{uuid}/pelajaran', 'ngajar')->name('guru.ngajar');
    Route::delete('/guru/pelajaran/{uuid}/hapus', 'hapusNgajar')->name('guru.hapusNgajar');
});
//Admin - Halaman Data Kelas
Route::resource('/kelas', KelasController::class)->except('show')->middleware(IsAdmin::class);
Route::middleware(IsAdmin::class)->controller(KelasController::class)->group(function () {
    Route::post('/kelas/{uuid}/walikelas', 'walikelas')->name('kelas.walikelas');
    Route::get('/kelas/{uuid}/walikelas', 'showWalikelas')->name('kelas.walikelas');
    Route::get('/kelas/setKelas', 'setKelasSiswa')->name('kelas.setKelas');
    Route::post('/kelas/{uuid}/saveRombel', 'saveRombel')->name('kelas.saveRombel');
    Route::get('/kelas/setKelas/histori', 'historiRombel')->name('kelas.historiRombel');
    Route::post('/kelas/setKelas/histori/{uuid}/hapus', 'historiHapus')->name('kelas.historiHapus');
    Route::get('/kelas/{uuid}/ruangan', 'showRuanganKelas')->name('kelas.ruang');
    Route::post('/kelas/{uuid}/ruangan', 'updateRuanganKelas')->name('kelas.ruang.update');
});

//Admin - Halaman Data Siswa
Route::resource('/siswa', SiswaController::class)->middleware(IsAdmin::class);
Route::middleware(IsAdmin::class)->controller(SiswaController::class)->group(function () {
    Route::post('/siswa/{uuid}/reset/siswa', 'resetSiswa')->name('siswa.reset');
    Route::post('/siswa/{uuid}/reset/password', 'resetOrangtua')->name('siswa.resetOrtu');
});

//Admin - Halaman Data Pelajaran
Route::resource('/pelajaran', PelajaranController::class)->except('show')->middleware(IsAdmin::class);
Route::middleware(IsAdmin::class)->controller(PelajaranController::class)->group(function () {
    Route::get('/pelajaran/sort', 'sort')->name('pelajaran.sort');
    Route::post('/pelajaran/sort', 'sorting')->name('pelajaran.sorting');
    Route::get('/pelajaran/penjabaran', 'getPenjabaran')->name('pelajaran.penjabaran');
    Route::post('/pelajaran/penjabaran', 'setPenjabaran')->name('pelajaran.penjabaran');
});

//Admin - Halaman Data Setting
Route::middleware(IsAdmin::class)->controller(SettingController::class)->group(function () {
    Route::get('/settings', 'index')->name('setting.index');
    Route::post('/settings/semester', 'updateSemester')->name('setting.semester');
    Route::post('/settings/nis', 'updatenis')->name('setting.nis');
    Route::post('/settings/setPoinTerlambat', 'setPoinTerlambat')->name('setting.poinTerlambat');
    Route::post('/settings/setWaktuTerlambat', 'setWaktuTerlambat')->name('setting.waktuTerlambat');
    Route::post('/settings/identitasSekolah', 'setIdentitasSekolah')->name('setting.identitas.sekolah');
    Route::post('/settings/raporPelajaran', 'setMapelRapor')->name('setting.rapor.pelajaran');
    Route::post('/settings/tanggalRapor', 'setTanggalRapor')->name('setting.rapor.tanggal');
});

//Admin - Halaman Cetak Excel
Route::middleware(IsAdmin::class)->controller(CetakController::class)->group(function () {
    Route::get('/cetak/siswa', 'siswa')->name('cetak.siswa.index');
    Route::get('/cetak/siswa/{params}', 'cetakSiswa')->name('cetak.siswa.excel');
    Route::get('/cetak/guru', 'guru')->name('cetak.guru.excel');
    Route::get('/cetak/absensi/guru', 'absensiGuru')->name('cetak.absensi.guru.index');
    Route::get('/cetak/absensi/guru/{dari}/{sampai}', 'cetakAbsensiGuru')->name('cetak.absensi.guru.excel');
});

// {----------------------------------Halaman Penilaian dan Pelajaran------------------------------------------------------}

//Admin - Halaman Data Penilaian
Route::middleware(IsAdminKurikulumKepala::class)->controller(PenilaianController::class)->group(function () {
    //Penilaian Index
    Route::get('/penilaian', 'index')->name('penilaian.admin.index');
    Route::get('/penilaian/{uuid}/get', 'get')->name('penilaian.admin.get');
    //Show All PTS
    Route::get('/penilaian/pts', 'ptsIndexAll')->name('penilaian.admin.pts');
    Route::get('/penilaian/pts/{uuid}/showAll', 'ptsShowAll')->name('penilaian.admin.pts.showAll');
    //show All PAS
    Route::get('/penilaian/pas', 'pasIndexAll')->name('penilaian.admin.pas');
    Route::get('/penilaian/pas/{uuid}/showAll', 'pasShowAll')->name('penilaian.admin.pas.showAll');
    //show All Rapor
    Route::get('/penilaian/rapor', 'raporIndexAll')->name('penilaian.admin.rapor');
    Route::get('/penilaian/rapor/{uuid}/showAll', 'raporShowAll')->name('penilaian.admin.rapor.showAll');
    //Materi
    Route::get('/penilaian/materi/{uuid}/show', 'materiShow')->name('penilaian.admin.materi.show');
    //formatif
    Route::get('/penilaian/formatif/{uuid}/show', 'formatifShow')->name('penilaian.admin.formatif.show');
    //Sumatif
    Route::get('/penilaian/sumatif/{uuid}/show', 'sumatifShow')->name('penilaian.admin.sumatif.show');
    //pts
    Route::get('/penilaian/pts/{uuid}/show', 'ptsShow')->name('penilaian.admin.pts.show');
    //pas
    Route::get('/penilaian/pas/{uuid}/show', 'pasShow')->name('penilaian.admin.pas.show');
    //Rapor
    Route::get('/penilaian/rapor/{uuid}/show', 'raporShow')->name('penilaian.admin.rapor.show');
    //Penjabaran
    Route::get('/penilaian/penjabaran/{uuid}/show', 'penjabaranShow')->name('penilaian.admin.penjabaran.show');
    //Rapor Manual
    Route::get('/penilaian/manual', 'manual')->name('penilaian.admin.manual');
    Route::get('/penilaian/manual/getNilai', 'manualGetNilai')->name('penilaian.admin.manual.getNilai');
    Route::post('/penilaian/manual/{uuid}/create', 'manualCreate')->name('penilaian.admin.manual.create');
    Route::get('/penilaian/manual/history', 'manualHistory')->name('penilaian.admin.manual.history');
    Route::get('/penilaian/manual/{uuid}/edit', 'manualEdit')->name('penilaian.admin.manual.edit');
    Route::put('/penilaian/manual/{uuid}/update', 'manualUpdate')->name('penilaian.admin.manual.update');
    Route::delete('/penilaian/manual/{uuid}/delete', 'manualDelete')->name('penilaian.admin.manual.delete');
});
//Guru - Halaman Buku Guru Penilaian
Route::middleware(isNgajar::class)->controller(PenilaianController::class)->group(function () {
    //kktp
    Route::get('/bukuguru/kktp', 'kktpIndex')->name('penilaian.kktp.index');
    Route::post('/bukuguru/kktp', 'kktpEdit')->name('penilaian.kktp.edit');
    //Materi
    Route::get('/bukuguru/materi', 'materiIndex')->name('penilaian.materi.index');
    Route::get('/bukuguru/materi/{uuid}/show', 'materiShow')->name('penilaian.materi.show');
    //formatif
    Route::get('/bukuguru/formatif', 'formatifIndex')->name('penilaian.formatif.index');
    Route::get('/bukuguru/formatif/{uuid}/show', 'formatifShow')->name('penilaian.formatif.show');
    //Sumatif
    Route::get('/bukuguru/sumatif', 'sumatifIndex')->name('penilaian.sumatif.index');
    Route::get('/bukuguru/sumatif/{uuid}/show', 'sumatifShow')->name('penilaian.sumatif.show');
    //pts
    Route::get('/bukuguru/pts', 'ptsIndex')->name('penilaian.pts.index');
    Route::get('/bukuguru/pts/{uuid}/show', 'ptsShow')->name('penilaian.pts.show');
    //pas
    Route::get('/bukuguru/pas', 'pasIndex')->name('penilaian.pas.index');
    Route::get('/bukuguru/pas/{uuid}/show', 'pasShow')->name('penilaian.pas.show');
    //Rapor
    Route::get('/bukuguru/rapor', 'raporIndex')->name('penilaian.rapor.index');
    Route::get('/bukuguru/rapor/{uuid}/show', 'raporShow')->name('penilaian.rapor.show');
    //Perangkat Ajar
    Route::get('/bukuguru/perangkat', 'perangkat')->name('penilaian.perangkat.index');
    //Penjabaran
    Route::get('/bukuguru/penjabaran', 'penjabaranIndex')->name('penilaian.penjabaran.index');
    Route::get('/bukuguru/penjabaran/{uuid}/show', 'penjabaranShow')->name('penilaian.penjabaran.show');
});
//Admin - Guru - Action untuk Penilaian
Route::middleware(isPenilaianController::class)->controller(PenilaianController::class)->group(function () {
    //Materi
    Route::post('/bukuguru/materi/{uuid}/create', 'materiCreate')->name('penilaian.materi.create');
    Route::put('/bukuguru/materi/{uuid}/edit/', 'materiUpdate')->name('penilaian.materi.edit');
    Route::delete('/bukuguru/materi/{uuid}/delete/', 'materiDelete')->name('penilaian.materi.delete');
    Route::post('/bukuguru/materi/{uuid}/createTupe/', 'materiCreateTupe')->name('penilaian.materi.createTupe');
    Route::post('/bukuguru/materi/{uuid}/updateTupe/', 'materiUpdateTupe')->name('penilaian.materi.updateTupe');
    Route::delete('/bukuguru/materi/{uuid}/deleteTupe/', 'materiDeleteTupe')->name('penilaian.materi.deleteTupe');
    //formatif
    Route::put('/bukuguru/formatif/edit', 'formatifEdit')->name('penilaian.formatif.edit');
    //Sumatif
    Route::put('/bukuguru/sumatif/edit', 'sumatifEdit')->name('penilaian.sumatif.edit');
    //pts
    Route::post('/bukuguru/pts/{uuid}/store', 'ptsStore')->name('penilaian.pts.store');
    Route::put('/bukuguru/pts/edit', 'ptsEdit')->name('penilaian.pts.edit');
    Route::delete('/bukuguru/pts/{uuid}/destroy', 'ptsDestroy')->name('penilaian.pts.destroy');
    //pas
    Route::post('/bukuguru/pas/{uuid}/store', 'pasStore')->name('penilaian.pas.store');
    Route::put('/bukuguru/pas/edit', 'pasEdit')->name('penilaian.pas.edit');
    Route::delete('/bukuguru/pas/{uuid}/destroy', 'pasDestroy')->name('penilaian.pas.destroy');
    //Rapor
    Route::post('/bukuguru/rapor/{uuid}/edit', 'raporEdit')->name('penilaian.rapor.edit');
    Route::delete('/bukuguru/rapor/{uuid}/delete', 'raporDelete')->name('penilaian.rapor.delete');
    Route::post('/bukuguru/rapor/{uuid}/konfirmasi', 'raporKonfirmasi')->name('penilaian.rapor.konfirmasi');
    Route::delete('/bukuguru/rapor/{uuid}/konfirmasi', 'hapusRaporKonfirmasi')->name('penilaian.rapor.konfirmasi');
    //Penjabaran
    Route::post('/bukuguru/penjabaran/{uuid}/store', 'penjabaranStore')->name('penilaian.penjabaran.store');
    Route::put('/bukuguru/penjabaran/edit', 'penjabaranEdit')->name('penilaian.penjabaran.edit');
    Route::delete('/bukuguru/penjabaran/{uuid}/destroy', 'penjabaranDestroy')->name('penilaian.penjabaran.destroy');
});

//{---------------------------------------------------End------------------------------------------------------------------}


// {----------------------------------------------Halaman Absensi----------------------------------------------------------}

Route::middleware(IsAdminKurikulum::class)->controller(AbsensiController::class)->group(function () {
    Route::get('/absensi', 'index')->name('absensi.index');
    Route::get('/absensi/get', 'get')->name('absensi.get');
    Route::get('/absensi/create', 'create')->name('absensi.create');
    Route::post('/absensi/store', 'store')->name('absensi.store');
    Route::delete('/absensi/destroy', 'destroy')->name('absensi.destroy');
});

Route::post('/absensi/rekap', [AbsensiController::class, 'rekapAbsensi'])->name('absensi.guru.rekap')->middleware(IsAdmin::class);

Route::middleware(isPenilaianController::class)->controller(AbsensiController::class)->group(function () {
    Route::get('/absensi/kehadiran', [AbsensiController::class, 'kehadiranIndex'])->name('absensi.kehadiran');
    Route::get('/absensi/kehadiran/histori', [AbsensiController::class, 'kehadiranHistori'])->name('absensi.kehadiran.histori');
    Route::get('/absensi/kehadiran/hadir/{jenis}', [AbsensiController::class, 'kehadiranHadir'])->name('absensi.kehadiran.hadir');
    Route::post('/absensi/kehadiran/hadir/{jenis}', [AbsensiController::class, 'kehadiranStoreHadir'])->name('absensi.kehadiran.hadir');
});
Route::middleware(IsSiswa::class)->controller(AbsensiController::class)->group(function () {
    Route::get('/absensi/siswa/kehadiran', [AbsensiController::class, 'kehadiranSiswaIndex'])->name('absensi.siswa.kehadiran');
    Route::get('/absensi/siswa/kehadiran/hadir/{jenis}', [AbsensiController::class, 'kehadiranSiswaHadir'])->name('absensi.kehadiran.siswa.hadir');
    Route::post('/absensi/siswa/kehadiran/hadir/{jenis}', [AbsensiController::class, 'kehadiranSiswaStoreHadir'])->name('absensi.kehadiran.siswa.hadir');
    Route::get('/absensi/siswa/kehadiran/histori', [AbsensiController::class, 'kehadiranSiswaHistori'])->name('absensi.siswa.kehadiran.histori');
});
// {----------------------------------------------------End------------------------------------------------------------------}


// {----------------------------------------------Halaman Jadwal-------------------------------------------------------------}
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index')->middleware('auth');

Route::middleware(IsAdminKurikulum::class)->controller(JadwalController::class)->group(function () {
    Route::get('/jadwal/create', 'create')->name('jadwal.create');
    Route::post('/jadwal/create', 'store')->name('jadwal.store');
    Route::post('/jadwal/edit', 'edit')->name('jadwal.edit');
    Route::post('/jadwal/{uuid}/generate', 'generate')->name('jadwal.generate');
    Route::put('/jadwal/{uuid}/show', 'update')->name('jadwal.update');
    Route::get('/jadwal/{uuid}/show', 'show')->name('jadwal.show');
    Route::get('/jadwal/{uuid}/show/waktu', 'waktuIndex')->name('jadwal.waktu.index');
    Route::get('/jadwal/{uuid}/show/waktu/create', 'waktuCreate')->name('jadwal.waktu.create');
    Route::post('/jadwal/{uuid}/show/waktu/create', 'waktuStore')->name('jadwal.waktu.store');
    Route::get('/jadwal/{uuid}/show/waktu/{waktuUUID}/edit', 'waktuEdit')->name('jadwal.waktu.edit');
    Route::put('/jadwal/{uuid}/show/waktu/{waktuUUID}/edit', 'waktuUpdate')->name('jadwal.waktu.update');
    Route::delete('/jadwal/{uuid}/show/waktu/{waktuUUID}/delete', 'waktuDelete')->name('jadwal.waktu.delete');
});
Route::middleware(isPenilaianController::class)->controller(JadwalController::class)->group(function () {
    Route::get('/event', 'eventIndex')->name('event.index');
    Route::get('/event/create', 'eventCreate')->name('event.create');
    Route::post('/event/store', 'eventStore')->name('event.store');
    Route::get('/event/{uuid}/show', 'eventShow')->name('event.show');
    Route::get('/event/{uuid}/edit', 'eventEdit')->name('event.edit');
    Route::put('/event/{uuid}/update', 'eventUpdate')->name('event.update');
    Route::delete('/event/{uuid}/delete', 'eventDelete')->name('event.delete');
});
// {----------------------------------------------------END------------------------------------------------------------------}

// {----------------------------------------------Halaman Agenda-------------------------------------------------------------}
Route::middleware(isNgajar::class)->controller(AgendaController::class)->group(function () {
    Route::get('/agenda', 'index')->name('agenda.index');
    Route::get('/agenda/create', 'create')->name('agenda.create');
    Route::get('/agenda/{uuid}/show', 'show')->name('agenda.show');
    Route::get('/agenda/{uuid}/edit', 'edit')->name('agenda.edit');
    Route::post('/agenda/{uuid}/edit', 'update')->name('agenda.update');
    Route::get('/agenda/create/{uuid}', 'createWithID')->name('agenda.createID');
    Route::post('/agenda/create', 'store')->name('agenda.store');
    Route::get('/agenda/cektanggal', 'cektanggal')->name('agenda.cekTanggal');
    Route::get('/agenda/cekjadwal', 'cekjadwal')->name('agenda.cekJadwal');
    Route::post('/agenda/{uuid}/edit/absensi', 'storeAbsensi')->name('agenda.store.absensi');
    Route::post('/agenda/{uuid}/edit/pancasila', 'storePancasila')->name('agenda.store.pancasila');
    Route::delete('/agenda/{uuid}/delete/absensi', 'deleteAbsensi')->name('agenda.delete.absensi');
    Route::delete('/agenda/{uuid}/delete/pancasila', 'deletePancasila')->name('agenda.delete.pancasila');
    Route::get('/agenda/history', 'history')->name('agenda.history');
    Route::get('/agenda/history/rekap', 'historyRekapAgenda')->name('agenda.historyRekapAgenda');
    Route::get('/agenda/create/{uuid}/copy', 'createWithCopy')->name('agenda.createCopy');
});
Route::middleware(IsAdminKurikulumKepala::class)->controller(AgendaController::class)->group(function () {
    Route::get('/agenda/rekap', 'rekap')->name('agenda.rekap');
    Route::get('/agenda/rekap/tanggal', 'getTanggal')->name('agenda.getTanggal');
    Route::get('/agenda/rekap/{idGuru}/{idMinggu}', 'rekapGuru')->name('agenda.rekap.guru');
});
// {----------------------------------------------------END------------------------------------------------------------------}

// {---------------------------------------------Halaman Classroom------------------------------------------------------------}
Route::middleware(isNgajar::class)->controller(ClassroomController::class)->group(function () {
    Route::get('/classroom', 'index')->name('classroom.index');
    Route::get('/classroom/{uuid}/show', 'show')->name('classroom.show');
    Route::get('/classroom/{uuid}/create/{jenis}', 'create')->name('classroom.create');
    Route::get('/classroom/{uuid}/edit/{uuidClassroom}', 'edit')->name('classroom.edit');
    Route::post('/classroom/{uuid}/create/{jenis}', 'store')->name('classroom.store');
    Route::post('/classroom/{uuid}/edit/{uuidClassroom}', 'update')->name('classroom.update');
    Route::post('/classroom/delete', 'deleteFile')->name('classroom.delete.file');
    Route::post('/classroom/{uuid}/assign', 'assign')->name('classroom.assign');
    Route::delete('/classroom/{uuid}/delete', 'delete')->name('classroom.delete');
    Route::post('/calssroom/{uuid}/arsip', 'arsip')->name('classroom.arsip');
    Route::get('/calssroom/{uuid}/archived', 'archived')->name('classroom.archived');
    Route::get('/classroom/{uuid}/preview/{uuidClassroom}', 'preview')->name('classroom.preview');
    Route::post('/classroom/{uuid}/nilai}', 'nilai')->name('classroom.nilai');
    Route::post('/classroom/{uuid}/showNilai', 'showNilai')->name('classroom.showNilai');
});
// {-------------------------------------------Halaman Classroom Siswa---------------------------------------------------------}
Route::middleware(IsSiswa::class)->controller(ClassroomController::class)->group(function () {
    Route::get('/classroom/siswa', 'siswaIndex')->name('classroom.siswa.index');
    Route::get('/classroom/siswa/{uuid}/show', 'siswaShow')->name('classroom.siswa.show');
    Route::get('/classroom/siswa/{uuid}/show/{uuidClassroom}', 'siswaPreview')->name('classroom.siswa.preview');
    Route::post('/classroom/siswa/token', 'siswaCekToken')->name('classroom.siswa.cekToken');
    Route::post('/classroom/siswa/detectOut/{uuid}', 'siswaDetectOut')->name('classroom.siswa.detectOut');
    Route::post('/classroom/siswa/create', 'siswaCreate')->name('classroom.siswa.create');
    Route::post('/classroom/siswa/submit', 'siswaSubmit')->name('classroom.siswa.submit');
});
Route::middleware(IsAdminKurikulumKepala::class)->controller(ClassroomController::class)->group(function () {
    Route::get('/penilaian/classroom', 'adminClassroomIndex')->name('penilaian.classroom.index');
    Route::get('/penilaian/classroom/{uuid}', 'adminClassroomShow')->name('penilaian.classroom.show');
    Route::get('/penilaian/classroom/{uuid}/show', 'adminClassroom')->name('penilaian.classroom.class');
    Route::get('/penilaian/classroom/{uuid}/archived', 'adminClassroomArchived')->name('penilaian.classroom.archived');
    Route::get('/penilaian/classroom/{uuid}/preview/{uuidClassroom}', 'adminClassroomPreview')->name('penilaian.classroom.preview');
});
Route::middleware(isPenilaianController::class)->controller(ClassroomController::class)->group(function () {
    Route::post('/classroom/{uuid}/resetSiswa/{uuidClassroom}', 'resetSiswa')->name('classroom.resetSiswa');
    Route::post('/classroom/{uuid}/resetSemuaSiswa/{uuidClassroom}', 'resetSemuaSiswa')->name('classroom.resetSemuaSiswa');
    Route::get('/classroom/{uuid}/lihatJawaban}', 'lihatJawaban')->name('classroom.lihatJawaban');
    Route::post('/classroom/{uuid}/reset', 'resetToken')->name('classroom.resetToken');
});
// {----------------------------------------------------END------------------------------------------------------------------}

// {-------------------------------------------Halaman Walikelas---------------------------------------------------------------}
Route::middleware(IsWalikelas::class)->controller(WalikelasController::class)->group(function () {
    Route::get('/walikelas/absensi', 'absensi')->name('walikelas.absensi');
    Route::get('/walikelas/absensi/create', 'absensiCreate')->name('walikelas.absensi.create');
    Route::post('/walikelas/absensi/create', 'absensiStore')->name('walikelas.absensi.create');
    Route::get('/walikelas/absensi/getAbsen', 'absensiGet')->name('walikelas.absensi.getAbsen');
    Route::get('/walikelas/siswa', 'siswa')->name('walikelas.siswa');
    Route::get('/walikelas/siswa/{uuid}', 'siswaShow')->name('walikelas.siswa.show');
    Route::post('/walikelas/siswa/{uuid}/reset/', 'resetSiswa')->name('walikelas.siswa.reset');
    Route::post('/walikelas/siswa/{uuid}/resetOrtu/', 'resetOrangtua')->name('walikelas.siswa.resetOrtu');
    Route::post('/walikelas/siswa/setSekretaris/', 'setSekretaris')->name('walikelas.siswa.sekretaris');
    Route::get('/walikelas/poin', 'poinIndex')->name('walikelas.poin');
    Route::get('/walikelas/poin/{uuid}/show', 'poinShow')->name('walikelas.poin.show');
    Route::get('/walikelas/poin/temp', 'poinTempIndex')->name('walikelas.poin.temp');
    Route::get('/walikelas/poin/temp/create', 'poinTempCreate')->name('walikelas.poin.temp.create');
    Route::get('/walikelas/ruang', 'ruang')->name('walikelas.ruang');
    Route::get('/walikelas/ruang/{uuid}/create', 'ruangCreate')->name('walikelas.ruang.create');
    Route::get('/walikelas/ruang/{uuid}/{uuidBarang}/edit', 'ruangEdit')->name('walikelas.ruang.edit');
    Route::get('/walikelas/classroom', 'classroom')->name('walikelas.classroom');
    Route::get('/walikelas/classroom/{uuid}', 'classroomShow')->name('walikelas.classroom.show');
    Route::get('/walikelas/classroom/{uuid}', 'classroomShow')->name('walikelas.classroom.show');
    Route::get('/walikelas/classroom/{uuid}/archived', 'classroomArchived')->name('walikelas.classroom.archived');
    Route::get('/walikelas/classroom/{uuid}/preview/{uuidClassroom}', 'classroomPreview')->name('walikelas.classroom.preview');
    Route::get('/walikelas/laporan', 'laporanIndex')->name('walikelas.laporan');
    Route::get('/walikelas/laporan/get', 'laporanGet')->name('walikelas.laporan.get');
    Route::get('/walikelas/rapor', 'raporIndex')->name('walikelas.rapor');
    Route::get('/walikelas/rapor/{uuid}/show', 'raporShow')->name('walikelas.rapor.show');
});

// {-------------------------------------------Halaman Sekretaris---------------------------------------------------------------}
Route::middleware(IsSekretaris::class)->controller(SekretarisController::class)->group(function () {
    Route::get('/sekretaris/absensi', 'absensi')->name('sekretaris.absensi');
    Route::get('/sekretaris/poin', 'poin')->name('sekretaris.poin');
    Route::get('/sekretaris/poin/create', 'poinCreate')->name('sekretaris.poin.create');
});

// {--------------------------------Halaman Proses Walikelas dan Sekretaris-----------------------------------------------------}
Route::middleware(IsWaliSekredanGuru::class)->controller(WalikelasController::class)->group(function () {
    Route::post('/walikelas/absensi/create', 'absensiStore')->name('walikelas.absensi.create');
    Route::get('/walikelas/absensi/getAbsen', 'absensiGet')->name('walikelas.absensi.getAbsen');
    Route::get('/walikelas/poin/temp/getaturan', 'poinGetAturan')->name('walikelas.poin.temp.getAturan');
    Route::post('/walikelas/poin/temp/create', 'poinTempStore')->name('walikelas.poin.temp.create');
    Route::delete('/walikelas/poin/temp/delete', 'poinTempDelete')->name('walikelas.poin.temp.delete');
});


// {----------------------------------------------------END------------------------------------------------------------------}

// {-------------------------------------------Halaman Aturan--------------------------------------------------------------}
Route::resource('/aturan', \App\Http\Controllers\PoinController::class)->except('show')->middleware(IsAdminKesiswaan::class);
Route::middleware(IsAdminKesiswaan::class)->controller(PoinController::class)->group(function () {
    Route::get('/poin', 'poinIndex')->name('poin.index');
    Route::get('/poin/{uuid}/show', 'poinShow')->name('poin.show');
    Route::get('/poin/{uuid}/create', 'poinCreate')->name('poin.create');
    Route::get('/poin/getaturan', 'poinGetAturan')->name('poin.getAturan');
    Route::post('/poin/{uuid}/store', 'poinStore')->name('poin.store');
    Route::post('/poin/{uuid}/delete', 'poinDelete')->name('poin.delete');
    Route::get('/temp', 'tempIndex')->name('temp.index');
    Route::post('/temp/{uuid}', 'tempUpdate')->name('temp.update');
    Route::get('/temp/approve', 'tempApprove')->name('temp.approve');
    Route::get('/temp/disapprove', 'tempDisapprove')->name('temp.disapprove');
});
Route::middleware(isPenilaianController::class)->controller(PoinController::class)->group(function() {
    Route::get('/bukuguru/poin','guruPoinIndex')->name('poin.guru.index');
    Route::get('/bukuguru/poin/create','guruPoinCreate')->name('poin.guru.create');
});

// {----------------------------------------------------END------------------------------------------------------------------}

// {--------------------------------------------Halaman Sapras---------------------------------------------------------------}
Route::resource('ruang', \App\Http\Controllers\RuangController::class)->middleware(IsAdminSapras::class);
Route::resource('ruang/{uuid}', \App\Http\Controllers\BarangController::class, ['parameters' => ['{uuid}' => 'uuidBarang']])->except('index')->names('barang')->middleware(IsAdminSapras::class);
Route::middleware(isPenilaianController::class)->controller(PengRuangController::class)->group(function () {
    Route::get('/penggunaan', 'index')->name('penggunaan.index');
    Route::get('/penggunaan/create', 'create')->name('penggunaan.create');
    Route::get('/penggunaan/create/{uuid}/getJadwal', 'getJadwal')->name('penggunaan.getJadwal');
    Route::post('/penggunaan/create', 'store')->name('penggunaan.store');
    Route::delete('/penggunaan/{uuid}/delete', 'destroy')->name('penggunaan.delete');
});

// {----------------------------------------------------END------------------------------------------------------------------}

// {--------------------------------------------Halaman Perangkat Ajar-------------------------------------------------------}
Route::middleware(IsAdminKurikulumKepala::class)->controller(PerangkatAjarController::class)->group(function () {
    Route::get('/perangkat-ajar', 'index')->name('perangkat.index');
    Route::get('/perangkat-ajar/create', 'create')->name('perangkat.create');
    Route::post('/perangkat-ajar/create', 'store')->name('perangkat.create');
    Route::get('/perangkat-ajar/{uuid}/edit', 'edit')->name('perangkat.edit');
    Route::put('/perangkat-ajar/{uuid}/edit', 'update')->name('perangkat.update');
    Route::delete('/perangkat-ajar/{uuid}/delete', 'delete')->name('perangkat.delete');
    Route::get('/perangkat-ajar/{uuid}/show', 'show')->name('perangkat.show');
});
Route::middleware(isPenilaianController::class)->controller(PerangkatAjarController::class)->group(function () {
    Route::post('/perangkat-ajar/{uuid}/{uuidPerangkat}/upload', 'upload')->name('perangkat.guru.upload');
    Route::get('/perangkat-ajar/{uuid}/download', 'download')->name('perangkat.guru.download');
    Route::delete('/perangkat-ajar/{uuid}/hapus', 'deletePerangkat')->name('perangkat.guru.delete');
    Route::get('/perangkat-ajar/{uuid}/zip', 'downloadZip')->name('perangkat.guru.zip');
});
// {------------------------------------------------- END --------------------------------------------------------------------}

// {----------------------------------------Halaman Detail Informasi Siswa----------------------------------------------------}
Route::middleware(IsSiswaOrangtua::class)->controller(DetailController::class)->group(function () {
    Route::get('/detail/absensi', 'absensi')->name('detail.absensi.index');
    Route::get('/detail/poin', 'poin')->name('detail.poin.index');
    Route::get('/detail/nilai', 'nilai')->name('detail.nilai.index');
    Route::get('/detail/nilai/{uuid}/show', 'nilaiShow')->name('detail.nilai.show');
});
// {------------------------------------------------- END --------------------------------------------------------------------}

// {-------------------------------------------Halaman EkstraKurikuler--------------------------------------------------------}

Route::middleware(IsAdminKurikulumKepala::class)->controller(EkskulController::class)->group(function () {
    Route::get('/ekskul', 'index')->name('ekskul.index');
    Route::get('/ekskul/create', 'create')->name('ekskul.create');
    Route::get('/ekskul/{uuid}/edit', 'edit')->name('ekskul.edit');
    Route::get('/ekskul/sort', 'sort')->name('ekskul.sort');
});
Route::middleware(isPenilaianController::class)->controller(EkskulController::class)->group(function () {
    Route::get('/ekskul/nilai', 'nilai')->name('ekskul.nilai.index');
    Route::get('/ekskul/nilai/{uuid}/show', 'showNilai')->name('ekskul.nilai.show');
    Route::get('/ekskul/nilai/get', 'getNilai')->name('ekskul.nilai.get');
    Route::post('/ekskul/nilai/create', 'createNilai')->name('ekskul.nilai.create');
    Route::post('/ekskul/store', 'store')->name('ekskul.store');
    Route::put('/ekskul/{uuid}/update', 'update')->name('ekskul.update');
    Route::delete('/ekskul/{uuid}/destroy', 'destroy')->name('ekskul.destroy');
    Route::post('/ekskul/sorting', 'sorting')->name('ekskul.sorting');
});;
