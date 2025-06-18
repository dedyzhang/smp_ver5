@extends('layouts.main')

@section('container')
    {{ Breadcrumbs::render('walikelas-p3-show',$siswa) }}
    <div class="body-contain-customize col-12">
        <h5>Pelanggaran, Prestasi dan Partisipasi</h5>
        <p>Halaman ini diperuntukkan Walikelas untuk Melihat secara Terperinci Pelanggaran, Prestasi dan Partisipasi Siswa didalam Kelasnya</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-5 col-xl-5">
        <table class="table table-striped fs-12">
            <tr>
                <td width="35%">Nama</td>
                <td width="3%">:</td>
                <td width="62%">{{$siswa->nama}}</td>
            </tr>
            <tr>
                <td width="35%">NIS</td>
                <td width="3%">:</td>
                <td width="62%">{{$siswa->nis}}</td>
            </tr>
            <tr>
                <td width="35%">Kelas</td>
                <td width="3%">:</td>
                <td width="62%">{{$siswa->kelas ? $siswa->kelas->tingkat.$siswa->kelas->kelas : "-"}}</td>
            </tr>
        </table>
    </div>
    <div class="body-contain-customize mt-3 col-12">
        <table class="table table-bordered table-striped fs-11" id="datatables-p3">
            <thead>
                <tr>
                    <td>No</td>
                    <td>Tanggal</td>
                    <td>Jenis</td>
                    <td>Keterangan</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($p3 as $elem)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d M Y',strtotime($elem->tanggal)) }}</td>
                        <td class="@if ($elem->jenis == "pelanggaran") text-danger @elseif ($elem->jenis == "partisipasi") text-warning @else text-success @endif">{{ $elem->jenis }}</td>
                        <td>{{ $elem->deskripsi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        var table = new DataTable('#datatables-p3',{
            columns: [
                {width: '5%'},
                {width: '15%'},
                {width: '20%'},
                {width: '40%'},
            ],
            columnDefs: [
                { className: 'text-center', targets: [0,1,2] },
             ],
            "initComplete" : function(settings,json) {
                $('#datatables-p3').wrap('<div style="overflow:auto; width:100%; position:relative"></div>');
            }
        });
    </script>
@endsection
