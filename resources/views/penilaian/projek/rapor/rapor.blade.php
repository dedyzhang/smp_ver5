@extends('layouts.main') @section('container')
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
                        <td width="3%">No</td>
                        <td style="min-width: 170px" width="20%">Nama</td>
                        <td width="10%">#</td>
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
