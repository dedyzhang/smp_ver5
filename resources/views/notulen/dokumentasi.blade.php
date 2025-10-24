@extends('layouts.main')

@section('container')
    {{ Breadcrumbs::render('notulen-dokumentasi',$notulen) }}
    <div class="col-12 body-contain-customize">
        <h5>Notulen Rapat</h5>
        <p>Halaman ini diperuntukkan Guru yang ditunjuk, Admin, Kurikulum maupun kepala sekolah untuk membuat, melihat, mengupdate dan mencetak Notulen Hasil Rapat</p>
    </div>
     <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-5 col-xl-5">
        <table class="table table-striped fs-12">
            <tr>
                <td width="35%">Hari</td>
                <td width="3%">:</td>
                <td width="62%">{{date('l',strtotime($notulen->tanggal_rapat))}}</td>
            </tr>
             <tr>
                <td width="35%">Tanggal</td>
                <td width="3%">:</td>
                <td width="62%">{{date('d M Y',strtotime($notulen->tanggal_rapat))}}</td>
            </tr>
        </table>
    </div>
    <div class="col-12 body-contain-customize mt-3">
        <p>Form Upload Dokumentasi Rapat</p>
        <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <label for="file">Upload File</label>
            <p>File Boleh diupload lebih dari 1, Maksimal 5. Jika Menggunakan Laptop, Gunakan (ctrl + klik) kiri mouse untuk memilih file lebih dari 1</p>
            <input type="file" name="file" id="file" class="file-input-gambar" multiple class="form-control" placeholder="Masukkan Judul Materi">
        </div>
        <button class="btn btn-sm btn-primary mt-2 upload-gambar"><i class="fas fa-upload"></i> Upload</button>
    </div>
    <div class="col-12 body-contain-customize mt-3">
        <div class="row m-0 p-0">
            @if ($notulen->dokumentasi != null)
                @foreach ($gambar as $item)
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4 mb-2">
                        <img src="{{asset("storage/notulen/")."/".date('d M Y',strtotime($notulen->tanggal_rapat))."/".$item}}" class="img-fluid img-thumbnail mb-1" alt="dokumentasi">
                        <div class="d-grid gap-2">
                            <button class="btn btn-sm btn-danger hapus-dokumentasi" data-uuid="{{ $notulen->uuid }}" data-gambar="{{ $item }}"><i class="fas fa-trash"></i> Hapus Gambar</button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <script>
        $('.upload-gambar').on('click',function() {
            var uploadGambar = function() {
                BigLoading('Aplikasi sedang mengupload data Gambar Notulen Rapat, Mohon untuk menunggu');
                var formdata = new FormData();
                 for (var i = 0; i < $('#file').get(0).files.length; ++i) {
                    formdata.append('files[]', document.getElementById('file').files[i]);
                }
                var url = "{{ route('notulen.dokumentasi.store',':id') }}";
                url = url.replace(':id','{{ $notulen->uuid }}');
                $.ajax({
                    type: "POST",
                    url : url,
                    data : formdata,
                    processData: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success : function(data) {
                        if(data.status == 'success') {
                            removeLoadingBig();
                            cAlert('green','Sukses','Dokumentasi Notulen Rapat Berhasil diupload',true);
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
            cConfirm("Perhatian","Apakah anda yakin ingin mengupload file ini?",uploadGambar);
        });
        $('.hapus-dokumentasi').on('click',function() {
            var uuid = $(this).data('uuid');
            var gambar = $(this).data('gambar');
            var hapusDokumentasi = function() {
                BigLoading('Aplikasi sedang menghapus data Gambar Notulen Rapat, Mohon untuk menunggu');
                var url = "{{ route('notulen.dokumentasi.delete',':id') }}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "DELETE",
                    url : url,
                    data : {gambar : gambar},
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success : function(data) {
                        if(data.status == 'success') {
                            removeLoadingBig();
                            cAlert('green','Sukses','Dokumentasi Notulen Rapat Berhasil dihapus',true);
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
            cConfirm("Perhatian","Apakah anda yakin ingin menghapus gambar dokumentasi ini?",hapusDokumentasi);
        });
    </script>
@endsection