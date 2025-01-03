@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('siswa')}}
    <div class="body-contain-customize col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex gap-2">
            <a href="{{route('siswa.create')}}" class="btn btn-sm btn-warning fs-14">
                <i class="fa-solid fa-plus"></i>
                Tambah Siswa
            </a>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-primary dropdown-toggle fs-13" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-upload"></i>
                Import Siswa
                </button>
                <ul class="dropdown-menu">
                <li><a class="dropdown-item fs-12" href="{{asset('files/Format Import Siswa.xlsx')}}">Download Format</a></li>
                <li><a class="dropdown-item fs-12" data-bs-toggle="modal" data-bs-target="#import-siswa" href="#">Import Siswa</a></li>
                </ul>
            </div>

        </div>
    <div class="body-contain-customize col-md-12 col-lg-12 col-sm-12 mt-3">
        <h5><b>Data Siswa</b></h5>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert">
                <i class="bi flex-shrink-0 me-2 fa-solid fa-check" aria-label="Success:"></i>
                <div>
                    <strong>Sukses !</strong> {{session('success')}}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session('error')) @endif
    </div>
    <div class="body-contain-customize col-md-12 col-lg-12 col-xl-12 col-sm-12 col-12 mt-3">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 ms-auto">
                <label for="kelas">Cari Berdasarkan Kelas : </label>
                <select name="kelas" id="kelas" class="form-control">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($kelas as $kelas)
                        <option value="{{$kelas->tingkat.$kelas->kelas}}">{{$kelas->tingkat.$kelas->kelas}}</option>
                    @endforeach
                    <option value="--">Tidak Ada Kelas</option>
                </select>
            </div>
        </div>
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
                @foreach ($siswa as $siswa)
                    <tr data-id="{{$siswa->uuid}}">
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center">{{$siswa->nis}}</td>
                        <td style="text-align:left !important;">{{$siswa->nama}}</td>
                        <td class="text-center">{{Str::upper($siswa->jk)}}</td>
                        <td class="text-center">{{$siswa->kelas !== NULL ? $siswa->kelas->tingkat.$siswa->kelas->kelas : "--"}}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-success View" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Lihat Data"><i class="fa-solid fa-eye"></i></button>
                            <a href="{{route('siswa.edit',$siswa->uuid)}}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Data"><i class="fa-solid fa-pencil"></i></a>
                            <button class="btn btn-sm btn-info reset-siswa" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Reset Siswa"><i class="fa-solid fa-recycle"></i></button>
                            <button class="btn btn-sm btn-primary reset-ortu" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Reset OrangTua"><i class="fa-solid fa-person-cane"></i></button>
                            <button class="btn btn-sm btn-danger hapus-siswa" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Hapus Siswa"><i class="fa-solid fa-trash-can"></i></button>
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
    </div>
    <div class="modal fade in p-0" id="import-siswa">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6><b>Import Siswa</b></h6>
                    <button class="btn btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="fs-12">Pastikan mengupload format excel yang sudah disediakan didalam aplikasi. Download format excel sebelum mengimport data siswa.</p>
                    <div class="row m-0 p-0 mt-2">
                        <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label class="mb-2" for="importFile">Import Siswa</label>
                            <input type="file" class="form-control" name="importFile" id="importFile">
                        </div>
                        <div class="button-place mt-3">
                            <button type="button" class="btn btn-sm btn-warning text-warning-emphasis import-siswa-button">
                                <i class="fa-solid fa-upload"></i> Upload
                            </button>
                        </div>
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
            var url = "{{route('siswa.show',':id')}}";
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
            var url = "{{route('siswa.reset',':id')}}";
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
            var url = "{{route('siswa.resetOrtu',':id')}}";
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
         $('#datatable-siswa').on('click','.hapus-siswa',function() {
            var init = this;
            var HapusSiswa = function() {
                loading();
                var id = $(init).closest('tr').data('id');
                var url = "{{route('siswa.destroy', ':id')}}";
                url = url.replace(':id', id);
                var token = '{{csrf_token()}}';
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        "_token": token
                    },
                    success:function(data){
                        setTimeout(() => {
                            removeLoading();
                            cAlert("green","Sukses","Sukses Menghapus Data Siswa",true);
                        }, 500);
                        // console.log(data);
                    },
                    error: function(data){
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });
            }
            cConfirm("WARNING","Apakah Anda Yakin untuk Menghapus Siswa ini",HapusSiswa);
        });
        $('#kelas').change(function(){
            var val = $(this).val();
            $('input[type="search"]').val(val).keyup();
        });
        $('.import-siswa-button').click(function() {
            var uploadExcel = () => {
                var formData = new FormData();
                var files = formData.append('file',document.getElementById('importFile').files[0]);
                BigLoading('Aplikasi sedang mengimport Data Yang Sudah DiUpload, Mohon Untuk Menunggu Sampai Proses Selesai');
                $.ajax({
                    type: "POST",
                    url: "{{route('siswa.import')}}",
                    data: formData,
                    headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                    enctype: 'multipart/form-data',
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if(data.success == false) {
                            removeLoadingBig();
                            oAlert("red","Error",data.message);
                        } else {
                            removeLoadingBig();
                            cAlert("green","Sukses",data.message,true);
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }
            cConfirm("Perhatian","Apakah anda yakin untuk mengimport data siswa dengan format excel yang diupload ?",uploadExcel);
        });
    </script>
@endsection
