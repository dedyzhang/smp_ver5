@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Absensi Kelas</b></h5>
        <p>Halaman Walikelas untuk mengatur absensi siswa</p>
    </div>
    @if ($iswalikelas === false)
        <div class="body-contain-customize col-12 mt-3">
            <h4>ANDA BELUM TERJARING SEBAGAI WALIKELAS PADA TAHUN PELAJARAN INI</h4>
        </div>
    @else
        <div class="body-contain-customize col-12 mt-3">
            <a href="{{route('walikelas.absensi.create')}}" class="btn btn-sm btn-warning text-warning-emphasis">
                <i class="fas fa-plus"></i> Tambah Absensi
            </a>
        </div>
        <div class="body-contain-customize col-12 mt-3">
            <div class="table-responsive">
                <table class="table table-bordered table-striped fs-12" id="table-absensi">
                    <thead>
                        <tr class="text-center align-middle">
                            <td rowspan="2" width="5%">No</td>
                            <td rowspan="2" width="30%">Nama Siswa</td>
                            <td rowspan="2" width="10%">Jumlah Hari</td>
                            <td colspan="5">Jumlah Absensi</td>
                        </tr>
                        <tr class="text-center">
                            <td width="10%">Hadir</td>
                            <td width="10%">Sakit</td>
                            <td width="10%">Izin</td>
                            <td width="10%">Alpha</td>
                            <td width="10%">Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kelas->siswa as $siswa)
                            <tr class="text-center">
                                <td>{{$loop->iteration}}</td>
                                <td class="text-start">{{$siswa->nama}}</td>
                                <td>{{$jumlahAbsensi}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
