@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('walikelas-poin')}}
    <div class="body-contain-customize col-12">
        <h5><b>Poin Siswa</b></h5>
        <p>Halaman ini berguna untuk melihat sisa poin siswa dan Menlihat Detail Poin Siswa</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex">
        <a href="{{route('walikelas.poin.temp')}}" class="btn btn-sm btn-warning text-warning-emphasis">
            <i class="fa fa-plus"></i> Pengajuan Poin
        </a>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <table class="table table-bordered table-striped fs-12" id="datatables-siswa">
            <thead>
                <tr>
                    <td>No</td>
                    <td>NIS</td>
                    <td>Nama</td>
                    <td>Kelas</td>
                    <td>Sisa Poin</td>
                    <td>#</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($siswa as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->nis}}</td>
                        <td>{{$item->nama}}</td>
                        <td>{{$item->kelas ? $item->kelas->tingkat.$item->kelas->kelas : ""}}</td>
                        @php
                            $sisa = 100;
                        @endphp
                        @if (isset($array_poin[$item->uuid]))
                            @foreach ($array_poin[$item->uuid] as $element)
                                @php
                                    if($element['jenis'] == "kurang") {
                                        $sisa -= $element['poin'];
                                    } else {
                                        $sisa += $element['poin'];
                                    }
                                @endphp
                            @endforeach
                            <td>{{$sisa}}</td>
                        @else
                            <td>{{$sisa}}</td>
                        @endif
                        <td>
                            <a href="{{route('walikelas.poin.show',$item->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
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
