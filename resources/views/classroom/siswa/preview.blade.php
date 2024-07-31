@extends('layouts.main')

@section('container')
{{Breadcrumbs::render('classroom-siswa-preview',$classroom,$ngajar)}}
@if($classroom->token != "XXXX" && $siswa_aktif->status == "out")
<script>
    $('document').ready(function() {
        var backRoute = "{{route('classroom.siswa.show',':id')}}";
        backRoute = backRoute.replace(':id','{{$classroom->id_ngajar}}');
        cAlert("red","Perhatian","<p><b>ANDA TERDETEKSI KELUAR</b></p><p>Hubungi Guru Mengajar Untuk Mereset Akses</p>",false,backRoute);
    });
</script>
@endif
<div class="body-contain-customize col-12">
    <h5><b>{{$classroom->judul}}</b></h5>
    <p>{{$classroom->deskripsi}}</p>
</div>
<div class="body-contain-customize col-12 mt-3">
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

@if($classroom->jenis == "latihan")
    <div class="body-contain-customize col-12 mt-3">
        <p><b>C. Halaman Lembar Jawaban</b></p>
        @if ($getJawaban === null || $getJawaban->selesai == false || $getJawaban->status == "revisi")
            <label for="deskripsiMateri">Masukkan Jawaban Anda, Gunakan bahasa yang sopan dan benar</label>
            <textarea class="tinymce-select" id="deskripsiMateri">
                {{$jawaban}}
            </textarea>
            <div class="button-place mt-3">
                <button class="btn btn-sm btn-warning simpan-jawaban">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        @else
            <div class="detail-jawaban mt-3 fs-12">
                <p class="mb-0">Batas Waktu Pengumpulan {{date('d F Y, H:i',strtotime($classroom->tanggal_due))}} </p>
                <p class="mb-0">Dikumpulkan Pada {{date('d F Y, H:i',strtotime($getJawaban->updated_at))}}</p>
                @php
                    $due = strtotime($classroom->tanggal_due);
                    $kumpul = strtotime($getJawaban->updated_at);
                @endphp
                @if ($kumpul > $due)
                    <p class="text-danger"><b>Anda Sudah Melewati dari Batas Pengumpulan</b></p>
                @endif
            </div>
            <div class="jawaban-siswa mt-2">
                <p class="mb-2"><b>Jawaban</b></p>
                {!!$jawaban !!}
            </div>
        @endif

    </div>
    @if ($getJawaban === null || $getJawaban->selesai == false || $getJawaban->status == "revisi")
        <div class="body-contain-customize col-12 mt-3">
            <p>Setelah Jawaban Sudah Final, Pastikan Jawaban sudah tersimpan baru meng-klik tombol Kumpulkan Jawaban</p>
            <button class="btn btn-sm btn-primary kumpul-jawaban" @if($jawaban === null) disabled @endif>
                <i class="fa fa-paper-plane"></i> Kumpulkan Jawaban
            </button>
        </div>
        @if($getJawaban !== null && $getJawaban->status == "revisi")
        <div class="body-contain-customize col-12 col-sm-12 col-md-10 col-lg-5 col-xl-5 mt-3">
            <table class="table table-striped fs-12">
                <tbody>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td>{{$getJawaban->status == "belum" ? "Belum Dinilai" : "Sudah Dinilai"}}</td>
                    </tr>
                    <tr>
                        <td>Nilai</td>
                        <td>:</td>
                        <td>{{$getJawaban->status == "belum" ? "-" : $getJawaban->nilai}}</td>
                    </tr>
                    <tr>
                        <td>Komentar</td>
                        <td>:</td>
                        <td>{{$getJawaban->komentar}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    @else
        <div class="body-contain-customize col-12 col-sm-12 col-md-10 col-lg-5 col-xl-5 mt-3">
            <table class="table table-striped fs-12">
                <tbody>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td>{{$getJawaban->status == "belum" ? "Belum Dinilai" : "Sudah Dinilai"}}</td>
                    </tr>
                    <tr>
                        <td>Nilai</td>
                        <td>:</td>
                        <td>{{$getJawaban->status == "belum" ? "-" : $getJawaban->nilai}}</td>
                    </tr>
                    <tr>
                        <td>Komentar</td>
                        <td>:</td>
                        <td>{{$getJawaban->komentar}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif
@endif
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
    @if ($classroom->token !== "XXXX")
        document.addEventListener("visibilitychange", function() {
            if (document.visibilityState === 'visible') {
                // alert('masuk');
            } else {
                var url = "{{route('classroom.siswa.detectOut',':id')}}";
                url = url.replace(':id','{{$siswa_aktif->uuid}}');
                var backRoute = "{{route('classroom.siswa.show',':id')}}";
                backRoute = backRoute.replace(':id','{{$classroom->id_ngajar}}');
                $.ajax({
                    type: "POST",
                    url : url,
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        cAlert("red","Perhatian","<p><b>ANDA TERDETEKSI KELUAR</b></p><p>Hubungi Guru Mengajar Untuk Mereset Akses</p>",false,backRoute);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }
        });
    @endif
    $('.buka-modal').click(function() {
        var file = $(this).data('file');
        var newPath = "{{asset('/storage/classroom/teacher/'.':id')}}";
        newPath = newPath.replace(':id',file);
        var url = "{{asset('/js/pdfjs/web/viewer.html?file=:id')}}";
        url = url.replace(':id',newPath);
        $('#pdf-reader').html('<iframe src="'+url+'" style="width:100%; height:100%;" frameborder="0"></iframe>');
        $('#showFileModal').modal("show");

    });
    @if($classroom->jenis == "latihan")
        $('.simpan-jawaban').click(function() {
            var jawaban = tinymce.get("deskripsiMateri").getContent();
            var idClassroom = "{{$classroom->uuid}}";
            if(jawaban !== "") {
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{route('classroom.siswa.create')}}",
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    data: {jawaban : jawaban, idClassroom : idClassroom},
                    success: function(data) {
                        cAlert('green','Sukses','Jawaban Sukses Disimpan',true);
                        removeLoading();
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            } else {
                oAlert("orange","Perhatian","Jawaban Tidak Boleh Kosong");
            }
        });
        $('.kumpul-jawaban').click(function() {
            var idClassroom = "{{$classroom->uuid}}";
            var kumpulJawaban = () => {
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{route('classroom.siswa.submit')}}",
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    data: {idClassroom : idClassroom},
                    success: function(data) {
                        removeLoading();
                        cAlert("green","Berhasil","Jawaban Berhasil Tersimpan",true);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }

            cConfirm("Perhatian","Yakin untuk mengumpulkan jawaban ini, Jawaban yang dikumpulkan tidak bisa dikumpulkan ulang",kumpulJawaban);
        })
    @endif
</script>
@endsection
