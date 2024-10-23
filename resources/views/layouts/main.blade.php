<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')

</head>
<body>
    @include('layouts.sidebar')
    <div class="blackdrop"></div>
    <div class="main-container">
        @include('layouts.navbar')
        <div class="body-container">
            <div class="row">
                @yield('container')
            </div>
        </div>
        @canany(['admin','kurikulum','guru','kesiswaan','sapras','kepalasekolah'])
        <button class="tombol-gemini"><img src="{{asset('img/google-gemini.svg')}}" width="30" /></button>
        @endcan
    </div>
    @canany(['admin','kurikulum','guru','kesiswaan','sapras','kepalasekolah'])
    <div class="gemini-chatbox" id="gemini-chatbox">
        <div class="gemini-container">
            <div class="gemini-result">
                <div class="welcome-gemini">
                    <img src="{{asset('img/google-gemini.svg')}}" width="60px" height="60px">
                    <h4 class="mt-2">Hi, Saya Gemini</h4>
                    <h4>Apa Yang Bisa Saya Bantu ?</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="input-group">
                        <textarea class="form-control resize-ta" onkeyup="textAreaAdjust(this)" id="gemini" aria-label="With textarea" rows="1" placeholder="minta bantuan Gemini"></textarea>
                        <button class="btn btn-outline-secondary gemini_search" type="button"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        var first = true;
        $('.tombol-gemini').click(function() {
            if($('.gemini-chatbox').hasClass('active')) {
                $('.gemini-chatbox').removeClass('active');
                $('.tombol-gemini').removeClass('active');
            } else {
                if(first == true) {
                    $('.welcome-gemini').addClass('active');
                }
                $('.gemini-chatbox').addClass('active');
                $('.tombol-gemini').addClass('active');
            }
        })
        $('.gemini_search').click(function() {
            var geminiText = $('#gemini').val();
            if(first == true) {
                $('.welcome-gemini').html("");
                first = false;
            }
            var htmlText = `
                <div class="my-question">${geminiText}</div>
                <div class="clearfix"></div>
                <div class="d-flex align-items-start" id="loading"><img src="{{asset("img/google-gemini.svg")}}" class="gemini-img mt-1"><div class="bot"><div class="loading"><span></span><span></span><span></span></div></div></div>
            `;
            $('.gemini-result').append(htmlText);

            $('#gemini').attr('disabled',true).val("");
            $(this).attr('disabled',true);

            $.ajax({
                type: "POST",
                url: "{{route('gemini.get')}}",
                data: {text : geminiText},
                headers: {'X-CSRF-TOKEN' : '{{csrf_token()}}'},
                success: function(data) {
                    $('#loading').remove();
                    $('.gemini-result').append('<div class="d-flex align-items-start"><img src="{{asset("img/google-gemini.svg")}}" class="gemini-img mt-1"><pre class="bot"></pre><div class="clearBoth"></div></div>');
                    var result = data.split('');
                    var typing = new Promise((resolve, reject) => {
                        result.forEach((char, i,array) => {
                            setTimeout(function() {
                                if(char != "*") $('.bot').last().append(char);
                                if(i === array.length -1) resolve();
                            }, i * 10);
                        });
                    });

                    typing.then(() => {
                        $('.bot').last().append('<button class="copy-button" style="display:block"><i class="fas fa-clipboard"></i></button>');
                    });

                    $('#gemini').attr('disabled',false);
                    $('.gemini_search').attr('disabled',false);
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            });
        });
        $('.gemini-result').on('click','.copy-button',function() {
            if(!$(this).hasClass('copied')) {
                $(this).html('<i class="fas fa-clipboard-check"></i>');
                $(this).addClass('copied');
            }
            var clipboard = $(this).closest('.bot').text();
            navigator.clipboard.writeText(clipboard);
        });
    </script>
    @endcan
    @include('layouts.foot')
</body>
</html>
#bde0b0
