@extends('layouts.main') @section('container')
    {{Breadcrumbs::render('proyek-rapor-kelas',$kelas)}}
    <div class="body-contain-customize col-12">
        <h5>
            <b>Rapor P5 Kelas {{ $kelas->tingkat . $kelas->kelas }}</b>
        </h5>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="table-responsive">
            <table class="table table-bordered fs-11">
                <thead>
                    <tr class="text-center align-middle">
                        <td width="3%" rowspan="2">No</td>
                        <td style="min-width: 170px" width="20%" rowspan="2">Nama</td>
                        @if (isset($proyek))
                            @foreach ($proyek as $item)
                                <td colspan="{{isset($array_proyek_detail[$item->proyek->uuid]) ? count($array_proyek_detail[$item->proyek->uuid]) : 1}}">{{$item->proyek->judul}}</td>
                            @endforeach
                        @endif
                        <td width="10%" rowspan="2">#</td>
                    </tr>
                    <tr class="text-center align-middle">
                        @if (isset($proyek))
                            @foreach ($proyek as $item)
                                @if (isset($array_proyek_detail[$item->proyek->uuid]))
                                    @foreach ($array_proyek_detail[$item->proyek->uuid] as $detail)
                                        <td width="5%"><i class="fas fa-eye"></i></td>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $siswa)
                        @php
                            $jumlah = 0;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $siswa->nama }}</td>
                            <td><a href="{{route('penilaian.p5.rapor.print',$siswa->uuid)}}"><i class="fas fa-print"></i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
