@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('detail-absensi')}}
    <div class="body-contain-customize col-12">
        <h5><b>Informasi Absensi Siswa</b></h5>
        <p>Halaman ini diperuntukkan siswa melihat rekap absensi siswa selama proses pembelajaran berlangsung</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5">
        <table class="table table-striped fs-13">
            <tr>
                <td width="30%">Nama</td>
                <td width="5%">:</td>
                <td>{{$siswa->nama}}</td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>:</td>
                <td>{{$siswa->nis}}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{$siswa->kelas->tingkat.$siswa->kelas->kelas}}</td>
            </tr>
        </table>
    </div>
    <div class="body-contain-customize mt-3 col-12">
        <p>Rekap Kehadiran Per Jenis Absensi</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Total Hari</th>
                    <th>Sakit</th>
                    <th>Izin</th>
                    <th>Alpa</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$jumlah}}</td>
                    <td>{{$absensi->sakit}}</td>
                    <td>{{$absensi->izin}}</td>
                    <td>{{$absensi->alpa}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row m-0 p-0 mt-3">
        <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 pe-xl-3 col-12 col-sm-6 col-md-6 col-lg-5 col-xl-4">
            <div class="card rounded-4 border-0">
                <div class="card-body">
                    <p><b>Persentase Kehadiran</b></p>
                    <canvas id="chart-kelas" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="p-0 mt-3 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0 col-12 col-sm-6 col-md-6 col-lg-7 col-xl-8">
            <div class="card rounded-4 border-0">
                <div class="card-body">
                    <p><b>List Absensi Per Semester</b></p>
                    <div class="vertical-align" style="height:300px; overflow-y:auto;">
                        <table id="table-siswa" class="fs-12 mt-2 table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="10%">No</th>
                                    <th width="20%">Tanggal</th>
                                    <th width="10%">Absensi</th>
                                    <th width="30%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absensiSemua as $abs)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{date('d M Y',strtotime($abs->tanggal->tanggal))}}</td>
                                        <td>{{$abs->absensi}}</td>
                                        <td>{{$abs->keterangan}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        @php
            $jumlah == 0 ? $jumlahSemua = 0 : $jumlahSemua = $jumlah;
            $absensi->sakit == 0 ? $jumlahSakit = 0 : $jumlahSakit = $absensi->sakit;
            $absensi->izin == 0 ? $jumlahIzin = 0 : $jumlahIzin = $absensi->izin;
            $absensi->alpa == 0 ? $jumlahAlpa = 0 : $jumlahAlpa = $absensi->alpa;
            $totalTidakHadir = $jumlahSakit + $jumlahIzin + $jumlahAlpa;
            $totalHadir = $jumlahSemua - $totalTidakHadir;
            $persentaseHadir = round($totalHadir / $jumlahSemua * 100,1);
            $persentaseSakit = round($jumlahSakit / $jumlahSemua * 100,1);
            $persentaseIzin = round($jumlahIzin / $jumlahSemua * 100,1);
            $persentaseAlpa = round($jumlahAlpa / $jumlahSemua * 100,1);

        @endphp
        <script>
            const ctx = document.getElementById('chart-kelas');
            new Chart(ctx, {
                type: 'pie',
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Pesentase Kehadiran Siswa'
                        },
                    },
                },
                data: {
                    labels: ['Hadir', 'Sakit','Izin','Alpa'],
                    responsive : true,
                    datasets: [{
                        label: 'Persentase (%)',
                        data: [{{$persentaseHadir}},{{$persentaseSakit}},{{$persentaseIzin}},{{$persentaseAlpa}}],
                        backgroundColor: [
                            '#a0e1ff','#fd9494','#a1ff8e','#f9fd7a'
                        ],
                    }]
                },
            });

        </script>
    </div>
@endsection
