@extends('layouts.main')

@section('container')
    @if (\Request::route()->getName() === 'penilaian.admin.materi.show')
        {{Breadcrumbs::render('penilaian-admin-materi-show',$ngajar->pelajaran,$ngajar->kelas,$ngajar)}}
    @else
        {{Breadcrumbs::render('penilaian-materi-show',$ngajar->pelajaran,$ngajar->kelas,$ngajar)}}
    @endif
    <div class="body-contain-customize col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5">
        <p><b>Data Ngajar</b></p>
        <table class="table table-striped fs-13">
            <tr>
                <td width="30%">Pelajaran</td>
                <td width="5%">:</td>
                <td>{{$ngajar->pelajaran->pelajaran}}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{$ngajar->kelas->tingkat.$ngajar->kelas->kelas}}</td>
            </tr>
            <tr>
                <td>Guru</td>
                <td>:</td>
                <td>{{$ngajar->guru->nama}}</td>
            </tr>
            <tr>
                <td>KKTP</td>
                <td>:</td>
                <td>{{$ngajar->kkm}}</td>
            </tr>
        </table>
        @if ($ngajar->kkm == 0)
                <div
                    class="alert alert-warning" role="alert">
                    <strong><i class="fas fa-triangle-exclamation"></i> Perhatian</strong> KKTP untuk data ngajar masih belum diatur. Guru dapat mengatur KKTP dihalaman Buku Guru > KKTP
                </div>
            @endif
    </div>
    <div class="row ms-0 ps-0 gy-3">
        <div class="body-contain-customize col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto">
            <button class="btn btn-sm btn-warning text-light" data-bs-toggle="modal" data-bs-target="#modal-tambah-materi"><i class="fas fa-plus"></i> Tambah Materi</button>
            <button class="btn btn-sm btn-primary text-light" data-bs-toggle="modal" data-bs-target="#modal-duplikat-materi"><i class="fas fa-copy"></i> Duplikat Materi</button>
        </div>
        <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <p><b>List Materi</b></p>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>Nama Materi</td>
                            <td colspan="2">Tujuan Pembelajaran</td>
                            <td>#</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materi as $mtri)
                            @foreach ($mtri->tupe()->get() as $tupe)
                                @if ($loop->iteration == 1)
                                    <tr data-materi="{{$mtri->uuid}}" data-tupe="{{$tupe->uuid}}" data-jumlahtupe="{{$mtri->tupe}}">
                                        <td width="25%" class="materi-view" style="min-width: 150px; cursor: pointer;" rowspan="{{$mtri->tupe}}">{{$mtri->materi}}</td>
                                        <td width="7%" class="text-center">F0{{$loop->iteration}}</td>
                                        <td width="58%" class="tupe-view" style="min-width: 200px;cursor: pointer;">{{$tupe->tupe}}</td>
                                        @if ($tupe->show == 0)
                                            <td width="10%"><button class="btn btn-sm btn-success tambahkan-nilai" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Masukkan ke Formatif"><i class="fa fa-sign-in-alt"></i></button></td>
                                        @else
                                            <td width="10%"><button class="btn btn-sm btn-danger hapus-nilai" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Hapus dari Formatif"><i class="fa fa-trash-can"></i></button></td>
                                        @endif

                                    </tr>
                                @else
                                    <tr data-materi="{{$mtri->uuid}}" data-tupe="{{$tupe->uuid}}">
                                        <td class="text-center">F0{{$loop->iteration}}</td>
                                        <td class="tupe-view" style="cursor: pointer">{{$tupe->tupe}}</td>
                                        @if ($tupe->show == 0)
                                            <td width="10%"><button class="btn btn-sm btn-success tambahkan-nilai" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Masukkan ke Formatif"><i class="fa fa-sign-in-alt"></i></button></td>
                                        @else
                                            <td width="10%"><button class="btn btn-sm btn-danger hapus-nilai" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Hapus dari Formatif"><i class="fa fa-trash-can"></i></button></td>
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade in" tabindex="-1" id="modal-tambah-materi">
        <div class="modal-dialog-centered modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Materi</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-materi" action="{{route('penilaian.materi.create',$ngajar->uuid)}}" method="POST">
                        <div class="row p-0 m-0">
                            <div class="col-12 p-0 form-group">
                                <label for="materi" class="fs-12">Nama Materi</label>
                                <input type="text" class="form-control" name="materi" id="materi" placeholder="Masukkan Nama Materi">
                            </div>
                            <div class="col-12 p-0 mt-3 form-group">
                                <label for="tupe" class="fs-12">Jumlah Tujuan Pembelajaran ( Maks. 4 )</label>
                                <input type="number" class="form-control" name="tupe" id="tupe" placeholder="Masukkan Jumlah Tujuan Pembelajaran">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success btn-sm tambah-materi"><i class="fas fa-plus"></i> Tambah Materi</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade in" tabindex="-1" id="modal-view-materi">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Data Materi</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-0 m-0">
                        <div class="col-12 p-0 form-group">
                            <label for="viewMateri" class="fs-12">Materi</label>
                            <input type="text" class="form-control" name="viewMateri" id="viewMateri" placeholder="Masukkan nama materi">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-warning text-warning-emphasis tambah-tupe"><i class="fas fa-add"></i> Tambah tupe</button>
                    <button class="btn btn-sm btn-danger delete-materi"><i class="fas fa-trash-can"></i> Delete</button>
                    <button class="btn btn-sm btn-success simpan-materi"><i class="fas fa-save"></i> Edit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade in" tabindex="-1" id="modal-view-tp">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Tujuan Pembelajaran</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-0 p-0">
                        <div class="col-12 p-0">
                            <label for="tp">Tujuan Pembelajaran</label>
                            <textarea name="tp" id="tp" cols="30" rows="5" class="form-control" maxlength="150" placeholder="Masukkan Tujuan Pembelajaran"></textarea>
                            <p class="float-right fs-10"><span class="counter">0</span>/150</p>
                            <div class="form-text">Tujuan Pembelajaran <b>tidak perlu</b> diawali dengan huruf kapital dan titik pada akhir kalimat</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-warning simpan-tupe text-warning-emphasis"><i class="fa-solid fa-save"></i> Simpan</button>
                    <button class="btn btn-sm btn-danger hapus-tupe"><i class="fa-solid fa-trash-can"></i> Hapus</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade in" tabindex="-1" id="modal-duplikat-materi">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Duplikat Materi</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-0 p-0">
                        <div class="form-group col-12 p-0">
                            <label for="duplikat-materi">Materi</label>
                            <select name="duplikat-materi" class="form-control" id="duplikat-materi">
                                <option value="">Pilih Salah Satu</option>
                                @foreach ($materi as $elemen)
                                    <option value="{{$elemen->uuid}}">{{$elemen->materi}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 p-0">
                            <label for="ke-kelas">Ke Kelas</label>
                            <select name="ke-kelas" class="form-control" id="ke-kelas">
                                <option value="">Pilih Salah Satu</option>
                                @foreach ($listNgajar as $elemen)
                                    <option value="{{$elemen->uuid}}">{{$elemen->pelajaran->pelajaran_singkat." (".$elemen->kelas->tingkat.$elemen->kelas->kelas.")"}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-warning duplikat-tupe text-warning-emphasis"><i class="fa-solid fa-save"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var idmateri = "";
        var jumlahTupe = "";
        var ini = "";
        var idTupe = "";
        $('.tambah-materi').click(function() {
            var materi = $('#materi').val();
            var tupe = $('#tupe').val();

            if(materi == "" || tupe == "") {
                oAlert("orange","Perhatian","Data materi tidak boleh kosong");
            } else {
                if(tupe >= "5") {
                    oAlert("orange","Perhatian","Tujuan pembelajaran tidak boleh lebih dari 4 dalam 1 materi")
                } else {
                    loading();
                    var form = $('#form-materi');
                    $.ajax({
                        type : form.attr('method'),
                        url: form.attr('action'),
                        headers: {
                            "X-CSRF-TOKEN" : "{{csrf_token()}}",
                        },
                        data: form.serialize(),
                        success: function(data) {
                            if(data.success === true) {
                                setTimeout(() => {
                                    removeLoading();
                                    cAlert('green','Sukses','Sukses menambahkan materi',true);
                                }, 500);
                            } else {
                                removeLoading();
                                oAlert('blue','info',data.message);
                            }
                            // removeLoading();
                            // console.log(data);
                        },
                        error: function(data) {
                            var errors = data.responseJSON;
                            console.log(errors.message);
                        }
                    })
                }
            }
        });
        $('.materi-view').click(function() {
            idmateri = $(this).closest('tr').data('materi');
            var materi = $(this).text();
            ini = this;
            $('#viewMateri').val(materi);
            $('#modal-view-materi').modal("show");

            $('.simpan-materi').click(function() {
                var newMateri = $('#viewMateri').val();
                var url = "{{route('penilaian.materi.edit',':id')}}";
                url = url.replace(':id',idmateri);
                if(newMateri == "") {
                    oAlert("orange","Warning","Nama materi tidak boleh kosong");
                } else {
                    loading();
                    $.ajax({
                        type: "put",
                        url: url,
                        data: {
                            materi : newMateri
                        },
                        headers: {
                            "X-CSRF-TOKEN": "{{csrf_token()}}"
                        },
                        success: function(data) {
                            setTimeout(() => {
                                removeLoading();
                                cAlert("green","Sukses","sukses mengedit materi",true);
                            },500)
                        },
                        error: function(data) {
                            var errors = error.responseJSON;
                            console.log(errors);
                        }
                    });
                }
            });
        });
        $('.tambah-tupe').click(function() {
            jumlahTupe = $(ini).closest('tr').data('jumlahtupe');
            if(jumlahTupe >= 4) {
                oAlert("orange","Perhatian","1 materi hanya boleh mempunyai 4 tujuan pembelajaran");
            } else {
                var tambahTupe = () => {
                    loading();
                    var url = "{{route('penilaian.materi.createTupe',':id')}}";
                    url = url.replace(':id',idmateri);
                    $.ajax({
                        type: "post",
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": "{{csrf_token()}}"
                        },
                        data : {
                            idNgajar : "{{$ngajar->uuid}}",
                            tupe: jumlahTupe,
                        },
                        success: function(data) {
                            setTimeout(() => {
                                removeLoading();
                                cAlert("green","Sukses","sukses menambahkan tujuan pembelajaran",true);
                            },500);
                        },error: function(data) {
                            console.log(data.responseJSON);
                        }
                    })
                }
                cConfirm("Perhatian","Yakin untuk menambahkan tujuan pembelajaran ?",tambahTupe);
            }
        });
        $('.tupe-view').click(function() {
            idTupe = $(this).closest('tr').data('tupe');
            var tupe = $(this).text();
            $('#tp').val(tupe);
            var panjangTulisan = tupe.length;
            $('.counter').text(panjangTulisan);
            $('#modal-view-tp').modal("show");
        });
        $('#tp').on('keyup',function(event){
            var inputEvent = this;
            $('.counter').text(this.value.replace(/{.*}/g,'').length);
            if($('.counter').text() >= 150) {
                $(inputEvent).val($(inputEvent).val().substr(0,150));
            }
        });
        $('.simpan-tupe').click(function() {
            var tupe = $('#tp').val();

            if(tupe == "") {
                oAlert("orange","Perhatian","Tujuan pembelajaran tidak boleh kosong");
            } else {
                loading();
                var url = "{{route('penilaian.materi.updateTupe',':id')}}";
                url = url.replace(':id',idTupe);
                $.ajax({
                    type: "post",
                    url: url,
                    headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                    data: {
                        tupe: tupe,
                    },
                    success: function(data) {
                        setTimeout(() => {
                            removeLoading();
                            cAlert('green',"Sukses","Tujuan pembelajaran berhasil diedit",true);
                        }, 500);
                    },
                    error : function(data) {
                        console.log(data.responseJSON);
                    }
                })
            }
        });
        $('.hapus-tupe').click(function() {
            var url = "{{route('penilaian.materi.deleteTupe',':id')}}";
            url = url.replace(':id',idTupe);

            var hapusTupe = () => {
                loading();
                $.ajax({
                    type: "delete",
                    url: url,
                    headers: {'X-CSRF-TOKEN' : "{{csrf_token()}}"},
                    success: function(data) {
                        setTimeout(() => {
                            removeLoading()
                            if(data.success === false) {
                                oAlert("blue","Informasi",data.message);
                            } else {
                                cAlert("green","Berhasil","Tujuan pembelajaran berhasil dihapus",true);
                            }
                        },500)
                    },
                    error: function(data) {
                        console.log(data.responseJSON);
                    }
                })
            }
            cConfirm("Perhatian","Yakin untuk menghapus tujuan pembelajaran",hapusTupe);
        });
        $('.delete-materi').click(function() {
            var hapusMateri = () => {
                loading();
                var url = "{{route('penilaian.materi.delete',':id')}}";
                url = url.replace(':id',idmateri);
                $.ajax({
                    type: "delete",
                    url: url,
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        setTimeout(() => {
                            removeLoading();
                            cAlert("green","Sukses","Berhasil menghapus materi",true);
                        }, 500);
                    },error: function(data) {
                        console.log(data.responseJSON);
                    }
                })
            }
            cConfirm("Perhatian","Yakin untuk menghapus seluruh materi ?",hapusMateri);
        });
        $('.tambahkan-nilai').click(function() {
            var idTupe = $(this).closest('tr').data('tupe');
            var idMateri = $(this).closest('tr').data('materi');
            var idNgajar = '{{$ngajar->uuid}}';

            var confirmTambahkanNilai = function() {
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{route('penilaian.materi.tambahFormatif')}}",
                    data: {idTupe: idTupe, idMateri: idMateri,idNgajar : idNgajar},
                    headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                    success: function(data) {
                        removeLoading();
                        console.log(data);
                        cAlert("green","Berhasil","Tujuan Pembelajaran berhasil ditambahkan",true);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
            cConfirm("Perhatian","Apakah anda yakin untuk menambahkan tujuan pembelajaran ini kedalam buku nilai formatif ?",confirmTambahkanNilai);
        });
        $('.hapus-nilai').click(function() {
            var idTupe = $(this).closest('tr').data('tupe');
            var idMateri = $(this).closest('tr').data('materi');
            var idNgajar = '{{$ngajar->uuid}}';

            var confirmHapusNilai = function() {
                loading();
                $.ajax({
                    type: "post",
                    url: "{{route('penilaian.materi.hapusFormatif')}}",
                    data: {idTupe: idTupe, idMateri: idMateri,idNgajar : idNgajar},
                    headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                    success: function(data) {
                        removeLoading();
                        cAlert("green","Berhasil","Tujuan Pembelajaran berhasil Dihapus",true);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
            cConfirm("Perhatian","Apakah anda yakin untuk menambahkan tujuan pembelajaran ini kedalam buku nilai formatif ?",confirmHapusNilai);
        });
        $('.duplikat-tupe').click(function() {
            var materi = $('#duplikat-materi').val();
            var ngajar = $('#ke-kelas').val();

            loading();
            $.ajax({
                type: "POST",
                url: "{{route('penilaian.materi.duplikat')}}",
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                data: {materi: materi, ngajar: ngajar},
                success: function(data) {
                    removeLoading();
                    console.log(data);
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        })
    </script>
@endsection

