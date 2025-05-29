@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Pelanggaran, Prestasi dan Partisipasi</h5>
        <p>Halaman ini diperuntukkan admin dan Wakil Kesiswaan Mengatur Pelanggaran, Prestasi dan Partisipasi Siswa</p>
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
    <div class="clearfix"></div>
    <div class="body-contain-customize mt-3 col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto col-lg-flex col-xl-auto col-xl-flex">
        <a href="{{ route('p3.siswa.create',$siswa->uuid) }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Tambah P3</a>
    </div>
    <div class="body-contain-customize mt-3 col-12">
        <table class="table table-bordered table-striped fs-12" id="datatables-p3">
            <thead>
                <tr>
                    <td>No</td>
                    <td>Tanggal</td>
                    <td>Jenis</td>
                    <td>Keterangan</td>
                    <td>#</td>
                </tr>
            </thead>
        </table>   
    </div>
    <script>
        var table = new DataTable('#datatables-p3',{
            columns: [
                {width: '5%'},
                {width: '10%'},
                {width: '20%'},
                {width: '50%'},
                {width: '10%'},
            ],
            columnDefs: [
                { className: 'text-center', targets: [0,1,3,4,5] },
             ],
            "initComplete" : function(settings,json) {
                $('#datatables-p3').wrap('<div style="overflow:auto; width:100%; position:relative"></div>');
            }
        });
    </script>
@endsection