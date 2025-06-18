@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('detail-p3')}}
    <div class="body-contain-customize col-12">
        <h5>Informasi Pelanggaran, Prestasi dan Partisipasi</h5>
        <p>Halaman ini diperuntukan siswa untuk memantau Pelanggaran, Prestasi dan Partisipasi selama tahun pelajaran berlangsung</p>
    </div>
    @php
        $p3_prestasi = $p3->filter(function($elem) {
            return $elem->jenis == "prestasi";
        });
        $p3_pelanggaran = $p3->filter(function($elem) {
            return $elem->jenis == "pelanggaran";
        });
        $p3_partisipasi = $p3->filter(function($elem) {
            return $elem->jenis == "partisipasi";
        });
    @endphp
    <div class="row m-0 p-0 mt-3">
        <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 pe-xl-3 mt-2 col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
            <div class="card bg-success-subtle rounded-3 border-0">
                <div class="card-body d-inline-flex align-items-center">
                    <img src="{{asset('img/siswa-p3-prestasi.png')}}" width="90" alt="">
                    <div class="ms-2">
                        <p class="fs-14 m-0">Prestasi</p>
                        <p class="m-0 fs-30"><b>{{isset($p3_prestasi) ? count($p3_prestasi) : 0}}</b></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 pe-xl-3 mt-2 col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
            <div class="card bg-danger-subtle rounded-3 border-0">
                <div class="card-body d-inline-flex align-items-center">
                    <img src="{{asset('img/siswa-p3-pelanggaran.png')}}" width="90" alt="">
                    <div class="ms-2">
                        <p class="fs-14 m-0">Pelanggaran</p>
                        <p class="m-0 fs-30"><b>{{isset($p3_pelanggaran) ? count($p3_pelanggaran) : 0}}</b></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 pe-xl-3 mt-2 col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
            <div class="card bg-warning-subtle rounded-3 border-0">
                <div class="card-body d-inline-flex align-items-center">
                    <img src="{{asset('img/siswa-p3-partisipasi.png')}}" width="90" alt="">
                    <div class="ms-2">
                        <p class="fs-14 m-0">Partisipasi</p>
                        <p class="m-0 fs-30"><b>{{isset($p3_partisipasi) ? count($p3_partisipasi) : 0}}</b></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>Detail <span class="text-success">Prestasi</span> Siswa</b></p>
        <div class="table-responsive">
            <table class="table table-bordered fs-12">
                <thead>
                    <tr>
                        <td width="5%">No</td>
                        <td width="15%">Tanggal</td>
                        <td width="80%">Deskripsi</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @if(isset($p3_prestasi))
                        @foreach ($p3_prestasi as $prestasi)
                            <tr>
                                <td>{{ $no;  }}</td>
                                <td>{{ date('d F Y', strtotime($prestasi->tanggal)) }}</td>
                                <td>{{ $prestasi->deskripsi }}</td>
                            </tr>
                            @php
                                $no++;
                            @endphp
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <p class="mt-3"><b>Detail <span class="text-danger">Pelanggaran</span> Siswa</b></p>
        <div class="table-responsive">
            <table class="table table-bordered fs-12">
                <thead>
                    <tr>
                        <td width="5%">No</td>
                        <td width="15%">Tanggal</td>
                        <td width="80%">Deskripsi</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @if(isset($p3_pelanggaran))
                        @foreach ($p3_pelanggaran as $pelanggaran)
                            <tr>
                                <td>{{ $no;  }}</td>
                                <td>{{ date('d F Y', strtotime($pelanggaran->tanggal)) }}</td>
                                <td>{{ $pelanggaran->deskripsi }}</td>
                            </tr>
                            @php
                                $no++;
                            @endphp
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <p class="mt-3"><b>Detail <span class="text-warning">Partisipasi</span> Siswa</b></p>
        <div class="table-responsive">
            <table class="table table-bordered fs-12">
                <thead>
                    <tr>
                        <td width="5%">No</td>
                        <td width="15%">Tanggal</td>
                        <td width="80%">Deskripsi</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @if(isset($p3_partisipasi))
                        @foreach ($p3_partisipasi as $partisipasi)
                            <tr>
                                <td>{{ $no;  }}</td>
                                <td>{{ date('d F Y', strtotime($partisipasi->tanggal)) }}</td>
                                <td>{{ $partisipasi->deskripsi }}</td>
                            </tr>
                            @php
                                $no++;
                            @endphp
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
