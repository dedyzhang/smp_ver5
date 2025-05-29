@extends('layouts.main')

@section('container')
    @if (\Request::route()->getName() === 'penilaian.p5.rapor.print')
        {{Breadcrumbs::render('proyek-rapor-show',$siswa)}}
    @else
        {{Breadcrumbs::render('walikelas-nilai-proyek-print',$siswa)}}
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
        <h5>Rapor</h5>
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
                <div class="col-12 mb-0"><h5 class="text-center"><b>RAPOR PROYEK PENGUATAN</b></h5></div>
                <div class="col-12 mb-0"><h5 class="text-center"><b>PROFIL PELAJAR PANCASILA</b></h5></div>
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
                    <div class="col-3">Tahun Pelajaran</div>
                    <div class="col-3">: {{$semester->tp}}</div>
                </div>
            </div>
            <div class="row mt-1 mb-1">
                <div class="col-12">
                    @foreach ($proyek as $item)
                        <h6 class="fs-12 mt-2 mb-0"><b>PROYEK {{$loop->iteration}}</b> <span class="ms-3">{{$item->proyek->judul}}</span></h6>
                        <table class="table table-bordered fs-10" style="border:1px solid #3f3f3f">
                            <tr style="height:50px" class="transback">
                                <td style="padding:3px">{{$item->proyek->deskripsi}}</td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            </div>
            {{-- Bagian Penilaian --}}
            @php
                $no_projek = 0;
            @endphp
            @foreach ($proyek as $item)
                @php
                    $no_projek++;
                @endphp
                @if ($no_projek == 1)
                    <div class="row mt-1 mb-1 breakafter">
                        <div class="col-12">
                            <table class='table table-bordered' style="border:1px solid #3f3f3f;">
                                <thead>
                                    <tr style="height:40px" class="table-primary">
                                        <td style="vertical-align:middle">
                                            {{$item->proyek->judul}}
                                        </td>
                                        <td style="text-align:center; vertical-align:middle">{{$rentang && $rentang[1] !== null ? $rentang[1]['singkat'] : ""}}</td>
                                        <td style="text-align:center; vertical-align:middle">{{$rentang && $rentang[2] !== null ? $rentang[2]['singkat'] : ""}}</td>
                                        <td style="text-align:center; vertical-align:middle">{{$rentang && $rentang[3] !== null ? $rentang[3]['singkat'] : ""}}</td>
                                        <td style="text-align:center; vertical-align:middle">{{$rentang && $rentang[4] !== null ? $rentang[4]['singkat'] : ""}}</td>
                                    </tr>
                                </thead>
                                <tbody class="fs-10">
                                    @if (isset($array_proyek_detail[$item->proyek->uuid]))
                                        @php
                                            $no_detail = 0;
                                        @endphp

                                        @foreach ($array_proyek_detail[$item->proyek->uuid] as $detail)
                                            @php
                                                $no_detail++;
                                            @endphp
                                            <tr class="table-info">
                                                <td style="vertical-align:middle" colspan="5">
                                                    {{$detail['dimensi']}}
                                                </td>
                                            </tr>
                                            <tr class="transback">
                                                <td style="height:40px; vertical-align:middle">{{$detail['capaian']}}</td>
                                                {!! $array_nilai && isset($array_nilai[$detail['id_detail'].".".$siswa->uuid]) && $array_nilai[$detail['id_detail'].".".$siswa->uuid] == 1 ? '<td class="text-center" style="vertical-align:middle"><i class="fas fa-2x fa-check"></i></td>' : '<td></td>' !!}
                                                {!! $array_nilai && isset($array_nilai[$detail['id_detail'].".".$siswa->uuid]) && $array_nilai[$detail['id_detail'].".".$siswa->uuid] == 2 ? '<td class="text-center" style="vertical-align:middle"><i class="fas fa-2x fa-check"></i></td>' : '<td></td>' !!}
                                                {!! $array_nilai && isset($array_nilai[$detail['id_detail'].".".$siswa->uuid]) && $array_nilai[$detail['id_detail'].".".$siswa->uuid] == 3 ? '<td class="text-center" style="vertical-align:middle"><i class="fas fa-2x fa-check"></i></td>' : '<td></td>' !!}
                                                {!! $array_nilai && isset($array_nilai[$detail['id_detail'].".".$siswa->uuid]) && $array_nilai[$detail['id_detail'].".".$siswa->uuid] == 4 ? '<td class="text-center" style="vertical-align:middle"><i class="fas fa-2x fa-check"></i></td>' : '<td></td>' !!}
                                            </tr>
                                        @endforeach

                                        @if ($no_detail < 4)
                                            @php
                                                $kekurangan = 4 - $no_detail;
                                            @endphp
                                            @for ($k = 0; $k < $kekurangan; $k++)
                                                <tr class="table-info">
                                                    <td style="vertical-align:middle; height:40px" colspan="5">

                                                    </td>
                                                </tr>
                                                <tr class="transback">
                                                    <td style="height:40px;"></td>
                                                    <td style="height:40px;"></td>
                                                    <td style="height:40px;"></td>
                                                    <td style="height:40px;"></td>
                                                    <td style="height:40px;"></td>
                                                </tr>
                                            @endfor
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                            <table class="table table-bordered fs-10 mt-2" style="border:1px solid #3f3f3f">
                                <tr class="table-primary">
                                    <td>Catatan Proses</td>
                                </tr>
                                <tr style="height:50px" class="transback">
                                    <td style="padding:3px">{{$array_deskripsi && isset($array_deskripsi[$item->proyek->uuid.".".$siswa->uuid]) ? $array_deskripsi[$item->proyek->uuid.".".$siswa->uuid] : ""}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
        {{-- Page 2 --}}
        <div style="background:linear-gradient(rgba(255,255,255,.8), rgba(255,255,255,.8)), url('{{asset('img/logo.png')}}'); background-size: contain;background-repeat: no-repeat;background-position:50% 70%;">
             @php
                $no_projek = 0;
            @endphp
            @foreach ($proyek as $item)
                @php
                    $no_projek++;
                @endphp
                @if ($no_projek >= 2)
                    <div class="row mt-1 mb-1 @if($no_projek == 3) breakafter @endif">
                        <div class="col-12">
                            <table class='table table-bordered' style="border:1px solid #3f3f3f;">
                                <thead>
                                    <tr style="height:40px" class="table-primary">
                                        <td style="vertical-align:middle">
                                            {{$item->proyek->judul}}
                                        </td>
                                        <td style="text-align:center; vertical-align:middle">{{$rentang && $rentang[1] !== null ? $rentang[1]['singkat'] : ""}}</td>
                                        <td style="text-align:center; vertical-align:middle">{{$rentang && $rentang[2] !== null ? $rentang[2]['singkat'] : ""}}</td>
                                        <td style="text-align:center; vertical-align:middle">{{$rentang && $rentang[3] !== null ? $rentang[3]['singkat'] : ""}}</td>
                                        <td style="text-align:center; vertical-align:middle">{{$rentang && $rentang[4] !== null ? $rentang[4]['singkat'] : ""}}</td>
                                    </tr>
                                </thead>
                                <tbody class="fs-10">
                                    @if (isset($array_proyek_detail[$item->proyek->uuid]))
                                        @php
                                            $no_detail = 0;
                                        @endphp

                                        @foreach ($array_proyek_detail[$item->proyek->uuid] as $detail)
                                            @php
                                                $no_detail++;
                                            @endphp
                                            <tr class="table-info">
                                                <td style="vertical-align:middle" colspan="5">
                                                    {{$detail['dimensi']}}
                                                </td>
                                            </tr>
                                            <tr class="transback">
                                                <td style="height:40px; vertical-align:middle">{{$detail['capaian']}}</td>
                                                {!! $array_nilai && isset($array_nilai[$detail['id_detail'].".".$siswa->uuid]) && $array_nilai[$detail['id_detail'].".".$siswa->uuid] == 1 ? '<td class="text-center" style="vertical-align:middle"><i class="fas fa-2x fa-check"></i></td>' : '<td></td>' !!}
                                                {!! $array_nilai && isset($array_nilai[$detail['id_detail'].".".$siswa->uuid]) && $array_nilai[$detail['id_detail'].".".$siswa->uuid] == 2 ? '<td class="text-center" style="vertical-align:middle"><i class="fas fa-2x fa-check"></i></td>' : '<td></td>' !!}
                                                {!! $array_nilai && isset($array_nilai[$detail['id_detail'].".".$siswa->uuid]) && $array_nilai[$detail['id_detail'].".".$siswa->uuid] == 3 ? '<td class="text-center" style="vertical-align:middle"><i class="fas fa-2x fa-check"></i></td>' : '<td></td>' !!}
                                                {!! $array_nilai && isset($array_nilai[$detail['id_detail'].".".$siswa->uuid]) && $array_nilai[$detail['id_detail'].".".$siswa->uuid] == 4 ? '<td class="text-center" style="vertical-align:middle"><i class="fas fa-2x fa-check"></i></td>' : '<td></td>' !!}
                                            </tr>
                                        @endforeach

                                        @if ($no_detail < 4)
                                            @php
                                                $kekurangan = 4 - $no_detail;
                                            @endphp
                                            @for ($k = 0; $k < $kekurangan; $k++)
                                                <tr class="table-info">
                                                    <td style="vertical-align:middle; height:40px" colspan="5">

                                                    </td>
                                                </tr>
                                                <tr class="transback">
                                                    <td style="height:40px;"></td>
                                                    <td style="height:40px;"></td>
                                                    <td style="height:40px;"></td>
                                                    <td style="height:40px;"></td>
                                                    <td style="height:40px;"></td>
                                                </tr>
                                            @endfor
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                            <table class="table table-bordered fs-10 mt-2" style="border:1px solid #3f3f3f">
                                <tr class="table-primary">
                                    <td>Catatan Proses</td>
                                </tr>
                                <tr style="height:50px" class="transback">
                                    <td style="padding:3px">{{$array_deskripsi && isset($array_deskripsi[$item->proyek->uuid.".".$siswa->uuid]) ? $array_deskripsi[$item->proyek->uuid.".".$siswa->uuid] : ""}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
            @if (count($proyek) == 2)
                <div class="row mt-3 mb-4">
                    <h6 class="fs-12 mt-2 mb-2 text-center"><b>KETERANGAN TINGKAT PENCAPAIAN PESERTA DIDIK</b></h6>
                    <table class='table table-bordered' style="border:1px solid #3f3f3f;">
                        <tr class="table-primary">
                        <td width="25%" style="text-align:center; vertical-align:middle">BB</td>
                        <td width="25%" style="text-align:center; vertical-align:middle">MB</td>
                        <td width="25%" style="text-align:center; vertical-align:middle">BSH</td>
                        <td width="25%" style="text-align:center; vertical-align:middle">SB</td>
                        </tr>
                        <tr class="table-info">
                            <td width="25%" style="text-align:center; vertical-align:middle">Belum Berkembang</td>
                            <td width="25%" style="text-align:center; vertical-align:middle">Mulai Berkembang</td>
                            <td width="25%" style="text-align:center; vertical-align:middle">Berkembang Sesuai Harapan</td>
                            <td width="25%" style="text-align:center; vertical-align:middle">Sudah Berkembang</td>
                        </tr>
                        <tr class="transback">
                            <td width="25%" style="text-align:center; vertical-align:middle">Siswa masih membutuhkan bimbingan dalam mengembangkan kemampuan</td>
                            <td width="25%" style="text-align:center; vertical-align:middle">Siswa mulai mengembangkan kemampuan namun masih belum membudaya</td>
                            <td width="25%" style="text-align:center; vertical-align:middle">Siswa telah mengembangkan kemampuan hingga berada dalam tahap membudaya</td>
                            <td width="25%" style="text-align:center; vertical-align:middle">Siswa mengembangkan kemampuannya melampaui harapan</td>
                        </tr>
                    </table>
                </div>
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
                <div class="row mt-1 mb-1">
                    <div class="col-12 text-center">
                        <p class="m-0">Mengetahui</p>
                        <p class="m-0 mb-5">Kepala {{$nama_sekolah ? $nama_sekolah->nilai : ""}}</p>
                        <p class="m-0">{{$kepala_sekolah !== null ? $kepala_sekolah->nama : ""}}</p>
                        <p class="m-0">NIK.{{$kepala_sekolah !== null ? $kepala_sekolah->nik : ""}}</p>
                    </div>
                </div>
            @endif

        </div>
        {{-- Page 3 --}}
        @if (count($proyek) > 2)
            <div style="background:linear-gradient(rgba(255,255,255,.8), rgba(255,255,255,.8)), url('{{asset('img/logo.png')}}'); min-height:100%; background-size: contain;background-repeat: no-repeat;background-position:50% 70%">
                <div class="row mt-3 mb-4">
                    <h6 class="fs-12 mt-2 mb-2 text-center"><b>KETERANGAN TINGKAT PENCAPAIAN PESERTA DIDIK</b></h6>
                    <table class='table table-bordered' style="border:1px solid #3f3f3f;">
                        <tr class="table-primary">
                        <td width="25%" style="text-align:center; vertical-align:middle">BB</td>
                        <td width="25%" style="text-align:center; vertical-align:middle">MB</td>
                        <td width="25%" style="text-align:center; vertical-align:middle">BSH</td>
                        <td width="25%" style="text-align:center; vertical-align:middle">SB</td>
                        </tr>
                        <tr class="table-info">
                            <td width="25%" style="text-align:center; vertical-align:middle">Belum Berkembang</td>
                            <td width="25%" style="text-align:center; vertical-align:middle">Mulai Berkembang</td>
                            <td width="25%" style="text-align:center; vertical-align:middle">Berkembang Sesuai Harapan</td>
                            <td width="25%" style="text-align:center; vertical-align:middle">Sudah Berkembang</td>
                        </tr>
                        <tr class="transback">
                            <td width="25%" style="text-align:center; vertical-align:middle">Siswa masih membutuhkan bimbingan dalam mengembangkan kemampuan</td>
                            <td width="25%" style="text-align:center; vertical-align:middle">Siswa mulai mengembangkan kemampuan namun masih belum membudaya</td>
                            <td width="25%" style="text-align:center; vertical-align:middle">Siswa telah mengembangkan kemampuan hingga berada dalam tahap membudaya</td>
                            <td width="25%" style="text-align:center; vertical-align:middle">Siswa mengembangkan kemampuannya melampaui harapan</td>
                        </tr>
                    </table>
                </div>
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
                <div class="row mt-1 mb-1" style="height:300px">
                    <div class="col-12 text-center">
                        <p class="m-0">Mengetahui</p>
                        <p class="m-0 mb-5">Kepala {{$nama_sekolah ? $nama_sekolah->nilai : ""}}</p>
                        <p class="m-0">{{$kepala_sekolah !== null ? $kepala_sekolah->nama : ""}}</p>
                        <p class="m-0">NIK.{{$kepala_sekolah !== null ? $kepala_sekolah->nik : ""}}</p>
                    </div>
                </div>
            </div>
        @endif
        {{-- Footer --}}
        <div class="col-12 ms-4 me-4 pe-4" style="position:fixed !important; bottom:1mm;width:90%;border-top:1px dotted #000;background-color:#fff">
            <p class="m-0"><span class="bg-primary ps-3 pe-3"></span> <span class="ms-3">Rapor P5 {{$nama_sekolah->nilai}} Tanjungpinang</span></p>
            <p class="m-0"><span class="bg-primary-subtle ps-3 pe-3"></span> <span class="ms-3">{{$siswa->nis}} | {{$siswa->nama}} | Kelas {{$siswa->kelas->tingkat.$siswa->kelas->kelas}} | Semester {{$semester->semester}} | {{$semester->tp}}</span></p>
        </div>
    </div>
    <script>
        $('.print-rapor').click(function() {
            window.print();
        });
    </script>
@endsection
