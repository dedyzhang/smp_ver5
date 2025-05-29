@extends('layouts.main')

@section('container')
    @if (\Request::route()->getName() === 'walikelas.rapor.show')
        {{Breadcrumbs::render('walikelas-rapor-show',$siswa)}}
    @else
        {{Breadcrumbs::render('penilaian-admin-rapor-individu',$siswa)}}
    @endif
    @php
        $nama_sekolah = $setting->first(function($item) {
            return $item->jenis == 'nama_sekolah';
        });
        $kop_rapor = $setting->first(function($item) {
            return $item->jenis == 'kop_rapor';
        });
        $fase_rapor = $setting->first(function($item) {
            return $item->jenis == "fase_rapor";
        });
        if(isset($fase_rapor)) {
            $fase_rapor_array = unserialize($fase_rapor->nilai);
        } else {
            $fase_rapor_array = array();
        }
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
        <div style="background:linear-gradient(rgba(255,255,255,.8), rgba(255,255,255,.8)), url('{{asset('img/logo.png')}}'); background-size: contain;background-repeat: no-repeat;background-position:50% 70%">
            <div class="row d-flex align-items-center pt-0 mt-0 pb-2" style="border-bottom:5px double #000">
                <div class="col-2"><img src="{{asset('img/tutwuri.png')}}" alt="" width="100%"></div>
                <div class="col-8 text-center kop-surat">
                    {!! $kop_rapor && $kop_rapor->nilai ? $kop_rapor->nilai : "" !!}
                </div>
                <div class="col-2"><img src="{{asset('img/maitreyawira_square.png')}}" alt="" width="80%"></div>
            </div>
            <div class="row mt-0 mb-1">
                <div class="col-12 mb-0"><h5 class="text-center"><b>PENCAPAIAN KOMPETENSI PESERTA DIDIK</b></h5></div>
                <div class="row mt-3">
                    <div class="col-2">Nama</div>
                    <div class="col-4">: {{$siswa->nama}}</div>
                    <div class="col-3">Kelas</div>
                    <div class="col-3">: {{$siswa->kelas->tingkat.$siswa->kelas->kelas}}</div>
                    <div class="col-2">No Induk</div>
                    <div class="col-4">: {{$siswa->nis}}</div>
                    <div class="col-3">Fase</div>
                    <div class="col-3">: {{isset($fase_rapor_array[$siswa->kelas->tingkat]) ? $fase_rapor_array[$siswa->kelas->tingkat] : ""}}</div>
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
                    <table class='table table-bordered' style="border:1px solid #3f3f3f;">
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
                                        <tr class="fs-10 transback">
                                            <td rowspan="2" style="text-align:center; vertical-align:middle">{{$no}}</td>
                                            <td rowspan="2" style="vertical-align:middle">{{$item->pelajaran->pelajaran}}</td>
                                            <td rowspan="2" style="text-align:center; vertical-align:middle;font-size:14px">{{$rapor && $rapor->nilai ? $rapor->nilai : 0}}</td>
                                            <td style="height:40px;vertical-align:middle;font-size:9px; padding:3px">
                                                {{$rapor && $rapor->deskripsi_positif ? $rapor->deskripsi_positif : ""}}
                                            </td>
                                        </tr>
                                        <tr class="fs-10 transback">
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
        </div>
        {{-- Page 2 --}}
        <div style="background:linear-gradient(rgba(255,255,255,.8), rgba(255,255,255,.8)), url('{{asset('img/logo.png')}}'); background-size: contain;background-repeat: no-repeat;background-position:center">
            <div class="row mt-0 mb-0">
                <div class="col-12 mt-5 mb-1">
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
                                        <tr class="fs-10 transback">
                                            <td rowspan="2" style="text-align:center; vertical-align:middle">{{$no}}</td>
                                            <td rowspan="2" style="vertical-align:middle">{{$item->pelajaran->pelajaran}}</td>
                                            <td rowspan="2" style="text-align:center; vertical-align:middle;font-size:14px">{{$rapor && $rapor->nilai ? $rapor->nilai : 0}}</td>
                                            <td style="height:40px;vertical-align:middle;font-size:9px; padding:3px">
                                                {{$rapor && $rapor->deskripsi_positif ? $rapor->deskripsi_positif : ""}}
                                            </td>
                                        </tr>
                                        <tr class="fs-10 transback">
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
            <div class="row mt-0 mb-0">
                <div class="col-12 mt-1 mb-1">
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
                                    <tr class="transback" style="height:40px">
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
                                            $kkm = $ngajarEkskul->kkm;
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
                                    <tr style="height:40px" class="transback">
                                        <td width="20%" style="vertical-align:middle;font-size:9px; padding:3px 5px">{{$item->ekskul}}</td>
                                        <td width="80%" style="height:40px;vertical-align:middle;font-size:9px; padding:3px 5px"><b>{{$rapor ? "( ".$predikat." )" : ""}}</b>.{{$rapor ? $rapor->deskripsi_positif : ""}}</td>
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
                                    <tr style="height:40px" class="transback">
                                        <td width="30%"></td>
                                        <td width="70%"></td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-0 mb-0">
                <div class="col-5">
                    <p class="m-0"><b>Ketidakhadiran</b></p>
                    <table class="table table-bordered fs-10" style="border:1px solid #3f3f3f">
                        <tbody>
                            <tr class="transback">
                                <td width="60%">Sakit</td>
                                <td width="40%">{{$absensi->sakit}}</td>
                            </tr>
                            <tr class="transback">
                                <td width="60%">Izin</td>
                                <td width="40%">{{$absensi->izin}}</td>
                            </tr>
                            <tr class="transback">
                                <td width="60%">Alpha</td>
                                <td width="40%">{{$absensi->alpa}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($semester->semester == 2)
                <div class="row mt-0 mb-0">
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
                            if($kelas_sekarang == 6 || $kelas_sekarang == 9 || $kelas_sekarang == 12) {
                                $kelas_baru = "";
                            } else {
                                $kelas_baru = $kelas_sekarang + 1;
                            }

                            $('.naik_kelas_val').html($kelas_deskripsi_array[$kelas_baru] != null ? $kelas_deskripsi_array[$kelas_baru] : "");
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
            <div class="row mt-0 mb-0">
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
            <div class="row mt-1 mb-1 breakafter">
                <div class="col-12 text-center">
                    <p class="m-0">Mengetahui</p>
                    <p class="m-0 mb-5">Kepala {{$nama_sekolah ? $nama_sekolah->nilai : ""}}</p>
                    <p class="m-0">{{$kepala_sekolah !== null ? $kepala_sekolah->nama : ""}}</p>
                    <p class="m-0">NIK.{{$kepala_sekolah !== null ? $kepala_sekolah->nik : ""}}</p>
                </div>
            </div>
        </div>
        {{-- Page 3 --}}
        <div style="background:linear-gradient(rgba(255,255,255,.8), rgba(255,255,255,.8)), url('{{asset('img/logo.png')}}'); background-size: contain;background-repeat: no-repeat;background-position:center; position:relative; height:100%">
            <div class="row d-flex align-items-center pb-2" style="border-bottom:5px double #000">
                <div class="col-2"><img src="{{asset('img/tutwuri.png')}}" alt="" width="100%"></div>
                <div class="col-8 text-center kop-surat">
                    {!! $kop_rapor && $kop_rapor->nilai ? $kop_rapor->nilai : "" !!}
                </div>
                <div class="col-2"><img src="{{asset('img/maitreyawira_square.png')}}" alt="" width="80%"></div>
            </div>
            <div class="row mt-1 mb-1">
                <div class="col-12 mb-0">
                    <h5 class="text-center m-0 p-0"><b>PENCAPAIAN KOMPETENSI PESERTA DIDIK</b></h5>
                    @if (!empty($jabarKomputer))
                        <h5 class="text-center m-0 p-0"><b>PENJABARAN BAHASA INGGRIS, MANDARIN DAN KOMPUTER</b></h5>
                    @else
                        <h5 class="text-center m-0 p-0"><b>PENJABARAN BAHASA INGGRIS DAN MANDARIN</b></h5>
                    @endif
                </div>
                <div class="row mt-3">
                    <div class="col-2">Nama</div>
                    <div class="col-4">: {{$siswa->nama}}</div>
                    <div class="col-3">Kelas</div>
                    <div class="col-3">: {{$siswa->kelas->tingkat.$siswa->kelas->kelas}}</div>
                    <div class="col-2">No Induk</div>
                    <div class="col-4">: {{$siswa->nis}}</div>
                    <div class="col-3">Semester</div>
                    <div class="col-3">: {{$semester->semester}} ( {{$semester->semester == "1" ? "Ganjil" : "Genap"}} )</div>
                    <div class="col-2">Sekolah</div>
                    <div class="col-4">: {{$nama_sekolah->nilai}}</div>
                    <div class="col-3">Tahun Pelajaran</div>
                    <div class="col-3">: {{$semester->tp}}</div>
                </div>
            </div>
            <div class="row mt-1 mb-0">
                @php
                    $pInggris = $ngajar->first(function($elem) {
                        return $elem->pelajaran->has_penjabaran == 1;
                    });
                @endphp
                <div class="col-12"><p class="m-0">Ketuntasan Minimal Belajar : {{$pInggris ? $pInggris->kkm : 0}}</p></div>
                <div class="col-12">
                    @php
                        $no_urut = 1;
                        $jumlah_inggris = 0;
                        $total_inggris = 0;
                    @endphp
                    <table class="table table-bordered fs-12 penjabaran" style="border:1px solid #3f3f3f">
                        <tr class="table-primary">
                            <td colspan="4"><b>A. English</b></td>
                        </tr>
                        {{-- Listening --}}
                        @if ($jabarInggris !== null && $jabarInggris->listening !== 0)
                            <tr class="transback">
                                <td width="5%">{{$no_urut}}</td>
                                <td width="30%">Listening / Mendengarkan</td>
                                <td width="10%" class="text-center">{{$jabarInggris->listening}}</td>
                                <td width="45%">
                                    @if ($jabarInggris->listening < $pInggris->kkm)
                                        @if ($semester->semester == 1) Belum Tuntas
                                        @else Tidak Tuntas
                                        @endif
                                    @elseif ($jabarInggris->listening == $pInggris->kkm) Tuntas
                                    @else Terlampaui
                                    @endif
                                </td>
                            </tr>
                            @php
                                $no_urut++;
                                $jumlah_inggris++;
                                $total_inggris += $jabarInggris->listening;
                            @endphp
                        @endif
                        {{-- Speaking --}}
                        @if ($jabarInggris !== null && $jabarInggris->speaking !== 0)
                            <tr class="transback">
                                <td>{{$no_urut}}</td>
                                <td>Speaking / Berbicara</td>
                                <td class="text-center">{{$jabarInggris->speaking}}</td>
                                <td>
                                    @if ($jabarInggris->speaking < $pInggris->kkm)
                                        @if ($semester->semester == 1) Belum Tuntas
                                        @else Tidak Tuntas
                                        @endif
                                    @elseif ($jabarInggris->speaking == $pInggris->kkm) Tuntas
                                    @else Terlampaui
                                    @endif
                                </td>
                            </tr>
                            @php
                                $no_urut++;
                                $jumlah_inggris++;
                                $total_inggris += $jabarInggris->speaking;
                            @endphp
                        @endif
                        {{-- Writting --}}
                        @if ($jabarInggris !== null && $jabarInggris->writing !== 0)
                            <tr class="transback">
                                <td>{{$no_urut}}</td>
                                <td>Writing / Menulis</td>
                                <td class="text-center">{{$jabarInggris->writing}}</td>
                                <td>
                                    @if ($jabarInggris->writing < $pInggris->kkm)
                                        @if ($semester->semester == 1) Belum Tuntas
                                        @else Tidak Tuntas
                                        @endif
                                    @elseif ($jabarInggris->writing == $pInggris->kkm) Tuntas
                                    @else Terlampaui
                                    @endif
                                </td>
                            </tr>
                            @php
                                $no_urut++;
                                $jumlah_inggris++;
                                $total_inggris += $jabarInggris->writing;
                            @endphp
                        @endif
                        {{-- Reading --}}
                        @if ($jabarInggris !== null && $jabarInggris->reading !== 0)
                            <tr class="transback">
                                <td>{{$no_urut}}</td>
                                <td>Reading / Membaca</td>
                                <td class="text-center">{{$jabarInggris->reading}}</td>
                                <td>
                                    @if ($jabarInggris->reading < $pInggris->kkm)
                                        @if ($semester->semester == 1) Belum Tuntas
                                        @else Tidak Tuntas
                                        @endif
                                    @elseif ($jabarInggris->reading == $pInggris->kkm) Tuntas
                                    @else Terlampaui
                                    @endif
                                </td>
                            </tr>
                            @php
                                $no_urut++;
                                $jumlah_inggris++;
                                $total_inggris += $jabarInggris->reading;
                            @endphp
                        @endif
                        {{-- Grammar --}}
                        @if ($jabarInggris !== null && $jabarInggris->grammar !== 0)
                            <tr class="transback">
                                <td>{{$no_urut}}</td>
                                <td>Grammar / Tata Bahasa</td>
                                <td class="text-center">{{$jabarInggris->grammar}}</td>
                                <td>
                                    @if ($jabarInggris->grammar < $pInggris->kkm)
                                        @if ($semester->semester == 1) Belum Tuntas
                                        @else Tidak Tuntas
                                        @endif
                                    @elseif ($jabarInggris->grammar == $pInggris->kkm) Tuntas
                                    @else Terlampaui
                                    @endif
                                </td>
                            </tr>
                            @php
                                $no_urut++;
                                $jumlah_inggris++;
                                $total_inggris += $jabarInggris->grammar;
                            @endphp
                        @endif
                        {{-- Vocabulary--}}
                        @if ($jabarInggris !== null && $jabarInggris->vocabulary !== 0)
                            <tr class="transback">
                                <td>{{$no_urut}}</td>
                                <td>Vocabulary / Kosakata</td>
                                <td class="text-center">{{$jabarInggris->vocabulary}}</td>
                                <td>
                                    @if ($jabarInggris->vocabulary < $pInggris->kkm)
                                        @if ($semester->semester == 1) Belum Tuntas
                                        @else Tidak Tuntas
                                        @endif
                                    @elseif ($jabarInggris->vocabulary == $pInggris->kkm) Tuntas
                                    @else Terlampaui
                                    @endif
                                </td>
                            </tr>
                            @php
                                $no_urut++;
                                $jumlah_inggris++;
                                $total_inggris += $jabarInggris->vocabulary;
                            @endphp
                        @endif
                        {{-- Singing --}}
                        @if ($jabarInggris !== null && $jabarInggris->singing !== 0)
                            <tr class="transback">
                                <td>{{$no_urut}}</td>
                                <td>Singing / Bernyanyi</td>
                                <td class="text-center">{{$jabarInggris->singing}}</td>
                                <td>
                                    @if ($jabarInggris->singing < $pInggris->kkm)
                                        @if ($semester->semester == 1) Belum Tuntas
                                        @else Tidak Tuntas
                                        @endif
                                    @elseif ($jabarInggris->singing == $pInggris->kkm) Tuntas
                                    @else Terlampaui
                                    @endif
                                </td>
                            </tr>
                            @php
                                $no_urut++;
                                $jumlah_inggris++;
                                $total_inggris += $jabarInggris->singing;
                            @endphp
                        @endif
                        <tr>
                            <td colspan="2" class="table-info"><b>Jumlah</b></td>
                            <td class="text-center transback"><b>{{$total_inggris}}</b></td>
                            <td class="transback"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="table-primary"><b>Rata-rata</b></td>
                            <td class="text-center transback"><b>{{$jumlah_inggris !== 0 ? round($total_inggris/$jumlah_inggris,2) : 0}}</b></td>
                            <td class="transback"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row mt-0 mb-0">
                @php
                    $pMandarin = $ngajar->first(function($elem) {
                        return $elem->pelajaran->has_penjabaran == 2;
                    });
                @endphp
                <div class="col-12 mt-1"><p class="m-0">Ketuntasan Minimal Belajar : {{$pMandarin ? $pMandarin->kkm : 0}}</p></div>
                <div class="col-12">
                    @php
                        $no_urut = 1;
                        $jumlah_mandarin = 0;
                        $total_mandarin = 0;
                    @endphp
                    <table class="table table-bordered fs-12 penjabaran" style="border:1px solid #3f3f3f">
                        <tr class="table-primary">
                            <td colspan="4"><b>B. Mandarin</b></td>
                        </tr>
                        {{-- Listening --}}
                        @if ($jabarMandarin !== null && $jabarMandarin->listening !== 0)
                            <tr class="transback">
                                <td width="5%">{{$no_urut}}</td>
                                <td width="30%">听力 (tīng lì) Mendengarkan</td>
                                <td width="10%" class="text-center">{{$jabarMandarin->listening}}</td>
                                <td width="45%">
                                    @if ($jabarMandarin->listening < $pMandarin->kkm)
                                        @if ($semester->semester == 1) Belum Tuntas
                                        @else Tidak Tuntas
                                        @endif
                                    @elseif ($jabarMandarin->listening == $pMandarin->kkm) Tuntas
                                    @else Terlampaui
                                    @endif
                                </td>
                            </tr>
                            @php
                                $no_urut++;
                                $jumlah_mandarin++;
                                $total_mandarin += $jabarMandarin->listening;
                            @endphp
                        @endif
                        {{-- Speaking --}}
                        @if ($jabarMandarin !== null && $jabarMandarin->speaking !== 0)
                            <tr class="transback">
                                <td width="5%">{{$no_urut}}</td>
                                <td width="30%">会话 (huì huà) Berbicara</td>
                                <td width="10%" class="text-center">{{$jabarMandarin->speaking}}</td>
                                <td width="45%">
                                    @if ($jabarMandarin->speaking < $pMandarin->kkm)
                                        @if ($semester->semester == 1) Belum Tuntas
                                        @else Tidak Tuntas
                                        @endif
                                    @elseif ($jabarMandarin->speaking == $pMandarin->kkm) Tuntas
                                    @else Terlampaui
                                    @endif
                                </td>
                            </tr>
                            @php
                                $no_urut++;
                                $jumlah_mandarin++;
                                $total_mandarin += $jabarMandarin->speaking;
                            @endphp
                        @endif
                        {{-- Writting --}}
                        @if ($jabarMandarin !== null && $jabarMandarin->writing !== 0)
                            <tr class="transback">
                                <td width="5%">{{$no_urut}}</td>
                                <td width="30%">书写 (xiě zì) Menulis</td>
                                <td width="10%" class="text-center">{{$jabarMandarin->writing}}</td>
                                <td width="45%">
                                    @if ($jabarMandarin->writing < $pMandarin->kkm)
                                        @if ($semester->semester == 1) Belum Tuntas
                                        @else Tidak Tuntas
                                        @endif
                                    @elseif ($jabarMandarin->writing == $pMandarin->kkm) Tuntas
                                    @else Terlampaui
                                    @endif
                                </td>
                            </tr>
                            @php
                                $no_urut++;
                                $jumlah_mandarin++;
                                $total_mandarin += $jabarMandarin->writing;
                            @endphp
                        @endif
                        {{-- Reading --}}
                        @if ($jabarMandarin !== null && $jabarMandarin->reading !== 0)
                            <tr class="transback">
                                <td width="5%">{{$no_urut}}</td>
                                <td width="30%">阅读 (yuè dú) Membaca</td>
                                <td width="10%" class="text-center">{{$jabarMandarin->reading}}</td>
                                <td width="45%">
                                    @if ($jabarMandarin->reading < $pMandarin->kkm)
                                        @if ($semester->semester == 1) Belum Tuntas
                                        @else Tidak Tuntas
                                        @endif
                                    @elseif ($jabarMandarin->reading == $pMandarin->kkm) Tuntas
                                    @else Terlampaui
                                    @endif
                                </td>
                            </tr>
                            @php
                                $no_urut++;
                                $jumlah_mandarin++;
                                $total_mandarin += $jabarMandarin->reading;
                            @endphp
                        @endif
                        {{-- Vocabulary--}}
                        @if ($jabarMandarin !== null && $jabarMandarin->vocabulary !== 0)
                            <tr class="transback">
                                <td width="5%">{{$no_urut}}</td>
                                <td width="30%">词汇 (cí huì) Kosakata</td>
                                <td width="10%" class="text-center">{{$jabarMandarin->vocabulary}}</td>
                                <td width="45%">
                                    @if ($jabarMandarin->vocabulary < $pMandarin->kkm)
                                        @if ($semester->semester == 1) Belum Tuntas
                                        @else Tidak Tuntas
                                        @endif
                                    @elseif ($jabarMandarin->vocabulary == $pMandarin->kkm) Tuntas
                                    @else Terlampaui
                                    @endif
                                </td>
                            </tr>
                            @php
                                $no_urut++;
                                $jumlah_mandarin++;
                                $total_mandarin += $jabarMandarin->vocabulary;
                            @endphp
                        @endif
                        {{-- Singing --}}
                        @if ($jabarMandarin !== null && $jabarMandarin->singing !== 0)
                            <tr class="transback">
                                <td width="5%">{{$no_urut}}</td>
                                <td width="30%">唱歌 (chàng gē) Bernyanyi</td>
                                <td width="10%" class="text-center">{{$jabarMandarin->singing}}</td>
                                <td width="45%">
                                    @if ($jabarMandarin->singing < $pMandarin->kkm)
                                        @if ($semester->semester == 1) Belum Tuntas
                                        @else Tidak Tuntas
                                        @endif
                                    @elseif ($jabarMandarin->singing == $pMandarin->kkm) Tuntas
                                    @else Terlampaui
                                    @endif
                                </td>
                            </tr>
                            @php
                                $no_urut++;
                                $jumlah_mandarin++;
                                $total_mandarin += $jabarMandarin->singing;
                            @endphp
                        @endif
                        <tr>
                            <td colspan="2" class="table-info"><b>Jumlah</b></td>
                            <td class="text-center transback"><b>{{$total_mandarin}}</b></td>
                            <td class="transback"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="table-primary"><b>Rata-rata</b></td>
                            <td class="text-center transback"><b>{{$jumlah_mandarin !== 0 ? round($total_mandarin/$jumlah_mandarin,2) : 0}}</b></td>
                            <td class="transback"></td>
                        </tr>
                    </table>
                </div>
            </div>
            @if (!empty($jabarKomputer))
                <div class="row mt-0 mb-0">
                    @php
                        $pKomputer = $ngajar->first(function($elem) {
                            return $elem->pelajaran->has_penjabaran == 3;
                        });
                    @endphp
                    <div class="col-12 mt-1"><p class="m-0">Ketuntasan Minimal Belajar : {{$pKomputer ? $pKomputer->kkm : 0}}</p></div>
                    <div class="col-12">
                        @php
                            $no_urut = 1;
                            $jumlah_komputer = 0;
                            $total_komputer = 0;
                        @endphp
                        <table class="table table-bordered fs-12 penjabaran" style="border:1px solid #3f3f3f">
                            <tr class="table-primary">
                                <td colspan="4"><b>C. Komputer</b></td>
                            </tr>
                            {{-- Pengetahuan --}}
                            @if (isset($jabarKomputer) && $jabarKomputer->pengetahuan !== 0)
                                <tr class="transback">
                                    <td width="5%">{{$no_urut}}</td>
                                    <td width="30%">Pengetahuan</td>
                                    <td width="10%" class="text-center">{{$jabarKomputer->pengetahuan}}</td>
                                    <td width="45%">
                                        @if ($jabarKomputer->pengetahuan < $pKomputer->kkm)
                                            @if ($semester->semester == 1) Belum Tuntas
                                            @else Tidak Tuntas
                                            @endif
                                        @elseif ($jabarKomputer->pengetahuan == $pKomputer->kkm) Tuntas
                                        @else Terlampaui
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $no_urut++;
                                    $jumlah_komputer++;
                                    $total_komputer += $jabarKomputer->pengetahuan;
                                @endphp
                            @endif
                            {{-- Keterampilan --}}
                            @if (isset($jabarKomputer) && $jabarKomputer->keterampilan !== 0)
                                <tr class="transback">
                                    <td width="5%">{{$no_urut}}</td>
                                    <td width="30%">Keterampilan</td>
                                    <td width="10%" class="text-center">{{$jabarKomputer->keterampilan}}</td>
                                    <td width="45%">
                                        @if ($jabarKomputer->keterampilan < $pKomputer->kkm)
                                            @if ($semester->semester == 1) Belum Tuntas
                                            @else Tidak Tuntas
                                            @endif
                                        @elseif ($jabarKomputer->keterampilan == $pKomputer->kkm) Tuntas
                                        @else Terlampaui
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $no_urut++;
                                    $jumlah_komputer++;
                                    $total_komputer += $jabarKomputer->keterampilan;
                                @endphp
                            @endif
                            <tr>
                                <td colspan="2" class="table-info"><b>Jumlah</b></td>
                                <td class="text-center transback"><b>{{$total_komputer}}</b></td>
                                <td class="transback"></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="table-primary"><b>Rata-rata</b></td>
                                <td class="text-center transback"><b>{{$jumlah_komputer !== 0 ? round($total_komputer/$jumlah_komputer,2) : 0}}</b></td>
                                <td class="transback"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif
            <div class="row mt-1 mb-1" style="position:absolute;bottom:30px; width:90%;">
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

        {{-- Footer --}}
        <div class="col-12 ms-4 me-4 pe-4" style="position:fixed !important; bottom:1mm;width:90%;border-top:1px dotted #000;background-color:#fff">
            <p class="m-0"><span class="bg-primary ps-3 pe-3"></span> <span class="ms-3">Rapor {{$nama_sekolah->nilai}} Tanjungpinang</span></p>
            <p class="m-0"><span class="bg-primary-subtle ps-3 pe-3"></span> <span class="ms-3">{{$siswa->nis}} | {{$siswa->nama}} | Kelas {{$siswa->kelas->tingkat.$siswa->kelas->kelas}} | Semester {{$semester->semester}} | {{$semester->tp}}</span></p>
        </div>
    </div>
    <script>
        $('.print-rapor').click(function() {
            window.print();
        });
    </script>
@endsection
