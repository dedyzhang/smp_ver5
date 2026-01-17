@extends('layouts.main')

@section('container')
    {{-- @if (\Request::route()->getName() === 'walikelas.rapor.show')
        {{Breadcrumbs::render('walikelas-rapor-show',$siswa)}}
    @else
        {{Breadcrumbs::render('penilaian-admin-rapor-individu',$siswa)}}
    @endif --}}
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
        @foreach ($siswa as $siswa_item)
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
                            <div class="col-4">: {{$siswa_item->nama}}</div>
                            <div class="col-3">Kelas</div>
                            <div class="col-3">: {{$siswa_item->kelas->tingkat.$siswa_item->kelas->kelas}}</div>
                            <div class="col-2">No Induk</div>
                            <div class="col-4">: {{$siswa_item->nis}}</div>
                            <div class="col-3">Fase</div>
                            <div class="col-3">: {{isset($fase_rapor_array[$siswa_item->kelas->tingkat]) ? $fase_rapor_array[$siswa_item->kelas->tingkat] : ""}}</div>
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
                {{-- Footer --}}
                <div class="col-12 ms-4 me-4 pe-4" style="position:fixed !important; bottom:1mm;width:90%;border-top:1px dotted #000;background-color:#fff">
                    <p class="m-0"><span class="bg-primary ps-3 pe-3"></span> <span class="ms-3">Rapor {{$nama_sekolah->nilai}} Tanjungpinang</span></p>
                    <p class="m-0"><span class="bg-primary-subtle ps-3 pe-3"></span> <span class="ms-3">{{$siswa_item->nis}} | {{$siswa_item->nama}} | Kelas {{$siswa_item->kelas->tingkat.$siswa_item->kelas->kelas}} | Semester {{$semester->semester}} | {{$semester->tp}}</span></p>
                </div>
        @endforeach
    </div>
    <script>
        $('.print-rapor').click(function() {
            window.print();
        });
    </script>
@endsection
