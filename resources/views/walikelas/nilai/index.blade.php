@extends('layouts.main')

@section('container')
{{Breadcrumbs::render('walikelas-nilai')}}
<div class="body-contain-customize col-12">
    <h5 class="m-0 mb-1"><b>Nilai</b></h5>
    <p>
        Halaman ini diperuntukkan walikelas dalam mengecek keseluruhan nilai dalam kelas
    </p>
</div>
@php
    $settingHarian = $setting->first(function($elemen) {
        return $elemen->jenis == "akses_harian_walikelas";
    });
@endphp
@if ($settingHarian && $settingHarian->nilai == 1)
    <div class="col-md-6 col-lg-4 col-xl-3 col-sm-6 col-12 mt-3 pe-2 ps-0">
        <div class="card border-light rounded-4">
            <div class="card-body">
                <h6 class="m-0 p-0 mt-2 fs-18">
                    <b>HARIAN</b>
                </h6>
                <p
                    class="m-0 p-0 mt-3 p-2 rounded-3 bg-success-subtle"
                >
                    {{$kelas->tingkat.$kelas->kelas}}
                </p>
                <div class="button-place mt-3">
                    <a
                        href="{{route('walikelas.nilai.harian')}}"
                        class="btn btn-sm btn-warning text-warning-emphasis"
                        ><i class="fas fa-eye"></i
                    ></a>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="col-md-6 col-lg-4 col-xl-3 col-sm-6 col-12 mt-3 pe-2 ps-0">
    <div class="card border-light rounded-4">
        <div class="card-body">
            <h6 class="m-0 p-0 mt-2 fs-18">
                <b>PTS</b>
            </h6>
            <p
                class="m-0 p-0 mt-3 p-2 rounded-3 bg-info-subtle"
            >
                {{$kelas->tingkat.$kelas->kelas}}
            </p>
            <div class="button-place mt-3">
                <a
                    href="{{route('walikelas.nilai.pts')}}"
                    class="btn btn-sm btn-warning text-warning-emphasis"
                    ><i class="fas fa-eye"></i
                ></a>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6 col-lg-4 col-xl-3 col-sm-6 col-12 mt-3 pe-2 ps-0">
    <div class="card border-light rounded-4">
        <div class="card-body">
            <h6 class="m-0 p-0 mt-2 fs-18">
                <b>PAS</b>
            </h6>
            <p
                class="m-0 p-0 mt-3 p-2 rounded-3 bg-warning-subtle"
            >
                {{$kelas->tingkat.$kelas->kelas}}
            </p>
            <div class="button-place mt-3">
                <a
                    href="{{route('walikelas.nilai.pas')}}"
                    class="btn btn-sm btn-warning text-warning-emphasis"
                    ><i class="fas fa-eye"></i
                ></a>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6 col-lg-4 col-xl-3 col-sm-6 col-12 mt-3 pe-2 ps-0">
    <div class="card border-light rounded-4">
        <div class="card-body">
            <h6 class="m-0 p-0 mt-2 fs-18">
                <b>Olahan</b>
            </h6>
            <p
                class="m-0 p-0 mt-3 p-2 rounded-3 bg-danger-subtle"
            >
                {{$kelas->tingkat.$kelas->kelas}}
            </p>
            <div class="button-place mt-3">
                <a
                    href="{{route('walikelas.nilai.olahan')}}"
                    class="btn btn-sm btn-warning text-warning-emphasis"
                    ><i class="fas fa-eye"></i
                ></a>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6 col-lg-4 col-xl-3 col-sm-6 col-12 mt-3 pe-2 ps-0">
    <div class="card border-light rounded-4">
        <div class="card-body">
            <h6 class="m-0 p-0 mt-2 fs-18">
                <b>P5</b>
            </h6>
            <p
                class="m-0 p-0 mt-3 p-2 rounded-3 bg-primary-subtle"
            >
                {{$kelas->tingkat.$kelas->kelas}}
            </p>
            <div class="button-place mt-3">
                <a
                    href="{{route('walikelas.nilai.proyek')}}"
                    class="btn btn-sm btn-warning text-warning-emphasis"
                    ><i class="fas fa-eye"></i
                ></a>
            </div>
        </div>
    </div>
</div>
@endsection
