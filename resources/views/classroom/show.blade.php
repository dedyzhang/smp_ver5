@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Classroom</b></h5>
        <p>Halaman ini digunakan guru untuk membuat materi pembelajaran maupun mengupload file pembelajaran yang akan diakses oleh siswa bersangkutan</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5">
        <table class="table table-striped fs-13">
            <tr>
                <td width="30%">Pelajaran</td>
                <td width="5%">:</td>
                <td>{{$ngajar->pelajaran->pelajaran}}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{$ngajar->kelas->tingkat.$ngajar->kelas->kelas}}</td>
            </tr>
            <tr>
                <td>Guru</td>
                <td>:</td>
                <td>{{$ngajar->guru->nama}}</td>
            </tr>
            <tr>
                <td>KKTP</td>
                <td>:</td>
                <td>{{$ngajar->kkm}}</td>
            </tr>
        </table>
    </div>
    <div class="row m-0 p-0">
        <div class="body-contain-customize mt-3 col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex gap-2">
            <a href="{{route('classroom.create',['uuid' => $ngajar->uuid,'jenis' => 'materi'])}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-plus"></i> Tambah Materi Pembelajaran</a>
            <a href="{{route('classroom.create',['uuid' => $ngajar->uuid,'jenis' => 'latihan'])}}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah Latihan</a>
        </div>
    </div>
@endsection
