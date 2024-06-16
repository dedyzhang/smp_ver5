<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PelajaranController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

//Middleware
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsAdminKurikulum;
use App\Http\Middleware\IsGuru;
use App\Http\Middleware\IsKurikulum;
use App\Http\Middleware\isNgajar;
use App\Http\Middleware\isPenilaianController;
use Illuminate\View\View as ViewView;

Route::get('/', function () {
    return 'main Page';
});

//Route Login
Route::get('/login',function() { return view('auth.login');})->name('login')->middleware('guest');
Route::post('/login', [LoginController::class,'login']);
Route::post('/logout',[LoginController::class,'logout']);
Route::post('/ganti-password',[LoginController::class,'gantiPassword']);
Route::get('/home',[LoginController::class,'index'])->name('auth.home')->middleware('auth');

//Admin - Halaman Data Guru
Route::resource('/guru',\App\Http\Controllers\GuruController::class)->middleware(IsAdmin::class);
Route::middleware(IsAdmin::class)->controller(GuruController::class)->group(function() {
    Route::post('/guru/{uuid}/reset','reset')->name('guru.reset');
    Route::get('/guru/{uuid}/pelajaran','pelajaran')->name('guru.pelajaran');
    Route::post('/guru/{uuid}/pelajaran','ngajar')->name('guru.ngajar');
    Route::delete('/guru/pelajaran/{uuid}/hapus','hapusNgajar')->name('guru.hapusNgajar');
});
//Admin - Halaman Data Kelas
Route::resource('/kelas',KelasController::class)->except('show')->middleware(IsAdmin::class);
Route::middleware(IsAdmin::class)->controller(KelasController::class)->group(function() {
    Route::post('/kelas/{uuid}/walikelas','walikelas')->name('kelas.walikelas');
    Route::get('/kelas/{uuid}/walikelas','showWalikelas')->name('kelas.walikelas');
    Route::get('/kelas/setKelas','setKelasSiswa')->name('kelas.setKelas');
    Route::post('/kelas/{uuid}/saveRombel','saveRombel')->name('kelas.saveRombel');
    Route::get('/kelas/setKelas/histori', 'historiRombel')->name('kelas.historiRombel');
    Route::post('/kelas/setKelas/histori/{uuid}/hapus','historiHapus')->name('kelas.historiHapus');
});

//Admin - Halaman Data Siswa
Route::resource('/siswa', SiswaController::class)->middleware(IsAdmin::class);
Route::middleware(IsAdmin::class)->controller(SiswaController::class)->group(function(){
    Route::post('/siswa/{uuid}/reset/siswa','resetSiswa')->name('siswa.reset');
    Route::post('/siswa/{uuid}/reset/password','resetOrangtua')->name('siswa.resetOrtu');
});

//Admin - Halaman Data Pelajaran
Route::resource('/pelajaran',PelajaranController::class)->except('show')->middleware(IsAdmin::class);
Route::middleware(IsAdmin::class)->controller(PelajaranController::class)->group(function() {
    Route::get('/pelajaran/sort','sort')->name('pelajaran.sort');
    Route::post('/pelajaran/sort','sorting')->name('pelajaran.sorting');
    Route::get('/pelajaran/penjabaran','getPenjabaran')->name('pelajaran.penjabaran');
    Route::post('/pelajaran/penjabaran','setPenjabaran')->name('pelajaran.penjabaran');
});

// {----------------------------------Halaman Penilaian dan Pelajaran------------------------------------------------------}

//Admin - Halaman Data Penilaian
Route::middleware(IsAdminKurikulum::class)->controller(PenilaianController::class)->group(function() {
    //Penilaian Index
    Route::get('/penilaian','index')->name('penilaian.admin.index');
    Route::get('/penilaian/{uuid}/get','get')->name('penilaian.admin.get');
    //Show All PTS
    Route::get('/penilaian/pts','ptsIndexAll')->name('penilaian.admin.pts');
    Route::get('/penilaian/pts/{uuid}/showAll','ptsShowAll')->name('penilaian.admin.pts.showAll');
    //show All PAS
    Route::get('/penilaian/pas','pasIndexAll')->name('penilaian.admin.pas');
    Route::get('/penilaian/pas/{uuid}/showAll','pasShowAll')->name('penilaian.admin.pas.showAll');
    //show All Rapor
    Route::get('/penilaian/rapor','raporIndexAll')->name('penilaian.admin.rapor');
    Route::get('/penilaian/rapor/{uuid}/showAll','raporShowAll')->name('penilaian.admin.rapor.showAll');
    //Materi
    Route::get('/penilaian/materi/{uuid}/show','materiShow')->name('penilaian.admin.materi.show');
    //formatif
    Route::get('/penilaian/formatif/{uuid}/show','formatifShow')->name('penilaian.admin.formatif.show');
    //Sumatif
    Route::get('/penilaian/sumatif/{uuid}/show','sumatifShow')->name('penilaian.admin.sumatif.show');
    //pts
    Route::get('/penilaian/pts/{uuid}/show','ptsShow')->name('penilaian.admin.pts.show');
    //pas
    Route::get('/penilaian/pas/{uuid}/show','pasShow')->name('penilaian.admin.pas.show');
    //Rapor
    Route::get('/penilaian/rapor/{uuid}/show','raporShow')->name('penilaian.admin.rapor.show');
    //Rapor
    Route::get('/penilaian/penjabaran/{uuid}/show','penjabaranShow')->name('penilaian.admin.penjabaran.show');
});
//Guru - Halaman Buku Guru Penilaian
Route::middleware(isNgajar::class)->controller(PenilaianController::class)->group(function() {
    //kktp
    Route::get('/bukuguru/kktp','kktpIndex')->name('penilaian.kktp.index');
    Route::post('/bukuguru/kktp','kktpEdit')->name('penilaian.kktp.edit');
    //Materi
    Route::get('/bukuguru/materi','materiIndex')->name('penilaian.materi.index');
    Route::get('/bukuguru/materi/{uuid}/show','materiShow')->name('penilaian.materi.show');
    //formatif
    Route::get('/bukuguru/formatif','formatifIndex')->name('penilaian.formatif.index');
    Route::get('/bukuguru/formatif/{uuid}/show','formatifShow')->name('penilaian.formatif.show');
    //Sumatif
    Route::get('/bukuguru/sumatif','sumatifIndex')->name('penilaian.sumatif.index');
    Route::get('/bukuguru/sumatif/{uuid}/show','sumatifShow')->name('penilaian.sumatif.show');
    //pts
    Route::get('/bukuguru/pts','ptsIndex')->name('penilaian.pts.index');
    Route::get('/bukuguru/pts/{uuid}/show','ptsShow')->name('penilaian.pts.show');
    //pas
    Route::get('/bukuguru/pas','pasIndex')->name('penilaian.pas.index');
    Route::get('/bukuguru/pas/{uuid}/show','pasShow')->name('penilaian.pas.show');
    //Rapor
    Route::get('/bukuguru/rapor','raporIndex')->name('penilaian.rapor.index');
    Route::get('/bukuguru/rapor/{uuid}/show','raporShow')->name('penilaian.rapor.show');
    //Penjabaran
    Route::get('/bukuguru/penjabaran','penjabaranIndex')->name('penilaian.penjabaran.index');
    Route::get('/bukuguru/penjabaran/{uuid}/show','penjabaranShow')->name('penilaian.penjabaran.show');
});
//Admin - Guru - Action untuk Penilaian
Route::middleware(isPenilaianController::class)->controller(PenilaianController::class)->group(function() {
    //Materi
    Route::post('/bukuguru/materi/{uuid}/create','materiCreate')->name('penilaian.materi.create');
    Route::put('/bukuguru/materi/{uuid}/edit/','materiUpdate')->name('penilaian.materi.edit');
    Route::delete('/bukuguru/materi/{uuid}/delete/','materiDelete')->name('penilaian.materi.delete');
    Route::post('/bukuguru/materi/{uuid}/createTupe/','materiCreateTupe')->name('penilaian.materi.createTupe');
    Route::post('/bukuguru/materi/{uuid}/updateTupe/','materiUpdateTupe')->name('penilaian.materi.updateTupe');
    Route::delete('/bukuguru/materi/{uuid}/deleteTupe/','materiDeleteTupe')->name('penilaian.materi.deleteTupe');
    //formatif
    Route::put('/bukuguru/formatif/edit','formatifEdit')->name('penilaian.formatif.edit');
    //Sumatif
    Route::put('/bukuguru/sumatif/edit','sumatifEdit')->name('penilaian.sumatif.edit');
    //pts
    Route::post('/bukuguru/pts/{uuid}/store','ptsStore')->name('penilaian.pts.store');
    Route::put('/bukuguru/pts/edit','ptsEdit')->name('penilaian.pts.edit');
    Route::delete('/bukuguru/pts/{uuid}/destroy','ptsDestroy')->name('penilaian.pts.destroy');
    //pas
    Route::post('/bukuguru/pas/{uuid}/store','pasStore')->name('penilaian.pas.store');
    Route::put('/bukuguru/pas/edit','pasEdit')->name('penilaian.pas.edit');
    Route::delete('/bukuguru/pas/{uuid}/destroy','pasDestroy')->name('penilaian.pas.destroy');
    //Rapor
    Route::post('/bukuguru/rapor/{uuid}/edit','raporEdit')->name('penilaian.rapor.edit');
    Route::delete('/bukuguru/rapor/{uuid}/delete','raporDelete')->name('penilaian.rapor.delete');
    Route::post('/bukuguru/rapor/{uuid}/konfirmasi','raporKonfirmasi')->name('penilaian.rapor.konfirmasi');
    Route::delete('/bukuguru/rapor/{uuid}/konfirmasi','hapusRaporKonfirmasi')->name('penilaian.rapor.konfirmasi');
    //Penjabaran
    Route::post('/bukuguru/penjabaran/{uuid}/store','penjabaranStore')->name('penilaian.penjabaran.store');
    Route::put('/bukuguru/penjabaran/edit','penjabaranEdit')->name('penilaian.penjabaran.edit');
    Route::delete('/bukuguru/penjabaran/{uuid}/destroy','penjabaranDestroy')->name('penilaian.penjabaran.destroy');
});

//{---------------------------------------------------End------------------------------------------------------------------}


// {----------------------------------------------Halaman Absensi----------------------------------------------------------}

Route::middleware(IsAdminKurikulum::class)->controller(AbsensiController::class)->group(function() {
    Route::get('/absensi','index')->name('absensi.index');
    Route::get('/absensi/get','get')->name('absensi.get');
    Route::get('/absensi/create','create')->name('absensi.create');
    Route::post('/absensi/store','store')->name('absensi.store');
    Route::delete('/absensi/destroy','destroy')->name('absensi.destroy');
});
// {----------------------------------------------------End------------------------------------------------------------------}


// {----------------------------------------------Halaman Jadwal-------------------------------------------------------------}
Route::middleware(IsAdminKurikulum::class)->controller(JadwalController::class)->group(function() {
    Route::get('/jadwal','index')->name('jadwal.index');
    Route::get('/jadwal/create','create')->name('jadwal.create');
    Route::post('/jadwal/create','store')->name('jadwal.store');
    Route::post('/jadwal/edit','edit')->name('jadwal.edit');
    Route::post('/jadwal/{uuid}/generate','generate')->name('jadwal.generate');
    Route::get('/jadwal/{uuid}/show','show')->name('jadwal.show');
    Route::get('/jadwal/{uuid}/show/waktu','waktuIndex')->name('jadwal.waktu.index');
    Route::get('/jadwal/{uuid}/show/waktu/create','waktuCreate')->name('jadwal.waktu.create');
    Route::post('/jadwal/{uuid}/show/waktu/create','waktuStore')->name('jadwal.waktu.store');
    Route::get('/jadwal/{uuid}/show/waktu/{waktuUUID}/edit','waktuEdit')->name('jadwal.waktu.edit');
    Route::put('/jadwal/{uuid}/show/waktu/{waktuUUID}/edit','waktuUpdate')->name('jadwal.waktu.update');
    Route::delete('/jadwal/{uuid}/show/waktu/{waktuUUID}/delete','waktuDelete')->name('jadwal.waktu.delete');
});
