@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('walikelas-poin-show',$siswa)}}
    <div class="body-contain-customize col-12">
        <h5><b>Lihat Poin</b></h5>
        <p>Halaman untuk menampilkan data siswa dan poin siswa bersangkutan</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-5 col-xl-5">
        <table class="table table-striped fs-12">
            <tr>
                <td width="35%">Nama</td>
                <td width="3%">:</td>
                <td width="62%">{{$siswa->nama}}</td>
            </tr>
            <tr>
                <td width="35%">NIS</td>
                <td width="3%">:</td>
                <td width="62%">{{$siswa->nis}}</td>
            </tr>
            <tr>
                <td width="35%">Kelas</td>
                <td width="3%">:</td>
                <td width="62%">{{$siswa->kelas ? $siswa->kelas->tingkat.$siswa->kelas->kelas : "-"}}</td>
            </tr>
            @php
                $sisa = 100;
            @endphp
            @foreach ($poin as $item)
                @php
                    if($item->aturan->jenis == 'kurang') {
                        $sisa -= $item->aturan->poin;
                    } else {
                        $sisa += $item->aturan->poin;
                    }
                @endphp
            @endforeach
            <tr>
                <td width="35%">Poin</td>
                <td width="3%">:</td>
                <td width="62%">{{$sisa}}</td>
            </tr>
            <tr>
            @php
                if($sisa < 75 && $sisa >= 50) {
                    $peringatan = "Peringatan 1";
                } else if($sisa < 50 && $sisa >= 25) {
                    $peringatan = "Peringatan 2";
                } else if($sisa < 25) {
                    $peringatan = "Peringatan 3";
                } else {
                    $peringatan = "-";
                }
            @endphp
                <td width="45%">Peringatan</td>
                <td width="3%">:</td>
                <td width="52%">{{$peringatan}}</td>
            </tr>
        </table>
    </div>
    <div class="clearBoth"></div>
    <div class="body-contain-customize mt-3 col-12">
        <div class="table-responsive">
            <table class="table table-bordered fs-12">
                <tr>
                    <td width="5%">No</td>
                    <td width="15%">Tanggal</td>
                    <td width="10%">Jenis</td>
                    <td width="40%">Aturan</td>
                    <td width="10%">Poin</td>
                    <td width="10%">Sisa</td>
                </tr>
                <tr class="table-secondary">
                    <td colspan="4"><b>Poin Awal</b></td>
                    <td></td>
                    <td class="text-center">100</td>
                </tr>
                @php
                    $sisa = 100;
                @endphp
                @foreach ($poin as $item)
                    @php
                        if($item->aturan->jenis == 'kurang') {
                            $sisa -= $item->aturan->poin;
                        } else {
                            $sisa += $item->aturan->poin;
                        }
                    @endphp
                    <tr id="poin.{{$item->uuid}}">
                        <td>{{$loop->iteration}}</td>
                        <td>{{date('d M Y',strtotime($item->tanggal))}}</td>
                        <td class="@if ($item->aturan->jenis == "kurang") text-danger @else text-success @endif">{{$item->aturan->jenis}}</td>
                        <td>{{$item->aturan->aturan}}</td>
                        <td class="text-center @if ($item->aturan->jenis == "kurang") text-danger @else text-success @endif">{{$item->aturan->poin}}</td>
                        <td class="text-center @if ($item->aturan->jenis == "kurang") text-danger @else text-success @endif">{{$sisa}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
