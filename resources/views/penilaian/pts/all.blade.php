@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>PTS Kelas {{$kelas->tingkat.$kelas->kelas}}</b></h5>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td rowspan="2" width="3%">No</td>
                        <td rowspan="2" width="15%">Nama</td>
                        <td colspan="{{$ngajar->count()}}">Nilai</td>
                    </tr>
                    <tr>
                        @foreach ($ngajar as $item)
                            <td width="5%">{{$item->pelajaran_singkat}}</td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $siswa)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$siswa->nama}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
