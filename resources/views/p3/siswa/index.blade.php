@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('p3-siswa')}}
    <div class="body-contain-customize col-12">
        <h5>Pelanggaran, Prestasi dan Partisipasi</h5>
        <p>Halaman ini diperuntukkan admin dan Wakil Kesiswaan Mengatur Pelanggaran, Prestasi dan Partisipasi Siswa</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <table class="table table-bordered table-striped fs-11" id="datatables-siswa">
            <thead>
                <tr>
                    <td rowspan="2">No</td>
                    <td rowspan="2">NIS</td>
                    <td rowspan="2">Nama</td>
                    <td rowspan="2">Kelas</td>
                    <td colspan="3">Jumlah Poin</td>
                    <td rowspan="2">#</td>
                </tr>
                <tr>
                    <td data-bs-toggle="tooltip" data-bs-title="Pelanggaran" class="text-danger">P</td>
                    <td data-bs-toggle="tooltip" data-bs-title="Prestasi" class="text-success">P</td>
                    <td data-bs-toggle="tooltip" data-bs-title="Partisipasi" class="text-warning">P</td>
                </tr>

            </thead>
            <tbody>
                @foreach ($siswa as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->nis}}</td>
                        <td>{{$item->nama}}</td>
                        <td>{{$item->kelas ? $item->kelas->tingkat.$item->kelas->kelas : ""}}</td>
                        <td>{{ isset($array_p3[$item->uuid]) && $array_p3[$item->uuid]['pelanggaran'] != null ? $array_p3[$item->uuid]['pelanggaran'] : 0}}</td>
                        <td>{{ isset($array_p3[$item->uuid]) && $array_p3[$item->uuid]['prestasi'] != null ? $array_p3[$item->uuid]['prestasi'] : 0}}</td>
                        <td>{{ isset($array_p3[$item->uuid]) && $array_p3[$item->uuid]['partisipasi'] != null ? $array_p3[$item->uuid]['partisipasi'] : 0}}</td>
                        <td><a href="{{ route('p3.siswa.show',$item->uuid) }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Lihat Rincian" class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        var table = new DataTable('#datatables-siswa',{
            columns: [
                {width: '5%'},
                {width: '10%'},
                {width: '50%'},
                {width: '10%'},
                {width: '10%'},
                {width: '10%'},
                {width: '10%'},
                {width: '15%'},
            ],
            columnDefs: [
                { className: 'text-center', targets: [0,1,3,4,5,6,7] },
             ],
            "initComplete" : function(settings,json) {
                $('#datatables-siswa').wrap('<div style="overflow:auto; width:100%; position:relative"></div>');
            }
        });
    </script>
@endsection
