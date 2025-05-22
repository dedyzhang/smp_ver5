@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('poin')}}
    <div class="body-contain-customize col-12">
        <h5>Pelanggaran, Prestasi dan Partisipasi</h5>
        <p>Halaman ini diperuntukkan admin dan Wakil Kesiswaan Mengatur Pelanggaran, Prestasi dan Partisipasi Siswa</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <table class="table table-bordered table-striped fs-12" id="datatables-siswa">
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
                    <td>P</td>
                    <td>P</td>
                    <td>P</td>
                </tr>

            </thead>
            <tbody>
                @foreach ($siswa as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->nis}}</td>
                        <td>{{$item->nama}}</td>
                        <td>{{$item->kelas ? $item->kelas->tingkat.$item->kelas->kelas : ""}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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
                { className: 'text-center', targets: [0,1,3,4,5] },
             ],
            "initComplete" : function(settings,json) {
                $('#datatables-siswa').wrap('<div style="overflow:auto; width:100%; position:relative"></div>');
            }
        });
    </script>
@endsection
