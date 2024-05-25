@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('penilaian-formatif-show',$ngajar->pelajaran,$ngajar->kelas,$ngajar)}}
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
    <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <p><b>Penilaian Formatif</b></p>
        <div class="table-responsive">
            <table class="table table-bordered fs-12">
                <thead>
                    <tr class="text-center">
                        <td width="5%" rowspan="3">No</td>
                        <td width="30%" rowspan="3" style="min-width: 150px">Siswa</td>
                        <td colspan="{{$count}}">Materi</td>
                    </tr>
                    <tr class="text-center">
                        @foreach ($materiArray as $item)
                            <td colspan="{{count($materiArray) + 1 }}">M{{$loop->iteration}}</td>
                        @endforeach
                    </tr>
                    <tr class="text-center">
                        @foreach ($materiArray as $materi)
                            @foreach ($tupeArray as $tupe)
                                @if ($tupe['id_materi'] === $materi['uuid'])
                                    <td width="5%" data-bs-toggle="tooltip" @if($tupe['tupe'] !== null ) data-bs-title="{{$tupe['tupe']}}" @endif data-bs-placement="top">TP{{$loop->iteration}}</td>
                                @endif
                            @endforeach
                            <td width="5%">NA</td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ngajar->siswa as $siswa)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$siswa->nama}}</td>
                            @foreach ($materiArray as $item)
                                @php
                                    $countMateri = 0;
                                    $jumlah = 0;
                                @endphp
                                @foreach ($tupeArray as $tupe)
                                    @if ($tupe['id_materi'] === $item['uuid'])
                                        @php
                                            $jumlah += $formatif_array[$tupe['uuid'].".".$siswa->uuid];
                                            $countMateri += 1;
                                        @endphp
                                        <td width="5%" class="text-center @if ($formatif_array[$tupe['uuid'].".".$siswa->uuid] < $ngajar->kkm)
                                        text-danger bg-danger-subtle
                                        @endif">{{$formatif_array[$tupe['uuid'].".".$siswa->uuid]}}</td>
                                    @endif
                                @endforeach
                                    <td width="5%" class="text-center @if (round($jumlah / $countMateri ,0) < $ngajar->kkm)
                                        text-danger bg-danger-subtle
                                    @endif">{{round($jumlah / $countMateri,0)}}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
