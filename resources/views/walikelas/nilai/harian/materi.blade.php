@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('walikelas-nilai-harian-materi',$ngajar)}}
    <div class="body-contain-customize col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5">
        <p><b>Data Ngajar</b></p>
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
        @if ($ngajar->kkm == 0)
                <div
                    class="alert alert-warning" role="alert">
                    <strong><i class="fas fa-triangle-exclamation"></i> Perhatian</strong> KKTP untuk data ngajar masih belum diatur. Guru dapat mengatur KKTP dihalaman Buku Guru > KKTP
                </div>
            @endif
    </div>
    <div class="row ms-0 ps-0 gy-3">
        <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <p><b>List Materi</b></p>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>Nama Materi</td>
                            <td colspan="2">Tujuan Pembelajaran</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materi as $mtri)
                            @foreach ($mtri->tupe()->get() as $tupe)
                                @if ($loop->iteration == 1)
                                    <tr data-materi="{{$mtri->uuid}}" data-tupe="{{$tupe->uuid}}" data-jumlahtupe="{{$mtri->tupe}}">
                                        <td width="25%" class="materi-view" style="min-width: 150px; cursor: pointer;" rowspan="{{$mtri->tupe}}">{{$mtri->materi}}</td>
                                        <td width="7%" class="text-center">F0{{$loop->iteration}}</td>
                                        <td width="68%" class="tupe-view" style="min-width: 200px;cursor: pointer;">{{$tupe->tupe}}</td>
                                    </tr>
                                @else
                                    <tr data-materi="{{$mtri->uuid}}" data-tupe="{{$tupe->uuid}}">
                                        <td class="text-center">F0{{$loop->iteration}}</td>
                                        <td class="tupe-view" style="cursor: pointer">{{$tupe->tupe}}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

