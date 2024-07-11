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
    @if ($classroom->link !== "")
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
</script>
@endsection
