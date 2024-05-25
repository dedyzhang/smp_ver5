@extends('layouts.main')

@section('container')
@if ($user->token == 1)
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                        <img src="{{asset('img/change-password.gif')}}" alt="" class="w-100">
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-7 col-xl-7 align-self-xl-center align-self-lg-center">
                        <h3><b>Ganti Password</b></h3>
                        <p style="text-align: justify">Mohon lakukan penggantian password dengan memperhatikan ketentuan berikut: password baru Anda harus terdiri dari minimal delapan karakter dan mengandung setidaknya satu huruf kapital, satu angka, dan satu simbol khusus seperti @, #, $, %, ^, &, *, atau ! untuk meningkatkan keamanan akun Anda.</p>
                        <form class="form-group" id="form-validation">
                            <div class="has-validation">
                                <label for="password" class="mb-2 form-label">Password Baru</label>
                                <input type="password" name="password" id="password" class="form-control">
                                <div class="invalid-feedback password-1">
                                    Password Harus Mengandung 8 Karakter atau lebih dengan gabungan huruf, huruf Kapital, Nomor dan Simbol.
                                </div>
                            </div>
                            <div class="has-validation">
                                <label for="password-confirm" class="mb-2 form-label">Konfirmasi Password</label>
                                <input type="password" name="password-confirm" id="password-confirm" class="form-control">
                                <div class="invalid-feedback password-2">
                                    Konfirmasi Password Harus sama dengan password
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light submit-form">Ganti Password</button>
            </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            const myModalAlternative = new bootstrap.Modal('#staticBackdrop');
            myModalAlternative.show();
        });

        $('.submit-form').click(function(event) {
            event.preventDefault();
            var password1 = $('#password').val();
            var password2 = $('#password-confirm').val();
            var test = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{6,64}$/i;
            var myform = document.getElementById("form-validation");
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
                    url: '/ganti-password',
                    headers: {'X-CSRF-TOKEN': token},
                    data: {
                        password: password1
                    },
                    success: function(data) {
                        setTimeout(() => {
                            removeLoading();
                            oAlert("green","Sukses","Password berhasil diganti");
                            $('#staticBackdrop').modal("hide");

                        }, 500);
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
@endif
{{Breadcrumbs::render('home')}}
<div class="body-contain-customize col-md-12 col-lg-12 col-sm-12">
    <h6>Home Page</h6>
</div>
@endsection

