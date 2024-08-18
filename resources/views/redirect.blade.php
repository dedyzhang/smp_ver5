<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')
    <style>
        :root {
        background: #222838;
        height: 100vh;
        }

        .container {
        width: 334px;
        margin: 0 auto;
        position: absolute;
        top: 50%;
        left: 0;
        text-align: center;
        right: 0;
        -webkit-transform: translateY(-50%);
                transform: translateY(-50%);
        }

        body
        {
        background: #222838;
        }
        a {
        color: white;
        text-decoration: none;
        }

        h1, h2 {
        color: white;
        font-family: 'Oswald', sans-serif;
        font-weight: normal;
        }

        h2 {
        font-size: 14px;
        margin-bottom: 30px;
        color: #24E2B8;
        }

        .one, .two, .three, .four, .five {
        border: none;
        border-radius: 4px;
        text-shadow: 0px 0px 10px rgba(0, 0, 0, 0.48);
        overflow: hidden;
        padding: 20px 50px 20px 70px;
        margin-bottom: 20px;
        font-size: 20px;
        position: relative;
        color: white;
        outline: none;
        cursor: pointer;
        width: 100%;
        -webkit-transition: background-position .7s,box-shadow .4s;
        transition: background-position .7s,box-shadow .4s;
        background-size: 110%;
        font-family: 'Oswald', sans-serif;
        }
        .one:hover, .two:hover, .three:hover, .four:hover, .five:hover {
        background-position: 0% 30%;
        }
        .one:hover:after, .two:hover:after, .three:hover:after, .four:hover:after, .five:hover:after {
        right: -40px;
        -webkit-transition: right .4s,-webkit-transform 30s .2s linear;
        transition: right .4s,-webkit-transform 30s .2s linear;
        transition: right .4s,transform 30s .2s linear;
        transition: right .4s,transform 30s .2s linear,-webkit-transform 30s .2s linear;
        }
        .one:before, .two:before, .three:before, .four:before, .five:before, .one:after, .two:after, .three:after, .four:after, .five:after {
        font-family: FontAwesome;
        display: block;
        position: absolute;
        }
        .one:before, .two:before, .three:before, .four:before, .five:before {
        -webkit-transition: all 1s;
        transition: all 1s;
        transform: scale(0.3);
        left: -30px;
        top: -25px;
        }
        .one:after, .two:after, .three:after, .four:after, .five:after {
        -webkit-transition: right .4s, -webkit-transform .2s;
        transition: right .4s, -webkit-transform .2s;
        transition: right .4s, transform .2s;
        transition: right .4s, transform .2s, -webkit-transform .2s;
        font-size: 100px;
        opacity: .3;
        right: -120px;
        top: -17px;
        }

        .one {
        box-shadow: 0px 0px 0px 2px rgba(255, 255, 255, 0.16) inset, 0px 0px 10px 0px #cfcfcfd8;
        background-image: -webkit-gradient(linear, left top, left bottom, from(#cfcfcfd8), to(rgba(190, 190, 190, 0.18))), url("{{asset('img/Sekolah/smp.jpg')}}");
        background-image: linear-gradient(to bottom, #cfcfcfd8, rgba(190, 190, 190, 0.18)), url("{{asset('img/Sekolah/smp.jpg')}}");
        }
        .one:hover {
        box-shadow: 0px 0px 0px 2px rgba(255, 255, 255, 0.16) inset, 0px 0px 30px 0px #cfcfcfd8;
        }
        .one:hover:after {
        -webkit-transform: scale(1);
                transform: scale(1);
        }
        .one:hover:before {
        -webkit-transform: scale(0.4);
                transform: scale(0.4);
        }
        .one:after, .one:before {
            content: url("{{asset('img/Sekolah/logo-rounded.png')}}");
        }
        .one b {
        color: #cfcfcfd8;
        font-weight: 700;
        }

        .two {
        box-shadow: 0px 0px 0px 2px rgba(255, 255, 255, 0.16) inset, 0px 0px 10px 0px #cfcfcfd8;
        background-image: -webkit-gradient(linear, left top, left bottom, from(#cfcfcfd8), to(rgba(190, 190, 190, 0.18))), url("{{asset('img/Sekolah/smpbincen.jpg')}}");
        background-image: linear-gradient(to bottom, #cfcfcfd8, rgba(190, 190, 190, 0.18)), url("{{asset('img/Sekolah/smpbincen.jpg')}}");
        }
        .two:hover {
        box-shadow: 0px 0px 0px 2px rgba(255, 255, 255, 0.16) inset, 0px 0px 30px 0px #cfcfcfd8;
        }
        .two:hover:after {
        -webkit-transform: scale(1);
                transform: scale(1);
        }
        .two:hover:before {
        -webkit-transform: scale(0.4);
                transform: scale(0.4);
        }
        .two:after, .two:before {
        content: url("{{asset('img/Sekolah/logo-rounded.png')}}");
        }
        .two b {
        color: #cfcfcfd8;
        font-weight: 700;
        }

        .three {
        box-shadow: 0px 0px 0px 2px rgba(255, 255, 255, 0.16) inset, 0px 0px 10px 0px #cfcfcfd8;
        background-image: -webkit-gradient(linear, left top, left bottom, from(#cfcfcfd8), to(rgba(190, 190, 190, 0.18))), url("{{asset('img/Sekolah/sma.jpg')}}");
        background-image: linear-gradient(to bottom, #cfcfcfd8, rgba(190, 190, 190, 0.18)), url("{{asset('img/Sekolah/sma.jpg')}}");
        }
        .three:hover {
        box-shadow: 0px 0px 0px 2px rgba(255, 255, 255, 0.16) inset, 0px 0px 30px 0px #cfcfcfd8;
        }
        .three:hover:after {
        -webkit-transform: scale(1);
                transform: scale(1);
        }
        .three:hover:before {
        -webkit-transform: scale(0.4);
                transform: scale(0.4);
        }
        .three:after, .three:before {
        content: url("{{asset('img/Sekolah/sma-logo.png')}}");
        }
        .three b {
        color: #63FFAC;
        font-weight: 700;
        }
        h3 {
            color: #ffffff;
            font-size: 14px;
            margin:0;
        }
        p {
            color: #ffffff;
            padding: 5px;
            font-size: 14px;
            margin: 0 auto;
            text-align: center;
        }
        .box-sekolah {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            padding: 40px 0 0 0;
            align-items: center;
        }
        .box-sekolah .left {
            width: 38%;
            text-align: right;
        }
        .box-sekolah .left img {
            width: 60px;
            height: auto;
            margin-right: 15px;
        }
        .box-sekolah .right {
            width: 60%;
            align: right;
            line-height:15px;
        }
    </style>
</head>
<body>
    <div class="box-sekolah">
        <div class="left">
            <img src="{{asset('img/logo-rounded.png')}}" alt="">
        </div>
        <div class="right">
            <h3>Sekolah Maitreyawira</h3>
            <h1>慈 容 學 校</h1>
        </div>
        <p>Bright Heart . Bright Mind . Bright Future</p>
    </div>
    <div class='container'>
        <button class='getLink one' data-sekolah="smpmw"><a href="{{route('login')}}">SMPS Maitreyawira Tanjungpinang</a></button>
        <button class='getLink two' data-sekolah="smpmwbincen"><a href="https://bincent.smpmaitreyawiratpi.sch.id/signin">SMPS Maitreyawira Bintan Center</a></button>
        <button class='getLink three' data-sekolah="smamw"><a href="https://smasmaitreyawira.sch.id/signin">SMAS Maitreyawira Tanjungpinang</a></button>
    </div>
    <script>
        $('.getLink').click(function() {
            event.preventDefault();
            var link = $(this).find('a').attr('href');
            var url = "{{route('redirect.update')}}";
            $.ajax({
                type: "POST",
                url: url,
                data: {link: link },
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                success: function(data) {
                    console.log(data);
                    window.location.href=link;
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        });
    </script>
</body>
</html>
