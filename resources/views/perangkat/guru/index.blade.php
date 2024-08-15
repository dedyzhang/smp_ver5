@extends('layouts.main')

@section('container')
    <div class="col-12 body-contain-customize">
        <h5>Perangkat Guru</h5>
        <p>Halaman Ini untuk menampilkan perangkat guru yang sudah diupload</p>
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5 mt-3">
        <p><b>Data Guru</b></p>
        <table class="table table-striped fs-12">
            <tr>
                <td width="30%">Nama Guru</td>
                <td width="5%">:</td>
                <td>{{$guru->nama}}</td>
            </tr>
            <tr>
                <td width="30%">NIK</td>
                <td width="5%">:</td>
                <td>{{$guru->nik}}</td>
            </tr>
            <tr>
                <td width="30%">Access</td>
                <td width="5%">:</td>
                <td>{{$guru->users->access}}</td>
            </tr>
        </table>
    </div>
    <div class="clearBoth"></div>
    <div class="body-contain-customize col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex mt-3">
        <a href="{{route('perangkat.guru.zip',$guru->uuid)}}" class="btn btn-sm btn-warning">
            <i class="fa-solid fa-file-zipper"></i> Download Zip Perangkat
        </a>
    </div>
    @if (session('success'))
    <div class="body-contain-customize mt-3 col-12">
        <div class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert">
            <i class="bi flex-shrink-0 me-2 fa-solid fa-check" aria-label="Success:"></i>
            <div>
                <strong>Sukses !</strong> {{session('success')}}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif
    @foreach ($perangkat as $item)
        <div class="body-contain-customize col-12 mt-3">
            <p class="fs-13"><b>{{$item->perangkat}}</b></p>
            <form action="{{route('perangkat.guru.upload',['uuid' => $guru->uuid, 'uuidPerangkat' => $item->uuid])}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row m-0 p-0 mt-2">
                    <div class="form-group col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <label class="mb-2" for="perangkat">Upload Perangkat</label>
                        <input type="file" class="file-input-perangkat" name="perangkat" id="perangkat">
                    </div>
                    <div class="button-place">
                        <button type="submit" class="btn btn-sm btn-warning text-warning-emphasis">
                            <i class="fa-regular fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
            @if (isset($perangkat_array[$item->uuid.".".$guru->uuid]))
                <p class="fs-13 mt-4">Perangkat Yang Diupload</p>
                <div class="row m-0 p-0 mt-3">
                    @foreach ($perangkat_array[$item->uuid.".".$guru->uuid] as $element)
                        @php
                            $namaFile = explode('_',$element['file'])[1];
                        @endphp
                        <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <div class="card border-1 rounded-2 shadow-none">
                                <div class="card-body">
                                    <img class="mx-auto d-block" style="width:70px;height:auto" src="{{asset('img/folder.png')}}" alt="">
                                    <p class="fs-11 mt-3">{{$namaFile}}</p>
                                    <div class="button-place">
                                        <button class="btn btn-sm btn-success lihat-file" data-file="{{$element['file']}}">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                        <a href="{{route('perangkat.guru.download',$element['uuid'])}}" class="btn btn-sm btn-warning fa-warning-emphasis">
                                            <i class="fas fa-file-arrow-down"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger delete-file" data-uuid="{{$element['uuid']}}">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    @endforeach
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
        $(".file-input-perangkat").fileinput({
            showUpload: false,
            maxFileSize: 20000,
            dropZoneEnabled: false,
            allowedFileExtensions: ["pdf"],
        });
        $('.lihat-file').click(function() {
            var file = $(this).data('file');
            var newPath = "{{asset('storage/:id')}}";
            newPath = newPath.replace(':id',file);
            console.log(file);
            var url = "{{asset('/js/pdfjs/web/viewer.html?file=:id')}}";
            url = url.replace(':id',newPath);
            $('#pdf-reader').html('<iframe src="'+url+'" style="width:100%; height:100%;" frameborder="0"></iframe>');
            $('#showFileModal').modal("show");

        });
        $('.delete-file').click(function() {
            var uuid = $(this).data('uuid');

            var hapusPerangkat = () => {
                var url = "{{route('perangkat.guru.delete',':id')}}";
                url = url.replace(':id',uuid);
                loading();
                $.ajax({
                    type: "delete",
                    url: url,
                    headers: {'X-CSRF-TOKEN' : '{{csrf_token()}}'},
                    success: function(data) {
                        removeLoading();
                        cAlert('green','Sukses','Data Berhasil Dihapus',true);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }

            cConfirm("Perhatian","Yakin untuk menghapus perangkat ini",hapusPerangkat);
        });
    </script>
@endsection
