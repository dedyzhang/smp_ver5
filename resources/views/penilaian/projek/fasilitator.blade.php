@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Fasilitator Proyek</h5>
        <p>Halaman ini diperuntukkan admin dan kurikulum dalam pengaturan fasilitator proyek</p>
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
    <div class="body-contain-customize col-12 mt-3">
        <p><b>Pengaturan Fasilitator</b></p>
        <div class="row m-0 p-0 mt-4">
            <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                <label for="fasilitator">Fasilitator</label>
                <select name="fasilitator" id="fasilitator" class="form-select fasilitator-validate" data-toggle="select">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($guru as $elem)
                        <option value="{{$elem->uuid}}">{{$elem->nama}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row m-0 p-0 mt-3">
            <p>Untuk Kelas :</p>
            @foreach ($kelas as $kelas)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 gy-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="{{$kelas->uuid}}" id="kelas.{{$kelas->uuid}}" name="kelas" />
                        <label class="form-check-label" for="kelas.{{$kelas->uuid}}">Kelas {{$kelas->tingkat.$kelas->kelas}} </label>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row m-0 p-0 mt-3">
            <div class="col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex">
                <button class="btn btn-sm btn-warning text-warning-emphasis simpan-fasilitator">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>List Fasilitator</b></p>
        <table class="table table-bordered" id="tableFasilitator">
            <thead>
                <tr>
                    <td>No</td>
                    <td>Fasilitator</td>
                    <td>Kelas</td>
                    <td>#</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($fasilitator as $fs)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$fs->guru->nama}}</td>
                        <td>{{$fs->kelas->tingkat.$fs->kelas->kelas}}</td>
                        <td>
                            <button class="btn btn-sm btn-danger hapus-fasilitator" data-uuid="{{$fs->uuid}}">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        $('.simpan-fasilitator').click(function() {
            var fasilitator = $('#fasilitator').val();
            var kelas = $('input[name="kelas"]:checked').val();
            if(fasilitator == "" || kelas == undefined) {
                oAlert("red","Perhatian","Fasilitor dan Kelas tidak boleh kosong");
            } else {
                loading();
                var url = "{{route('penilaian.p5.fasilitator.store',':id')}}";
                url = url.replace(':id','{{$proyek->uuid}}');
                $.ajax({
                    url: url,
                    type: "POST",
                    headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                    data: {
                        fasilitator: fasilitator,
                        kelas: kelas
                    },
                    success: function(data) {
                        console.log(data);
                        if(data.success == true) {
                            removeLoading();
                            cAlert("green","Berhasil","Fasilitator berhasil ditambahkan",true);
                        } else {
                            removeLoading();
                            oAlert("red","Perhatian",data.message);
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON);
                    }
                });
            }
        });
        $('.hapus-fasilitator').click(function() {
            var uuid = $(this).data('uuid');
            var hapusFasilitator = function() {
                loading();
                var url = "{{route('penilaian.p5.fasilitator.delete',':id')}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    url: url,
                    type: "DELETE",
                    headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                    success: function(data) {
                        if(data.success == true) {
                            removeLoading();
                            cAlert("green","Berhasil","Fasilitator berhasil dihapus",true);
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON);
                    }
                });
            }
            cConfirm("Perhatian","Apakah anda yakin untuk menghapus fasilitator ini,<p class='fs-11'><b>Pastikan tidak ada nilai dan deskripsi proyek yang sudah diinput</b></p>",hapusFasilitator);
        });
    </script>
@endsection
