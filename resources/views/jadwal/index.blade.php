@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Jadwal Pelajaran</b></h5>
        <p>Jadwal pelajaran mengatur dan mengkoordinasikan berbagai kegiatan pembelajaran secara sistematis, sehingga siswa dan pengajar dapat mengetahui kapan dan di mana mereka harus berada untuk mengikuti pelajaran tertentu.</p>
    </div>
    @if(session('success'))
        <div class="body-contain-customize col-12 mt-3">
            <div class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert">
                <i class="bi flex-shrink-0 me-2 fa-solid fa-check" aria-label="Success:"></i>
                <div>
                    <strong>Sukses !</strong> Menambahkan data versi jadwal
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="body-contain-customize col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex mt-3">
        <a href="{{route('jadwal.create')}}" class="btn btn-sm btn-warning"><i class="fas fa-plus"></i> Tambah Versi</a>
    </div>
    <div class="row m-0 p-0 mt-3">
        @foreach ($jadwalVer as $version)
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <div class="card rounded-4 border-light" data-uuid="{{$version->uuid}}">
                    <div class="card-body">
                        <h5><i class="fas fa-clock"></i> <b>Ver. {{$version->versi}}</b></h5>
                        <p class="fs-12 mb-0" data-bs-toggle="tooltip" data-bs-title="{{$version->deskripsi}}" data-bs-placement="top">{{Str::limit($version->deskripsi,20)}}</p>
                        <p class="fs-12 mb-0">
                            @if ($version->status == "standby")
                                <i class="text-black-50"> Standby</i>
                            @else
                                <i class="text-primary"> Aktif</i>
                            @endif
                        </p>
                        <div class="button-place mt-4">
                            @if ($version->status == "standby")
                                <button data-bs-toggle="tooltip" data-bs-title="Set Jadwal Aktif" data-bs-placement="top" class="btn btn-sm btn-light set-aktif-jadwal">
                                    <i class="fas fa-toggle-off"></i>
                                </button>
                            @else
                                <button data-bs-toggle="tooltip" data-bs-title="Set Jadwal Standby" data-bs-placement="top" class="btn btn-sm btn-light set-standby-jadwal">
                                    <i class="fas text-success fa-toggle-on"></i>
                                </button>
                            @endif

                            <a href="{{route('jadwal.show',$version->uuid)}}" data-bs-toggle="tooltip" data-bs-title="Lihat Jadwal" data-bs-placement="top" class="btn btn-sm btn-warning text-warning-emphasis">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" data-bs-toggle="tooltip" data-bs-title="Hapus Jadwal" data-bs-placement="top" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash-can"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <script>
        $('.set-aktif-jadwal').click(function() {
            var uuid = $(this).closest('.card').data('uuid');
            var aktifkanJadwal = () => {
                loading();
                var url = "{{route('jadwal.edit')}}";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {uuid: uuid,purpose: "aktif"},
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        removeLoading();
                        location.reload();
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
            cConfirm("warning","Yakin untuk mengaktifkan jadwal berikut",aktifkanJadwal);
        });
        $('.set-standby-jadwal').click(function() {
            var uuid = $(this).closest('.card').data('uuid');
            var aktifkanJadwal = () => {
                loading();
                var url = "{{route('jadwal.edit')}}";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {uuid: uuid,purpose: "standby"},
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        removeLoading();
                        location.reload();
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
            cConfirm("warning","Yakin untuk menutup jadwal berikut",aktifkanJadwal);
        });
    </script>
@endsection
