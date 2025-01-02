@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Pengaturan Dimensi</h5>
        <p>Halaman untuk mengatur dimensi, elemen, subelemen dan pencapaian yang digunakan dalam proyek ini</p>
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5 mt-3">
        <p><b>Data Proyek</b></p>
        <table class="table table-striped fs-13">
            <tr>
                <td width="30%">Judul Proyek</td>
                <td width="5%">:</td>
                <td>{{$proyek->judul}}</td>
            </tr>
            <tr>
                <td>Untuk Tingkat</td>
                <td>:</td>
                <td>{{$proyek->tingkat}}</td>
            </tr>
            <tr>
                <td>Deskripsi</td>
                <td>:</td>
                <td class="fs-11">{{$proyek->deskripsi}}</td>
            </tr>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="body-contain-customize mt-3 col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex">
        <button class="btn btn-sm btn-warning text-warning-emphasis" data-bs-toggle="modal" data-bs-target="#modal-tambah">
            <i class="fas fa-plus"></i> Tambah Dimensi
        </button>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <td width="5%">No</td>
                    <td width="15%">Dimensi</td>
                    <td width="15%">Elemen</td>
                    <td width="25%" style="min-width:150px">Sub Elemen</td>
                    <td width="35%" style="min-width:200px">Pencapaian</td>
                    <td width="5%" style="min-width:70px">#</td>
                </tr>
                </thead>
                <tbody>
                    @foreach ($proyekDetail as $detail)
                        <tr class="fs-12">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$detail->dimensi->dimensi}}</td>
                            <td>{{$detail->elemen->elemen}}</td>
                            <td>{{$detail->subelemen->subelemen}}</td>
                            <td>{{$detail->subelemen->capaian}}</td>
                            <td>
                                <button class="btn btn-sm btn-danger hapus-detail" data-detail="{{$detail->uuid}}">
                                    <i class="fas fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade in" id="modal-tambah">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Tambah Dimensi di Proyek</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-0 p-0">
                        <div class="col-12 m-0 p-0 form-group">
                            <label for="dimensi">Dimensi</label>
                            <select class="form-select validate-dimensi" id="dimensi">
                                <option value="">Pilih Salah Satu</option>
                                @foreach ($dimensi as $dim)
                                    <option value="{{$dim->uuid}}">{{$dim->dimensi}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Wajib Diisi</div>
                        </div>
                        <div class="col-12 m-0 p-0 form-group">
                            <label for="elemen">Elemen</label>
                            <select class="form-select validate-dimensi" id="elemen">
                                <option value="">Pilih Salah Satu</option>
                            </select>
                            <div class="invalid-feedback">Wajib Diisi</div>
                        </div>
                        <div class="col-12 m-0 p-0 form-group">
                            <label for="subelemen">Sub Elemen</label>
                            <select class="form-select validate-dimensi" id="subelemen">
                                <option value="">Pilih Salah Satu</option>
                            </select>
                            <div class="invalid-feedback">Wajib Diisi</div>
                        </div>
                        <div class="col-12 m-0 p-0 mt-3">
                            <div class="card bg-primary-subtle rounded-2 border-0 shadow-sm">
                                <div class="card-body">
                                    <p><b>Pencapaian</b></p>
                                    <p class="capaian fs-11"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning text-warning-emphasis simpan-dimensi">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#dimensi').change(function() {
            var dimensi = $(this).val();
            var url = "{{route('penilaian.p5.elemen.get',':id')}}";
            url = url.replace(':id',dimensi);
            loading();
            $.ajax({
                type: "GET",
                url: url,
                success: function(data) {
                    removeLoading();
                    $('#elemen').html('<option value="">Pilih Salah Satu</option>');
                    data.elemen.forEach(function(item) {
                        $('#elemen').append('<option value="'+item.uuid+'">'+item.elemen+'</option>');
                    });
                }
            })
        });
        $('#elemen').change(function() {
            var elemen = $(this).val();
            var url = "{{route('penilaian.p5.subelemen.get',':id')}}";
            url = url.replace(':id',elemen);
            loading();
            $.ajax({
                type: "GET",
                url: url,
                success: function(data) {
                    removeLoading();
                    $('#subelemen').html('<option value="">Pilih Salah Satu</option>');
                    data.subelemen.forEach(function(item) {
                        $('#subelemen').append('<option data-capaian="'+item.capaian+'" value="'+item.uuid+'">'+item.subelemen+'</option>');
                    });
                }
            });
        });
        $('#subelemen').change(function() {
            var capaian = $(this).find(':selected').data('capaian');
            if(capaian == null) {
                capaian = '';
            }
            $('.capaian').html(capaian);
        });
        $('.simpan-dimensi').click(function() {
            var error = 0;

            $('.validate-dimensi').each(function() {
                if($(this).val() == '') {
                    $(this).addClass('is-invalid');
                    error++;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if(error == 0) {
                loading();
                var uuid = "{{$proyek->uuid}}";
                var url = "{{route('penilaian.p5.config.store',':id')}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "POST",
                    url: url,
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: {
                        dimensi: $('#dimensi').val(),
                        elemen: $('#elemen').val(),
                        subelemen: $('#subelemen').val()
                    },
                    success: function(data) {
                        if(data.success == true) {
                            removeLoading();
                            cAlert('green','Sukses','Berhasil Menambahkan Dimensi Proyek',true);
                        }
                    }
                })
            }
        });
        $('.hapus-detail').click(function() {
            var uuid = $(this).data('detail');
            var hapusDetail = function() {
                loading();
                var url = "{{route('penilaian.p5.config.delete',':id')}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "DELETE",
                    url: url,
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function(data) {
                        if(data.success == true) {
                            removeLoading();
                            cAlert('green','Sukses','Berhasil Menghapus Dimensi Proyek',true);
                        }
                    }
                });
            }
            cConfirm("Perhatian","Apakah kamu yakin untuk menghapus dimensi di proyek ini. <p class='fs-11'><b>Pastikan tidak ada nilai atau deskripsi yang sudah terinput</b></p>",hapusDetail);
        });
    </script>
@endsection
