@extends('layouts.main')


@section('container')
{{Breadcrumbs::render('absensi-histori-siswa')}}
<div class="body-contain-customize col-12">
    <h5><b>Histori Absensi</b></h5>
    <p>Halaman ini berguna untuk melihat absensi pada tanggal sebelumnya</p>
</div>
<div class="body-contain-customize col-12 mt-3">
    <table class="table table-bordered table-striped" id="table-absensi">
        <thead>
            <tr>
                <td>No</td>
                <td>Tanggal</td>
                <td>Absensi</td>
                <td>Waktu</td>
                <td>Keterangan</td>
            </tr>
        </thead>
        <tbody>
            @php
            $no = 1;
            @endphp
            @foreach ($tanggal as $item)
                @if (isset($absensi_array[$item->uuid]))
                    <tr>
                        <td>{{$no}}</td>
                        <td>{{date('d F Y',strtotime($item->tanggal))}}</td>
                        <td>{{$absensi_array[$item->uuid]['absensi']}}</td>
                        @if (isset($absensi_array[$item->uuid]['waktu']))
                            @php
                                if(strtotime($absensi_array[$item->uuid]['waktu']) >= strtotime('07:45:00')) {
                                    $class = "text-danger";
                                } else {
                                    $class = "";
                                }
                            @endphp
                            <td class="{{$class}}">
                                {{$absensi_array[$item->uuid]['waktu']}}
                            </td>
                        @else
                            <td>-</td>
                        @endif
                        <td>{{$absensi_array[$item->uuid]['keterangan']}}</td>
                        @php
                            $no++;
                        @endphp
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
<script>
    var table = new DataTable('#table-absensi',{
            // scrollX : true,
            columns: [{ width: '10%' },{ width: '15%' },{ width: '15%' },{ width: '15%' },{ width: '20%' }],
            "initComplete": function (settings, json) {
                $("#table-absensi").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
        });
</script>
@endsection
