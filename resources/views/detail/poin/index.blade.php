@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('detail-poin')}}
    <div class="body-contain-customize col-12">
        <h5>Informasi Poin Siswa</h5>
        <p>Halaman ini diperunttukan siswa untuk memantau poin aturan selama tahun pelajaran berlangsung</p>
    </div>
    <div class="row m-0 p-0 mt-3">
        <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 pe-xl-3 col-12 col-sm-6 col-md-6 col-lg-5 col-xl-4">
            <div class="card rounded-4 border-0">
                <div class="card-body">
                    Total akumulasi poin aturan dalam tahun pelajaran {{$semester->tp}} sebagai berikut :
                    <h1 class="text-center" style="font-weight:bold; font-size:66px;">{{$sisa}}</h1>
                    @if($sisa < 75)
                        @if ($sisa < 75 && $sisa >= 50)
                            <p>Sehingga diberikan <b>Peringatan 1</b></p>
                        @elseif ($sisa < 50 && $sisa >= 25)
                            <p>Sehingga diberikan <b>Peringatan 2</b></p>
                        @else
                            <p>Sehingga diberikan <b>Peringatan 3</b></p>
                        @endif
                    @else
                        <p>Pertahankan Poinnya</b></p>
                    @endif
                </div>
            </div>
        </div>
        <div class="p-0 mt-3 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0 col-12 col-sm-6 col-md-6 col-lg-7 col-xl-8">
            <div class="card rounded-4 border-0">
                <div class="card-body d-block d-sm-block d-md-block d-lg-inline-flex d-xl-inline-flex align-items-center">
                    <div>
                        @if($sisa < 75)
                            @if ($sisa < 75 && $sisa >= 50)
                                <img class="me-auto ms-auto d-block" src="{{asset('img/poin75.svg')}}" width="250" alt="">
                            @elseif ($sisa < 50 && $sisa >= 25)
                                <img class="me-auto ms-auto d-block" src="{{asset('img/poin50.svg')}}" width="250" alt="">
                            @else
                                <img class="me-auto ms-auto d-block" src="{{asset('img/poin25.svg')}}" width="250" alt="">
                            @endif
                        @else
                            <img class="me-auto ms-auto d-block" src="{{asset('img/poin100.svg')}}" width="250" alt="">
                        @endif
                    </div>
                    <div class="float-right fs-14">
                        @if($sisa < 75)
                            @if ($sisa < 75 && $sisa >= 50)
                                <p>Ini adalah peringatan <b>pertama</b> atas pelanggaran aturan yang kamu lakukan. Mohon perhatikan dan patuhi aturan yang sudah ditetapkan agar tidak terjadi pelanggaran di masa mendatang.Jadikan ini sebagai kesempatan untuk memperbaiki diri.</p>
                            @elseif ($sisa < 50 && $sisa >= 25)
                                <p>Ini adalah peringatan <b>kedua</b> atas pelanggaran aturan yang kamu lakukan. Kami telah memberikan kesempatan sebelumnya, namun kamu masih mengulangi kesalahan yang sama. Mohon segera memperbaiki perilaku dan mematuhi semua aturan yang ada. Jika terjadi pelanggaran lagi, akan ada tindakan yang lebih tegas sesuai dengan kebijakan sekolah.</p>
                            @else
                                <p>Ini adalah peringatan <b>terakhir</b> atas pelanggaran aturan yang kamu lakukan. Jika kamu kembali melanggar aturan, kami tidak punya pilihan selain mengambil tindakan disiplin yang lebih serius sesuai dengan ketentuan sekolah. Mohon pahami bahwa kesempatan ini adalah yang terakhir untuk memperbaiki sikap dan mengikuti semua aturan yang berlaku.</b></p>
                            @endif
                        @else
                            <p>Hebat! Usahamu menjaga disiplin dan tanggung jawab benar-benar luar biasa. Tetap pertahankan semangat ini, karena konsistensi adalah kunci menuju sukses!</p>
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>Detail Poin Siswa</b></p>
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
