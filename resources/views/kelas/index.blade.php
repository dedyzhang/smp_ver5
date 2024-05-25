@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('kelas')}}
    <div class="body-contain-customize d-sm-flex d-grid d-md-flex d-lg-flex d-xl-flex gap-2 col-auto col-md-auto col-sm-auto col-lg-auto col-xl-auto">
        <a href="{{route('kelas.create')}}" class="btn btn-sm btn-warning fs-14" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top">
            <i class="fa-solid fa-plus"></i>
            Tambah Kelas
        </a>
        <a href="{{route('kelas.setKelas')}}" class="btn btn-sm btn-primary fs-14 ms-md-2 ms-lg-2 ms-xl-2">
            <i class="fa-solid fa-home"></i>
            Atur Kelas Siswa
        </a>
    </div>
    <div class="body-contain-customize col-md-12 col-lg-12 col-sm-12 mt-3">
        <h5><b>Data Kelas</b></h5>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert">
                <i class="bi flex-shrink-0 me-2 fa-solid fa-check" aria-label="Success:"></i>
                <div>
                    <strong>Sukses !</strong> {{session('success')}}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session('error'))

        @endif
    </div>
    @foreach ($kelas as $kelas)
        <div class="col-md-6 col-lg-4 col-xl-3 col-sm-6 col-12 mt-3 pe-2 ps-0">
            <div class="card border-light rounded-4">
                <div class="card-body">

                    <p class="m-0 p-0 mt-2 fs-24"><b><i class="fa-solid fa-school"></i> {{$kelas->tingkat.$kelas->kelas}}</b></p>
                    <p class="m-0 p-0 mt-1 fs-12">Jumlah Anggota - Siswa</p>
                    <p class="m-0 p-0 mt-3 fs-14">Walikelas : {{empty($kelas->walikelas[0]->nama) ? "-" : Str::limit($kelas->walikelas[0]->nama,15)}}</p>
                    <div class="button-place mt-3">
                        <button data-id="{{$kelas->uuid}}" class="btn btn-sm btn-info buka-walikelas"><i class="fa-solid fa-person-chalkboard"></i></button>
                        <a href="{{route('kelas.edit',$kelas->uuid)}}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pencil"></i></a>
                        <a data-id="{{$kelas->uuid}}" class="btn btn-sm btn-danger hapus"><i class="fa-regular fa-trash-can"></i></a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="modal fade in" id="modal-tambah-walikelas">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Edit Walikelas</p>
                </div>
                <div class="modal-body">
                    <label for="guru">Walikelas</label>
                    <select name="guru" id="guru" class="form-control">
                        <option value="">Pilih Salah Satu</option>
                    @foreach ($guru as $guru)
                        <option value="{{$guru->uuid}}">{{$guru->nama}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary assign-walikelas">Assign Walikelas</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.buka-walikelas').click(function() {
            var id = $(this).data('id');
            var urlGet = "{{route('kelas.walikelas', ':id')}}";
            urlGet = urlGet.replace(':id', id);
            var token = '{{csrf_token()}}';

            $.ajax({
                type: "GET",
                url: urlGet,
                success: function(data) {
                    if(data.success == true) {
                    $('#guru').val(data.data.guru.uuid);
                    $('#modal-tambah-walikelas').modal("show");
                    } else {
                        $('#modal-tambah-walikelas').modal("show");
                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    console.log(errors);
                }
            })

            $('.assign-walikelas').click(function() {
                var idguru = $('#guru').val();
                var makeWalikelas = () => {
                    loading();
                    var url = "{{route('kelas.walikelas', ':id')}}";
                    url = url.replace(':id', id);
                    var token = '{{csrf_token()}}';
                    $.ajax({
                        type : "POST",
                        url : url,
                        headers: {'X-CSRF-TOKEN': token},
                        data: {idGuru : idguru,idKelas : id},
                        success: function(data) {
                            setTimeout(() => {
                                removeLoading();
                                cAlert('green','Berhasil','Walikelas Berhasil Diubah',true);
                            }, 500);
                        },
                        error: function(data) {
                            var errors = data.responseJSON;
                            console.log(errors);
                        }
                    });
                }
                if(idguru != "") {
                    cConfirm("Perhatian","Jadikan PTK Bersangkutan menjadi Walikelas Kelas ini",makeWalikelas);
                } else {
                    oAlert("blue","Perhatian","Walikelas Tidak Boleh Kosong");
                }
            });
        });
        $('.hapus').click(function() {
            var id = $(this).data('id');
            var HapusKelas = () => {
                loading();
                var url = "{{route('kelas.destroy',':id')}}";
                url = url.replace(':id',id);
                var token = '{{csrf_token()}}';
                $.ajax({
                    type: "DELETE",
                    url: url,
                    data: {
                        "_token": token
                    },
                    success: function(data) {
                        setTimeout(() => {
                            removeLoading();
                            cAlert("green","Sukses",'Data Kelas Berhasil Dihapus',true);
                        },500)
                        // console.log(data);
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                })
            }
            cConfirm("Perhatian","Hapus Data Kelas Ini ?",HapusKelas);
        });
    </script>
@endsection
