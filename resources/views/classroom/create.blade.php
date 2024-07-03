@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        @if ($jenis == 'materi')
            <h5><b>Tambah Materi Pembelajaran</b></h5>
        @else
            <h5><b>Tambah Latihan Pembelajaran</b></h5>
        @endif
    </div>
    @if ($jenis == 'materi')
        <div class="body-contain-customize col-12 mt-3">
            <p><b>A. Detail Materi Pembelajaran</b></p>
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-8 col-xl-8">
                    <label for="judul">Judul Materi</label>
                    <input type="text" name="judul" id="judul" class="form-control" placeholder="Masukkan Judul Materi">
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <label for="kelas">Kelas Yang Dituju</label>
                    <select class="form-control" data-toggle="select" name="kelas" id="kelas" placeholder="Masukkan Kelas Yang Dituju">

                    </select>
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-8 col-xl-8">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="4" id="deskripsi" placeholder="Masukkan Deskripsi Pembelajaran"></textarea>
                </div>
            </div>
        </div>
        <div class="body-contain-customize col-12 mt-3">
            <p><b>B. Isi Materi Pembelajaran</b></p>
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <label for="file">Upload File</label>
                    <p>File Boleh diupload lebih dari 1, Maksimal 5. Gunakan (ctrl + klik) kiri mouse untuk memilih file lebih dari 1</p>
                    <input type="file" name="file" id="file" class="file-input" multiple class="form-control" placeholder="Masukkan Judul Materi">
                </div>
            </div>
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <label for="link">Link Youtube</label>
                    <input type="text" name="link" id="link" class="form-control" placeholder="Masukkan Link Youtube">
                </div>
            </div>
        </div>
        <div class="body-contain-customize col-12 mt-3">
            <p><b>C. Deskripsi Materi Pembelajaran</b></p>
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <label for="deskripsi">Deskripsi Materi</label>
                    <textarea class="tinymce-select"></textarea>
                </div>
            </div>
        </div>
        <div class="body-contain-customize col-12 mt-3">
            <button class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Save Materi</button>
            <button class="btn btn-sm btn-warning"><i class="fas fa-plus"></i> Asign Materi</button>
        </div>
    @else

    @endif
@endsection
