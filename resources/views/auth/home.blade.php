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
@if (session('error'))
    <div class="row m-0 p-0 mt-3">
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                {{session('error')}}
            </div>
        </div>
    </div>
@endif
<div class="row m-0 p-0 mt-3">
    @if ($user->access != "siswa" && $user->access != "orangtua")
        @if ($event->count() > 0)
            <div class="p-0 col-12 mt-3 mb-3">
                <div class="card card-waning border-0 rounded-3" role="alert">
                    <div class="card-body">
                        <p class="m-0"><b>Kegiatan Mendatang</b></p>
                        <ul class="list-group mt-3 list-group-numbered">
                            @foreach ($event as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-warning fs-13">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">{{$item->judul}}</div>
                                        Tanggal Mulai : {{date('d M Y, H:i',strtotime($item->waktu_mulai))}}
                                    </div>
                                    <a href="{{route('event.show',$item->uuid)}}" class="fs-18 text-warning-emphasis"><i class="fa-solid fa-arrow-right"></i></a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        @if ($user->access == "admin" || $user->access == "kepala")
            <div class="row m-0 p-0">
                <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 pe-xl-3 mt-2 col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                    <div class="card bg-primary-subtle rounded-3 border-0">
                        <div class="card-body d-inline-flex align-items-center">
                            <img src="{{asset('img/admin-siswa.svg')}}" width="90" alt="">
                            <div class="ms-2">
                                <p class="fs-14 m-0">Peserta Didik</p>
                                <p class="m-0 fs-30"><b>{{$siswa->all}}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 pe-xl-3 mt-2 col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                    <div class="card bg-warning-subtle rounded-3 border-0">
                        <div class="card-body d-inline-flex align-items-center">
                            <img src="{{asset('img/admin-guru.svg')}}" width="90" alt="">
                            <div class="ms-2">
                                <p class="fs-14 m-0">Guru & Staf</p>
                                <p class="m-0 fs-30"><b>{{$guru->all}}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 pe-xl-3 mt-2 col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                    <div class="card bg-success-subtle rounded-3 border-0">
                        <div class="card-body d-inline-flex align-items-center">
                            <img src="{{asset('img/admin-rombel.svg')}}" width="90" alt="">
                            <div class="ms-2">
                                <p class="fs-14 m-0">Rombel</p>
                                <p class="m-0 fs-30"><b>{{$jumlahRombel}}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 mt-2 col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                    <div class="card bg-danger-subtle rounded-3 border-0">
                        <div class="card-body d-inline-flex align-items-center">
                            <img src="{{asset('img/admin-ruang.svg')}}" width="90" alt="">
                            <div class="ms-2">
                                <p class="fs-14 m-0">Ruang</p>
                                <p class="m-0 fs-30"><b>{{$jumlahRuang}}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0 mt-3">
                <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 pe-xl-3 mt-2 col-12 col-sm-6 col-md-6 col-lg-5 col-xl-5">
                    <div class="card rounded-3 border-0">
                        <div class="card-body">
                            <p>Data Siswa Berdasarkan Jenis Kelamin</p>
                            <div class="scroll-enable" style="max-height:300px">
                                <table class="table table-bordered mt-3 fs-12">
                                    <thead>
                                        <tr>
                                            <th width="15%">Kelas</th>
                                            <th width="70%">Jenis Kelamin</th>
                                            <th width="15%">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $array_tingkat = array();
                                        @endphp
                                        @foreach ($kelas as $item)
                                            @php
                                                if(!isset($array_tingkat[$item->tingkat])) {
                                                    $array_tingkat[$item->tingkat] = array(
                                                        "laki" => 0,
                                                        "perempuan" => 0,
                                                        "all" => 0
                                                    );
                                                }
                                                $jumlahPerKelas = $siswaPKelas->first(function($element) use($item) {
                                                    if($element->id_kelas == $item->uuid) {
                                                        return $element;
                                                    }
                                                });
                                                if(isset($array_tingkat[$item->tingkat])) {

                                                    isset($jumlahPerKelas->laki) ? $array_tingkat[$item->tingkat]['laki'] += $jumlahPerKelas->laki : $array_tingkat[$item->tingkat]['laki'] += 0;
                                                    isset($jumlahPerKelas->perempuan) ? $array_tingkat[$item->tingkat]['perempuan'] += $jumlahPerKelas->perempuan : $array_tingkat[$item->tingkat]['perempuan'] += 0;
                                                    isset($jumlahPerKelas->all) ? $array_tingkat[$item->tingkat]['all'] += $jumlahPerKelas->all : $array_tingkat[$item->tingkat]['all'] += 0;
                                                }
                                            @endphp
                                            <tr>
                                                <td rowspan="3" class="align-middle text-center">{{$item->tingkat.$item->kelas}}</td>
                                                <td class="table-primary">Laki-laki</td>
                                                <td class="text-center table-primary">{{isset($jumlahPerKelas->laki) ? $jumlahPerKelas->laki : 0 }}</td>
                                            </tr>
                                            <tr>
                                                <td class="table-danger">Perempuan</td>
                                                <td class="text-center table-danger">{{isset($jumlahPerKelas->perempuan) ? $jumlahPerKelas->perempuan : 0}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Total</b></td>
                                                <td class="text-center"><b>{{isset($jumlahPerKelas->all) ? $jumlahPerKelas->all : 0}}</b></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 pe-xl-3 mt-2 col-12 col-sm-6 col-md-6 col-lg-7 col-xl-7">
                    <div class="card rounded-3 border-0">
                        <div class="card-body">
                            <p>Data Jenis Kelamin Siswa Per Tingkat</p>
                            <canvas style="height:300px" id="rekapTingkat"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(".scroll-enable").mCustomScrollbar({
                    theme: "minimal-dark",
                    scrollbarPosition: "outside"
                });
                const ctx = document.getElementById('rekapTingkat');
                new Chart(ctx, {
                    type: 'bar',
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                        },
                    },
                    data: {
                        responsive : true,
                        datasets: [{
                            label: "Laki-laki",
                            data: [
                                @foreach ($array_tingkat as $tingkat => $value)
                                    {x: "tingkat {{$tingkat}}",y: {{isset($value['laki']) ? $value['laki'] : 0}}},
                                @endforeach

                            ],
                            backgroundColor: [
                                '#cfe2ff'
                            ],
                        },{
                            label: "Perempuan",
                            data: [
                                @foreach ($array_tingkat as $tingkat => $value)
                                    {x: "tingkat {{$tingkat}}",y: {{isset($value['perempuan']) ? $value['perempuan'] : 0}}},
                                @endforeach
                            ],
                            backgroundColor: [
                                '#f8d7da'
                            ],
                        }]
                    },
                });
            </script>
        @else
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
            <div class="row m-0 p-0 mt-3">
                <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 pe-xl-3 col-12 col-sm-6 col-md-6 col-lg-5 col-xl-4">
                    <div class="card rounded-4 border-0">
                        <div class="card-body d-grid">
                            <h5 class="fs-18"><b>Perangkat</b></h5>
                            <p class="fs-12">Perangkat yang harus diupload oleh guru meliputi :</p>
                            <ol class="list-group list-group-numbered fs-12">
                                @foreach ($listPerangkat as $list)
                                    @php
                                        if(in_array($list->uuid,$arrayUpload)) {
                                            $upload = "sudah";
                                        } else {
                                            $upload = "belum";
                                        }
                                    @endphp
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                        <div class="fw-bold">{{$list->perangkat}}</div>
                                        </div>
                                        <span class="badge @if ($upload == "sudah") text-bg-success @else text-bg-danger @endif rounded-pill"><i class="fas {{$upload == "sudah" ? "fa-check" : "fa-xmark"}}"></i></span>
                                    </li>
                                @endforeach
                            </ol>
                            <a href="{{route('penilaian.perangkat.index')}}" class="btn btn-sm mt-3 btn-warning text-warning-emphasis">Buka Halaman Perangkat</a>
                        </div>
                    </div>
                </div>
                <div class="p-0 mt-3 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0 col-12 col-sm-6 col-md-6 col-lg-7 col-xl-8">
                    <div class="card rounded-4 border-0">
                        <div class="card-body">
                            <h5 class="fs-18"><b>Alur Kerja Penginputan Nilai</b></h5>
                            <p class="fs-12">Dengan ini kami sampaikan demi kelancaran dalam proses penginputan nilai siswa untuk semester ini. Mohon untuk memperhatikan langkah-langkah berikut</p>
                            <div class="wrapper">
                                <ul class="StepProgress">
                                    <li class="StepProgress-item"><strong>Pendidik memasukkan rentang nilai ketuntasan pada Kriteria Ketercapaian Tujuan Pembelajaran (KKTP)</strong>
                                        Rentang nilai ketuntasan yang diinput adalah rentang nilai yang sudah disepakati bersama sebelum proses pembelajaran dimulai.
                                    </li>
                                    <li class="StepProgress-item"><strong>Memasukkan Materi ke dalam sistem</strong>
                                        Pastikan semua materi yang diajarkan selama periode penilaian telah diinput ke dalam sistem. Materi harus sesuai dengan Capaian Pembelajaran (CP) dan fase kurikulum yang berlaku untuk kelas dan mata pelajaran yang diajarkan.
                                    </li>
                                    <li class="StepProgress-item"><strong>Memasukkan Tujuan Pembelajaran</strong>
                                        Setiap materi pembelajaran harus disertai dengan tujuan pembelajaran yang jelas dan terukur. untuk mengoptimalisasikan sistem dalam menghasilkan deskripsi rapor, maka tujuan pembelajaran yang diinput <b>Tidak diawali dengan huruf kapital.</b> dan <b>tidak diakhiri dengan tanda titik</b>
                                    </li>
                                    <li class="StepProgress-item"><strong>Menginput Nilai Formatif dan Nilai Sumatif</strong>
                                        Lakukan penginputan nilai formatif dan sumatif Setelah proses penilaian Capaian Pembelajaran (CP) selesai dilaksanakan. <b>Dihimbau untuk tidak diinput bersamaan pada akhir Semester</b>
                                    </li>
                                    <li class="StepProgress-item"><strong>Menginput Nilai Sumatif Akhir Semester / Sumatif Akhir Tahun</strong></li>
                                    <li class="StepProgress-item"><strong>Melakukan Pengelolaan Nilai rapor</strong>
                                        melakukan pengelolaan nilai rapor dengan memerhatikan nilai yang dibawah rentang nilai ketuntasan dan deskripsi yang ganda ataupun format penulisan yang salah.
                                    </li>
                                    <li class="StepProgress-item"><strong>Melakuakn Konfirmasi Nilai Rapor</strong>
                                        Nilai Rapor yang sudah dikonfirmasi <b>tidak bisa diubah lagi</b>.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        @if($user->access == "siswa")
            @if($sisa < 75 && $sisa >= 50)
                <div class="p-0 col-12">
                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading">Peringatan!</h4>
                        <p>Ananda, kami ingin mengingatkan bahwa saat ini Anda telah menerima peringatan pertama terkait pelanggaran aturan sekolah. Mohon untuk memperhatikan dan mematuhi aturan yang berlaku demi kenyamanan dan keberhasilan bersama!</p>
                        <hr>
                        <a class="mb-0 text-warning" href="{{route('detail.poin.index')}}">Klik disini untuk menuju halaman poin</a>
                    </div>
                </div>
            @elseif($sisa < 50 && $sisa >= 25)
                <div class="p-0 col-12">
                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading">Peringatan!</h4>
                        <p>Tolong diperhatikan, Ananda, bahwa Anda sudah menerima peringatan kedua terkait pelanggaran aturan sekolah. Harap mematuhi aturan yang berlaku agar tidak mendapatkan peringatan lebih lanjut !</p>
                        <hr>
                        <a class="mb-0 text-warning" href="{{route('detail.poin.index')}}">Klik disini untuk menuju halaman poin</a>
                    </div>
                </div>
            @elseif($sisa < 25)
                <div class="p-0 col-12">
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Peringatan!</h4>
                        <p>Ananda, ini adalah peringatan terakhir mengenai pelanggaran aturan sekolah. Harap mematuhi aturan dengan baik untuk menghindari konsekuensi lebih lanjut !</p>
                        <hr>
                        <a class="mb-0 text-danger" href="{{route('detail.poin.index')}}">Klik disini untuk menuju halaman poin</a>
                    </div>
                </div>
            @endif
        @else
            @if($sisa < 75 && $sisa >= 50)
                <div class="p-0 col-12">
                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading">Peringatan!</h4>
                        <p>Kami ingin memberitahukan bahwa anak Bapak/Ibu, telah menerima peringatan pertama terkait pelanggaran aturan sekolah.Terima kasih atas perhatian dan kerjasamanya</p>
                        <hr>
                        <a class="mb-0" href="{{route('detail.poin.index')}}">Klik disini untuk menuju halaman poin</a>
                    </div>
                </div>
            @elseif($sisa < 50 && $sisa >= 25)
                <div class="p-0 col-12">
                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading">Peringatan!</h4>
                        <p>Kami ingin memberitahukan bahwa anak Bapak/Ibu telah menerima peringatan kedua terkait pelanggaran aturan sekolah. Kami mohon kerjasama Bapak/Ibu untuk mendiskusikan hal ini dengan anak dan memastikan bahwa dia mematuhi aturan sekolah dengan lebih baik ke depannya. Terima kasih atas perhatian dan dukungannya</p>
                        <hr>
                        <a class="mb-0" href="{{route('detail.poin.index')}}">Klik disini untuk menuju halaman poin</a>
                    </div>
                </div>
            @elseif($sisa < 25)
                <div class="p-0 col-12">
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Peringatan!</h4>
                        <p>Kami ingin memberitahukan bahwa anak Bapak/Ibu, telah menerima peringatan terakhir terkait pelanggaran aturan sekolah. Kami sangat berharap Bapak/Ibu dapat membimbing anak untuk mematuhi aturan yang ada agar tidak menghadapi tindakan lebih lanjut. Terima kasih atas perhatian dan kerjasamanya</p>
                        <hr>
                        <a class="mb-0" href="{{route('detail.poin.index')}}">Klik disini untuk menuju halaman poin</a>
                    </div>
                </div>
            @endif
        @endif
        <div class="p-0 pe-sm-2 pe-md-2 pe-lg-3 pe-xl-3 col-12 col-sm-6 col-md-6 col-lg-5 col-xl-4">
            <div class="card rounded-4 border-0">
                <div class="card-body">
                    <h5 class="fs-18"><b>Jadwal Hari ini</b></h5>
                    <ol class="list-group jadwal fs-12" style="max-height:300px">

                        @forelse ($jadwal as $jadwalElemen)
                            @if ($jadwalElemen->jenis != "spesial")
                                @if ($jadwalElemen->pelajaran != null || $jadwalElemen->guru != null)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">{{date('H:i',strtotime($jadwalElemen->waktu->waktu_mulai))}} - {{date('H:i',strtotime($jadwalElemen->waktu->waktu_akhir))}}</div>
                                            {{$jadwalElemen->pelajaran->pelajaran}} <br />( {{$jadwalElemen->guru->nama}} )
                                        </div>
                                    </li>
                                @endif
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

