@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('cetak-siswa')}}
    <div class="body-contain-customize col-12">
        <h5><b>Cetak Nilai SAS</b></h5>
        <p>Halaman Admin untuk mencetak Nilai Sumatif Akhir Semester Siswa</p>
    </div>

    <div class="body-contain-customize col-12 mt-3">
        <table class="table table-bordered dataTables" id="table-siswa" style="width:100%">
            <thead>
                <tr>
                    <td width="5%">No</td>
                    <td width="50%">Kelas</td>
                    <td width="10%">#</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($kelas as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->tingkat.$item->kelas}}</td>
                        <td><a href="{{route('cetak.pas.excel',$item->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fa-regular fa-file-excel"></i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        var table = new DataTable('#table-siswa',{
            // scrollX : true,
            columns: [{ width: '5%' },{ width: '80%' },{ width: '10%' }],
            "initComplete": function (settings, json) {
                $("#table-siswa").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
        });
    </script>
@endsection
