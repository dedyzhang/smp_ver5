@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('classroom-edit',$classroom,$ngajar)}}
    <div class="body-contain-customize col-12">
        @if ($classroom->jenis == 'materi')
            <h5><b>Edit Materi Pembelajaran</b></h5>
        @else
            <h5><b>Edit Latihan Pembelajaran</b></h5>
        @endif
    </div>
    @if ($classroom->jenis == 'materi')
        <div class="body-contain-customize col-12 mt-3">
            <p><b>A. Detail Materi Pembelajaran</b></p>
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-8 col-xl-8">
                    <label for="judul">Judul Materi</label>
                    <input type="text" value="{{$classroom->judul}}" name="judul" id="judul" class="form-control validate" placeholder="Masukkan Judul Materi">
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <label for="kelas">Kelas Yang Dituju</label>
                    <select class="form-control validate" data-toggle="select" name="kelas" id="kelas" multiple placeholder="Masukkan Kelas Yang Dituju">
                        <option value="">Pilih Kelas Yang Dituju</option>
                        @foreach ($list_ngajar as $element)
                            @php
                                if(in_array($element->uuid,$array_ngajar)) {
                                    $selected = "selected";
                                } else {
                                    $selected = "";
                                }
                            @endphp
                            <option {{$selected}} value="{{$element->uuid}}">{{$element->pelajaran_singkat." ".$element->tingkat.$element->kelas}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-8 col-xl-8">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control validate" name="deskripsi" rows="4" id="deskripsi" placeholder="Masukkan Deskripsi Pembelajaran">{{$classroom->deskripsi}}</textarea>
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <label for="token">Kunci Layar</label>
                    @php
                        if($classroom->token != "XXXX") {
                            $token = "iya";
                        } else {
                            $token = "tidak";
                        }
                    @endphp
                    <select class="form-control validate" data-toggle="select" name="token" id="token">
                        <option value="tidak" @if ($token == "tidak") selected @endif>tidak</option>
                        <option value="iya" @if ($token == "iya") selected @endif>iya</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="body-contain-customize col-12 mt-3">
            <p><b>List File dalam Materi</b></p>
        </div>
        @if(count($files) >= 1 && !empty($files[0]))
        <div class="row m-0 p-0 mt-3">
            @foreach ($files as $item)
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 p-0">
                    <div class="card rounded-4 m-2 border-0" data-path="{{$item}}">
                        <div class="card-body row m-0">
                            <div class="col-3">
                                <i class="fas fa-file fa-3x text-primary-emphasis"></i>
                            </div>
                            <div class="col-9">
                                <p class="card-title mb-0">Files {{$loop->iteration}}</p>
                                <div class="button-place">
                                    <button class="btn btn-sm btn-warning text-warning-emphasis lihat-file">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-can delete-file"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        @endif
        <div class="body-contain-customize col-12 mt-3">
            <p><b>Upload File Baru</b></p>
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
                    <input type="text" name="link" id="link" value="{{$classroom->link}}" class="form-control" placeholder="Masukkan Link Youtube">
                </div>
            </div>
        </div>
        <div class="body-contain-customize col-12 mt-3">
            <p><b>C. Deskripsi Materi Pembelajaran</b></p>
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <label for="deskripsiMateri">Deskripsi Materi</label>
                    <textarea class="tinymce-select" id="deskripsiMateri">{{$classroom->isi}}</textarea>
                </div>
            </div>
        </div>
        <div class="body-contain-customize col-12 mt-3">
            <button class="btn btn-sm btn-primary save-materi"><i class="fas fa-save"></i> Save Materi</button>
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
    @else
        <div class="body-contain-customize col-12 mt-3">
            <p><b>A. Detail Latihan Pembelajaran</b></p>
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-8 col-xl-8">
                    <label for="judul">Judul Latihan</label>
                    <input type="text" value="{{$classroom->judul}}" name="judul" id="judul" class="form-control validate" placeholder="Masukkan Judul Materi">
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <label for="kelas">Kelas Yang Dituju</label>
                    <select class="form-control validate" data-toggle="select" name="kelas" id="kelas" multiple placeholder="Masukkan Kelas Yang Dituju">
                        <option value="">Pilih Kelas Yang Dituju</option>
                        @foreach ($list_ngajar as $element)
                            @php
                                if(in_array($element->uuid,$array_ngajar)) {
                                    $selected = "selected";
                                } else {
                                    $selected = "";
                                }
                            @endphp
                            <option {{$selected}} value="{{$element->uuid}}">{{$element->pelajaran_singkat." ".$element->tingkat.$element->kelas}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-8 col-xl-8">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control validate" name="deskripsi" rows="4" id="deskripsi" placeholder="Masukkan Deskripsi Pembelajaran">{{$classroom->deskripsi}}</textarea>
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <label for="waktu">Tanggal & Jam Harus Selesai</label>
                    <input type="datetime-local" class="form-control validate" name="waktu" id="waktu" value="{{date('Y-m-d\Th:i',strtotime($classroom->tanggal_due))}}">
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <label for="token">Kunci Layar</label>
                    @php
                        if($classroom->token != "XXXX") {
                            $token = "iya";
                        } else {
                            $token = "tidak";
                        }
                    @endphp
                    <select class="form-control validate" data-toggle="select" name="token" id="token">
                        <option value="tidak" @if ($token == "tidak") selected @endif>tidak</option>
                        <option value="iya" @if ($token == "iya") selected @endif>iya</option>
                    </select>
                </div>
            </div>
        </div>
        @if(count($files) >= 1 && !empty($files[0]))
        <div class="body-contain-customize col-12 mt-3">
            <p><b>List File dalam Materi</b></p>
        </div>
        <div class="row m-0 p-0 mt-3">
            @foreach ($files as $item)
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 p-0">
                    <div class="card rounded-4 m-2 border-0" data-path="{{$item}}">
                        <div class="card-body row m-0">
                            <div class="col-3">
                                <i class="fas fa-file fa-3x text-primary-emphasis"></i>
                            </div>
                            <div class="col-9">
                                <p class="card-title mb-0">Files {{$loop->iteration}}</p>
                                <div class="button-place">
                                    <button class="btn btn-sm btn-warning text-warning-emphasis lihat-file">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-can delete-file"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
        <div class="body-contain-customize col-12 mt-3">
            <p><b>Upload File Baru</b></p>
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
                    <input type="text" name="link" id="link" value="{{$classroom->link}}" class="form-control" placeholder="Masukkan Link Youtube">
                </div>
            </div>
        </div>
        <div class="body-contain-customize col-12 mt-3">
            <p><b>C. Deskripsi Materi Pembelajaran</b></p>
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <label for="deskripsiMateri">Deskripsi Materi</label>
                    <textarea class="tinymce-select" id="deskripsiMateri">{{$classroom->isi}}</textarea>
                </div>
            </div>
        </div>
        <div class="body-contain-customize col-12 mt-3">
            <button class="btn btn-sm btn-primary save-materi"><i class="fas fa-save"></i> Save Materi</button>
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
    @endif
    <script>
        $('.save-materi').click(function() {
            var countError = 0;
            var formdata = new FormData();
            formdata.append('jenis','{{$classroom->jenis}}');
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

                var route = "{{route('classroom.update',['uuid' => ':id','uuidClassroom' => ':id2'])}}";
                route = route.replace(':id','{{$ngajar->uuid}}').replace(':id2','{{$classroom->uuid}}');
                $.ajax({
                    type: "post",
                    url: route,
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    data: formdata,
                    contentType: false,
                    processData: false,
                    enctype: 'multipart/form-data',
                    success: function(data) {
                        console.log(data);
                        if(data.success == true) {
                            removeLoadingBig();
                            cAlert("green","Berhasil","Classroom Berhasil Disimpan",true);
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
        $('.lihat-file').click(function() {
            var path = $(this).closest('.card').data('path');
            var newPath = "{{asset('/storage/classroom/teacher/'.':id')}}";
            newPath = newPath.replace(':id',path);
            var url = "{{asset('/js/pdfjs/web/viewer.html?file=:id')}}";
            url = url.replace(':id',newPath);
            $('#pdf-reader').html('<iframe src="'+url+'" style="width:100%; height:100%;" frameborder="0"></iframe>');
            $('#showFileModal').modal("show");
        });
        $('.delete-file').click(function() {
            var path = $(this).closest('.card').data('path');
            var newPath = "{{asset('/storage/classroom/teacher/'.':id')}}";
            newPath = newPath.replace(':id',path);

            var hapusFile = () => {
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{route('classroom.delete.file')}}",
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    data: {oldPath: path, newPath: newPath, idBahan : "{{$classroom->id_bahan}}"},
                    success: function(data) {
                        console.log(data);
                        setTimeout(() => {
                            removeLoading();
                            cAlert("green","Berhasil","File Berhasil Dihapus",true);
                        }, 500);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }
            cConfirm("Perhatian","Yakin untuk menghapus file ini",hapusFile);
        });
    </script>
@endsection
