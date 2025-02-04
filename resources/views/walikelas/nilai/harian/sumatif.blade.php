@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('walikelas-nilai-harian-sumatif',$ngajar)}}
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
    <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
        <p><b>Penilaian Sumatif</b></p>
        <div class="table-responsive">
            <table class="table table-bordered fs-12 nilai-table">
                <thead>
                    <tr class="text-center">
                        <td width="3%" rowspan="3">No</td>
                        <td width="30%" rowspan="3" class="sticky" style="min-width: 150px">Siswa</td>
                        <td class="mainNilaiCell" colspan="{{$count}}">Materi</td>
                    </tr>
                    <tr class="text-center">
                        @foreach ($materiArray as $item)
                            <td class="open-close-tab tab_{{$item['uuid']}}" data-bs-toggle="tooltip" data-bs-title="{{$item['materi']}}" data-bs-placement="top"  >M{{$loop->iteration}}</td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ngajar->siswa as $siswa)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class="sticky">{{$siswa->nama}}</td>
                            @foreach ($materiArray as $item)
                                <td width="5%" data-sumatif="{{$sumatif_array[$item['uuid'].".".$siswa->uuid]['uuid']}}" class="text-center nilai_{{$item['uuid']}} nilai editable @if ($sumatif_array[$item['uuid'].".".$siswa->uuid]['nilai'] < $ngajar->kkm) text-danger bg-danger-subtle @endif">{{$sumatif_array[$item['uuid'].".".$siswa->uuid]['nilai']}}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
