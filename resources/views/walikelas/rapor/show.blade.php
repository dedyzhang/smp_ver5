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
        @if ($semester->semester == 2)
            <div class="row m-0 p-0">
                <div class="col-4 form-group m-0 p-0">
                    <label for="keterangan">keterangan Rapor</label>
                    <select name="keterangan" id="keterangan" class="form-control">
                        <option value="">Pilih Salah Satu</option>
                        <option value="naik_kelas">Naik Kelas</option>
                        <option value="tidak_naik_kelas">Tidak Naik Kelas</option>
                    </select>
                </div>
            </div>
        @endif
        <button class="btn btn-sm btn-warning text-warning-emphasis mt-3 print-rapor">
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
        @php
            $rapor_pelajaran = $setting->first(function($elem) {
               if($elem->jenis == 'pelajaran_rapor') {
                    return $elem;
               }
            });
            if($rapor_pelajaran && $rapor_pelajaran->nilai) {
                $pelajaran_allow_array = explode(',',$rapor_pelajaran->nilai);
            } else {
                $pelajaran_allow_array = array();
            }
        @endphp
        <div class="row mt-1 mb-1 breakafter">
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
                        @php
                            $no = 1;
                            $no_pelajaran = 0;
                        @endphp
                        @foreach ($ngajar as $item)
                            @if (in_array($item->id_pelajaran,$pelajaran_allow_array))
                                @php
                                    $no_pelajaran ++;
                                @endphp
                                @if ($no_pelajaran <= 8)
                                    @php
                                        $rapor = $raporSiswa->first(function($elem) use ($item) {
                                            return $item->uuid == $elem->id_ngajar;
                                        });
                                    @endphp
                                    <tr class="fs-10">
                                        <td rowspan="2" style="text-align:center; vertical-align:middle">{{$no}}</td>
                                        <td rowspan="2" style="vertical-align:middle">{{$item->pelajaran->pelajaran}}</td>
                                        <td rowspan="2" style="text-align:center; vertical-align:middle;font-size:14px">{{$rapor && $rapor->nilai ? $rapor->nilai : 0}}</td>
                                        <td style="height:40px;vertical-align:middle;font-size:9px; padding:3px">
                                            {{$rapor && $rapor->deskripsi_positif ? $rapor->deskripsi_positif : ""}}
                                        </td>
                                    </tr>
                                    <tr class="fs-10">
                                        <td style="height:40px;vertical-align:middle;font-size:9px; padding:3px">
                                            {{$rapor && $rapor->deskripsi_negatif ? $rapor->deskripsi_negatif : ""}}
                                        </td>
                                    </tr>
                                    @php
                                        $no++;
                                    @endphp
                                @endif
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
                        @php
                            $no_pelajaran = 0;
                        @endphp
                        @foreach ($ngajar as $item)
                            @if (in_array($item->id_pelajaran,$pelajaran_allow_array))
                                @php
                                    $no_pelajaran ++;
                                @endphp
                                @if ($no_pelajaran >= 9 && $no_pelajaran <= 16)
                                    @php
                                        $rapor = $raporSiswa->first(function($elem) use ($item) {
                                            return $item->uuid == $elem->id_ngajar;
                                        });
                                    @endphp
                                    <tr class="fs-10">
                                        <td rowspan="2" style="text-align:center; vertical-align:middle">{{$no}}</td>
                                        <td rowspan="2" style="vertical-align:middle">{{$item->pelajaran->pelajaran}}</td>
                                        <td rowspan="2" style="text-align:center; vertical-align:middle;font-size:14px">{{$rapor && $rapor->nilai ? $rapor->nilai : 0}}</td>
                                        <td style="height:40px;vertical-align:middle;font-size:9px; padding:3px">
                                            {{$rapor && $rapor->deskripsi_positif ? $rapor->deskripsi_positif : ""}}
                                        </td>
                                    </tr>
                                    <tr class="fs-10">
                                        <td style="height:40px;vertical-align:middle;font-size:9px; padding:3px">
                                            {{$rapor && $rapor->deskripsi_negatif ? $rapor->deskripsi_negatif : ""}}
                                        </td>
                                    </tr>
                                    @php
                                        $no++;
                                    @endphp
                                @endif
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
                        @php
                            $no_ekskul = 0;
                        @endphp
                        @foreach ($ekskul as $item)
                            @php
                                $ekskulManual = $ekskulSiswa->first(function($elem) use ($item) {
                                    return $item->uuid == $elem->id_ekskul;
                                });
                            @endphp
                            @if ($item->id_pelajaran == "" && $ekskulManual)
                                <tr style="height:40px">
                                    <td width="20%" style="vertical-align:middle;font-size:9px; padding:3px 5px">{{$ekskulManual->ekskul->ekskul}}</td>
                                    <td width="80%" style="height:40px;vertical-align:middle;font-size:9px; padding:3px 5px">{{$ekskulManual->deskripsi}}</td>
                                </tr>
                                @php
                                    $no_ekskul++;
                                @endphp
                            @elseif ($item->id_pelajaran != "")
                                @php
                                    $ngajarEkskul = $ngajar->first(function($elem) use ($item) {
                                        return $item->id_pelajaran == $elem->id_pelajaran;
                                    });
                                    $rapor = $raporSiswa->first(function($elem) use ($ngajarEkskul) {
                                        return $ngajarEkskul->uuid == $elem->id_ngajar;
                                    });
                                    if($rapor) {
                                        //Menghitung rentan Nilai
                                        $kkm = $rapor->kkm;
                                        $interval = round((100 - $kkm) / 3, 0);
                                        $Cdown = $kkm;
                                        $Cup = $kkm + $interval - 1;
                                        $Bdown = $Cup + 1;
                                        $Bup = $Bdown + $interval - 1;
                                        $Adown = $Bup + 1;
                                        $Aup = 100;
                                        if ($rapor->nilai < $Cdown) {
                                            $predikat = "Kurang";
                                        } elseif ($rapor->nilai >= $Cdown && $rapor->nilai <= $Cup) {
                                            $predikat = "Cukup";
                                        } elseif ($rapor->nilai >= $Bdown && $rapor->nilai <= $Bup) {
                                           $predikat = "Baik";
                                        } elseif ($rapor->nilai >= $Adown && $rapor->nilai <= $Aup) {
                                            $predikat = "Baik";
                                        }
                                    }
                                @endphp
                                <tr style="height:40px">
                                    <td width="20%" style="vertical-align:middle;font-size:9px; padding:3px 5px">{{$item->ekskul}}</td>
                                    <td width="80%" style="height:40px;vertical-align:middle;font-size:9px; padding:3px 5px"><b>{{$rapor ? "( ".$predikat." )" : ""}}</b>.{{$rapor ? $rapor->deskripsi_positif : ""}}{{$rapor ? $rapor->deskripsi_negatif : ""}}</td>
                                </tr>
                                @php
                                    $no_ekskul++;
                                @endphp
                            @endif
                        @endforeach
                        @if ($no_ekskul <= 3)
                            @php
                                $no_belum = 3 - $no_ekskul;
                            @endphp
                            @for ($i = 0; $i < $no_belum; $i++)
                                <tr style="height:40px">
                                    <td width="30%"></td>
                                    <td width="70%"></td>
                                </tr>
                            @endfor
                        @endif
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
                            <td width="40%">{{$absensi->sakit}}</td>
                        </tr>
                        <tr>
                            <td width="60%">Izin</td>
                            <td width="40%">{{$absensi->izin}}</td>
                        </tr>
                        <tr>
                            <td width="60%">Alpha</td>
                            <td width="40%">{{$absensi->alpa}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @if ($semester->semester == 2)
            <div class="row mt-1 mb-1">
                <div class="col-6 ms-2 p-2 fs-10" style="border:1px solid #3f3f3f">
                    <p class="m-0">Keputusan :</p>
                    <p class="m-0">Berdasarkan pencapaian kompetensi pada semester ke-1 dan ke-2, peserta didik ditetapkan *):</p>
                    <div class="m-0 d-flex naik_kelas"><div style="width:120px">Naik Ke kelas </div><div class="ms-3 naik_kelas_val" style="width:100%;border-bottom:1px dotted #3f3f3f; display:inline; text-weight:bold"></div></div>
                    <div class="m-0 d-flex tidak_naik_kelas"><div style="width:120px">Tinggal di Kelas </div><div class="ms-3 tidak_naik_val" style="width:100%;border-bottom:1px dotted #3f3f3f; display:inline; text-weight:bold"></div></div>

                    <p class="m-0 mt-2">*) Coret Yang tidak perlu</p>
                </div>
            </div>
            <script>
                $('#keterangan').change(function() {
                    var jenis = $(this).val();
                    $kelas_deskripsi_array = {
                        1 : "I (Satu)",
                        2 : "II (Dua)",
                        3 : "III (Tiga)",
                        4 : "IV (Empat)",
                        5 : "V (Lima)",
                        6 : "VI (Enam)",
                        7 : "VII (Tujuh)",
                        8 : "VIII (Delapan)",
                        9 : "IX (Sembilan)",
                        10 : "X (Sepuluh)",
                        11 : "XI (Sebelas)",
                        12 : "XII (Dua Belas)",
                    };
                    if(jenis == "naik_kelas") {
                        $('.tidak_naik_kelas').addClass('text-decoration-line-through');
                        $('.naik_kelas').removeClass('text-decoration-line-through');
                        $kelas_sekarang = {{$siswa->kelas->tingkat}};
                        $kelas_baru = $kelas_sekarang + 1;

                        $('.naik_kelas_val').html($kelas_deskripsi_array[$kelas_baru]);
                        $('.tidak_naik_val').html("");

                    } else {
                        $('.naik_kelas').addClass('text-decoration-line-through');
                        $('.tidak_naik_kelas').removeClass('text-decoration-line-through');
                        $kelas_sekarang = {{$siswa->kelas->tingkat}};

                        $('.naik_kelas_val').html("");
                        $('.tidak_naik_val').html($kelas_deskripsi_array[$kelas_sekarang]);
                    }
                });
            </script>
        @endif

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
                <p class="m-0">{{$walikelas->Guru->nama}}</p>
                <p class="m-0">NIK.{{$walikelas->Guru->nik}}</p>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col-12 text-center">
                <p class="m-0">Mengetahui</p>
                <p class="m-0 mb-5">Kepala {{$nama_sekolah->nilai}}</p>
                <p class="m-0">{{$kepala_sekolah !== null ? $kepala_sekolah->nama : ""}}</p>
                <p class="m-0">NIK.{{$kepala_sekolah !== null ? $kepala_sekolah->nik : ""}}</p>
            </div>
        </div>
        <div class="col-12 ms-4 me-4 pe-4" style="position:fixed !important; bottom:1mm;width:90%;border-top:1px dotted #000;background-color:#fff">
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
