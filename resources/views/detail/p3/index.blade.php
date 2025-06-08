@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('detail-poin')}}
    <div class="body-contain-customize col-12">
        <h5>Informasi Pelanggaran, Prestasi dan Partisipasi</h5>
        <p>Halaman ini diperuntukan siswa untuk memantau Pelanggaran, Prestasi dan Partisipasi selama tahun pelajaran berlangsung</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>Detail Prestasi Siswa Siswa</b></p>
         <div class="table-responsive">
            <table class="table table-bordered fs-12">
                <thead>
                    <tr>
                        <td width="5%">No</td>
                        <td width="15%">Tanggal</td>
                        <td width="80%">Deskripsi</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $p3_prestasi = $p3->filter(function($elem) {
                            return $elem->jenis == "prestasi";
                        });
                        $no = 1;
                    @endphp
                    @if(isset($p3_prestasi))
                        @foreach ($p3_prestasi as $prestasi)
                            <tr>
                                <td>{{ $no;  }}</td>
                                <td>{{ date('d F Y', strtotime($prestasi->tanggal)) }}</td>
                                <td>{{ $prestasi->deskripsi }}</td>
                            </tr>
                            @php
                                $no++;
                            @endphp
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
