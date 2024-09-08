@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('classroom-show',$ngajar->pelajaran, $ngajar->kelas,$ngajar)}}
    <div class="body-contain-customize col-12">
        <h5><b>Classroom</b></h5>
        <p>Halaman ini digunakan guru untuk membuat materi pembelajaran maupun mengupload file pembelajaran yang akan diakses oleh siswa bersangkutan</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5">
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
    </div>
    <div class="row m-0 p-0">
        <div class="body-contain-customize mt-3 col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex gap-2">
            <a href="{{route('classroom.create',['uuid' => $ngajar->uuid,'jenis' => 'materi'])}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-plus"></i> Tambah Materi Pembelajaran</a>
            <a href="{{route('classroom.create',['uuid' => $ngajar->uuid,'jenis' => 'latihan'])}}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah Latihan</a>
            <a href="{{route('classroom.archived',$ngajar->uuid)}}" class="btn btn-sm btn-danger"><i class="fas fa-box-archive"></i> Lihat Arsip</a>
        </div>
    </div>
    <div id="aspect-content">
        @foreach ($classroom as $item)
            <div class="aspect-tab @if ($item->status == "save")
                bg-warning-subtle
            @endif">
                <input id="item-{{$item->uuid}}" type="checkbox" class="aspect-input" name="aspect">
                <label for="item-{{$item->uuid}}" class="aspect-label"></label>
                <div class="aspect-content">
                    <div class="aspect-info">
                        <div class="chart-pie negative over50">
                            <span class="chart-pie-count">0</span>
                            <div>
                                <div class="first-fill"></div>
                                <div class="second-fill" style="transform: rotate(249deg)"></div>
                            </div>
                        </div>
                        <div class="aspect-name">
                            <p class="m-0 text-secondary"><b>{{ucwords($item->jenis)}}</b></p>
                            <p class="m-0">{{$item->judul}}</p>
                        </div>
                    </div>
                </div>
                <div class="aspect-tab-content">
                    <div class="sentiment-wrapper">
                        <div>
                            <div>
                                <div class="opinion-header fs-10 text-danger mb-0">TANGGAL POST : {{date('d F Y, H:i:s',strtotime($item->tanggal_post))}}</div>
                                @if($item->tanggal_due !== null)
                                <div class="opinion-header fs-10 text-success">TANGGAL DUE : {{date('d F Y, H:i:s',strtotime($item->tanggal_due))}}</div>
                                @endif
                                <div class="mt-3">
                                    <div>{{$item->deskripsi}}</div>
                                    <div class="token-place mt-3">
                                        TOKEN : <span class="token-cycle">{{$item->token}}</span>
                                    </div>
                                    <div class="button-place mt-3 gap-2 d-grid d-sm-grid d-md-flex d-xl-flex d-lg-flex">
                                        @if ($item->status == "assign")
                                            <a href="{{route('classroom.preview',['uuid' => $ngajar->uuid,'uuidClassroom' => $item->uuid])}}" class="btn btn-sm btn-warning text-warning-emphasis">
                                                <i class="fas fa-pencil"></i> Preview
                                            </a>
                                            <button class="btn btn-sm btn-primary reset-token" data-uuid="{{$item->uuid}}">
                                                <i class="fas fa-recycle"></i> Reset Token
                                            </button>
                                            <button class="btn btn-sm btn-danger tutup-token" data-uuid="{{$item->uuid}}">
                                                <i class="fas fa-times"></i> Tutup Token
                                            </button>
                                        @else
                                            <a href="{{route('classroom.edit',['uuid' => $ngajar->uuid,'uuidClassroom' => $item->uuid])}}" class="btn btn-sm btn-warning text-warning-emphasis">
                                                <i class="fas fa-pencil"></i> Edit Materi
                                            </a>
                                            <button class="btn btn-sm btn-primary assign-materi" data-uuid="{{$item->uuid}}">
                                                <i class="fas fa-book-open"></i> Assign Materi
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-materi" data-uuid="{{$item->uuid}}">
                                                <i class="fas fa-trash-can"></i> Delete Materi
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <script>
            $('.assign-materi').click(function() {
                var uuid = $(this).data('uuid');
                var assignMateri = () => {
                    loading();
                    var route = "{{route('classroom.assign',':id')}}";
                    route = route.replace(':id',uuid);
                    $.ajax({
                        type: "POST",
                        headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                        url: route,
                        success: function(data) {
                            if(data.success == true) {
                                cAlert("green","Berhasil","Classroom berhasil di Assign",true);
                            }
                            console.log(data);
                        },
                        error: function(data) {
                            console.log(data.responseJSON.message);
                        }
                    })
                }
                cConfirm("Perhatian","Yakin untuk Assign Materi ini, setelah Materi Diassign, Maka Guru tidak bisa membatalkannya",assignMateri);
            });
            $('.delete-materi').click(function() {
                var uuid = $(this).data('uuid');
                var deleteMateri = () => {
                    var deleteAll = $('#check-delete-all').is(':checked');
                    loading();
                    var route = "{{route('classroom.delete',':id')}}";
                    route = route.replace(':id',uuid);
                    $.ajax({
                        type: "delete",
                        data: {deleteAll : deleteAll},
                        headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                        url: route,
                        success: function(data) {
                            if(data.success == true) {
                                cAlert("green","Berhasil","Classroom berhasil di Delete",true);
                            }
                            console.log(data);
                        },
                        error: function(data) {
                            console.log(data.responseJSON.message);
                        }
                    });
                }
                cConfirm("Perhatian","<p>Yakin Untuk Delete Materi</p><div class='form-check'><label for='check-delete-all'>Hapus Semua Materi Yang Sama</label><input type='checkbox' class='form-check-input' id='check-delete-all' value='yes'/></div>",deleteMateri);
            });
            $('.reset-token').click(function() {
                var uuid = $(this).data('uuid');
                var url = "{{route('classroom.resetToken',':id')}}";
                url = url.replace(':id',uuid);
                loading();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {token : 'reset'},
                    headers: {'X-CSRF-TOKEN' : '{{csrf_token()}}'},
                    success: function(data) {
                        $('.token-cycle').text(data.token);
                        removeLoading();
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            });
            $('.tutup-token').click(function() {
                var uuid = $(this).data('uuid');
                var url = "{{route('classroom.resetToken',':id')}}";
                url = url.replace(':id',uuid);
                loading();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {token : 'tutup'},
                    headers: {'X-CSRF-TOKEN' : '{{csrf_token()}}'},
                    success: function(data) {
                        $('.token-cycle').text(data.token);
                        removeLoading();
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            });
        </script>
    </div>
@endsection
