@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('walikelas-siswa')}}
    <div class="body-contain-customize col-md-12 col-lg-12 col-sm-12 mt-3">
        <h5><b>Data Siswa</b></h5>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-flex d-md-flex d-lg-flex d-xl-flex">
        <button class="btn btn-sm btn-warning text-warning-emphasis tambah-sekretaris">
            <i class="fas fa-user"></i> Set Sekretaris
        </button>
    </div>
    <div class="body-contain-customize col-md-12 col-lg-12 col-xl-12 col-sm-12 col-12 mt-3">
        <table id="datatable-siswa" class="dataTables table table-striped table-bordered text-center" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nis</th>
                    <th>Nama</th>
                    <th>Jk</th>
                    <th>Kelas</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siswa as $item)
                    <tr data-id="{{$item->uuid}}">
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center">{{$item->nis}}</td>
                        <td style="text-align:left !important;">{{$item->nama}}</td>
                        <td class="text-center">{{Str::upper($item->jk)}}</td>
                        <td class="text-center">{{$item->kelas !== NULL ? $item->kelas->tingkat.$item->kelas->kelas : "--"}}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-success View" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Lihat Data"><i class="fa-solid fa-eye"></i></button>
                            <button class="btn btn-sm btn-info reset-siswa" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Reset Siswa"><i class="fa-solid fa-recycle"></i></button>
                            <button class="btn btn-sm btn-primary reset-ortu" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Reset OrangTua"><i class="fa-solid fa-person-cane"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- Modal For Preview Siswa --}}
        <div class="modal fade in" id="modal-preview-siswa">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"><b>Data Siswa <span class="nama"></span></b></h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="container p-0">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 d-flex justify-content-center p-picture">

                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <p><b>A. Identitas Pribadi</b></p>
                                    <table class="table">
                                        <tr>
                                            <td width="40%">Nama</td>
                                            <td width="5%">:</td>
                                            <td width="55%" class="nama"></td>
                                        </tr>
                                        <tr>
                                            <td>NIS</td>
                                            <td>:</td>
                                            <td class="nis"></td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Kelamin</td>
                                            <td>:</td>
                                            <td class="jk"></td>
                                        </tr>
                                        <tr>
                                            <td>Tempat/Tanggal Lahir</td>
                                            <td>:</td>
                                            <td class="ttl"></td>
                                        </tr>
                                        <tr>
                                            <td>Agama</td>
                                            <td>:</td>
                                            <td class="agama"></td>
                                        </tr>
                                        <tr>
                                            <td>No Telepon</td>
                                            <td>:</td>
                                            <td class="no_telp"></td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td colspan="2">:</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="alamat"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <p><b>B. Identitas Sekolah</b></p>
                                    <table class="table">
                                        <tr>
                                            <td width="40%">NISN</td>
                                            <td width="5%">:</td>
                                            <td width="55%" class="nisn"></td>
                                        </tr>
                                        <tr>
                                            <td>Sekolah Asal</td>
                                            <td colspan="2">:</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="sekolah_asal"></td>
                                        </tr>
                                        <tr>
                                            <td>Nama Ijazah</td>
                                            <td>:</td>
                                            <td class="nama_ijazah"></td>
                                        </tr>
                                        <tr>
                                            <td>Ortu Ijazah</td>
                                            <td>:</td>
                                            <td class="ortu_ijazah"></td>
                                        </tr>
                                        <tr>
                                            <td>TTL Ijazah</td>
                                            <td>:</td>
                                            <td class="ttl_ijazah"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                    <p><b>C. Identitas Ayah</b></p>
                                    <table class="table">
                                        <tr>
                                            <td width="40%">nama Ayah</td>
                                            <td width="5%">:</td>
                                            <td width="55%" class="nama_ayah"></td>
                                        </tr>
                                        <tr>
                                            <td>Pekerjaan Ayah</td>
                                            <td>:</td>
                                            <td class="pekerjaan_ayah"></td>
                                        </tr>
                                        <tr>
                                            <td>No Telepon Ayah</td>
                                            <td>:</td>
                                            <td class="telepon_ayah"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                    <p><b>D. Identitas Ibu</b></p>
                                    <table class="table">
                                        <tr>
                                            <td width="40%">nama Ibu</td>
                                            <td width="5%">:</td>
                                            <td width="55%" class="nama_ibu"></td>
                                        </tr>
                                        <tr>
                                            <td>Pekerjaan Ibu</td>
                                            <td>:</td>
                                            <td class="pekerjaan_ibu"></td>
                                        </tr>
                                        <tr>
                                            <td>No Telepon Ibu</td>
                                            <td>:</td>
                                            <td class="telepon_ibu"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                    <p><b>E. Identitas Wali</b></p>
                                    <table class="table">
                                        <tr>
                                            <td width="40%">nama Wali</td>
                                            <td width="5%">:</td>
                                            <td width="55%" class="nama_wali"></td>
                                        </tr>
                                        <tr>
                                            <td>Pekerjaan Wali</td>
                                            <td>:</td>
                                            <td class="pekerjaan_wali"></td>
                                        </tr>
                                        <tr>
                                            <td>No Telepon Wali</td>
                                            <td>:</td>
                                            <td class="telepon_wali"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal For Tambah Sekretaris --}}
        <div class="modal fade in" id="modal-tambah-sekretaris">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"><b>Data Sekretaris</b></h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="row m-0 p-0">
                            <div class="form-group col-12">
                                <label for="sekretaris1">Masukkan Nama Sekretaris 1</label>
                                <select data-toggle="select" class="form-control" name="sekretaris1" id="sekretaris1">
                                    <option value="">Pilih Salah Satu</option>
                                    @foreach ($siswa as $item)
                                        <option {{$sekretaris !== null && $sekretaris->sekretaris1 == $item->uuid ? "selected" : ""}} value="{{$item->uuid}}">{{$item->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12">
                                <label for="sekretaris2">Masukkan Nama Sekretaris 2</label>
                                <select data-toggle="select" class="form-control" name="sekretaris2" id="sekretaris2">
                                    <option value="">Pilih Salah Satu</option>
                                    @foreach ($siswa as $item)
                                        <option {{$sekretaris !== null && $sekretaris->sekretaris2 == $item->uuid ? "selected" : ""}} value="{{$item->uuid}}">{{$item->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-warning text-warning-emphasis simpan-sekretaris">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var table = new DataTable('#datatable-siswa',{
            // scrollX : true,
            columns: [{ width: '10%' },{ width: '20%' },{ width: '30%' },{ width: '10%' },{ width: '10%' }, { width: '20%' }],
            "initComplete": function (settings, json) {
                $("#datatable-siswa").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
        });
        $('#datatable-siswa').on('click','.View',function() {
            var id = $(this).closest('tr').data('id');
            loading();
            var url = "{{route('walikelas.siswa.show',':id')}}";
            url = url.replace(':id',id);

            $.ajax({
                type: "GET",
                url: url,
                success:function(data) {
                    removeLoading();
                    var siswa = data.siswa;

                    $('.nama').html(siswa.nama);
                    $('.nis').html(siswa.nis);
                    $('.jk').html(siswa.jk == 'l' ? 'Laki-laki' : 'Perempuan');
                    if(siswa.jk == 'l') {
                        $('.p-picture').html('<img src="{{asset("img/student-boy.png")}}" alt="" width="100">');
                    } else {
                        $('.p-picture').html('<img src="{{asset("img/student-girl.png")}}" alt="" width="100">');
                    }
                    var ttl = siswa.tempat_lahir != "" && siswa.tanggal_lahir != "" && siswa.tempat_lahir+" / "+moment(siswa.tanggal_lahir).format('LL');
                    $('.ttl').html(ttl);
                    $('.agama').html(siswa.agama);
                    $('.no_telp').html(siswa.no_handphone);
                    $('.alamat').html(siswa.alamat);
                    $('.nisn').html(siswa.nisn);
                    $('.sekolah_asal').html(siswa.sekolah_asal);
                    $('.nama_ijazah').html(siswa.nama_ijazah);
                    $('.ortu_ijazah').html(siswa.ortu_ijazah);
                    var ttlIjazah = siswa.tempat_lahir_ijazah != "" && siswa.tanggal_lahir_ijazah != "" && siswa.tempat_lahir_ijazah+" / "+moment(siswa.tanggal_lahir_ijazah).format('LL');
                    $('.ttl_ijazah').html(ttlIjazah);
                    $('.nama_ayah').html(siswa.nama_ayah);
                    $('.pekerjaan_ayah').html(siswa.pekerjaan_ayah);
                    $('.telepon_ayah').html(siswa.no_telp_ayah);
                    $('.nama_ibu').html(siswa.nama_ibu);
                    $('.pekerjaan_ibu').html(siswa.pekerjaan_ibu);
                    $('.telepon_ibu').html(siswa.no_telp_ibu);
                    $('.nama_wali').html(siswa.nama_wali);
                    $('.pekerjaan_wali').html(siswa.pekerjaan_wali);
                    $('.telepon_wali').html(siswa.no_telp_wali);
                    $('#modal-preview-siswa').modal('show');
                }
            })
        });
        $('#datatable-siswa').on('click','.reset-siswa',function() {
            var id = $(this).closest('tr').data('id');
            var url = "{{route('walikelas.siswa.reset',':id')}}";
            url = url.replace(':id',id);
            var token = '{{csrf_token()}}';
            var resetPasswordSiswa = () => {
                loading();
                $.ajax({
                    type: "POST",
                    url: url,
                    data : {
                        "_token": token
                    },
                    success: function(data) {
                        setTimeout(() => {
                            removeLoading();
                            var html = "<p>Berhasil mereset data siswa dengan Token</p><h1 class='text-center fs-30'><b>"+data.password+"</b></h1>";
                            cAlert("green","Sukses",html,true);
                        }, 500);
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                })
            }
            cConfirm("Perhatian","Reset Password Siswa Bersangkutan ?",resetPasswordSiswa);
        });
        $('#datatable-siswa').on('click','.reset-ortu',function() {
            var id = $(this).closest('tr').data('id');
            var url = "{{route('walikelas.siswa.resetOrtu',':id')}}";
            url = url.replace(':id',id);
            var token = '{{csrf_token()}}';
            var resetPasswordOrtu = () => {
                loading();
                $.ajax({
                    type: "POST",
                    url: url,
                    data : {
                        "_token": token
                    },
                    success: function(data) {
                        setTimeout(() => {
                            removeLoading();
                            var html = "<p>Berhasil mereset data Orang Tua dengan Token</p><h1 class='text-center fs-30'><b>"+data.password+"</b></h1>";
                            cAlert("green","Sukses",html,true);
                        }, 500);
                        // console.log(data);
                        // removeLoading();
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                })
            }
            cConfirm("Perhatian","Reset Password Siswa Bersangkutan ?",resetPasswordOrtu);
        });
        $('.tambah-sekretaris').click(function() {
            $('#modal-tambah-sekretaris').modal("show");
        });
        $('.simpan-sekretaris').click(function() {
            var sekretaris1 = $('#sekretaris1').val();
            var sekretaris2 = $('#sekretaris2').val();

            if(sekretaris1 == "" || sekretaris2 == "") {
                oAlert("orange","Perhatian","Sekretaris 1 dan 2 tidak boleh kosong");
            } else {
                if(sekretaris1 == sekretaris2) {
                    oAlert("orange","Perhatian","Sekretaris 1 dan 2 tidak boleh sama");
                } else {
                    loading();
                    var url = "{{route('walikelas.siswa.sekretaris')}}";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data : {
                            sekretaris1 : sekretaris1,
                            sekretaris2 : sekretaris2,
                        },
                        headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                        success: function(data) {
                            cAlert("green","Sukses","Sekretaris berhasil ditetapkan",true);
                            removeLoading();
                        },
                        error: function(data) {
                            console.log(data.responseJSON.message);
                        }
                    })
                }
            }
        });
    </script>
@endsection
