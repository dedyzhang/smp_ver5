@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('jadwal')}}
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
    @canany(['admin','kurikulum'])
        <div class="body-contain-customize col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex mt-3">
            <a href="{{route('jadwal.create')}}" class="btn btn-sm btn-warning"><i class="fas fa-plus"></i> Tambah Versi</a>
        </div>
        <div class="row m-0 p-0 mt-3">
            @foreach ($jadwalVer as $version)
                <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 p-2">
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
    @endcanany
    @if(!empty($array_jadwal))
        @canany(['admin','kurikulum','kesiswaan','sapras','guru','kepalasekolah'])
        <div class="body-contain-customize col-12 mt-5">
            <div class="row m-0 p-0">
                <div class="col-4 m-0 form-group">
                    <label for="guru">Highlight Jadwal Guru</label>
                    <select name="guru" id="guru" class="form-control">
                        <option value="">Pilih Salah Satu</option>
                        @foreach ($guruAll as $item)
                            <option value="{{$item->uuid}}">{{$item->nama}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @endcanany
        <div class="body-contain-customize col-12 mt-3 position-relative printable-jadwal">
            <div class="text-place">
                <h6 class="fs-16 mb-1"><b>Jadwal Ver. {{$jadwalSekarang->versi}}</b></h6>
                <p class="mb-1 fs-10"><i>Dibuat pada tanggal {{$jadwalSekarang->created_at}}</i></p>
                <p class="mb-5">{{$jadwalSekarang->deskripsi}}</p>
                <button data-bs-title="print jadwal" data-bs-placement="left" data-bs-toggle="tooltip" class="btn btn-sm btn-warning text-warning-emphasis position-absolute rounded-4 print-jadwal" style="top: 10px; right: 10px"><i class="fas fa-print"></i></button>
            </div>
            @foreach ($hari as $hariItem)
                <div class="table-item">
                    <p class="fs-14 w-100"><b>{{$hariItem->nama_hari}}</b></p>
                    <div class="table-responsive">
                        <table class="table table-bordered fs-11" data-hari="{{$hariItem->uuid}}" id="hari-{{$hariItem->nama_hari}}">
                            <thead>
                                <tr>
                                    <td width="3%" style="min-width: 30px">No</td>
                                    <td width="10%" style="min-width: 100px">Waktu</td>
                                    @foreach ($kelas as $kelasItem)
                                    <td width="5%" style="min-width: 50px">{{$kelasItem->tingkat.$kelasItem->kelas}}</td>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($waktu as $waktuItem)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$waktuItem->waktu_mulai."-".$waktuItem->waktu_akhir}}</td>
                                        @foreach ($kelas as $kelasItem)
                                            @if(isset($array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]) && $array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['id_pelajaran'] === "")
                                                @if ($array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['jenis'] === "spesial")
                                                    <td class="editable spesial bg-info-subtle text-center" data-spesial="{{$array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['spesial']}}">{{$array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['spesial']}}</td>
                                                @else
                                                    <td class="editable"></td>
                                                @endif

                                            @elseif (isset($array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]) && $array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['id_pelajaran'] !== "")
                                                <td class="editable" data-bs-toggle="tooltip" data-bs-title="{{$array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['guru']}}" data-guru="{{$array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['id_guru']}}" data-bs-placement="top">{{$array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['pelajaran_singkat']}}</td>
                                            @else
                                                <td></td>
                                            @endif

                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <script>
        var count = 0;
        var td = "";
        var spesial = "";
        var hari = "";
        $(document).ready(function() {
            BigLoading('Aplikasi sedang menampilkan jadwal');
            $('.spesial').each(function() {
                var hariNow = $(this).closest('table').attr('id').split('-').pop();
                if(count == 0) {
                    spesial = $(this).data('spesial');
                    td = this;
                    count++;
                    hari = hariNow;
                } else {
                    if(spesial == $(this).data('spesial') && hari == hariNow ) {
                        count++;
                        $(td).attr('colspan',count);
                        $(this).hide();
                    } else {
                        spesial = $(this).data('spesial');
                        count = 1;
                        td = this;
                        hari = hariNow;
                    }
                }
            });
            $('.spesial').promise().done(function() {
                setTimeout(() => {
                    removeLoadingBig();
                }, 500);
            })
        });
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
        $('#guru').change(function() {
            var uuidGuru = $(this).val();
            $('[data-guru]').removeClass('bg-warning-subtle fw-bold');
            if(uuidGuru == "") {
                $('[data-guru]').removeClass('bg-warning-subtle fw-bold');
            } else {
                $('[data-guru]').each(function() {
                    if($(this).data('guru') == uuidGuru) {
                        $(this).addClass('bg-warning-subtle fw-bold');
                    }
                });
            }
        });
        $('.print-jadwal').click(function() {
            window.print();
        });
    </script>
@endsection
