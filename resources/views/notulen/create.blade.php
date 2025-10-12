@extends('layouts.main')

@section('container')
    <div class="col-12 body-contain-customize">
        <h5>Notulen Rapat</h5>
        <p>Halaman ini diperuntukkan Guru yang ditunjuk, Admin, Kurikulum maupun kepala sekolah untuk membuat, melihat, mengupdate dan mencetak Notulen Hasil Rapat</p>
    </div>
    <div class="col-12 body-contain-customize mt-3">
        <p><b>Form Penambahan Notulen Rapat</b></p>
        <div class="form-group col-12">
            <label for="pokok_pembahasan" class="form-label">Pokok Pembahasan</label>
            <textarea class="tinymce-select validate" id="pokok_pembahasan"></textarea>
        </div>
        <div class="form-group col-12 mt-3">
            <label for="hasil_rapat" class="form-label">Hasil Rapat</label>
            <textarea class="tinymce-select validate" id="hasil_rapat"></textarea>
        </div>
        <div class="col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-block col-lg-auto d-lg-block col-xl-auto d-xl-block mt-5">
            <button class="btn btn-sm btn-primary simpan-notulen"><i class="fas fa-save"></i> Simpan</button>
        </div>
    </div>
    <script>
        $('.simpan-notulen').click(function() {
            var countError = 0;
            var formdata = new FormData();
            $('.validate').each(function() {
                var id = $(this).attr('id');
                var myContent = tinymce.get(id).getContent();
                if(myContent == "") {
                    $(this).addClass('is-invalid');
                    if($(this).closest('.form-group').find('.invalid-feedback').length > 0) {
                        $(this).closest('.form-group').find('.invalid-feedback').html('Wajib Diisi');
                    } else {
                        $(this).closest('.form-group').append('<div class="invalid-feedback">Wajib Diisi</div>');
                    }
                    countError++;
                } else {
                    var nameValue = $(this).attr('id');
                    formdata.append(nameValue,myContent);
                    if($(this).hasClass('is-invalid')) {
                        $(this).removeClass('is-invalid');
                        $(this).closest('.invalid-feedback').remove();
                    }
                }
            });
            if(countError > 0) {
                oAlert('red','Perhatian','Terdapat '+countError+' isian yang wajib diisi, silahkan lengkapi terlebih dahulu.');
            } else {
                var simpanNotulen = function() {
                    loading();
                    $.ajax({
                        method: "POST",
                        url: "{{ route('notulen.store') }}",
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success : function(data) {
                            removeLoading();
                            cAlert('green','Sukses','Notulen Rapat Berhasil Disimpan',false,"{{ route('notulen.index') }}");
                        },
                        error: function(data) {
                            console.log(data.responseJSON.message);
                        }
                    });
                }
                cConfirm("Perhatian","Apakah anda yakin untuk menyimpan notulen rapat ini?",simpanNotulen);
            }
        });
    </script>
@endsection
