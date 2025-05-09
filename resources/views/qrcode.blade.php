<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Qr Code absensi - Absensi kehadiran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.md5@1.0.2/index.min.js"></script>
    <style>
        html,body {
            background-color: #F3FDFE;
        }
        .main-body {
            min-height:100vh;
        }
        .main-body::before {
            content: ' ';
            width: 100%;
            height: 100%;
            opacity: 0.3;
            position: absolute;
            bottom: 0;
            right: 0;
            background-image: url('{{asset('img/qrcode-object.svg')}}');
            background-repeat:no-repeat;
            background-size: 300px;
            background-position: bottom 0 right 0;
        }
        marquee {
            font-size:50px;
            margin-top:10px;
            margin-bottom:30px;
        }
        .qrcode {
            width:100%;
            display: flex;
            justify-content: center;
        }
        .qrcode canvas {
            width:70%;
        }
        @media screen and (max-width:900px) {
            .qrcode {
                display:none;
            }
        }
    </style>
</head>
<body>
    <marquee><b>QR CODE ABSENSI KEHADIRAN BAGI GURU DAN SISWA</b></marquee>
    <div class="row m-0 p-0 main-body">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 d-flex justify-content-center flex-wrap">
            <div id="qrcodedatang" class="qrcode"></div>
            <div class="d-block mt-2"><p style="font-size:30px"><b>QR CODE DATANG</b></p></div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 d-flex justify-content-center flex-wrap">
            <div id="qrcodepulang" class="qrcode"></div>
            <div class="d-block mt-2"><p style="font-size:30px"><b>QR CODE PULANG</b></p></div>
        </div>
        {{-- <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <p><b>Tata Cara Absensi</b></p>
            <ol>
                <li>Buka Aplikasi website ataupun kunjungi website "https://smpmaitreyawiratpi.sch.id"</li>
                <li>Setelah itu login menggunakan NIS ( Siswa ) dan NIK ( Guru ) dan Password Masing-masing</li>
                <li>Masuk ke menu absensi > Absen Kehadiran dan klik tombol hijau bertulisan "Absen Kehadiran"</li>
                <li>Scan Barcode Diatas, pastikan sudah mengizinkan permission pada kamera</li>
            </ol>
        </div> --}}
    </div>
</body>
<script>
    var date = moment().format('DD/MM/YYYY');
    var datang = $.md5("datang"+date);
    var pulang = $.md5("pulang"+date);
    var qrcodeDatang = new QRCode(document.getElementById("qrcodedatang"), {
        text: datang,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
    var qrcodePulang = new QRCode(document.getElementById("qrcodepulang"), {
        text: pulang,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</html>
