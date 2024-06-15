@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Jadwal Ver {{$versi->versi}}</h5>
        <p>Pengaturan Jadwal seperti penambahan Waktu dan pengaturan Jam Mata Pelajaran</p>
    </div>
    <div class="body-contain-customize col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex mt-3">
        <a href="{{route('jadwal.waktu.index',$versi->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-clock"></i> Atur Waktu</a>
    </div>
@endsection
