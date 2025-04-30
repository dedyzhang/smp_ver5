@extends('layouts.main')

@section('container')
    <div class="col-12 body-contain-customize">
        <h5>Pelanggaran, Prestasi dan Partisipasi</h5>
        <p>Halaman ini diperuntukkan admin dan Wakil Kesiswaan untuk menginput Pelanggaran, Prestasi dan Partisipasi yang akan dipilih oleh guru bersangkutan</p>
    </div>

    <div class="col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex body-contain-customize mt-3">
        <a href="{{route('p3.create')}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-plus"></i> Tambah List</a>
    </div>
@endsection
