@extends('layouts.main')

@section('container')
    {{-- {{Breadcrumbs::render('p3-siswa-print', $siswa)}} --}}
    @php
        $nama_sekolah = $setting->first(function($item) {
            return $item->jenis == 'nama_sekolah';
        });
        $kop_rapor = $setting->first(function($item) {
            return $item->jenis == 'kop_rapor';
        });
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
    <div class="body-contain-customize col-12">
        <h5>Print Pelanggaran, Prestasi dan Partisipasi Siswa</h5>
        <p>Halaman ini diperuntukkan admin dan Wakil Kesiswaan untuk mencetak data Pelanggaran, Prestasi dan Partisipasi Siswa</p>
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
        </table>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <button class="btn btn-sm btn-warning text-warning-emphasis mt-3 print-rapor">
            <i class="fas fa-print"></i> Print Rapor
        </button>
    </div>
    {{-- page1 --}}
    <div class="body-contain-customize col-12 mt-3 printable-p3">
        <div style="background:linear-gradient(rgba(255,255,255,.8), rgba(255,255,255,.8)), url('{{asset('img/logo.png')}}'); background-size: contain;background-repeat: no-repeat;background-position:50% 70%; height:100%">
            <div class="row d-flex align-items-center pt-0 mt-0 pb-2" style="border-bottom:5px double #000">
                <div class="col-2"><img src="{{asset('img/tutwuri.png')}}" alt="" width="80%"></div>
                <div class="col-8 text-center kop-surat">
                    {!! $kop_rapor && $kop_rapor->nilai ? $kop_rapor->nilai : "" !!}
                </div>
                <div class="col-2"><img src="{{asset('img/maitreyawira_square.png')}}" alt="" width="80%"></div>
            </div>
            <div class="row mt-0 mb-1">
                <div class="col-12 mb-0"><h5 class="text-center"><b>LAPORAN PRESTASI PESERTA DIDIK</b></h5></div>
                <div class="row mt-3">
                    <div class="col-2">Nama</div>
                    <div class="col-4">: {{$siswa->nama}}</div>
                    <div class="col-3">Kelas</div>
                    <div class="col-3">: {{$siswa->kelas->tingkat.$siswa->kelas->kelas}}</div>
                    <div class="col-2">NIS</div>
                    <div class="col-4">: {{$siswa->nis}}</div>
                    <div class="col-3">Semester</div>
                    <div class="col-3">: {{$semester->semester}} ( {{$semester->semester == "1" ? "Ganjil" : "Genap"}} )</div>
                    <div class="col-2">NISN</div>
                    <div class="col-4">: {{$siswa->nisn}}</div>
                    <div class="col-3">Tahun Pelajaran</div>
                    <div class="col-3">: {{$semester->tp}}</div>
                </div>
            </div>
            <div class="row mt-1 mb-1">
                <div class="col-12">
                    <p class="fs-14">A. Prestasi</p>
                    <table class='table table-bordered' style="border:1px solid #3f3f3f">
                        <thead>
                            <tr style="height:50px" class="table-primary">
                                <td width="10%" style="text-align:center; vertical-align:middle">No</td>
                                <td width="20%" style="text-align:center; vertical-align:middle">Tanggal</td>
                                <td width="60%" style="text-align:center; vertical-align:middle">Nama Perlombaan Yang Diikuti</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($p3_prestasi) && count($p3_prestasi) > 0)
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($p3_prestasi as $item)
                                    <tr class="fs-10 transback">
                                        <td>{{$no}}</td>
                                        <td>{{date('d F Y',strtotime($item->tanggal))}}</td>
                                        <td>{{$item->deskripsi}}</td>
                                    </tr>
                                    @php
                                        $no++;
                                    @endphp
                                @endforeach
                            @else
                                <tr class="fs-10 transback" style="height:30px">
                                    <td>1</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <p class="fs-14">B. Partisipasi</p>
                    <table class='table table-bordered' style="border:1px solid #3f3f3f">
                        <thead>
                            <tr style="height:50px" class="table-primary">
                                <td width="10%" style="text-align:center; vertical-align:middle">No</td>
                                <td width="20%" style="text-align:center; vertical-align:middle">Tanggal</td>
                                <td width="60%" style="text-align:center; vertical-align:middle">Nama Kegiatan Yang Diikuti</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($p3_partisipasi) && count($p3_partisipasi) > 0)
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($p3_partisipasi as $item)
                                    <tr class="fs-10 transback">
                                        <td>{{$no}}</td>
                                        <td>{{date('d F Y',strtotime($item->tanggal))}}</td>
                                        <td>{{$item->deskripsi}}</td>
                                    </tr>
                                    @php
                                        $no++;
                                    @endphp
                                @endforeach
                            @else
                                <tr class="fs-10 transback" style="height:30px">
                                    <td>1</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <p class="fs-14">C. Pelanggaran</p>
                    <table class='table table-bordered' style="border:1px solid #3f3f3f">
                        <thead>
                            <tr style="height:50px" class="table-primary">
                                <td width="10%" style="text-align:center; vertical-align:middle">No</td>
                                <td width="20%" style="text-align:center; vertical-align:middle">Tanggal</td>
                                <td width="60%" style="text-align:center; vertical-align:middle">Jenis Pelanggaran</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($p3_pelanggaran) && count($p3_pelanggaran) > 0)
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($p3_pelanggaran as $item)
                                    <tr class="fs-10 transback">
                                        <td>{{$no}}</td>
                                        <td>{{date('d F Y',strtotime($item->tanggal))}}</td>
                                        <td>{{$item->deskripsi}}</td>
                                    </tr>
                                    @php
                                        $no++;
                                    @endphp
                                @endforeach
                            @else
                                <tr class="fs-10 transback" style="height:30px">
                                    <td>1</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-0 mb-0 breakavoid">
                <div class="col-5">
                    <p class="m-0">Mengetahui</p>
                    <p class="m-0 mb-5">Orang Tua / Wali Murid</p>
                    <p class="m-0"></p>
                    <p>...............................................</p>
                </div>
                <div class="col-2"></div>
                <div class="col-5 text-center">
                    <p class="m-0">Tanjungpinang, {{$tanggal}}</p>
                    <p class="m-0 mb-5">Wali Kelas</p>
                    <p class="m-0">{{$walikelas->Guru->nama}}</p>
                    <p class="m-0">NIK.{{$walikelas->Guru->nik}}</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.print-rapor').click(function() {
            window.print();
        });
    </script>
@endsection
