@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('classroom-create',$ngajar,$jenis)}}
    <div class="body-contain-customize col-12">
        @if ($jenis == 'materi')
            <h5><b>Tambah Materi Pembelajaran</b></h5>
        @else
            <h5><b>Tambah Latihan Pembelajaran</b></h5>
        @endif
    </div>
    @if ($jenis == 'materi')
        <div class="body-contain-customize col-12 mt-3">
            <p><b>A. Detail Materi Pembelajaran</b></p>
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-8 col-xl-8">
                    <label for="judul">Judul Materi</label>
                    <input type="text" name="judul" id="judul" class="form-control validate" placeholder="Masukkan Judul Materi">
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <label for="kelas">Kelas Yang Dituju</label>
                    <select class="form-control validate" data-toggle="select" name="kelas" id="kelas" multiple placeholder="Masukkan Kelas Yang Dituju">
                        <option value="">Pilih Kelas Yang Dituju</option>
                        @foreach ($list_ngajar as $element)
                            <option
                                @if ($element->uuid == $ngajar->uuid)
                                    selected
                                @endif
                            value="{{$element->uuid}}">{{$element->pelajaran_singkat." ".$element->tingkat.$element->kelas}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-8 col-xl-8">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control validate" name="deskripsi" rows="4" id="deskripsi" placeholder="Masukkan Deskripsi Pembelajaran"></textarea>
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <label for="token">Kunci Layar</label>
                    <select class="form-control validate" data-toggle="select" name="token" id="token" >
                        <option value="tidak">tidak</option>
                        <option value="iya">iya</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="body-contain-customize col-12 mt-3">
            <p><b>B. Isi Materi Pembelajaran</b></p>
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <label for="file">Upload File</label>
                    <p>File Boleh diupload lebih dari 1, Maksimal 5. Gunakan (ctrl + klik) kiri mouse untuk memilih file lebih dari 1</p>
                    <input type="file" name="file" id="file" class="file-input" multiple class="form-control" placeholder="Masukkan Judul Materi">
                </div>
            </div>
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <label for="link">Link Youtube</label>
                    <input type="text" name="link" id="link" class="form-control" placeholder="Masukkan Link Youtube">
                </div>
            </div>
        </div>
        <div class="body-contain-customize col-12 mt-3">
            <p><b>C. Deskripsi Materi Pembelajaran</b></p>
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <label for="deskripsiMateri">Deskripsi Materi</label>
                    <textarea class="tinymce-select" id="deskripsiMateri"></textarea>
                </div>
            </div>
        </div>
        <div class="body-contain-customize col-12 mt-3">
            <button class="btn btn-sm btn-primary save-materi"><i class="fas fa-save"></i> Save Materi</button>
        </div>
    @else

    @endif
    <script>
        $('.save-materi').click(function() {
            var countError = 0;
            var formdata = new FormData();
            $('.validate').each(function() {
                if($(this).val() == "") {
                    $(this).addClass('is-invalid');
                    if($(this).closest('.form-group').find('.invalid-feedback').length > 0) {
                        $(this).closest('.form-group').find('.invalid-feedback').html('Wajib Diisi');
                    } else {
                        $(this).closest('.form-group').append('<div class="invalid-feedback">Wajib Diisi</div>');
                    }
                    countError++;
                } else {
                    var nameValue = $(this).attr('id');
                    formdata.append(nameValue,$(this).val());
                    if($(this).hasClass('is-invalid')) {
                        $(this).removeClass('is-invalid');
                        $(this).closest('.invalid-feedback').remove();
                    }
                }
            });
            if(countError == 0) {
                var adaFile = "tidak";
                BigLoading('Aplikasi sedang mengupload data Classroom, Mohon untuk menunggu');
                for (var i = 0; i < $('#file').get(0).files.length; ++i) {
                    formdata.append('files[]', document.getElementById('file').files[i]);
                    adaFile = "ada";
                }
                formdata.append('adafile',adaFile);
                formdata.append('link',$('#link').val());
                var myContent = tinymce.get("deskripsiMateri").getContent();
                formdata.append('isi',myContent);
                formdata.append('status','save');

                var route = "{{route('classroom.store',['uuid' => ':id','jenis' => 'materi'])}}";
                var backRoute = "{{route('classroom.show',':id')}}";
                route = route.replace(':id','{{$ngajar->uuid}}');
                backRoute = backRoute.replace(':id','{{$ngajar->uuid}}');
                $.ajax({
                    type: "post",
                    url: route,
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    data: formdata,
                    contentType: false,
                    processData: false,
                    enctype: 'multipart/form-data',
                    success: function(data) {
                        if(data.success == true) {
                            removeLoadingBig();
                            cAlert("green","Berhasil","Classroom Berhasil Disimpan",false,backRoute);
                        }
                    },
                    error: function(data) {
                        removeLoadingBig();
                        console.log(data.responseJSON.message);
                    }

                })
            } else {
                oAlert('orange','Perhatian','Data Poin A tidak boleh ada yang kosong');
            }

        });
    </script>
@endsection
