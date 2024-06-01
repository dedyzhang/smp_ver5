<?php

use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PelajaranController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

//Middleware
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsGuru;
use App\Http\Middleware\IsKurikulum;
use App\Http\Middleware\isNgajar;
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
});
//Admin - Halaman Data Penilaian
Route::middleware(IsAdmin::class)->controller(PenilaianController::class)->group(function() {
    //Penilaian Index
    Route::get('/penilaian','index')->name('penilaian.index');
    Route::get('/penilaian/{uuid}/get','get')->name('penilaian.get');
    //kktp
    Route::get('/penilaian/kktp','kktpIndex')->name('penilaian.kktp.index');
    Route::post('/penilaian/kktp','kktpEdit')->name('penilaian.kktp.edit');
    //Materi
    Route::get('/penilaian/materi','materiIndex')->name('penilaian.materi.index');
    Route::get('/penilaian/materi/{uuid}/show','materiShow')->name('penilaian.materi.show');
    Route::post('/penilaian/materi/{uuid}/create','materiCreate')->name('penilaian.materi.create');
    Route::put('/penilaian/materi/{uuid}/edit/','materiUpdate')->name('penilaian.materi.edit');
    Route::delete('/penilaian/materi/{uuid}/delete/','materiDelete')->name('penilaian.materi.delete');
    Route::post('/penilaian/materi/{uuid}/createTupe/','materiCreateTupe')->name('penilaian.materi.createTupe');
    Route::post('/penilaian/materi/{uuid}/updateTupe/','materiUpdateTupe')->name('penilaian.materi.updateTupe');
    Route::delete('/penilaian/materi/{uuid}/deleteTupe/','materiDeleteTupe')->name('penilaian.materi.deleteTupe');
    //formatif
    Route::get('/penilaian/formatif','formatifIndex')->name('penilaian.formatif.index');
    Route::get('/penilaian/formatif/{uuid}/show','formatifShow')->name('penilaian.formatif.show');
    Route::put('/penilaian/formatif/edit','formatifEdit')->name('penilaian.formatif.edit');
    //Sumatif
    Route::get('/penilaian/sumatif','sumatifIndex')->name('penilaian.sumatif.index');
    Route::get('/penilaian/sumatif/{uuid}/show','sumatifShow')->name('penilaian.sumatif.show');
    Route::put('/penilaian/sumatif/edit','sumatifEdit')->name('penilaian.sumatif.edit');
    //pts
    Route::get('/penilaian/pts','ptsIndex')->name('penilaian.pts.index');
    Route::get('/penilaian/pts/{uuid}/show','ptsShow')->name('penilaian.pts.show');
    Route::post('/penilaian/pts/{uuid}/store','ptsStore')->name('penilaian.pts.store');
    Route::put('/penilaian/pts/edit','ptsEdit')->name('penilaian.pts.edit');
    Route::delete('/penilaian/pts/{uuid}/destroy','ptsDestroy')->name('penilaian.pts.destroy');
    //pas
    Route::get('/penilaian/pas','pasIndex')->name('penilaian.pas.index');
    Route::get('/penilaian/pas/{uuid}/show','pasShow')->name('penilaian.pas.show');
    Route::post('/penilaian/pas/{uuid}/store','pasStore')->name('penilaian.pas.store');
    Route::put('/penilaian/pas/edit','pasEdit')->name('penilaian.pas.edit');
    Route::delete('/penilaian/pas/{uuid}/destroy','pasDestroy')->name('penilaian.pas.destroy');
    //Rapor
    Route::get('/penilaian/rapor','raporIndex')->name('penilaian.rapor.index');
    Route::get('/penilaian/rapor/{uuid}/show','raporShow')->name('penilaian.rapor.show');
    Route::post('/penilaian/rapor/{uuid}/edit','raporEdit')->name('penilaian.rapor.edit');
    Route::delete('/penilaian/rapor/{uuid}/delete','raporDelete')->name('penilaian.rapor.delete');
    Route::post('/penilaian/rapor/{uuid}/konfirmasi','raporKonfirmasi')->name('penilaian.rapor.konfirmasi');
    Route::delete('/penilaian/rapor/{uuid}/konfirmasi','hapusRaporKonfirmasi')->name('penilaian.rapor.konfirmasi');
});
//Guru - Halaman Buku Guru Penilaian
Route::middleware(isNgajar::class)->controller(PenilaianController::class)->group(function() {
    //kktp
    Route::get('/bukuguru/kktp','kktpIndex')->name('penilaian.kktp.index');
    Route::post('/bukuguru/kktp','kktpEdit')->name('penilaian.kktp.edit');
    //Materi
    Route::get('/bukuguru/materi','materiIndex')->name('penilaian.materi.index');
    Route::get('/bukuguru/materi/{uuid}/show','materiShow')->name('penilaian.materi.show');
    Route::post('/bukuguru/materi/{uuid}/create','materiCreate')->name('penilaian.materi.create');
    Route::put('/bukuguru/materi/{uuid}/edit/','materiUpdate')->name('penilaian.materi.edit');
    Route::delete('/bukuguru/materi/{uuid}/delete/','materiDelete')->name('penilaian.materi.delete');
    Route::post('/bukuguru/materi/{uuid}/createTupe/','materiCreateTupe')->name('penilaian.materi.createTupe');
    Route::post('/bukuguru/materi/{uuid}/updateTupe/','materiUpdateTupe')->name('penilaian.materi.updateTupe');
    Route::delete('/bukuguru/materi/{uuid}/deleteTupe/','materiDeleteTupe')->name('penilaian.materi.deleteTupe');
    //formatif
    Route::get('/bukuguru/formatif','formatifIndex')->name('penilaian.formatif.index');
    Route::get('/bukuguru/formatif/{uuid}/show','formatifShow')->name('penilaian.formatif.show');
    Route::put('/bukuguru/formatif/edit','formatifEdit')->name('penilaian.formatif.edit');
    //Sumatif
    Route::get('/bukuguru/sumatif','sumatifIndex')->name('penilaian.sumatif.index');
    Route::get('/bukuguru/sumatif/{uuid}/show','sumatifShow')->name('penilaian.sumatif.show');
    Route::put('/bukuguru/sumatif/edit','sumatifEdit')->name('penilaian.sumatif.edit');
    //pts
    Route::get('/bukuguru/pts','ptsIndex')->name('penilaian.pts.index');
    Route::get('/bukuguru/pts/{uuid}/show','ptsShow')->name('penilaian.pts.show');
    Route::post('/bukuguru/pts/{uuid}/store','ptsStore')->name('penilaian.pts.store');
    Route::put('/bukuguru/pts/edit','ptsEdit')->name('penilaian.pts.edit');
    Route::delete('/bukuguru/pts/{uuid}/destroy','ptsDestroy')->name('penilaian.pts.destroy');
    //pas
    Route::get('/bukuguru/pas','pasIndex')->name('penilaian.pas.index');
    Route::get('/bukuguru/pas/{uuid}/show','pasShow')->name('penilaian.pas.show');
    Route::post('/bukuguru/pas/{uuid}/store','pasStore')->name('penilaian.pas.store');
    Route::put('/bukuguru/pas/edit','pasEdit')->name('penilaian.pas.edit');
    Route::delete('/bukuguru/pas/{uuid}/destroy','pasDestroy')->name('penilaian.pas.destroy');
    //Rapor
    Route::get('/bukuguru/rapor','raporIndex')->name('penilaian.rapor.index');
    Route::get('/bukuguru/rapor/{uuid}/show','raporShow')->name('penilaian.rapor.show');
    Route::post('/bukuguru/rapor/{uuid}/edit','raporEdit')->name('penilaian.rapor.edit');
    Route::delete('/bukuguru/rapor/{uuid}/delete','raporDelete')->name('penilaian.rapor.delete');
    Route::post('/bukuguru/rapor/{uuid}/konfirmasi','raporKonfirmasi')->name('penilaian.rapor.konfirmasi');
    Route::delete('/bukuguru/rapor/{uuid}/konfirmasi','hapusRaporKonfirmasi')->name('penilaian.rapor.konfirmasi');
});
