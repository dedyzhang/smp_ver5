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
use App\Http\Middleware\IsKurikulum;
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

//Guru - Halaman Buku Guru Penilaian
Route::middleware(IsKurikulum::class)->controller(PenilaianController::class)->group(function() {
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
});
