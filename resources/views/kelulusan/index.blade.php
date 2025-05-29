@extends('layouts.main')

@section('container')
    <div class="col-12 body-contain-customize">
        <h5>Kelulusan</h5>
        <p>Halaman ini diperuntukkan Admin, Kurikulum dan Kepala Sekolah untuk mengelola kelulusan siswa tahun pelajaran berlangsung</p>
    </div>
    <div class="col-12 body-contain-customize mt-3">
        @php
            if (isset($pelajaran_kelulusan) && $pelajaran_kelulusan->nilai !== null) {
                $mapelKelulusan = explode(',',$pelajaran_kelulusan->nilai);
                $jumlah = count($mapelKelulusan);
            } else {
                $jumlah = 0;
            }
        @endphp
        <div class="table-responsive">
            <table class="table table-bordered fs-12">
            <thead>
                <tr>
                    <td rowspan="2" width="3%">No</td>
                    @if ($jumlah > 0)
                        <td rowspan="2" width="20%">Nama</td>
                        <td rowspan="2" witdh="5%">Kelas</td>
                        <td colspan="{{$jumlah}}" width="50%">Pelajaran</td>
                    @else
                        <td rowspan="2" width="70%">Nama</td>
                        <td rowspan="2" witdh="5%">Kelas</td>
                    @endif
                    <td rowspan="2" width="5%">L/TL</td>
                    <td rowspan="2" width="10%">#</td>
                </tr>
                <tr>
                    @if (isset($pelajaran_kelulusan) && $pelajaran_kelulusan->nilai !== null)
                        @foreach ($pelajaran as $mapel)
                            @if (in_array($mapel->uuid,$mapelKelulusan))
                                <td width="5%">{{$mapel->pelajaran_singkat}}</td>
                            @endif
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($siswa as $item)
                    @php
                        $nilai = $kelulusan->first(function($elem) use($item) {
                            if($elem->id_siswa == $item->uuid) {
                                return $elem;
                            }
                        });
                        if(isset($nilai) && $nilai->uuid !== null) {
                            $nilaiKelulusan = unserialize($nilai->nilai);
                        } else {
                            $nilaiKelulusan = array();
                        }
                    @endphp
                    <tr data-siswa="{{$item->uuid}}">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->nama}}</td>
                        <td>{{$item->kelas->tingkat.$item->kelas->kelas}}</td>
                        @if (isset($pelajaran_kelulusan) && $pelajaran_kelulusan->nilai !== null)
                            @foreach ($pelajaran as $mapel)
                                @if (in_array($mapel->uuid,$mapelKelulusan))
                                    <td contenteditable="true" class="editable nilai" data-uuid="{{$mapel->uuid}}">
                                        {{isset($nilaiKelulusan) && isset($nilaiKelulusan[$mapel->uuid]) ? $nilaiKelulusan[$mapel->uuid] : 0}}
                                    </td>
                                @endif
                            @endforeach
                        @endif
                        <td>
                            <div class="form-check form-switch">
                                <input {{isset($nilai) && $nilai->kelulusan !== null && $nilai->kelulusan == "true" ? "checked" : ""}} class="form-check-input switch-kelulusan" type="checkbox" role="switch" id="#switch-{{$item->uuid}}">
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-success simpan-nilai" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Simpan Nilai"><i class="fas fa-save"></i></button>
                            <button class="btn btn-sm btn-warning upload-file" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Upload File" data-siswa="{{$item->uuid}}" data-file="{{isset($nilai) && $nilai->file !== null ? $nilai->file : "" }}"><i class="fas fa-upload"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
    <div class="modal modal-lg fade in" id="modalUploadFile">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="fs-10">Upload Surat Keterangan untuk siswa. File Berbentuk PDF dengan Maks File 2MB</p>
                    <div class="form-group mb-3">
                        <input type="hidden" name="idSiswa" id="idSiswa">
                        <label for="formFileSm" class="form-label">Upload Surat Keterangan</label>
                        <input class="form-control form-control-sm" id="formFileSm" type="file">
                    </div>
                    <div id="pdf-reader"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary upload-button">Upload</button>
                    <a class="btn btn-danger hapus-upload d-none">Hapus File</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.nilai.editable').on('focus',function(e) {
            $(this).selectText();
        });
        $('.nilai.editable').on('focusout',function(){
            if($(this).is(':empty')) {
                $(this).html(0);
            } else if(parseInt($(this).text()) > 100) {
                $(this).html(100);
            }
        });
        $('.nilai.editable').on('keydown',function(e) {
            ini = this;
            if(e.keyCode == 13 || e.keyCode == 40) {
                var cell = $(this);
                var index = cell.closest('td').index();

                cell.closest('tr').next().find('td').eq(index).focus();
                e.preventDefault();
            } else if(e.keyCode == 38) {
                var cell = $(this);
                var index = cell.closest('td').index();
                cell.closest('tr').prev().find('td').eq(index).focus();
                e.preventDefault();
            } else if(e.keyCode == 39) {
                var cell = $(this);
                var nextCell = cell.closest('td').index() + 1;
                if(cell.closest('tr').find('td').eq(nextCell).hasClass('editable')) {
                    cell.closest('tr').find('td').eq(nextCell).focus();
                } else {
                    nextCell = nextCell + 1;
                    cell.closest('tr').find('td').eq(nextCell).focus();
                }
                e.preventDefault();
            } else if(e.keyCode == 37) {
                var cell = $(this);
                var nextCell = cell.closest('td').index() - 1;
                if(cell.closest('tr').find('td').eq(nextCell).hasClass('editable')) {
                    cell.closest('tr').find('td').eq(nextCell).focus();
                } else {
                    nextCell = nextCell - 1;
                    cell.closest('tr').find('td').eq(nextCell).focus();
                }
                e.preventDefault();
            }
        });
        $('.nilai.editable').on('keypress',function(e) {
            if((charCode >=45 &&  charCode <= 46) || (charCode >= 48 && charCode <= 57)) {
                e.preventDefault();
            }
        });
        $('.simpan-nilai').click(function(){
            var idSiswa = $(this).closest('tr').data('siswa');
            var arrayNilai = [];
            loading();
            $(this).closest('tr').find('.nilai').each(function() {
                var nilai = $(this).text();
                var uuidPelajaran = $(this).data('uuid');
                arrayNilai.push({
                    uuid : uuidPelajaran,
                    nilai: nilai
                });
            });
            var tl = $(this).closest('tr').find('.switch-kelulusan').is(':checked');

            var url = "{{route('penilaian.kelulusan.store',':id')}}";
            url = url.replace(':id',idSiswa);

            $.ajax({
                type: "post",
                url: url,
                data: {nilai: arrayNilai, kelulusan: tl},
                headers: {'X-CSRF-TOKEN' : "{{csrf_token()}}"},
                success: function(data) {
                    if(data.success) {
                        removeLoading();
                    }

                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        });
        $('.upload-file').click(function(){
            loading();
            var idSiswa = $(this).closest('tr').data('siswa');
            var file = $(this).data('file');
            //Tampilkan File kedalam pdf
            if(file != null && file != "") {
                var newPath = "{{asset('/storage/skl/'.':id')}}";
                newPath = newPath.replace(':id',file);
                var url = "{{asset('/js/pdfjs/web/viewer.html?file=:id')}}";
                url = url.replace(':id',newPath);
                $('#pdf-reader').html('<iframe src="'+url+'" style="width:100%; height:400px;" frameborder="0"></iframe>');
                $('.hapus-upload').addClass('d-inline').removeClass('d-none');
            } else {
                $('#pdf-reader').html("");
                $('.hapus-upload').removeClass('d-inline').addClass('d-none');
            }

            $('#idSiswa').val(idSiswa);
            $('#modalUploadFile').modal('show');
            removeLoading();
        });
        $('.hapus-upload').click(function(){
            var idSiswa = $('#idSiswa').val();
            var url = "{{route('penilaian.kelulusan.file.hapus',':id')}}";
            url = url.replace(':id',idSiswa);
            loading();
            $.ajax({
                type: "post",
                url: url,
                headers: {'X-CSRF-TOKEN' : "{{csrf_token()}}"},
                success: function(data) {
                    if(data.success) {
                        removeLoading();
                        $('#modalUploadFile').modal('hide');
                        $('.upload-file[data-siswa="'+idSiswa+'"]').data('file','');
                    }
                }
            })
        });
        $('#modalUploadFile').on('hidden.bs.modal', function () {
            $('#idSiswa').val('');
            $('#formFileSm').val('');
        });
        $('.upload-button').click(function(){
            var idSiswa = $('#idSiswa').val();
            var file = $('#formFileSm')[0].files[0];
            if(file.name.split('.').pop() != 'pdf') {
                oAlert("orange","Perhatian","File yang diupload harus berbentuk PDF")
            } else if(file.size > 2000000) {
                oAlert("orange","Perhatian","Maksimal File yang diupload adalah 2MB")
            } else {
                var formData = new FormData();
                formData.append('file',file);
                var url = "{{route('penilaian.kelulusan.upload',':id')}}";
                url = url.replace(':id',idSiswa);
                loading();
                $.ajax({
                    type: "post",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {'X-CSRF-TOKEN' : "{{csrf_token()}}"},
                    success: function(data) {
                        if(data.success) {
                            removeLoading();
                            $('#modalUploadFile').modal('hide');
                            $('.upload-file[data-siswa="'+idSiswa+'"]').data('file',data.file);
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
        });
    </script>
@endsection
