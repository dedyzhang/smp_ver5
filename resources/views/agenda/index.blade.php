@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('agenda')}}
    <div class="body-contain-customize col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex">
        <a href="{{route('agenda.create')}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-plus"></i> Tambah Agenda</a>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>Agenda Hari ini</b></p>
        <div class="table-responsive">
            <table class="table table-bordered table-hover fs-12">
                <thead>
                    <tr>
                        <td width="5%">No</td>
                        <td width="15%">Tanggal</td>
                        <td width="10%">Kelas</td>
                        <td width="35%">Pelajaran</td>
                        <td width="15%">Jam Mulai</td>
                        <td width="5%">Status</td>
                        <td width="20%">#</td>
                    </tr>
                </thead>
                <tbody>
                    @if ($cekTanggal !== null)
                        @foreach ($jadwal as $item)
                            @php
                                if(in_array($item->uuid,$array_agenda)) {
                                    $key = array_search($item->uuid,$array_agenda);
                                    $status = "<i class='fas fa-check fs-14 text-success'></i>";
                                    $button = '<button class="btn btn-sm btn-success view-agenda" data-uuid="'.$key.'" data-bs-toggle="tooltip" data-bs-title="lihat agenda" data-bs-placement="top"><i class="fas fa-eye"></i></button> <a href="'.route('agenda.edit',$key).'" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-title="Edit Agenda" data-bs-placement="top"><i class="fas fa-pencil"></i></a>';
                                } else {
                                    $status = "<i class='fas fa-times fs-14 text-danger'></i>";
                                    $button = '<a href="'.route('agenda.createID',$item->uuid).'" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-title="Tambah Agenda" data-bs-placement="top"><i class="fas fa-arrow-right"></i></a>';
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td class="text-center">{{date('d F Y',strtotime($cekTanggal->tanggal))}}</td>
                                <td class="text-center">{{$item->kelas->tingkat.$item->kelas->kelas}}</td>
                                <td>{{$item->pelajaran->pelajaran}}</td>
                                <td class="text-center">{{$item->waktu->waktu_mulai}}</td>
                                <td class="text-center">{!!$status!!}</td>
                                <td>
                                    {!!$button!!}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade in p-0" id="agenda-view">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-lg-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h6><b>View Agenda</b></h6>
                    <button class="btn btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-0 p-0">
                        <p class="fs-14"><b>A. Tanggal dan Data Ngajar</b></p>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <table class="table table-striped fs-12">
                                <tr>
                                    <td width="35%">Hari/Tanggal</td>
                                    <td width="5%">:</td>
                                    <td class="hariTanggal reset"></td>
                                </tr>
                                <tr>
                                    <td width="35%">Waktu Mulai</td>
                                    <td width="5%">:</td>
                                    <td class="waktuMulai reset"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <table class="table table-striped fs-12">
                                <tr>
                                    <td width="35%">Mapel</td>
                                    <td width="5%">:</td>
                                    <td class="mapel reset"></td>
                                </tr>
                                <tr>
                                    <td width="35%">Kelas</td>
                                    <td width="5%">:</td>
                                    <td class="kelas reset"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row m-0 p-0">
                        <p class="fs-14"><b>B. Data Agenda</b></p>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-7 col-xl-7">
                            <table class="table table-striped fs-12">
                                <tr>
                                    <td width="35%">Pokok Pembahasan</td>
                                    <td width="5%">:</td>
                                    <td class="pembahasan reset"></td>
                                </tr>
                                <tr>
                                    <td width="35%">Kegiatan</td>
                                    <td width="5%">:</td>
                                    <td class="kegiatan reset"></td>
                                </tr>
                                <tr>
                                    <td width="35%">Kendala</td>
                                    <td width="5%">:</td>
                                    <td class="kendala reset"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                            <table class="table table-striped fs-12">
                                <tr>
                                    <td width="35%">Metode</td>
                                    <td width="5%">:</td>
                                    <td class="metode reset"></td>
                                </tr>
                                <tr>
                                    <td width="35%">Proses</td>
                                    <td width="5%">:</td>
                                    <td class="proses reset"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-3">
                        <p class="fs-14"><b>C. Data Absensi</b></p>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                            <div class="table-responsive">
                                <table class="table table-bordered fs-12">
                                    <thead>
                                        <tr>
                                            <td style="min-width: 70px" width="10%">No</td>
                                            <td style="min-width: 120px" width="35%">Nama</td>
                                            <td style="min-width: 70px" width="10%">Absensi</td>
                                            <td style="min-width: 150px" width="45%">Keterangan</td>
                                        </tr>
                                    </thead>
                                    <tbody class="absensi reset"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-3">
                        <p class="fs-14"><b>D. Data Penilaian Profil Pancasila</b></p>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="table-responsive">
                                <table class="table table-bordered fs-12">
                                    <thead>
                                        <tr>
                                            <td style="min-width: 70px" width="10%">No</td>
                                            <td style="min-width: 120px" width="25%">Nama</td>
                                            <td style="min-width: 150px" width="30%">Dimensi</td>
                                            <td style="min-width: 150px" width="35%">Keterangan</td>
                                        </tr>
                                    </thead>
                                    <tbody class="pancasila reset">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('js/pancasila.js')}}"></script>
    <script>
        $('.view-agenda').click(function() {
            $('.reset').html('');
            loading();
            var uuid = $(this).data('uuid');
            var url = "{{route('agenda.show',':id')}}";
            url = url.replace(':id',uuid);
            $.ajax({
                type: "GET",
                url: url,
                success: function(data) {
                    var jadwal = data.jadwal;
                    var agenda = data.agenda;

                    var hari = jadwal.hari.nama_hari[0].toUpperCase() + jadwal.hari.nama_hari.substring(1).toLowerCase();
                    $('.hariTanggal').text(hari+" / "+moment(agenda.tanggal).format('DD MMMM YYYY'));
                    $('.waktuMulai').text(jadwal.waktu.waktu_mulai);
                    $('.mapel').text(jadwal.pelajaran.pelajaran);
                    $('.kelas').text(jadwal.kelas.tingkat+jadwal.kelas.kelas);
                    $('.pembahasan').text(agenda.pembahasan);
                    $('.kegiatan').text(agenda.kegiatan);
                    $('.kendala').text(agenda.kendala);
                    $('.metode').text(agenda.metode);
                    $('.proses').text(agenda.proses);

                    if(agenda.absensi) {
                        var no = 1;
                        agenda.absensi.forEach(element => {
                            $('.absensi').append(`
                                <tr>
                                    <td>${no}</td>
                                    <td>${element.siswa.nama}</td>
                                    <td>${element.absensi}</td>
                                    <td>${element.keterangan}</td>
                                </tr>
                            `);
                            no++;
                        });
                    }
                    if(agenda.pancasila) {
                        var no = 1;
                        agenda.pancasila.forEach(element => {
                            $('.pancasila').append(`
                                <tr>
                                    <td>${no}</td>
                                    <td>${element.siswa.nama}</td>
                                    <td>${dimensi[element.dimensi]}</td>
                                    <td>${element.keterangan}</td>
                                </tr>
                            `);
                            no++;
                        });
                    }

                    removeLoading();
                    $('#agenda-view').modal("show");
                }
            })
        })
    </script>
@endsection
