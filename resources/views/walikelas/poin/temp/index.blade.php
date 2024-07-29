@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('walikelas-poin-temp')}}
    <div class="body-contain-customize col-12">
        <h5><b>Pengajuan Poin</b></h5>
        <p>Halaman ini untuk menampilkan poin yang sudah diajukan walikelas ataupun sekretaris</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex">
        <a href="{{route('walikelas.poin.temp.create')}}" class="btn btn-sm btn-warning text-warning-emphasis">
            <i class="fa fa-plus"></i> Tambah Pengajuan
        </a>
    </div>
@endsection
