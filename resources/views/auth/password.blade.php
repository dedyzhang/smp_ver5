@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('ganti-password')}}
    <div class="body-contain-customize col-12">
        <h5><b>Ganti Password</b></h5>
        <p>Halaman ini diperuntukkan user untuk mengganti password</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p>Halaman Perubahan Password</p>
        <div class="row m-0 p-0">
            <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 form-group mt-2">
                <label for="password-lama">Password Lama</label>
                <input type="password" name="password-lama" id="password-lama" class="form-control" placeholder="Masukkan password lama anda">
                <div class="invalid-feedback password-3">
                    Password Lama yang dimasukkan salah
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 form-group mt-2">
                <label for="password">Password Baru</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password baru anda">
                <div class="invalid-feedback password-1">
                    Password Harus Mengandung 8 Karakter atau lebih dengan gabungan huruf, huruf Kapital, Nomor dan Simbol.
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 form-group mt-2">
                <label for="password-confirm">Konfirmasi Password</label>
                <input type="password" name="password-confirm" id="password-confirm" class="form-control" placeholder="Masukkan sekali lagi password baru anda">
                <div class="invalid-feedback password-2">
                    Konfirmasi Password Harus sama dengan password
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto mt-4 d-grid d-sm-grid d-md-block d-lg-block d-xl-block">
                <button class="btn btn-sm btn-warning text-warning-emphasis simpan-password"><i class="fa-solid fa-key"></i> Simpan Password</button>
            </div>
        </div>
    </div>
    <script>
        $('.simpan-password').click(function(event) {
            var password1 = $('#password').val();
            var password2 = $('#password-confirm').val();
            var password3 = $('#password-lama').val();
            var test = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{6,64}$/i;
            var error = 0;
            $('input[type="password"]').each(function() {
                if($(this).val() == "") {
                    $(this).addClass('is-invalid').removeClass('is-valid');
                    $(this).closest('.invalid-feedback').css('display','block');
                    error++;
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                    $(this).closest('.invalid-feedback').css('display','none');
                }
            })
            if(password1.length < 8 || password1.search(/[a-z]/) < 0 || password1.search(/[A-Z]/) < 0 || password1.search(/[0-9]/) < 0 || password1.match(test) == null) {
                $('#password').addClass('is-invalid').removeClass('is-valid');
                $('.password-1').css('display','block');
                error++;
            } else {
                $('#password').removeClass('is-invalid').addClass('is-valid');
                $('.password-1').css('display','none');
            }
            if ( password1 != password2 || password2 == "" ) {
                $('#password-confirm').addClass('is-invalid').removeClass('is-valid');
                $('.password-2').css('display','block');
               error++;
            } else {
                $('#password-confirm').removeClass('is-invalid').addClass('is-valid');
                $('.password-2').css('display','none');
            }
            var changePassword = () => {
                loading();
                var token = '{{csrf_token()}}';
                $.ajax({
                    type: "POST",
                    url: '/ganti-password-request',
                    headers: {'X-CSRF-TOKEN': token},
                    data: {
                        passwordLama : password3,
                        password: password1
                    },
                    success: function(data) {
                        removeLoading();
                        if(data.success === true) {
                            cAlert("green","Berhasil","Password berhasil diganti",true);
                            $('#password-lama').removeClass('is-invalid').addClass('is-valid');
                            $('.password-3').css('display','none');
                        } else {
                            $('#password-lama').addClass('is-invalid').removeClass('is-valid');
                            $('.password-3').css('display','block');
                        }
                    },
                    error: function(data){
                        var errors = data.responseJSON;
                        console.log(errors);

                    }
                })
            }
            if(error == 0) {
                cConfirm("Sukses","Apakah kamu yakin untuk mengubah password",changePassword)
            }
        })
    </script>
@endsection
