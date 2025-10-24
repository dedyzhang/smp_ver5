@extends('layouts.main')

@section('container')
    @php
        $nama_sekolah = $setting->first(function($item) {
            return $item->jenis == 'nama_sekolah';
        });
        $kop_rapor = $setting->first(function($item) {
            return $item->jenis == 'kop_rapor';
        });
        $nama_sekolah = $setting->first(function($item) {
            return $item->jenis == 'nama_sekolah';
        });

    @endphp
    {{ Breadcrumbs::render('notulen-cetak',$notulen) }}
    <div class="body-contain-customize col-12">
        <h5>Print Notulen Rapat</h5>
        <p>Halaman ini diperuntukkan Guru yang ditunjuk, Admin, Kurikulum maupun kepala sekolah untuk membuat, melihat, mengupdate dan mencetak Notulen Hasil Rapat</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-5 col-xl-5">
        <table class="table table-striped fs-12">
            <tr>
                <td width="35%">Hari</td>
                <td width="3%">:</td>
                <td width="62%">{{date('l',strtotime($notulen->tanggal_rapat))}}</td>
            </tr>
             <tr>
                <td width="35%">Tanggal</td>
                <td width="3%">:</td>
                <td width="62%">{{date('d M Y',strtotime($notulen->tanggal_rapat))}}</td>
            </tr>
        </table>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <button class="btn btn-sm btn-warning text-warning-emphasis mt-3 print-rapor">
            <i class="fas fa-print"></i> Print Rapor
        </button>
    </div>
    {{-- page1 --}}
    <div class="body-contain-customize col-12 mt-3 printable-rapor">
        <div>
            <div class="row d-flex align-items-center pt-0 mt-0 pb-2" style="border-bottom:5px double #000">
                <div class="col-2"><img src="{{asset('img/tutwuri.png')}}" alt="" width="80%"></div>
                <div class="col-8 text-center kop-surat">
                    {!! $kop_rapor && $kop_rapor->nilai ? $kop_rapor->nilai : "" !!}
                </div>
                <div class="col-2"><img src="{{asset('img/maitreyawira_square.png')}}" alt="" width="80%"></div>
            </div>
            <div class="row mt-0 mb-1">
                <div class="col-12 mb-0">
                    <h6 class="text-center mb-1"><b>NOTULEN</b></h6>
                    <h6 class="text-center mb-1"><b>RAPAT RUTIN DEWAN MAJELIS GURU</b></h6>
                    <h6 class="text-center mb-1"><b>{{$nama_sekolah && $nama_sekolah->nilai ? strtoupper($nama_sekolah->nilai) : ''}} TANJUNGPINANG</b></h6>
                    <h6 class="text-center mb-1"><b>TAHUN PELAJARAN {{strtoupper($semester->tp)}}</b></h6>
                </div>
            </div>
            <div class="row mt-1 mb-1">
                <div class="col-12">
                    <p>Pada hari ini {{\Carbon\Carbon::parse($notulen->tanggal_rapat)->isoFormat('dddd');}}, {{\Carbon\Carbon::parse($notulen->tanggal_rapat)->isoFormat('DD MMMM YYYY');}} telah dilaksanakan Rapat Rutin Dewan Majelis Guru {{$nama_sekolah && $nama_sekolah->nilai ? $nama_sekolah->nilai : ''}} Tanjungpinang Tahun Pelajaran {{$semester->tp}}. Bertempat di Ruang Rapat SMPS Maitreyawira Tanjungpinang yang dihadiri oleh Kepala Sekolah dan seluruh Dewan Majelis Guru SMPS Maitreyawira Tanjungpinang.</p>
                    <p>Adapun susunan acara tersebut sebagai berikut:</p>
                    <ol class="mb-1">
                        <li class="p-0">Pembukaan</li>
                        <li class="p-0">Pengarahan dari Kepala Sekolah</li>
                        <li class="p-0">Pembahasan</li>
                    </ol>
                    <div class="border border-dark" style="margin-left: 33px; padding:5px;">
                        {!! $notulen->pokok_permasalahan !!}
                    </div>
                    <ol class="mb-1" start="4">
                        <li class="p-0">Penutup</li>
                    </ol>
                    <p>Rapat ini telah mendapat suatu kesepakatan dan atau suatu keputusan antara lain :</p>
                    <div class="border border-dark" style="margin-left: 33px; padding:5px;">
                        {!! $notulen->hasil_rapat !!}
                    </div>
                    <p class="mt-2">Peserta yang mengikuti Rapat :</p>
                    <div class="border border-dark" style="margin-left: 33px; padding:5px;">
                        <div class="row m-0">
                            @foreach ($guru as $item)
                                <div class="col-6 mb-1">{{$item->nama}}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="mt-2">Demikian berita acara ini dibuat sebagai pedoman pelaksanaan selanjutnya.</p>
                </div>
            </div>
            <div class="row mt-0 mb-0">
                <div class="col-5"></div>
                <div class="col-2"></div>
                <div class="col-5 text-center">
                    <p class="m-0">Tanjungpinang, {{\Carbon\Carbon::parse($notulen->tanggal_rapat)->isoFormat('DD MMMM YYYY');}}</p>
                    <p class="m-0 mb-5">Kepala Sekolah</p>
                    <p class="m-0">{{$kepala_sekolah !== null ? $kepala_sekolah->nama : ""}}</p>
                    <p class="m-0">NIK.{{$kepala_sekolah !== null ? $kepala_sekolah->nik : ""}}</p>
                </div>
            </div>
        </div>
        <div>
            <div class="breakafter"></div>
            <div class="row d-flex align-items-center pt-0 mt-0 pb-2" style="border-bottom:5px double #000">
                <div class="col-2"><img src="{{asset('img/tutwuri.png')}}" alt="" width="80%"></div>
                <div class="col-8 text-center kop-surat">
                    {!! $kop_rapor && $kop_rapor->nilai ? $kop_rapor->nilai : "" !!}
                </div>
                <div class="col-2"><img src="{{asset('img/maitreyawira_square.png')}}" alt="" width="80%"></div>
            </div>
            <div class="row mt-0 mb-1">
                <div class="col-12 mb-0">
                    <h6 class="text-center mb-1"><b>DOKUMENTASI</b></h6>
                    <h6 class="text-center mb-1"><b>RAPAT RUTIN DEWAN MAJELIS GURU</b></h6>
                    <h6 class="text-center mb-1"><b>{{strtoupper(\Carbon\Carbon::parse($notulen->tanggal_rapat)->isoFormat('dddd'))}}, {{strtoupper(\Carbon\Carbon::parse($notulen->tanggal_rapat)->isoFormat('DD MMMM YYYY'))}}</b></h6>
                    <h6 class="text-center mb-1"><b>{{$nama_sekolah && $nama_sekolah->nilai ? strtoupper($nama_sekolah->nilai) : ''}} TANJUNGPINANG</b></h6>
                    <h6 class="text-center mb-1"><b>TAHUN PELAJARAN {{strtoupper($semester->tp)}}</b></h6>
                </div>
            </div>
            <div class="row mt-0 mb-1 justify-content-center">
                @php
                    $jumlah_gambar = count($dokumentasi);
                    if ($jumlah_gambar >= 3) {
                        $kelas = 'col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-2';
                    } else {
                        $kelas = 'col-span-1 col-12 col-sm-12 col-md-10 col-lg-10 col-xl-12 mb-2';
                    }
                @endphp
                @if ($notulen->dokumentasi != null)
                    @foreach($dokumentasi as $item)
                        <div class="{{ $kelas }}">
                            <img src="{{asset('storage/notulen/'.date('d M Y',strtotime($notulen->tanggal_rapat)).'/'.$item)}}" class="img-fluid img-thumbnail mb-1" alt="dokumentasi">
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <script>
        $('.print-rapor').click(function() {
            window.print();
        });
    </script>
@endsection
