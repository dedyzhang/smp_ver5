@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('guru')}}
    <div class="body-contain-customize col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex gap-2">

        {{-- <p class="body-title"><b>Menu Guru</b></p>
        <hr /> --}}
        <a href="{{route('guru.create')}}" class="btn btn-sm btn-warning fs-14" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tambah Guru">
            <i class="fa-solid fa-plus"></i>
            Tambah Guru
        </a>
        <a href="{{route('cetak.guru.excel')}}" class="btn btn-sm btn-primary fs-14" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Download Guru">
            <i class="fa-solid fa-file-excel"></i>
            Download Guru
        </a>
        <button class="btn btn-sm btn-secondary fs-14 tambah-sekretaris" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tambah Sekretaris">
            <i class="fas fa-user"></i>
            Sekretaris
        </button>
    </div>
    <div class="body-contain-customize col-md-12 col-lg-12 col-sm-12 mt-3">

        <h5><b>Data PTK</b></h5>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert">
                <i class="bi flex-shrink-0 me-2 fa-solid fa-check" aria-label="Success:"></i>
                <div>
                    <strong>Sukses !</strong> Menambahkan data PTK
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session('error'))

        @endif
    </div>
    @forelse ($gurus as $guru)
        <div class="col-md-6 col-lg-4 col-xl-3 col-sm-6 col-12 mt-3 pe-2 ps-0">
            <div class="card border-light rounded-4">
                <div class="card-body">
                    @if($guru->jk == "l")
                        <img src="{{asset('img/teacher-boy.png')}}" style="width: 50px; height:50px" />
                    @else
                        <img src="{{asset('img/teacher-girl.png')}}" style="width: 50px; height:50px" />
                    @endif
                    <p class="m-0 p-0 mt-2">{{Str::limit($guru->nama,18)}}</p>
                    <p class="m-0 p-0 fs-12"><i><strong>{{$guru->users->access}}</strong></i></p>

                    <div class="button-place mt-3">
                        <a data-id="{{$guru->uuid}}" class="btn btn-primary btn-sm preview-guru"><i class="fa-regular fa-eye"></i></a>
                        <a data-id="{{$guru->uuid}}" class="btn btn-sm btn-info reset-token"><i class="fa-solid fa-recycle"></i></a>
                        <a href="{{route('guru.pelajaran',$guru->uuid)}}" class="btn btn-sm btn-success"><i class="fa-solid fa-chalkboard"></i></a>
                        <a href="{{route('guru.edit',$guru->uuid)}}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pencil"></i></a>
                        <a data-id="{{$guru->uuid}}" class="btn btn-sm btn-danger hapus-guru"><i class="fa-regular fa-trash-can"></i></a>
                    </div>
                </div>
            </div>
        </div>
    @empty

    @endforelse
    {{-- Modal For Preview Guru --}}
    <div class="modal fade" tabindex="-1" id="modal-preview-guru">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Data Guru <span class="nama-guru"></span></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-6 col-sm-4 col-md-4 col-lg-2 col-xl-2 p-picture">

                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <p><strong><i>A. Identitas Pribadi</i></strong></p>
                            <table class="w-100">
                                <tbody>
                                    <tr>
                                        <td class="w-50">Nama</td>
                                        <td>:</td>
                                        <td class="nama-guru"></td>
                                    </tr>
                                    <tr>
                                        <td>NIK</td>
                                        <td>:</td>
                                        <td class="nik-guru"></td>
                                    </tr>
                                    <tr>
                                        <td>JK</td>
                                        <td>:</td>
                                        <td class="jk-guru"></td>
                                    </tr>
                                    <tr>
                                        <td>Tempat/Tanggal Lahir</td>
                                        <td>:</td>
                                        <td class="ttl-guru"></td>
                                    </tr>
                                    <tr>
                                        <td>Agama</td>
                                        <td>:</td>
                                        <td class="agama-guru"></td>
                                    </tr>
                                    <tr>
                                        <td>No Telepon</td>
                                        <td>:</td>
                                        <td class="telepon-guru"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">Alamat</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="alamat-guru"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <p><strong><i>B. Identitas Pendidikan</i></strong></p>
                            <table class="w-100">
                                <tbody>
                                    <tr>
                                        <td class="w-50">Tingkat Studi</td>
                                        <td>:</td>
                                        <td class="tingkat-studi-guru"></td>
                                    </tr>
                                    <tr>
                                        <td>Program Studi</td>
                                        <td>:</td>
                                        <td class="program-studi-guru"></td>
                                    </tr>
                                    <tr>
                                        <td>Universitas</td>
                                        <td>:</td>
                                        <td class="universitas-guru"></td>
                                    </tr>
                                    <tr>
                                        <td>Tahun Tamat</td>
                                        <td>:</td>
                                        <td class="tahun-tamat-guru"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                         <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <p><strong><i>C. Identitas Sekolah</i></strong></p>
                            <table class="w-100">
                                <tbody>
                                    <tr>
                                        <td class="w-50">TMT Mengajar</td>
                                        <td>:</td>
                                        <td class="tmt-mengajar-guru"></td>
                                    </tr>
                                    <tr>
                                        <td>TMT Sekolah</td>
                                        <td>:</td>
                                        <td class="tmt-smp-guru"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="modal-preview-sekretaris">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Data Sekretaris</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-0 p-0">
                        <div class="col-12 form-group">
                            <label for="sekretaris">Tambah Sekretaris</label>
                            <select name="sekretaris" id="sekretaris" class="form-control" data-toggle="select">
                                <option value="">Pilih Salah Satu</option>
                                @foreach ($gurus as $guru)
                                    <option value="{{ $guru->uuid }}">{{ $guru->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 button-place mt-2">
                            <button class="btn btn-sm btn-primary mt-1 tambahkan-sekretaris"><i class="fas fa-plus"></i> Tambah</button>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="m-0 p-0">Daftar Sekretaris</p>
                            @php
                                $sekretaris = $gurus->filter(function($elem) {
                                    return $elem->sekretaris == 1;
                                });
                            @endphp
                            <ul class="list-group">
                                @foreach ($sekretaris as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">{{$item->nama}}
                                        <span class="badge bg-primary rounded-pill hapus-sekretaris" data-guru="{{ $item->uuid }}" style="cursor: pointer;">X</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.preview-guru').click(function() {
            const myModalAlternative = new bootstrap.Modal('#modal-preview-guru');
            var id = $(this).data('id');
            var url = "{{route('guru.show', ':id')}}";
            url = url.replace(':id', id);

            $.ajax({
                type: 'GET',
                url: url,
                success:function(data){
                    console.log(data);
                    var guru = data.guru;
                    // loading();
                    $('.nama-guru').html(guru.nama);
                    if(guru.jk == "l") {
                        $('.p-picture').html('<img src={{asset("img/teacher-boy.png")}} style="width:100%;height:auto" />');
                    } else {
                        $('.p-picture').html('<img src={{asset("img/teacher-girl.png")}} style="width:100%;height:auto" />');
                    }
                    $('.nik-guru').html(guru.nik);
                    if(guru.jk == 'l') {
                        var jk = "Laki-laki";
                    } else {
                        var jk = "Perempuan";
                    }
                    $('.jk-guru').html(jk);
                    $('.ttl-guru').html(guru.tempat_lahir+" / "+guru.tanggal_lahir);
                    $('.agama-guru').html(guru.agama);
                    $('.telepon-guru').html(guru.no_telp);
                    $('.alamat-guru').html(guru.alamat);
                    $('.tingkat-studi-guru').html(guru.tingkat_studi);
                    $('.program-studi-guru').html(guru.program_studi);
                    $('.universitas-guru').html(guru.universitas);
                    $('.tahun-tamat-guru').html(guru.tahun_tamat);
                    $('.tmt-mengajar-guru').html(guru.tmt_ngajar);
                    $('.tmt-smp-guru').html(guru.tmt_smp);


                    myModalAlternative.show();
                },
                error: function(data){
                    var errors = data.responseJSON;
                    console.log(errors);
                }
            })

        })

        $('.hapus-guru').click(function() {
            var init = this;
            var HapusGuru = function() {
                loading();
                var id = $(init).data('id');
                var url = "{{route('guru.destroy', ':id')}}";
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
                            cAlert("green","Sukses","Sukses Menghapus Data PTK",true);
                        }, 500);
                    },
                    error: function(data){
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });
            }
            cConfirm("WARNING","Apakah Anda Yakin untuk Menghapus PTK ini",HapusGuru);
        });

        $('.reset-token').click(function() {
            var init = this;
            var ResetToken = function() {
                loading();
                var id = $(init).data('id');
                var url = "{{route('guru.reset', ':id')}}";
                url = url.replace(':id', id);
                var token = '{{csrf_token()}}';
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        "_token": token
                    },
                    success:function(data){
                        setTimeout(() => {
                            removeLoading();
                            var html = "<p>Berhasil Mereset Data PTK dengan Token</p><h1 class='text-center fs-30'><b>"+data.password+"</b></h1>"
                            cAlert("green","Sukses",html,true);
                        }, 500);
                    },
                    error: function(data){
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });
            }
            cConfirm("WARNING","Apakah Anda Yakin untuk Menghapus PTK ini",ResetToken);
        });

        $('.tambah-sekretaris').on('click',function() {
            $('#modal-preview-sekretaris').modal("show");
        });
        $('.tambahkan-sekretaris').on('click',function() {
            var sekretarisUuid = $('#sekretaris').val();
            if(sekretarisUuid == "") {
                oAlert("red","Error","Silahkan Pilih salah satu guru terlebih dahulu sebelum menambahkan sebagai sekkretaris");
            } else {
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('guru.sekretaris.tambah') }}",
                    data : {
                        idGuru: sekretarisUuid
                    },
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function(data) {
                        if(data.status == 'success') {
                            cAlert("green","Sukses",data.message,true);
                            removeLoading();
                        } else {
                            oAlert("red","Error",data.message);
                            removeLoading();
                        }
                    }
                })
            }
        });
        $('.hapus-sekretaris').on('click',function() {
            var sekretarisUuid = $(this).data('guru');

            cConfirm("Warning","Apakah anda yakin untuk mengnonaktifkan guru ini sebagai sekretaris?",function() {
                loading();
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('guru.sekretaris.hapus') }}",
                    data: {
                        idGuru: sekretarisUuid
                    },
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function(data) {
                        if(data.status == 'success') {
                            cAlert("green","Sukses",data.message,true);
                            removeLoading();
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            })
        })
    </script>
@endsection
