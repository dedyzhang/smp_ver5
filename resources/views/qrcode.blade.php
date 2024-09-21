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
        /* #qrcode img {
            width: 100%;
        } */
        .box {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            justify-content: center;
            align-items: center;
        }
        .left {
            width: 50%;
        }
        .right {
            width: 50%;
        }
        .left .left-box {
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .right .right-box {
            padding: 20px;
        }
    </style>
</head>
<body>
    <h1 class="mb-5" style="font-size: 100px; text-align: center;">Absensi Kehadiran Siswa</h1>
    <div class="box">
        <div class="left">
            <div class="left-box">
                <div id="qrcode"></div>
            </div>
        </div>
        <div class="right">
            <div class="right-box"></div>
        </div>
    </div>
</body>
<script>
    var date = moment().format('DD/MM/YYYY');
    console.log(date);
    var md = $.md5(date);
    console.log(md);
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: md,
        colorDark : "#000000",
        width: 1000,
        height:1000,
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</html>