@extends('layouts.main')

@section('container')
{{Breadcrumbs::render('classroom-siswa-show',$ngajar->pelajaran,$ngajar->kelas,$ngajar)}}
<div class="body-contain-customize col-12">
    <h5><b>Classroom</b></h5>
    <p>Halaman Classroom Siswa. Siswa dapat mengakses pembelajaran serta latihan didalam halaman ini</p>
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
<div id="aspect-content">
    @foreach ($classroom as $item)
    <div class="aspect-tab">
        <input id="item-{{$item->uuid}}" type="checkbox" class="aspect-input" name="aspect">
        <label for="item-{{$item->uuid}}" class="aspect-label"></label>
        <div class="aspect-content">
            <div class="aspect-info">
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
                        <div class="opinion-header fs-10 text-danger">TANGGAL POST : {{date('d F Y,
                            H:i:s',strtotime($item->tanggal_post))}}</div>
                        <div>
                            <div>{{$item->deskripsi}}</div>
                            <div class="button-place mt-3 gap-2 d-grid d-sm-grid d-md-flex d-xl-flex d-lg-flex">
                                @if ($item->token === "XXXX")
                                <a href="{{route('classroom.siswa.preview',['uuid' => $ngajar->uuid,'uuidClassroom' => $item->uuid])}}"
                                    class="btn btn-sm btn-light">
                                    <i class="fas fa-pencil"></i> Buka Classroom
                                </a>
                                @else
                                <button class="btn btn-sm btn-light open-token" data-uuid="{{$item->uuid}}">
                                    <i class="fas fa-paper-plane"></i> Buka Classroom
                                </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="modal fade in" id="modal-preview-classroom">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title">Buka Classroom</p>
                <button class="btn btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row m-0 p-0">
                    <div class="col-12 alert-place">

                    </div>
                    <input type="hidden" id="uuidClassroom">
                    <div class="col-12 form-group fs-12">
                        <label for="token">Masukkan Token Classroom</label>
                        <input type="text" class="form-control" id="token" placeholder="masukkan Token">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-light submit-token">
                    <i class="fas fa-save"></i> Submit
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $('.open-token').click(function() {
            var uuid = $(this).data('uuid');
            $('#uuidClassroom').val(uuid);
            $('#modal-preview-classroom').modal("show");
        });
        $('.submit-token').click(function() {
            var uuid = $('#uuidClassroom').val();
            var token = $('#token').val();
            $('.alert-place').html("");
            $.ajax({
                type: "POST",
                url : "{{route('classroom.siswa.cekToken')}}",
                data: {uuid : uuid, token: token},
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                success: function(data) {
                    if(data.success == true) {
                        $('.alert-place').html('<div class="alert alert-success fs-12" role="alert"><b>Sukses !</b> Classroom Berhasil Diakses</div>');
                        var url = "{{route('classroom.siswa.preview',['uuid' => ':id','uuidClassroom' => ':id2'])}}";
                        url = url.replace(':id','{{$ngajar->uuid}}').replace(':id2',uuid);
                        window.location.href=url;
                    } else {
                        $('.alert-place').html('<div class="alert alert-danger fs-12" role="alert"><b>Error !</b> '+data.message+'</div>');
                    }
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        });
</script>
@endsection
