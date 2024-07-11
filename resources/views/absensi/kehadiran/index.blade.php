@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('absensi-kehadiran')}}
    <div class="body-contain-customize col-12">
        <h5><b>Absensi Kehadiran</b></h5>
        <p>Halaman ini peruntukkan untuk user melakukan absensi kehadiran dan pulang</p>
    </div>
    @if ($adaTanggal == "ada")
        <div class="body-contain-customize col-12 mt-3">
            <p class="mb-0">Tanggal : {{date('d F Y')}}</p>
        </div>
        <div class="body-contain-customize col-12 mt-3 time-countdown @if (count($kehadiran_array) == 1)
            bg-primary-subtle
        @elseif(count($kehadiran_array) == 2)
            bg-warning-subtle
        @else
            bg-success-subtle
        @endif text-center">
            <h1 style="font-size: 50px"><b id="time">0:0:0</b></h1>
        </div>
        @if (!empty($kehadiran_array))
            @if (count($kehadiran_array) == 1)
                @if ($auth->access == "kurikulum" || $auth->access == "kesiswaan" || $auth->access == "guru" || $auth->access == "sapras")
                    <div class="body-contain-customize col-12 mt-3">
                        <p><b>Agenda Yang Belum Diisi</b></p>
                        <ul class="list-group mb-3">
                            @php
                                $countBelum = 0;
                            @endphp
                            @foreach ($jadwal as $item)
                                @if(!in_array($item->uuid,$array_agenda))
                                    <a href="{{route('agenda.createID',$item->uuid)}}" class="list-group-item list-group-item-action">{{$item->kelas->tingkat.$item->kelas->kelas." - ".$item->pelajaran->pelajaran_singkat. "( ".$item->waktu->waktu_mulai." )"}}</a>
                                    @php
                                        $countBelum++;
                                    @endphp
                                @endif
                            @endforeach
                        </ul>
                        <span><i>Klik untuk menuju ke pengisian agenda</i></span>
                    </div>
                    @if ($countBelum == 0)
                        <div class="body-contain-customize col-12 mt-3 d-grid d-sm-grid d-lg-none d-md-none d-xl-none">
                            <a href="{{route('absensi.kehadiran.hadir','pulang')}}" class="btn btn-sm btn-warning text-warning-emphasis">Absen Pulang</a>
                        </div>
                    @endif
                @else
                    <div class="body-contain-customize col-12 mt-3 d-grid d-sm-grid d-lg-none d-md-none d-xl-none">
                        <a href="{{route('absensi.kehadiran.hadir','pulang')}}" class="btn btn-sm btn-warning text-warning-emphasis">Absen Pulang</a>
                    </div>
                @endif
            @endif
            <script>
                $(document).ready(function() {
                    var jenis = "{{count($kehadiran_array)}}";
                    if(jenis == 1) {
                        setInterval(function() {
                            var times = "{{$kehadiran_array['datang']}}";
                            var timesSplit = times.split(':');
                            var now = new Date();
                            var finalTimes = new Date(now.getFullYear(), now.getMonth(), now.getDate(), ...timesSplit);
                            var timespan = countdown(finalTimes, new Date());
                            var div = document.getElementById('time');
                            div.innerHTML = timespan.hours + ":" + timespan.minutes + ":" + timespan.seconds;
                        }, 1000);
                    } else if(jenis == 2) {
                        var datang = "{{$kehadiran_array['datang']}}";
                        var datangSplit = datang.split(':');
                        var now = new Date();
                        var finalDatang = new Date(now.getFullYear(), now.getMonth(), now.getDate(), ...datangSplit);
                        var pulang = "@if(isset($kehadiran_array['pulang'])) {{$kehadiran_array['pulang']}} @else 0:0:0  @endif";
                        var pulangSplit = pulang.split(':');
                        var now = new Date();
                        var finalPulang = new Date(now.getFullYear(), now.getMonth(), now.getDate(), ...pulangSplit);
                        var timespan = countdown(finalDatang, finalPulang);
                        var div = document.getElementById('time');
                        div.innerHTML = timespan.hours + ":" + timespan.minutes + ":" + timespan.seconds;
                    }
                });
            </script>
        @else
            <div class="body-contain-customize col-12 mt-3 d-grid d-sm-grid d-lg-none d-md-none d-xl-none">
                <a href="{{route('absensi.kehadiran.hadir','datang')}}" class="btn btn-sm btn-success">Absen Kehadiran</a>
            </div>
        @endif

    @else
        <div class="body-contain-customize col-12 mt-3">
            <p>Maaf Tanggal belum ditambahkan oleh Admin, maupun Kurikulum. Contact Admin maupun Kurikulum untuk mengakses fitur absensi</p>
        </div>
    @endif

@endsection
