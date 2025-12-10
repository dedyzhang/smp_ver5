@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('kokurikuler')}}
    <div class="body-contain-customize col-12">
        <h5><b>Kokurikuler</b></h5>
        <p>Halaman ini berisi data Kokurikuler yang diinput oleh guru pada masa semester tahun pelajaran berjalan</p>
    </div>
    @foreach ($kelas as $kls)
        <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mt-3">
            <div class="card border-light rounded-4" data-uuid="{{$kls->uuid}}">
                <div class="card-body">
                    <h5><b>{{$kls->tingkat.$kls->kelas}}</b></h5>
                    <div class="button-place mt-5 d-flex justify-content-end">
                        <a href="{{route('penilaian.koku.show',$kls->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-eye"></i></a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
