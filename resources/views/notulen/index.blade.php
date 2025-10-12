@extends('layouts.main')

@section('container')
    <div class="col-12 body-contain-customize">
        <h5>Notulen Rapat</h5>
        <p>Halaman ini diperuntukkan Guru yang ditunjuk, Admin, Kurikulum maupun kepala sekolah untuk membuat, melihat, mengupdate dan mencetak Notulen Hasil Rapat</p>
    </div>
    <div class="col-12 body-contain-customize d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex mt-3">
        <a href="{{route('notulen.create')}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-plus"></i> Tambah Notulen Rapat</a>
    </div>
    <div class="col-12 body-contain-customize mt-3">
        <table id="datatable-notulen" class="dataTables table table-striped table-bordered text-center" style="width:100%">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="85%">Tanggal</th>
                    <th width="10%">#</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notulen as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{date('d M Y',strtotime($item->tanggal_rapat))}}</td>
                        <td>
                            <button class="btn btn-sm btn-success lihat-notulen" data-bs-toggle="tooltip" data-bs-title="Lihat Notulen" data-bs-placement="top" data-id="{{$item->uuid}}"><i class="fas fa-eye"></i></button>
                            <a href="{{ route('notulen.edit',$item->uuid) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-title="Edit Notulen" data-bs-placement="top" ><i class="fas fa-pencil"></i></a>
                            <a href="{{ route('notulen.absensi',$item->uuid) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-title="Absen Guru" data-bs-placement="top" ><i class="fas fa-user"></i></a>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-title="Hapus Notulen Rapat" data-bs-placement="top"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade in p-0" id="agenda-notulen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-lg-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h6><b>View Notulen Rapat</b></h6>
                    <button class="btn btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-0 p-0">
                        <p class="fs-14"><b>A. Data Tanggal</b></p>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <table class="table table-striped fs-12">
                                <tr>
                                    <td width="35%">Hari</td>
                                    <td width="5%">:</td>
                                    <td class="hari reset"></td>
                                </tr>
                                <tr>
                                    <td width="35%">Tanggal</td>
                                    <td width="5%">:</td>
                                    <td class="tanggal reset"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row m-0 p-0">
                        <p class="fs-14"><b>B. Data Notulen</b></p>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-12 col-xl-12">
                            <table class="table table-striped fs-12">
                                <tr>
                                    <td width="35%"><b>Pokok Pembahasan</b></td>
                                    <td width="5%">:</td>
                                </tr>
                                <tr>
                                    <td class="permasalahan reset"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-12 col-xl-12">
                            <table class="table table-striped fs-12">
                                <tr>
                                    <td width="35%"><b>Hasil Rapat</b></td>
                                    <td width="5%">:</td>
                                </tr>
                                <tr>
                                    <td class="hasil reset"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-3">
                        <p class="fs-14"><b>C. Data Absensi</b></p>
                        <div class="row m-0 p-p mt-1 absensi reset"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var table = new DataTable("#datatable-notulen", {
            // scrollX : true,
            initComplete: function (settings, json) {
                $("#datatable-notulen").wrap(
                    "<div style='overflow:auto; width:100%;position:relative;'></div>"
                );
            },
            columnDefs: [
                {
                    className: "text-center",
                    targets: [0, 2],
                },
                {
                    className: "text-start",
                    targets: [1],
                },
            ],
        });
        $('#datatable-notulen').on('click','.lihat-notulen',function() {
            var id = $(this).data('id');
            loading();
            var url = "{{route('notulen.show',':id')}}";
            url = url.replace(':id',id);
            $.ajax({
                type: "GET",
                url : url,
                success: function(data) {
                    $('.reset').html('');
                    $('.hari').html(moment(data.data.tanggal_rapat).locale('id').format('dddd'));
                    $('.tanggal').html(moment(data.data.tanggal_rapat).locale('id').format('DD/MM/YYYY'));
                    $('.permasalahan').html(data.data.pokok_permasalahan);
                    $('.hasil').html(data.data.hasil_rapat);
                    var absensiGuru = '';
                    if(data.guru.length > 0) {
                        var guru = data.guru;
                        guru.forEach(function(item) {
                            absensiGuru += '<div class="col-12 col-sm-12 col-lg-6 col-xl-6"><p class="m-0 p-0 fs-12 p-2">'+item.nama+'</p></div>';
                        });
                        $('.absensi').html(absensiGuru);
                    }
                    $('#agenda-notulen').modal('show');
                    removeLoading();
                }
            })
        })
    </script>
@endsection
