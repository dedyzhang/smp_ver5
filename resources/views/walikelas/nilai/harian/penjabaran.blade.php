@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('walikelas-nilai-harian-penjabaran',$ngajar)}}
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
        <p><b>Penjabaran @if ($jabaran == "inggris") Bahasa Inggris @elseif($jabaran == "mandarin") Mandarin @else Komputer @endif</b></p>
        <div class="table-responsive">
            <table class="table table-bordered fs-12 nilai-table">
                <thead>
                    <tr class="text-center">
                        <td rowspan="2" width="3%">No</td>
                        <td rowspan="2" width="50%" class="sticky" style="min-width: 150px">Siswa</td>
                        @if (count($penjabaran) !== 0)
                            <td class="mainNilaiCell" colspan="@if ($jabaran == "inggris") 8 @elseif($jabaran == "mandarin") 7 @else 3 @endif">Nilai</td>
                        @endif
                    </tr>
                    <tr class="text-center">
                        @if (count($penjabaran) !== 0)
                            @if ($jabaran == "inggris")
                                <td class="@if (isset($rata2Penjabaran['inggris']) && in_array('listening',$rata2Penjabaran['inggris'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="Listening" data-bs-placement="top" data-bs-toggle="tooltip">P1</td>
                                <td class="@if (isset($rata2Penjabaran['inggris']) && in_array('speaking',$rata2Penjabaran['inggris'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="Speaking" data-bs-placement="top" data-bs-toggle="tooltip">P2</td>
                                <td class="@if (isset($rata2Penjabaran['inggris']) && in_array('writing',$rata2Penjabaran['inggris'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="Writing" data-bs-placement="top" data-bs-toggle="tooltip">P3</td>
                                <td class="@if (isset($rata2Penjabaran['inggris']) && in_array('reading',$rata2Penjabaran['inggris'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="Reading" data-bs-placement="top" data-bs-toggle="tooltip">P4</td>
                                <td class="@if (isset($rata2Penjabaran['inggris']) && in_array('grammar',$rata2Penjabaran['inggris'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="Grammar" data-bs-placement="top" data-bs-toggle="tooltip">P5</td>
                                <td class="@if (isset($rata2Penjabaran['inggris']) && in_array('vocabulary',$rata2Penjabaran['inggris'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="Vocabulary" data-bs-placement="top" data-bs-toggle="tooltip">P6</td>
                                <td class="@if (isset($rata2Penjabaran['inggris']) && in_array('singing',$rata2Penjabaran['inggris'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="Singing" data-bs-placement="top" data-bs-toggle="tooltip">P7</td>
                                <td width="5%" data-bs-title="Rata-Rata" data-bs-placement="top" data-bs-toggle="tooltip">RT</td>
                            @elseif($jabaran == "mandarin")
                                <td class="@if (isset($rata2Penjabaran['mandarin']) && in_array('listening',$rata2Penjabaran['mandarin'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="听力" data-bs-placement="top" data-bs-toggle="tooltip">P1</td>
                                <td class="@if (isset($rata2Penjabaran['mandarin']) && in_array('speaking',$rata2Penjabaran['mandarin'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="会话" data-bs-placement="top" data-bs-toggle="tooltip">P2</td>
                                <td class="@if (isset($rata2Penjabaran['mandarin']) && in_array('writing',$rata2Penjabaran['mandarin'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="书写" data-bs-placement="top" data-bs-toggle="tooltip">P3</td>
                                <td class="@if (isset($rata2Penjabaran['mandarin']) && in_array('reading',$rata2Penjabaran['mandarin'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="阅读" data-bs-placement="top" data-bs-toggle="tooltip">P4</td>
                                <td class="@if (isset($rata2Penjabaran['mandarin']) && in_array('vocabulary',$rata2Penjabaran['mandarin'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="词汇" data-bs-placement="top" data-bs-toggle="tooltip">P5</td>
                                <td class="@if (isset($rata2Penjabaran['mandarin']) && in_array('singing',$rata2Penjabaran['mandarin'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="唱歌" data-bs-placement="top" data-bs-toggle="tooltip">P6</td>
                                <td width="5%" data-bs-title="Rata-Rata" data-bs-placement="top" data-bs-toggle="tooltip">RT</td>
                            @else
                                <td class="@if (isset($rata2Penjabaran['komputer']) && in_array('pengetahuan',$rata2Penjabaran['komputer'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="Pengetahuan" data-bs-placement="top" data-bs-toggle="tooltip">P1</td>
                                <td class="@if (isset($rata2Penjabaran['komputer']) && in_array('keterampilan',$rata2Penjabaran['komputer'])) bg-success-subtle @else bg-danger-subtle @endif" width="5%" data-bs-title="Keterampilan" data-bs-placement="top" data-bs-toggle="tooltip">P2</td>
                                <td width="5%" data-bs-title="Rata-Rata" data-bs-placement="top" data-bs-toggle="tooltip">RT</td>
                            @endif
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ngajar->siswa as $siswa)
                        <tr class="siswa" data-ngajar="{{$ngajar->uuid}}" data-siswa="{{$siswa->uuid}}">
                            <td>{{$loop->iteration}}</td>
                            <td class="sticky">{{$siswa->nama}}</td>
                            @if (isset($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]) && ($jabaran == "inggris" || $jabaran == "mandarin"))
                                <td
                                    data-penjabaran="{{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['uuid']}}"
                                    class="nilai  text-center
                                    @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['listening'] < $ngajar->kkm) text-danger bg-danger-subtle @endif
                                    @if (isset($rata2Penjabaran[$jabaran]) && in_array('listening',$rata2Penjabaran[$jabaran])) diratakan @endif" content="true">
                                    {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['listening']}}
                                </td>
                                <td
                                    class="nilai  text-center
                                    @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['speaking'] < $ngajar->kkm) text-danger bg-danger-subtle @endif
                                    @if (isset($rata2Penjabaran[$jabaran]) && in_array('speaking',$rata2Penjabaran[$jabaran])) diratakan @endif" content="true">
                                    {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['speaking']}}
                                </td>
                                <td
                                    class="nilai  text-center
                                    @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['writing'] < $ngajar->kkm) text-danger bg-danger-subtle @endif
                                    @if (isset($rata2Penjabaran[$jabaran]) && in_array('writing',$rata2Penjabaran[$jabaran])) diratakan @endif" content="true">
                                    {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['writing']}}
                                </td>
                                <td
                                    class="nilai  text-center
                                    @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['reading'] < $ngajar->kkm) text-danger bg-danger-subtle @endif
                                    @if (isset($rata2Penjabaran[$jabaran]) && in_array('reading',$rata2Penjabaran[$jabaran])) diratakan @endif" content="true">
                                    {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['reading']}}
                                </td>
                                @if ($jabaran == "inggris")
                                    <td
                                        class="nilai  text-center
                                        @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['grammar'] < $ngajar->kkm) text-danger bg-danger-subtle @endif
                                        @if (isset($rata2Penjabaran[$jabaran]) && in_array('grammar',$rata2Penjabaran[$jabaran])) diratakan @endif" content="true">
                                        {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['grammar']}}
                                    </td>
                                @endif
                                <td
                                    class="nilai  text-center
                                    @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['vocabulary'] < $ngajar->kkm) text-danger bg-danger-subtle @endif
                                    @if (isset($rata2Penjabaran[$jabaran]) && in_array('vocabulary',$rata2Penjabaran[$jabaran])) diratakan @endif" content="true">
                                    {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['vocabulary']}}
                                </td>
                                <td
                                    class="nilai  text-center
                                    @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['singing'] < $ngajar->kkm) text-danger bg-danger-subtle @endif
                                    @if (isset($rata2Penjabaran[$jabaran]) && in_array('singing',$rata2Penjabaran[$jabaran])) diratakan @endif" content="true">
                                    {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['singing']}}
                                </td>
                                {{-- Rata-Rata --}}
                                @php
                                    $getrata2 = $rata2Penjabaran[$jabaran];
                                    if(isset($getrata2)) {
                                        $rata2 = 0;
                                        $jumlahMateri = 0;
                                        foreach($getrata2 as $rata) {
                                            if($penjabaran_array[$ngajar->uuid.".".$siswa->uuid][$rata] != 0) {
                                                $rata2 += $penjabaran_array[$ngajar->uuid.".".$siswa->uuid][$rata];
                                                $jumlahMateri++;
                                            }
                                        }
                                        if($jumlahMateri != 0) {
                                            $rataRata = round($rata2 / $jumlahMateri,0);
                                        } else {
                                            $rataRata = 0;
                                        }
                                    } else {
                                        $rataRata = 0;
                                    }
                                @endphp
                                <td class="nilai text-center final-rata
                                @if ($rataRata < $ngajar->kkm) text-danger bg-danger-subtle @endif
                                ">{{$rataRata}}</td>
                            @elseif(isset($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]) && $jabaran == "komputer")
                                <td
                                    data-penjabaran="{{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['uuid']}}"
                                    class="nilai  text-center
                                    @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['pengetahuan'] < $ngajar->kkm) text-danger bg-danger-subtle @endif
                                    @if (isset($rata2Penjabaran[$jabaran]) && in_array('pengetahuan',$rata2Penjabaran[$jabaran])) diratakan @endif" content="true">
                                    {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['pengetahuan']}}
                                </td>
                                <td
                                    class="nilai  text-center
                                    @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['keterampilan'] < $ngajar->kkm) text-danger bg-danger-subtle @endif
                                    @if (isset($rata2Penjabaran[$jabaran]) && in_array('keterampilan',$rata2Penjabaran[$jabaran])) diratakan @endif" content="true">
                                    {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['keterampilan']}}
                                </td>
                                {{-- Rata-Rata --}}
                                @php
                                    $getrata2 = $rata2Penjabaran[$jabaran];
                                    if(isset($getrata2)) {
                                        $rata2 = 0;
                                        $jumlahMateri = 0;
                                        foreach($getrata2 as $rata) {
                                            if($penjabaran_array[$ngajar->uuid.".".$siswa->uuid][$rata] != 0) {
                                                $rata2 += $penjabaran_array[$ngajar->uuid.".".$siswa->uuid][$rata];
                                                $jumlahMateri++;
                                            }
                                        }
                                        if($jumlahMateri != 0) {
                                            $rataRata = round($rata2 / $jumlahMateri,0);
                                        } else {
                                            $rataRata = 0;
                                        }
                                    } else {
                                        $rataRata = 0;
                                    }
                                @endphp
                                <td class="nilai text-center final-rata
                                @if ($rataRata < $ngajar->kkm) text-danger bg-danger-subtle @endif
                                ">{{$rataRata}}</td>
                            @else
                                @php
                                    if($jabaran == "inggris") {
                                        $jumlah = 8;
                                    } elseif($jabaran == "mandarin") {
                                        $jumlah = 7;
                                    } else {
                                        $jumlah = 3;
                                    }
                                @endphp
                                <td width="5%" colspan="{{$jumlah}}" class="text-center">-</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
