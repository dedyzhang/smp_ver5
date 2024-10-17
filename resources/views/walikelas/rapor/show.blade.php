@extends('layouts.main')

@section('container')
     @php
        $nama_sekolah = $setting->first(function($item) {
            return $item->jenis == 'nama_sekolah';
        });
    @endphp
    <div class="body-contain-customize col-12">
        <h5>Rapor Kelas</h5>
        <p>Halaman ini digunakan untuk menampilkan dan mencetak seluruh halaman rapor siswa</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5">
        <p><b>Data Rapor</b></p>
        <table class="table table-striped fs-13">
            <tr>
                <td width="30%">Nama Siswa</td>
                <td width="5%">:</td>
                <td>{{$siswa->nama}}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{$siswa->kelas->tingkat.$siswa->kelas->kelas}}</td>
            </tr>
            <tr>
                <td>Semester / TP</td>
                <td>:</td>
                <td>{{$semester->semester == 1 ? "Ganjil" : "Genap"}} / {{$semester->tp}}</td>
            </tr>
        </table>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <button class="btn btn-sm btn-warning text-warning-emphasis print-rapor">
            <i class="fas fa-print"></i> Print Rapor
        </button>
    </div>
    <div class="body-contain-customize col-12 mt-3 printable-rapor">
        {{-- Page1 --}}
        <div class="row d-flex align-items-center pb-2" style="border-bottom:5px double #000">
            <div class="col-2"><img src="{{asset('img/tutwuri.png')}}" alt="" width="100%"></div>
            <div class="col-8 text-center">
                <h6 class="m-0"><b>YAYASAN BUMI MAITRI</b></h6>
                <h4 class="m-0"><b>SMP MAITREYAWIRA TANJUNGPINANG</b></h4>
                <h6 class="m-0"><b>TERAKREDITASI A</b></h6>
                <p class="m-0 fs-10">Komp. Gedung Pendidikan dan Pelatihan Buddhis Bumi Maitreya</p>
                <p class="m-0 fs-10">Jl. Prof. Ir Sutami No 38 (0771) 4505723 Email smpmai.tpi@gmail.com</p>
                <p class="m-0 fs-10">Tanjungpinang Kepulauan Riau</p>
            </div>
            <div class="col-2"><img src="{{asset('img/maitreyawira_square.png')}}" alt="" width="80%"></div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col-12 mb-0"><h5 class="text-center"><b>PENCAPAIAN KOMPETENSI PESERTA DIDIK</b></h5></div>
            <div class="row mt-3">
                <div class="col-2">Nama</div>
                <div class="col-4">: {{$siswa->nama}}</div>
                <div class="col-3">Kelas</div>
                <div class="col-3">: {{$siswa->kelas->tingkat.$siswa->kelas->kelas}}</div>
                <div class="col-2">No Induk</div>
                <div class="col-4">: {{$siswa->nis}}</div>
                <div class="col-3">Fase</div>
                <div class="col-3">: D</div>
                <div class="col-2">Sekolah</div>
                <div class="col-4">: {{$nama_sekolah->nilai}}</div>
                <div class="col-3">Semester</div>
                <div class="col-3">: {{$semester->semester}} ( {{$semester->semester == "1" ? "Ganjil" : "Genap"}} )</div>
                <div class="col-2"></div>
                <div class="col-4"></div>
                <div class="col-3">Tahun Pelajaran</div>
                <div class="col-3">: {{$semester->tp}}</div>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col-12">
                <table class='table table-bordered' style="border:1px solid #3f3f3f">
                    <thead>
                        <tr style="height:70px" class="table-primary">
                            <td width="10%" style="text-align:center; vertical-align:middle">No</td>
                            <td width="20%" style="text-align:center; vertical-align:middle">Mata Pelajaran</td>
                            <td width="10%" style="text-align:center; vertical-align:middle">Nilai</td>
                            <td width="60%" style="text-align:center; vertical-align:middle">Deskripsi</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ngajar as $item)
                            @if ($loop->iteration <= 8)
                                <tr class="fs-10">
                                    <td rowspan="2" style="text-align:center; vertical-align:middle">{{$loop->iteration}}</td>
                                    <td rowspan="2" style="vertical-align:middle">{{$item->pelajaran->pelajaran}}</td>
                                    <td rowspan="2" style="text-align:center; vertical-align:middle;font-size:14px">80</td>
                                    <td style="height:40px;vertical-align:middle;font-size:9px; padding:3px">
                                        memiliki penguasaan yang amat baik dalam menyimak dan memaknai informasi berupa gagasan, pikiran, dan perasaan, dari teks dongeng fantasi fantasi fantasi fantasi fantasi fantasi fantasi fant.
                                    </td>
                                </tr>
                                <tr class="fs-10">
                                    <td style="height:40px;vertical-align:middle;font-size:9px; padding:3px">
                                        perlu ditingkatkan dalam menyimak dan memaknai informasi berupa gagasan, pikiran, dan perasaan, dari teks dongeng fantasi fantasi fantasi fantasi fantasi fantasi fantasi fant.
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Page 2 --}}
        <div class="row mt-5 mb-1">
            <div class="col-12">
                <table class='table table-bordered' style="border:1px solid #3f3f3f">
                    <thead>
                        <tr style="height:70px" class="table-primary">
                            <td width="10%" style="text-align:center; vertical-align:middle">No</td>
                            <td width="20%" style="text-align:center; vertical-align:middle">Mata Pelajaran</td>
                            <td width="10%" style="text-align:center; vertical-align:middle">Nilai</td>
                            <td width="60%" style="text-align:center; vertical-align:middle">Deskripsi</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ngajar as $item)
                            @if ($loop->iteration >= 9 && $loop->iteration <= 16)
                                <tr class="fs-10">
                                    <td rowspan="2" style="text-align:center; vertical-align:middle">{{$loop->iteration}}</td>
                                    <td rowspan="2" style="vertical-align:middle">{{$item->pelajaran->pelajaran}}</td>
                                    <td rowspan="2" style="text-align:center; vertical-align:middle;font-size:14px">80</td>
                                    <td style="height:40px;vertical-align:middle;font-size:9px; padding:3px">
                                        memiliki penguasaan yang amat baik dalam menyimak dan memaknai informasi berupa gagasan, pikiran, dan perasaan, dari teks dongeng fantasi fantasi fantasi fantasi fantasi fantasi fantasi fant.
                                    </td>
                                </tr>
                                <tr class="fs-10">
                                    <td style="height:40px;vertical-align:middle;font-size:9px; padding:3px">
                                        perlu ditingkatkan dalam menyimak dan memaknai informasi berupa gagasan, pikiran, dan perasaan, dari teks dongeng fantasi fantasi fantasi fantasi fantasi fantasi fantasi fant.
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
         <div class="row mt-1 mb-1">
            <div class="col-12">
                <p class="m-0"><b>Ekstrakurikuler</b></p>
                <table class="table table-bordered fs-10" style="border:1px solid #3f3f3f">
                    <tbody>
                        <tr>
                            <td width="20%">Mandarin</td>
                            <td width="80%" style="height:40px;vertical-align:middle;font-size:9px; padding:3px 5px">memiliki penguasaan yang amat baik dalam menyimak dan memaknai informasi berupa gagasan, pikiran, dan perasaan, dari teks dongeng fantasi fantasi fantasi fantasi fantasi fantasi fantasi fant. perlu ditingkatkan dalam menyimak dan memaknai informasi berupa gagasan, pikiran, dan perasaan, dari teks dongeng fantasi fantasi fantasi fantasi fantasi fantasi fantasi fant.</td>
                        </tr>
                        <tr>
                            <td width="20%">Mandarin</td>
                            <td width="80%" style="height:40px;vertical-align:middle;font-size:9px; padding:3px 5px">memiliki penguasaan yang amat baik dalam menyimak dan memaknai informasi berupa gagasan, pikiran, dan perasaan, dari teks dongeng fantasi fantasi fantasi fantasi fantasi fantasi fantasi fant. perlu ditingkatkan dalam menyimak dan memaknai informasi berupa gagasan, pikiran, dan perasaan, dari teks dongeng fantasi fantasi fantasi fantasi fantasi fantasi fantasi fant.</td>
                        </tr>
                        <tr>
                            <td width="20%">Mandarin</td>
                            <td width="80%" style="height:40px;vertical-align:middle;font-size:9px; padding:3px 5px">memiliki penguasaan yang amat baik dalam menyimak dan memaknai informasi berupa gagasan, pikiran, dan perasaan, dari teks dongeng fantasi fantasi fantasi fantasi fantasi fantasi fantasi fant. perlu ditingkatkan dalam menyimak dan memaknai informasi berupa gagasan, pikiran, dan perasaan, dari teks dongeng fantasi fantasi fantasi fantasi fantasi fantasi fantasi fant.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col-5">
                <p class="m-0"><b>Ketidakhadiran</b></p>
                <table class="table table-bordered fs-10" style="border:1px solid #3f3f3f">
                    <tbody>
                        <tr>
                            <td width="60%">Sakit</td>
                            <td width="40%"></td>
                        </tr>
                        <tr>
                            <td width="60%">Izin</td>
                            <td width="40%"></td>
                        </tr>
                        <tr>
                            <td width="60%">Alpha</td>
                            <td width="40%"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col-6 ms-2 p-2 fs-10" style="border:1px solid #3f3f3f">
                <p class="m-0">Keputusan :</p>
                <p class="m-0">Berdasarkan pencapaian kompetensi pada semester ke-1 dan ke-2, peserta didik ditetapkan *):</p>
                <div class="m-0 d-flex"><div style="width:100px">Naik Ke kelas </div><div class="ms-3" style="width:100%;border-bottom:1px dotted #3f3f3f; display:inline; text-weight:bold">8 ( Delapan )</div></div>
                <div class="m-0 d-flex text-decoration-line-through"><div style="width:100px">Naik Ke kelas </div><div class="ms-3" style="width:100%;border-bottom:1px dotted #3f3f3f; display:inline; text-weight:bold"></div></div>

                <p class="m-0 mt-2">*) Coret Yang tidak perlu</p>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col-5">
                <p class="m-0">Mengetahui</p>
                <p class="m-0 mb-5">Orang Tua / Wali Murid</p>
                <p class="m-0"></p>
                <p>...............................................</p>
            </div>
            <div class="col-2"></div>
            <div class="col-5 text-center">
                <p class="m-0">Tanjungpinang, 13 Desember 2024</p>
                <p class="m-0 mb-5">Wali Kelas</p>
                <p class="m-0">Septa Karmila, S.Sos.</p>
                <p class="m-0">NIK.10010070010</p>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col-12 text-center">
                <p class="m-0">Mengetahui</p>
                <p class="m-0 mb-5">Kepala {{$nama_sekolah->nilai}}</p>
                <p class="m-0">Dra.Megawati</p>
                <p class="m-0">NIK.10010070010</p>
            </div>
        </div>
        <div class="col-12 ms-4 me-4 pe-4" style="position:fixed !important; bottom:1mm;width:90%;border-top:1px dotted #000">
            <p class="m-0"><span class="bg-primary ps-3 pe-3"></span> <span class="ms-3">Rapor {{$nama_sekolah->nilai}}</span></p>
            <p class="m-0"><span class="bg-primary-subtle ps-3 pe-3"></span> <span class="ms-3">{{$siswa->nis}} | {{$siswa->nama}} | Kelas {{$siswa->kelas->tingkat.$siswa->kelas->kelas}} | Semester {{$semester->semester}} | {{$semester->tp}}</span></p>
        </div>
        {{-- Page 3 --}}
    </div>
    <script>
        $('.print-rapor').click(function() {
            window.print();
        });
    </script>
@endsection
