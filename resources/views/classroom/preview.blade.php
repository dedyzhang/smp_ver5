@extends('layouts.main')

@section('container')
{{Breadcrumbs::render('classroom-preview',$uuid, $classroom,$classroom->ngajar)}}
<div class="body-contain-customize col-12">
    <h5><b>{{$classroom->judul}}</b></h5>
    <p class="mb-2">{{$classroom->deskripsi}}</p>
    <p class="fs-12 mb-0"><i>Di Post Pada Tanggal {{date('d F Y, H:i:s',strtotime($classroom->tanggal_post))}}</i></p>
    @if($classroom->tanggal_due !== null)
    <p class="fs-12 text-danger-emphasis"><i>Tugas Selesai Sebelum {{date('d F Y, H:i:s',strtotime($classroom->tanggal_due))}}</i></p>
    @endif
</div>
<div class="body-contain-customize col-12 mt-3">
    @if($classroom->status != "arsip")
        <button class="btn btn-sm btn-danger float-end arsip-classroom" data-bs-toggle="tooltip"
            data-bs-placement="top" data-bs-title="Arsip Classroom">
            <i class="fas fa-box-archive"></i>
        </button>
        <a href="{{route('classroom.edit',['uuid' => $classroom->ngajar->uuid,'uuidClassroom' => $classroom->uuid])}}" class="btn btn-sm btn-warning text-warning-emphasis float-end me-2" data-bs-toggle="tooltip"
            data-bs-placement="top" data-bs-title="Edit Classroom">
            <i class="fas fa-pencil"></i>
        </a>
    @endif

</div>
<div class="body-contain-customize col-12 mt-3">
    <ul class="nav nav-tabs" id="classroom-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="isi-tab" data-bs-toggle="tab" data-bs-target="#isi-tab-pane"
                type="button" role="tab" aria-controls="isi-tab-pane" aria-selected="true">Detail</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="siswa-tab" data-bs-toggle="tab" data-bs-target="#siswa-tab-pane" type="button"
                role="tab" aria-controls="siswa-tab-pane" aria-selected="false">Siswa</button>
        </li>
        @if ($classroom->jenis == "latihan")
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="jawaban-tab" data-bs-toggle="tab" data-bs-target="#jawaban-tab-pane" type="button"
                role="tab" aria-controls="jawaban-tab-pane" aria-selected="false">Jawaban</button>
        </li>
        @endif
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active p-3" id="isi-tab-pane" role="tabpanel" aria-labelledby="isi-tab"
            tabindex="0">
            <p><b>A. Isi Classroom</b></p>
            {!! $classroom->isi !!}

            <div class="file-classroom mt-5">
                <p><b>B. File Classroom</b></p>
                <div class="row m-0 p-0">
                    @foreach ($file_array as $file)
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                        <div class="card rounded-4 border-1 m-2">
                            <div class="card-body">
                                <div class="row m-0 p-0 d-flex align-items-center">
                                    <div class="col-3">
                                        <i class="fas fa-file fa-3x"></i>
                                    </div>
                                    <div class="col-7">
                                        <p class="mb-0">Files {{$loop->iteration}}</p>
                                    </div>
                                    <div class="col-2 buka-modal" data-file="{{$file}}">
                                        <i class="fas fa-chevron-right fs-20"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @if ($classroom->link !== null)
            @php
            $url = $classroom->link;
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/
            ]{11})%i', $url, $match);
            $youtube_id = $match[1];
            @endphp
            <div class="youtube-place mt-5">
                <p><b>C. Video Classroom</b></p>
                <iframe width="100%" height="500" src="https://www.youtube.com/embed/{{$youtube_id}}"></iframe>
            </div>
            @endif
        </div>
        <div class="tab-pane fade p-3" id="siswa-tab-pane" role="tabpanel" aria-labelledby="siswa-tab" tabindex="0">
            <div class="row m-0 p-0">
                <div class="col-12">
                    <div class="button-place mt-2 mb-2">
                        <button class="btn btn-sm btn-warning reset-semua-siswa">
                            <i class="fas fa-recycle"></i> Reset Semua Siswa
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped fs-12">
                            <thead>
                                <tr>
                                    <td width="5%">No</td>
                                    <td width="30%">Nama Siswa</td>
                                    <td width="10%">Status</td>
                                    <td width="30%">Aktivitas</td>
                                    <td width="10%">#</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswa as $item)
                                <tr class="siswa" data-uuid="{{$item->uuid}}">
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->nama}}</td>
                                    <td>
                                        @if(isset($status_array[$item->uuid]))
                                        <span>{{$status_array[$item->uuid]['status']}}</span>
                                        @else
                                        <i class="text-danger">Belum Buka</i>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($status_array[$item->uuid]))
                                        <span>{{$status_array[$item->uuid]['last_seen']}}</span>
                                        @else
                                        <i class="text-danger">Belum Buka</i>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($status_array[$item->uuid]))
                                        <button class="btn btn-sm btn-warning text-warning-emphasis reset-siswa"
                                            data-bs-toggle="tooltip" data-bs-title="Reset Siswa"
                                            data-bs-placement="top">
                                            <i class="fas fa-recycle"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if ($classroom->jenis == "latihan")
            <div class="tab-pane fade p-3" id="jawaban-tab-pane" role="tabpanel" aria-labelledby="siswa-tab" tabindex="0">
                <div class="float-end form-switch show-nilai" style="width:150px">
                    <input class="form-check-input" type="checkbox" role="switch" id="tampilkan_nilai" @if ($classroom->show_nilai == 1) checked @endif>
                    <label class="form-check-label" for="tampilkan_nilai">Tampilkan Nilai</label>
                </div>
                <div class="float-start w-75">
                    <p>Klik Nama Siswa Bersangkutan untuk melihat dan memberikan nilai kepada siswa tersebut</p>
                </div>
                <div class="clearfix"></div>
                <div class="row m-0 p-0">
                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
                        <div class="table-responsive">
                            <table class="table table-bordered fs-10">
                                <thead>
                                    <tr>
                                        <td width="5%">No</td>
                                        <td width="30%">Nama Siswa</td>
                                        <td width="10%">Nilai</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $item)
                                    @php
                                        if(!empty($jawaban_array) && isset($jawaban_array[$item->uuid])) {
                                            if($jawaban_array[$item->uuid]['status'] == "perbaikan" || $jawaban_array[$item->uuid]['status'] == "revisi") {
                                                $class = "table-warning";
                                            } else if($jawaban_array[$item->uuid]['status'] == "belum") {
                                                $class = "table-info";
                                            } else if($jawaban_array[$item->uuid]['status'] == "selesai") {
                                                $class = "table-success";
                                            } else {
                                                $class = "";
                                            }
                                            $nilai = $jawaban_array[$item->uuid]['nilai'];
                                        } else {
                                            $class = "";
                                            $nilai = 0;
                                        }
                                    @endphp
                                    <tr class="siswa-{{$item->uuid}} lihat-jawaban cursor-pointer {{$class}}" data-uuid="{{$item->uuid}}">
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$item->nama}}</td>
                                        <td class="nilai-siswa">
                                            {{$nilai}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-8 col-lg-9 col-xl-9">
                        <div class="p-2 jawaban-siswa"></div>
                    </div>
                </div>
                <div class="modal fade in" id="modal-preview-nilai">
                    <div class="modal-dialog modal-fullscreen-md-down">
                        <div class="modal-content rounded-2">
                            <div class="modal-header">
                                <p class="modal-title">Jawaban Siswa</p>
                                <button class="btn btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body jawaban-siswa-modal">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $('.lihat-jawaban').click(function() {
                    loading();

                    if($(window).width() >= 768) {
                        var url = "{{route('classroom.lihatJawaban',':id')}}";
                        url = url.replace(':id',"{{$classroom->uuid}}");
                        var idSiswa = $(this).data('uuid');
                        $('.jawaban-siswa').html("");
                        $('.jawaban-siswa-modal').html("");
                        $.ajax({
                            type: "GET",
                            data: {idSiswa : idSiswa},
                            url: url,
                            success: function(data) {
                                var jawaban = data.jawaban;
                                if(data.jawaban === null) {
                                    $('.jawaban-siswa').html(`
                                        <div class="alert alert-danger">
                                            <i class="fa fa-exclamation-triangle"></i>
                                            Siswa Belum Mengerjakan Latihan Ini
                                        </div>
                                    `);
                                } else {
                                    var startTime = new Date("{{$classroom->tanggal_due}}");
                                    var endTime = new Date(jawaban.updated_at);
                                    if(endTime > startTime) {
                                        var terlambat = true;
                                    } else {
                                        var terlambat = false;
                                    }

                                    var jawabanHTML = `
                                        <div class="row m-0 p-0 row-jawaban" data-uuid="${jawaban.id_siswa}">
                                            <div class="col-12">
                                                <p class="mb-1"><b>Jawaban Siswa</b></p>
                                                <p class="${terlambat == true ? "text-danger" : ""}"><i>Dikumpulkan Pada ${moment(jawaban.updated_at).format('DD MMM YYYY hh:mm:ss')} ${terlambat == true ? " - Terlambat" : ""}</i></p>
                                                <div class="rounded-2 border border-1 p-2">
                                                    ${jawaban.jawaban}
                                                </div>
                                                <div class="rounded-2 p-2 mt-3 bg-warning-subtle">
                                                    <p><b>Lembar Penilaian</b></p>
                                                    <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                                        <label for="nilai">Nilai</label>
                                                        <input type="number" class="form-control" name="nilai" id="nilai" placeholder="Nilai Untuk Latihan Ini" value="${jawaban.nilai}">
                                                    </div>
                                                    <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                        <label for="komentar">Feedback</label>
                                                        <textarea class="form-control" rows="3" name="komentar" id="komentar" placeholder="Masukkan Feedback dari Jawaban Yang Dimasukkan">${jawaban.komentar === null ? "" : jawaban.komentar}</textarea>
                                                    </div>
                                                    <div class="form-check form-switch mt-2">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="revisi" ${jawaban.status == "revisi" ? "checked" : ""}>
                                                        <label class="form-check-label" for="revisi">Revisi</label>
                                                    </div>
                                                    <div class="button-place mt-2">
                                                        <button class="btn btn-sm btn-warning text-warning-emphasis simpan-nilai">
                                                            <i class="fas fa-save"></i> Simpan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    $('.jawaban-siswa').html(jawabanHTML);
                                }
                                removeLoading();
                            },
                            error: function(data) {
                                console.log(data);
                            }
                        });
                    } else {
                        var url = "{{route('classroom.lihatJawaban',':id')}}";
                        url = url.replace(':id',"{{$classroom->uuid}}");
                        var idSiswa = $(this).data('uuid');
                        $('.jawaban-siswa-modal').html("");
                        $('.jawaban-siswa').html("");
                        $.ajax({
                            type: "GET",
                            data: {idSiswa : idSiswa},
                            url: url,
                            success: function(data) {
                                var jawaban = data.jawaban;
                                if(data.jawaban === null) {
                                    $('.jawaban-siswa-modal').html(`
                                        <div class="alert alert-danger">
                                            <i class="fa fa-exclamation-triangle"></i>
                                            Siswa Belum Mengerjakan Latihan Ini
                                        </div>
                                    `);
                                } else {
                                    var startTime = new Date("{{$classroom->tanggal_due}}");
                                    var endTime = new Date(jawaban.updated_at);
                                    if(endTime > startTime) {
                                        var terlambat = true;
                                    } else {
                                        var terlambat = false;
                                    }

                                    var jawabanHTML = `
                                        <div class="row m-0 p-0 row-jawaban" data-uuid="${jawaban.id_siswa}">
                                            <div class="col-12">
                                                <p class="mb-1"><b>Jawaban Siswa</b></p>
                                                <p class="${terlambat == true ? "text-danger" : ""}"><i>Dikumpulkan Pada ${moment(jawaban.updated_at).format('DD MMM YYYY hh:mm:ss')} ${terlambat == true ? " - Terlambat" : ""}</i></p>
                                                <div class="rounded-2 border border-1 p-2">
                                                    ${jawaban.jawaban}
                                                </div>
                                                <div class="rounded-2 p-2 mt-3 bg-warning-subtle">
                                                    <p><b>Lembar Penilaian</b></p>
                                                    <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                                        <label for="nilai">Nilai</label>
                                                        <input type="number" class="form-control" name="nilai" id="nilai" placeholder="Nilai Untuk Latihan Ini" value="${jawaban.nilai}">
                                                    </div>
                                                    <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                        <label for="komentar">Feedback</label>
                                                        <textarea class="form-control" rows="3" name="komentar" id="komentar" placeholder="Masukkan Feedback dari Jawaban Yang Dimasukkan">${jawaban.komentar === null ? "" : jawaban.komentar}</textarea>
                                                    </div>
                                                    <div class="form-check form-switch mt-2">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="revisi" ${jawaban.status == "revisi" ? "checked" : ""}>
                                                        <label class="form-check-label" for="revisi">Revisi</label>
                                                    </div>
                                                    <div class="button-place mt-2">
                                                        <button class="btn btn-sm btn-warning text-warning-emphasis simpan-nilai">
                                                            <i class="fas fa-save"></i> Simpan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    $('.jawaban-siswa-modal').html(jawabanHTML);
                                }
                                removeLoading();
                                $('#modal-preview-nilai').modal("show");

                            },
                            error: function(data) {
                                console.log(data);
                            }
                        });
                    }
                });
                $('.show-nilai').click(function() {
                    var show = $(this).find('.form-check-input').is(':checked');
                    var url = "{{route('classroom.showNilai',':id')}}";
                    url = url.replace(':id',"{{$classroom->uuid}}");
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {show : show},
                        headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                        success: function(data) {

                        },
                        error: function(data) {
                            console.log(data.responseJSON.message);
                        }
                    })
                });
            </script>
        @endif
    </div>
</div>
<div class="modal fade in p-0" id="showFileModal">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h6>PDF Reader</h6>
                <button class="btn btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="pdf-reader" style="height:100%"></div>
            </div>

        </div>
    </div>
</div>
<script>
    $('.buka-modal').click(function() {
        var file = $(this).data('file');
        var newPath = "{{asset('/storage/classroom/teacher/'.':id')}}";
        newPath = newPath.replace(':id',file);
        var url = "{{asset('/js/pdfjs/web/viewer.html?file=:id')}}";
        url = url.replace(':id',newPath);
        $('#pdf-reader').html('<iframe src="'+url+'" style="width:100%; height:100%;" frameborder="0"></iframe>');
        $('#showFileModal').modal("show");

    });
    $('.reset-siswa').click(function() {
        var uuid = $(this).closest('.siswa').data('uuid');
        var url = "{{route('classroom.resetSiswa',['uuid' => ':id','uuidClassroom' => ':id2'])}}";
        url = url.replace(':id',"{{$classroom->id_ngajar}}").replace(":id2","{{$classroom->uuid}}");
        loading();
        $.ajax({
            type: "POST",
            url : url,
            data: {uuid: uuid},
            headers: {"X-CSRF-TOKEN": "{{csrf_token()}}"},
            success: function(data) {
                console.log(data);
                cAlert("green","Sukses","Berhasil mereset siswa",true);
                removeLoading();
            },
            error: function(data) {
                console.log(data.responseJSON.message);
            }
        })
    });
    $('.reset-semua-siswa').click(function() {
        var resetSemua = () => {
            var url = "{{route('classroom.resetSemuaSiswa',['uuid' => ':id','uuidClassroom' => ':id2'])}}";
            url = url.replace(':id',"{{$classroom->id_ngajar}}").replace(":id2","{{$classroom->uuid}}");
            loading();
            $.ajax({
                type: "POST",
                url : url,
                headers: {"X-CSRF-TOKEN": "{{csrf_token()}}"},
                success: function(data) {
                    console.log(data);
                    cAlert("green","Sukses","Berhasil mereset siswa",true);
                    removeLoading();
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        }
        cConfirm("Perhatian","Apakah Anda Yakin untuk mereset semua siswa",resetSemua);
    });
    $('body').on('click','.simpan-nilai',function() {
        loading();
        var nilai = $('#nilai').val();
        var deskripsi = $('#komentar').val();
        var revisi = $('#revisi').is(':checked');
        var idSiswa = $(this).closest('.row-jawaban').data('uuid');
        var idClassroom = "{{$classroom->uuid}}";
        var url = "{{route('classroom.nilai',':id')}}";
        url = url.replace(':id',idClassroom);
        $.ajax({
            type: "POST",
            url: url,
            data: {nilai : nilai, deskripsi : deskripsi, revisi : revisi, idSiswa : idSiswa},
            headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
            success: function(data) {
                if(data.success === true) {
                    $('.siswa-'+idSiswa).find('.nilai-siswa').text(nilai);
                    removeLoading();
                    if(revisi === false) {
                        $('.siswa-'+idSiswa).addClass('table-success').removeClass('table-warning');
                    } else {
                        $('.siswa-'+idSiswa).removeClass('table-success').addClass('table-warning');
                    }
                }
            },
            error: function(data) {
                console.log(data);
            }
        })
    })
    $('.arsip-classroom').click(function() {
        var arsipClassroom = () => {
            loading();
            var route = "{{route('classroom.arsip',':id')}}";
            route = route.replace(':id',"{{$classroom->uuid}}");
            $.ajax({
                type: "POST",
                url: route,
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                success: function(data) {
                    if(data.success == true) {
                        var url = "{{route('classroom.show',':id')}}";
                        url = url.replace(':id','{{$classroom->ngajar->uuid}}');
                        cAlert("green","Sukses","Materi/Latihan berhasil diarsip",false,url);
                    }
                }
            })
        }
        cConfirm("Perhatian","Apakah anda Yakin untuk mengarsip Materi / Latihan ini, Materi yang sudah diarsip tidak bisa diakses oleh siswa lagi",arsipClassroom);
    });
</script>
@endsection
