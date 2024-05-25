<!DOCTYPE html>
<html lang="en">
<head>
@include('layouts.head')
</head>
<body>
    <div class="login-box">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 left-login">
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 p-xl-5 p-lg-5 p-md-4 p-sm-4 p-4 form-group">
                <img src="{{asset('img/Logo-rounded.png')}}" alt="" width="50" height="50" class="mb-3">
                <h4><b>LOGIN</b></h4>
                @if(session('LoginError'))
                    <div class="alert alert-warning alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert">
                        <i class="bi flex-shrink-0 me-2 fa-solid fa-triangle-exclamation" aria-label="Warning:"></i>
                        <div>
                            <strong>Warning !</strong> Login Gagal, Username dan Password Salah
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="form-group">
                    <form action="/login" method="post">
                        @csrf
                        <label class="mt-2 mb-1" for="username">NIS Siswa / NIK Guru</label>
                        <div class="input-group has-validation">
                            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" placeholder="Masukkan NIS Siswa / NIK Guru">
                            @error('username')
                                <div class="invalid-feedback">
                                    Username Wajib Diisi
                                </div>
                            @enderror
                        </div>
                        <label class="mt-2 mb-1" for="password">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan Password Akun">
                            @error('password')
                                <div class="invalid-feedback">
                                    Password Wajib Diisi
                                </div>
                            @enderror
                        </div>
                        <div class="form-check mt-3">
                            <input type="checkbox" name="remember_me" id="remember_me" class="form-check-input">
                            <label for="remember_me" class="form-check-label">Remember Me</label>
                        </div>
                        <div class="button-place d-grid gap-2 mt-3" style="width: 100%">
                            <button type="submit" class="btn btn-info btn-block">Login Ke Akun</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
@include('layouts.foot')
