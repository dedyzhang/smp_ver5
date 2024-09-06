@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('agenda-history')}}
    <div class="body-contain-customize col-12">
        <h5>Rekap Agenda</h5>
        <p>Halaman ini diperuntukkan guru untuk melihat rekap agenda yang sudah diisi</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="row m-0 p-0">
            <div class="col-12 col-sm-12 col-md-8 col-lg-6 col-xl-6">
                <label for="minggu-ke">Minggu Ke</label>
                <select class="form-control" data-toggle="select" name="minggu-ke" id="minggu-ke">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($tanggal as $item => $value)
                        @php
                            $getVal = "";
                            $count = count($value);
                            $i = 0;
                            $tanggalVal = "";
                        @endphp
                        @foreach ($value as $elm)
                            @php
                                if($i === 0) {
                                    $tanggalVal .= date('d M Y', strtotime($elm->tanggal))." - ";
                                } else if($i == $count - 1) {
                                    $tanggalVal .= date('d M Y', strtotime($elm->tanggal));
                                }
                                $getVal .= $elm->uuid.",";
                                $i++;
                            @endphp
                        @endforeach
                        @php
                            $getVal = substr($getVal,0,-1);
                        @endphp
                        <option value="{{$getVal}}">{{$item}} ({{$tanggalVal}})</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="agenda-place col-12 m-0 p-0"></div>
    <script>
        $('#minggu-ke').change(function() {
            var minggu = $(this).val();
            loading();
            $.ajax({
                type: "GET",
                url : "{{route('agenda.historyRekapAgenda')}}",
                data: {minggu : minggu},
                success: function(data) {
                    var agendaPlace = "";
                    var jadwalArray = data.jadwal_array;
                    if(jadwalArray !== undefined) {
                        data.jadwal_array.forEach(function(item) {
                            var waktu = item.tanggal+"T"+item.waktu;
                            var uuid = item.uuid;
                            var agendaArray = Object.values(data.agenda_array);

                            const result = agendaArray.filter(element => element.id_jadwal === uuid);
                            if(result.length == 1) {
                                //Absensi Siswa
                                var absensi = "";
                                if(result[0].absensi.length > 0) {
                                    var no = 1;
                                    result[0].absensi.forEach(function(absen) {
                                        absensi += `<tr>
                                            <td class="text-center">${no}</td>
                                            <td>${absen.siswa.nama}</td>
                                            <td class="text-center">${absen.absensi}</td>
                                            <td>${absen.keterangan}</td>
                                        </tr>`;
                                        no++;
                                    });
                                }
                                //Pancasila
                                var pancasila = "";
                                const pancasilaArray = [{
                                    1 : "Beriman, bertakwa kepada Tuhan Yang Maha Esa, dan berakhlak mulia",
                                    2 : "Mandiri",
                                    3 : "Bergotong-royong",
                                    4 : "Berkebinekaan global",
                                    5 : "Bernalar kritis",
                                    6 :  "Kreatif",
                                }];
                                if(result[0].pancasila.length > 0) {
                                    var no = 1;
                                    result[0].pancasila.forEach(function(pancasilaItem) {
                                        var dimensi = pancasilaItem.dimensi;
                                        pancasila += `
                                            <tr>
                                                <td class="text-center">${no}</td>
                                                <td>${pancasilaItem.siswa.nama}</td>
                                                <td class="text-center">${pancasilaArray[0][dimensi]}</td>
                                                <td>${pancasilaItem.keterangan}</td>
                                            </tr>
                                        `;
                                        no++;
                                    });

                                }
                                //Agenda Siswa
                                var route = "{{route('agenda.edit',':id')}}";
                                route = route.replace(':id',result[0].uuid);

                                //Duplikat Data
                                var duplikatRoute = "{{route('agenda.createCopy',':id')}}";
                                duplikatRoute = duplikatRoute.replace(':id',result[0].uuid);
                                var agenda = `
                                <div class="row m-0 p-0 mt-3">
                                    <div class="col-12 col-sm-7 col-md-6 col-lg-6 col-xl-6 mt-2 mb-2">
                                        <h6><b>Agenda</b></h6>
                                    </div>
                                    <div class="col-12 col-sm-5 col-md-6 col-lg-6 col-xl-6 gap-2 text-end">
                                        <a href="${route}" class="btn btn-sm btn-light"><i class="fas fa-pencil"></i></a>
                                        <a href="${duplikatRoute}" class="btn btn-sm btn-light"><i class="fas fa-copy"></i></a>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <p class="m-0"><b><i class="fa-solid fa-chalkboard me-1"></i> Pembahasan</b></p>
                                        <p class="text-justify ms-4">${result[0].pembahasan}</p>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <p class="m-0"><b><i class="fa-solid fa-bullhorn me-1"></i> Metode</b></p>
                                        <p class="text-justify ms-4">${result[0].metode}</p>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <p class="m-0"><b><i class="fa-solid fa-person-chalkboard me-1"></i> Kegiatan</b></p>
                                        <p class="text-justify ms-4">${result[0].kegiatan}</p>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <p class="m-0"><b><i class="fa-solid fa-business-time me-1"></i> Proses</b></p>
                                        <p class="text-justify ms-4">${result[0].proses}</p>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <p class="m-0"><b><i class="fa-solid fa-book me-1"></i> Kendala</b></p>
                                        <p class="text-justify ms-4">${result[0].kendala}</p>
                                    </div>
                                </div>
                                <div class="row m-0 p-0 mt-3">
                                    <div class="col-12 col-sm-12 order-1 order-sm-1 order-md-1 order-lg-1 order-xl-1 col-md-8 col-lg-6 col-xl-6 mt-2 mb-2">
                                        <h6><b>Absensi Siswa</b></h6>
                                    </div>
                                    <div class="col-12 col-sm-12 order-3 order-sm-3 order-md-3 order-lg-2 order-xl-2 col-md-8 col-lg-6 col-xl-6 mt-2 mb-2">
                                        <h6><b>Penilaian P5 Siswa</b></h6>
                                    </div>
                                    <div class="col-12 col-sm-12 order-2 order-sm-2 order-md-2 order-lg-3 order-xl-3 col-md-8 col-lg-6 col-xl-6">
                                        <table class="table table-bordered fs-12" width="100">
                                            <thead>
                                                <tr>
                                                    <td width="5%">No</td>
                                                    <td width="50%">Nama Siswa</td>
                                                    <td width="10%">Absensi</td>
                                                    <td width="35%">Keterangan</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${absensi}
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-12 col-sm-12 order-4 order-sm-4 order-md-4 order-lg-4 order-xl-4 col-md-8 col-lg-6 col-xl-6">
                                        <table class="table table-bordered fs-12" width="100">
                                            <thead>
                                                <tr>
                                                    <td width="5%">No</td>
                                                    <td width="50%">Nama Siswa</td>
                                                    <td width="10%">Dimensi</td>
                                                    <td width="35%">Keterangan</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${pancasila}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>`;
                            } else {
                                var agenda = `<div class="alert alert-danger mt-3" role="alert">
                                    <strong>Tidak Ada Agenda !</strong> Guru bersangkutan belum membuat agenda ini
                                </div>`;
                            }
                            agendaPlace += `
                                <div class="body-contain-customize nostrip mt-3 col-12">
                                    <div class="row m-0 p-3 pe-1 ps-1 rounded-3 bg-primary-subtle">
                                        <div class="col-5 col-sm-4 col-md-2 col-lg-2 col-xl-2">
                                            Tanggal :
                                        </div>
                                        <div class="col-7 col-sm-8 col-md-4 col-lg-4 col-xl-4">${moment(item.tanggal).format('ddd, DD MMM YYYY')}</div>
                                        <div class="col-5 col-sm-4 col-md-2 col-lg-2 col-xl-2">
                                            Jam Ke :
                                        </div>
                                        <div class="col-7 col-sm-8 col-md-4 col-lg-4 col-xl-4">${moment(waktu).format('HH:mm')}</div>
                                        <div class="col-5 col-sm-4 col-md-2 col-lg-2 col-xl-2 mt-md-2 mt-lg-2 mt-xl-2">
                                            Pelajaran :
                                        </div>
                                        <div class="col-7 col-sm-8 col-md-4 col-lg-4 col-xl-4 mt-md-2 mt-lg-2 mt-xl-2">${item.pelajaran}</div>
                                        <div class="col-5 col-sm-4 col-md-2 col-lg-2 col-xl-2 mt-md-2 mt-lg-2 mt-xl-2">
                                            Kelas :
                                        </div>
                                        <div class="col-7 col-sm-8 col-md-4 col-lg-4 col-xl-4 mt-md-2 mt-lg-2 mt-xl-2">${item.kelas}</div>
                                        <div class="col-5 col-sm-4 col-md-2 col-lg-2 col-xl-2 mt-md-2 mt-lg-2 mt-xl-2">
                                            Versi Jadwal :
                                        </div>
                                        <div class="col-7 col-sm-8 col-md-4 col-lg-4 col-xl-4 mt-md-2 mt-lg-2 mt-xl-2">${item.versi}</div>
                                    </div>
                                    ${agenda}
                                </div>
                            `;
                        });
                        $('.agenda-place').html(agendaPlace);
                    }
                    removeLoading();
                },
                error: function(data) {
                    console.error(data.responseJSON);
                }
            })
        });
    </script>
@endsection
