@extends('layouts.main')


@section('container')
    {{Breadcrumbs::render('absensi-histori')}}
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
                    <td>Datang</td>
                    <td>Pulang</td>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($tanggal as $item)
                    @if (isset($absensi_array['datang-'.$item->uuid]) || isset($absensi_array['pulang-'.$item->uuid]))
                        <tr>
                            <td>{{$no}}</td>
                            <td>{{date('d F Y',strtotime($item->tanggal))}}</td>
                                @if (isset($absensi_array['datang-'.$item->uuid]))
                                @php
                                    if(strtotime($absensi_array['datang-'.$item->uuid]) >= strtotime('07:45:00')) {
                                        $class = "text-danger";
                                    } else {
                                        $class = "";
                                    }
                                @endphp
                                <td class="{{$class}}">
                                    {{$absensi_array['datang-'.$item->uuid]}}
                                </td>
                                @else
                                    <td>-</td>
                                @endif
                            <td>
                                @if (isset($absensi_array['pulang-'.$item->uuid]))
                                    {{$absensi_array['pulang-'.$item->uuid]}}
                                @else
                                    -
                                @endif
                            </td>
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
            columns: [{ width: '10%' },{ width: '30%' },{ width: '20%' },{ width: '20%' }],
            "initComplete": function (settings, json) {
                $("#table-absensi").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
        });
    </script>
@endsection
