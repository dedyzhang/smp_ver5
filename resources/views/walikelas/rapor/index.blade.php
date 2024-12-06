@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('walikelas-rapor')}}
    <div class="body-contain-customize col-12">
        <h5>Rapor Kelas</h5>
        <p>Halaman ini digunakan untuk menampilkan dan mencetak seluruh halaman rapor siswa</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5">
        <p><b>Data Rapor</b></p>
        <table class="table table-striped fs-13">
            <tr>
                <td width="30%">Nama Guru</td>
                <td width="5%">:</td>
                <td>{{$guru->nama}}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{$kelas->tingkat.$kelas->kelas}}</td>
            </tr>
            <tr>
                <td>Semester / TP</td>
                <td>:</td>
                <td>{{$semester->semester == 1 ? "Ganjil" : "Genap"}} / {{$semester->tp}}</td>
            </tr>
        </table>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td width="5%">No</td>
                        <td width="15%">Nis</td>
                        <td width="70%">Nama Siswa</td>
                        <td width="10%">#</td>
                    </tr>
                </thead>
                <tbody>
                    @if ($kelas->siswa)
                         @foreach ($kelas->siswa as $siswa)
                            <tr class="fs-12">
                                <td>{{$loop->iteration}}</td>
                                <td>{{$siswa->nis}}</td>
                                <td>{{$siswa->nama}}</td>
                                <td>
                                    <a href="{{route('walikelas.rapor.show',$siswa->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
