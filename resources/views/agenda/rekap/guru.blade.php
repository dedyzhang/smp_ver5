@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('agenda-rekap-guru',$guru,$idMinggu)}}
    <div class="body-contain-customize col-12">
        <h5>Rekap Agenda Guru</h5>
        <p>Halaman ini diperuntukkan kepala sekolah, wakil kurikulum dan admin untuk mengecek agenda harian yang dibuat oleh guru</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-5 col-xl-5">
        <table class="table table-striped fs-12">
            <tr>
                <td width="35%">Nama Guru</td>
                <td width="3%">:</td>
                <td width="62%">{{$guru->nama}}</td>
            </tr>
            <tr>
                <td width="35%">NIK</td>
                <td width="3%">:</td>
                <td width="62%">{{$guru->nik}}</td>
            </tr>
            <tr>
                <td width="35%">Agenda Tanggal</td>
                <td width="3%">:</td>
                @php
                    $count = count($tanggal);
                    $i = 0;
                    $tanggalVal = "";
                @endphp
                @foreach ($tanggal as $item)
                        @php
                            if($i === 0) {
                                $tanggalVal .= date('d M Y', strtotime($item->tanggal))." - ";
                            } else if($i == $count - 1) {
                                $tanggalVal .= date('d M Y', strtotime($item->tanggal));
                            }
                            $i++;
                        @endphp
                    @endforeach
                <td width="62%">{{$tanggalVal}}</td>
            </tr>
        </table>
    </div>
    @foreach ($jadwal_array as $item)
        @php
            $uuid = $item['uuid'];
            $agenda_key = array_search($uuid,array_column($agenda_array,'id_jadwal'));
        @endphp
        <div class="body-contain-customize mt-3 col-12">
            <div class="row m-0 p-3 pe-1 ps-1 rounded-3 bg-primary-subtle">
                <div class="col-4 col-sm-4 col-md-2 col-lg-2 col-xl-2">
                    Tanggal :
                </div>
                <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">{{date('l, d M Y',strtotime($item["tanggal"]))}}</div>
                <div class="col-4 col-sm-4 col-md-2 col-lg-2 col-xl-2">
                    Jam Ke :
                </div>
                <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">{{date('H:i',strtotime($item['waktu']))}}</div>
                <div class="col-4 col-sm-4 col-md-2 col-lg-2 col-xl-2 mt-md-2 mt-lg-2 mt-xl-2">
                    Pelajaran :
                </div>
                <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4 mt-md-2 mt-lg-2 mt-xl-2">{{$item['pelajaran']}}</div>
                <div class="col-4 col-sm-4 col-md-2 col-lg-2 col-xl-2 mt-md-2 mt-lg-2 mt-xl-2">
                    Kelas :
                </div>
                <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4 mt-md-2 mt-lg-2 mt-xl-2">{{$item['kelas']}}</div>
                <div class="col-4 col-sm-4 col-md-2 col-lg-2 col-xl-2 mt-md-2 mt-lg-2 mt-xl-2">
                    Versi Jadwal :
                </div>
                <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4 mt-md-2 mt-lg-2 mt-xl-2">{{$item['versi']}}</div>
            </div>
            @if ($agenda_key !== false)
                @php
                    $agenda = $agenda_array[$agenda_key];
                @endphp
                <div class="row m-0 p-0 mt-3">
                    <div class="col-12 mt-2 mb-2">
                        <h6><b>Agenda</b></h6>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <p class="m-0"><b><i class="fa-solid fa-chalkboard me-1"></i> Pembahasan</b></p>
                        <p class="text-justify ms-4">{{$agenda['pembahasan']}}</p>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <p class="m-0"><b><i class="fa-solid fa-bullhorn me-1"></i> Metode</b></p>
                        <p class="text-justify ms-4">{{$agenda['metode']}}</p>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <p class="m-0"><b><i class="fa-solid fa-person-chalkboard me-1"></i> Kegiatan</b></p>
                        <p class="text-justify ms-4">{{$agenda['kegiatan']}}</p>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <p class="m-0"><b><i class="fa-solid fa-business-time me-1"></i> Proses</b></p>
                        <p class="text-justify ms-4">{{$agenda['proses']}}</p>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <p class="m-0"><b><i class="fa-solid fa-book me-1"></i> Kendala</b></p>
                        <p class="text-justify ms-4">{{$agenda['kendala']}}</p>
                    </div>
                </div>
                <div class="row m-0 p-0 mt-3">
                    <div class="col-12 col-sm-12 order-1 order-sm-1 order-md-1 order-lg-1 order-xl-1 col-md-8 col-lg-6 col-xl-6 mt-2 mb-2">
                        <h6><b>Absensi Siswa</b></h6>
                    </div>
                    <div class="col-12 col-sm-12 order-3 order-sm-3 order-md-3 order-lg-2 order-xl-2 col-md-8 col-lg-6 col-xl-6 mt-2 mb-2">
                        <h6><b>Penilaian P5 Siswa</b></h6>
                    </div>
                    <div class="col-12 col-sm-12 order-2 order-sm-2 order-md-2 order-lg-3 order-xl-3 col-md-8 col-lg-6 col-xl-6">
                        <table class="table table-bordered fs-12" width="100">
                            <thead>
                                <tr>
                                    <td width="5%">No</td>
                                    <td width="50%">Nama Siswa</td>
                                    <td width="10%">Absensi</td>
                                    <td width="35%">Keterangan</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($agenda['absensi']))
                                    @foreach ($agenda['absensi'] as $absensi)
                                        <tr>
                                            <td class="text-center">{{$loop->iteration}}</td>
                                            <td>{{$absensi['siswa']['nama']}}</td>
                                            <td class="text-center">{{$absensi['absensi']}}</td>
                                            <td>{{$absensi['keterangan']}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 col-sm-12 order-4 order-sm-4 order-md-4 order-lg-4 order-xl-4 col-md-8 col-lg-6 col-xl-6">
                        <table class="table table-bordered fs-12" width="100">
                            <thead>
                                <tr>
                                    <td width="5%">No</td>
                                    <td width="50%">Nama Siswa</td>
                                    <td width="10%">Dimensi</td>
                                    <td width="35%">Keterangan</td>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $pancasila_array = array(
                                        1 => "Beriman, bertakwa kepada Tuhan Yang Maha Esa, dan berakhlak mulia",
                                        2 => "Mandiri",
                                        3 => "Bergotong-royong",
                                        4 => "Berkebinekaan global",
                                        5 => "Bernalar kritis",
                                        6 => "Kreatif",
                                    );
                                @endphp
                                @if (isset($agenda['pancasila']))
                                    @foreach ($agenda['pancasila'] as $pancasila)
                                        <tr>
                                            <td class="text-center">{{$loop->iteration}}</td>
                                            <td>{{$pancasila['siswa']['nama']}}</td>
                                            <td class="text-center">{{$pancasila_array[$pancasila['dimensi']]}}</td>
                                            <td>{{$pancasila['keterangan']}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="alert alert-danger mt-3" role="alert">
                    <strong>Tidak Ada Agenda !</strong> Guru bersangkutan belum membuat agenda ini
                </div>
            @endif
        </div>
    @endforeach
@endsection
