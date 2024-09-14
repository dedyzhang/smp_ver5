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
 @php
    if($user->access != "siswa" && $user->access != "orangtua") {
        $namaGuru = explode(' ',$user->guru->nama);
        if(strlen($namaGuru[0]) <= 4) {
            $nama = $user->guru->nama;
        } else {
            $nama = $namaGuru[0];
        }
        $jk = $user->guru->jk;
    } else {
        if($user->access == "orangtua") {
            $namaSiswa = explode(' ',$account->siswa->nama);
            $nama = $namaSiswa[0]." Parent";
            $jk = $account->siswa->jk;
        } else {
            $namaSiswa = explode(' ',$account->nama);
            $nama = $namaSiswa[0];
            $jk = $account->jk;
        }
    }
@endphp
<div class="home-page-box {{$jk == "l" ? "boy" : "girl"}} col-md-12 col-lg-12 col-sm-12">
    <div class="info-box">
        <h6 class="nama">Hello, <span>{{$nama}}</span></h6>
        <p class="tanggal">Have a Nice {{date('l')}}</p>
        <div class="kotak-bantuan">
            <p>Butuh Bantuan Layanan ?</p>
            <a href="https://wa.me/6281277464404" class="btn btn-sm btn-success">
                <i class="fa-brands fa-whatsapp"></i>
            </a>
            <a href="https://ig.me/m/smpsmaitreyawiratpi" class="btn btn-sm btn-warning text-warning-emphasis">
                <i class="fa-brands fa-instagram"></i>
            </a>
            <a href="https://m.me/smpsmaitreyawira.tanjungpinang" class="btn btn-sm btn-primary">
                <i class="fa-brands fa-facebook"></i>
            </a>
        </div>
    </div>
</div>
<div class="row m-0 p-0 mt-3">
    @if ($user->access != "siswa" && $user->access != "orangtua")
        @if ($account->walikelas !== null)
            <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 pe-xl-3 col-12 col-sm-6 col-md-6 col-lg-5 col-xl-4">
                <div class="card rounded-4 border-0">
                    <div class="card-body">
                        <h5 class="fs-18"><b>Info Kelas</b></h5>
                        <canvas id="chart-kelas" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="p-0 mt-3 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0 col-12 col-sm-6 col-md-6 col-lg-7 col-xl-8">
                <div class="card rounded-4 border-0">
                    <div class="card-body">
                        <h5 class="fs-18"><b>Info Siswa</b></h5>
                        <div class="vertical-align" style="height:300px; overflow-y:auto;">
                            <table id="table-siswa" class="fs-12 mt-2 table table-striped">
                                <thead>
                                    <tr>
                                        <th width="10%">No</th>
                                        <th width="40%">Nama</th>
                                        <th width="15%">Nis</th>
                                        <th width="10%">Jk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$item->nama}}</td>
                                            <td>{{$item->nis}}</td>
                                            <td>{{$item->jk}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <script>
                const ctx = document.getElementById('chart-kelas');
                new Chart(ctx, {
                    type: 'pie',
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            title: {
                                display: true,
                                text: 'Data Siswa Per Jenis Kelamin'
                            },
                        },
                    },
                    data: {
                        labels: ['Laki-Laki', 'Perempuan'],
                        responsive : true,
                        datasets: [{
                            label: 'Jumlah',
                            data: [{{$jumlah->laki}},{{$jumlah->perempuan}}],
                            backgroundColor: [
                                '#BBE9FF','#FFC6C6'
                            ],
                        }]
                    },
                });

            </script>
        @endif
    @else
        <div class="p-0 col-12">
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Peringatan!</h4>
                <p>Ananda, kami ingin mengingatkan bahwa saat ini Anda telah menerima peringatan pertama terkait pelanggaran aturan sekolah. Mohon untuk memperhatikan dan mematuhi aturan yang berlaku demi kenyamanan dan keberhasilan bersama!</p>
                <hr>
                <a class="mb-0">Klik disini untuk</a>
            </div>
        </div>
        <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 pe-xl-3 col-12 col-sm-6 col-md-6 col-lg-5 col-xl-4">
            <div class="card rounded-4 border-0">
                <div class="card-body">
                    <h5 class="fs-18"><b>Jadwal Hari ini</b></h5>
                    <ol class="list-group jadwal fs-12" style="max-height:300px">

                        @forelse ($jadwal as $jadwalElemen)
                            @if ($jadwalElemen->jenis != "spesial")
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">{{date('H:i',strtotime($jadwalElemen->waktu->waktu_mulai))}} - {{date('H:i',strtotime($jadwalElemen->waktu->waktu_akhir))}}</div>
                                        {{$jadwalElemen->pelajaran->pelajaran}} <br />( {{$jadwalElemen->guru->nama}} )
                                    </div>
                                </li>
                            @else
                                <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">{{date('H:i',strtotime($jadwalElemen->waktu->waktu_mulai))}} - {{date('H:i',strtotime($jadwalElemen->waktu->waktu_akhir))}}</div>
                                        {{$jadwalElemen->spesial}}
                                    </div>
                                </li>
                            @endif
                        @empty
                            <li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Tidak Ada Jadwal</div>
                                    Tidak ada pembelajaran untuk hari ini
                                </div>
                            </li>
                        @endforelse
                    </ol>
                </div>
            </div>
        </div>
        <div class="p-0 mt-3 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0 col-12 col-sm-6 col-md-6 col-lg-7 col-xl-8">
            <div class="card rounded-4 border-0">
                <div class="card-body" style="height:300px">
                    <h5 class="fs-18"><b>Rekap Absensi</b></h5>
                    <canvas id="chartRekap"></canvas>
                </div>
            </div>
        </div>
        <script>
            $(".list-group.jadwal").mCustomScrollbar({
                theme: "minimal-dark",
                scrollbarPosition: "outside"
            });
            const ctx = document.getElementById('chartRekap');
            @php
                if($jumlah != 0) {
                    $hadir = $jumlah - ($absensi->sakit + $absensi->izin + $absensi->alpa);
                } else {
                    $hadir = 0;
                }
            @endphp
            new Chart(ctx, {
                type: 'bar',
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Graphic Kehadiran Siswa'
                        },
                    },
                },
                data: {
                    labels: ['Hadir', 'Sakit','Izin','Alpa'],
                    responsive : true,
                    datasets: [{
                        label: "Kehadiran Siswa",
                        data: [{{$hadir}},{{$absensi->sakit}},{{$absensi->izin}},{{$absensi->alpa}}],
                        backgroundColor: [
                            '#a0e1ff','#fd9494','#a1ff8e','#f9fd7a'
                        ],
                    }]
                },
            });
        </script>
    @endif
</div>
@endsection

