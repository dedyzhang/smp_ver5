@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('absensi-kehadiran-hadir',$jenis)}}
    @if ($jenis == "datang")
        <audio id="audioAbsen" src="{{asset('files/datang.wav')}}"></audio>
    @else
        <audio id="audioAbsen" src="{{asset('files/terimakasih.wav')}}"></audio>
    @endif

    <div class="body-contain-customize col-12">
        <div id="reader"></div>
    </div>
    <div class="alert-place">

    </div>
    <div class="body-contain-customize col-12 mt-3 text-center">
        <p>Pastikan Kamera sudah terbuka, klik allow permission pada kamera untuk mengizinkan aplikasi untuk membuka kamera</p>
    </div>
    <script>
        var hasScan = "no";
        Html5Qrcode.getCameras().then(devices => {
            const html5QrCode = new Html5Qrcode(/* element id */ "reader");
            html5QrCode.start(
            {
                facingMode: "environment"
            },
            {
                fps: 10,    // Optional, frame per seconds for qr code scanning
                qrbox: { width: 200, height: 200 },  // Optional, if you want bounded box UI
            },
            (decodedText, decodedResult) => {
                if(hasScan == "no") {
                    loading();
                    if(decodedText !== "") {
                        var url = "{{route('absensi.kehadiran.hadir',':id')}}";
                        url = url.replace(':id','{{$jenis}}');
                        $('.alert-place').html('');
                        $.ajax({
                            type: "post",
                            url: url,
                            headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                            data: {message: decodedText},
                            success: function(data) {
                                if(data.success == false) {
                                    $('.alert-place').html(`
                                        <div class="alert alert-warning alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert"><i class="bi flex-shrink-0 me-2 fa-solid fa-triangle-exclamation" aria-label="Success:"></i><div><strong>Perhatian !</strong> ${data.message}</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
                                    `);
                                    $('.alert-place').promise().done(function() {
                                        setTimeout(() => {
                                            hasScan = "no";
                                            removeLoading();
                                        }, 500);
                                    });
                                } else {
                                    document.getElementById('audioAbsen').play();
                                    $('#audioAbsen').promise().done(function() {
                                        removeLoading();
                                        setTimeout(() => {
                                            cAlert('green',"Sukses","Sukses Menambahkan Absensi untuk User "+data.data,false,"{{route('absensi.kehadiran')}}");
                                        }, 1000);
                                    })
                                }
                            },
                            error: function(data) {
                                console.log(data.responseJSON.message);
                            }
                        })
                    }
                    hasScan = "yes";
                }
            },
            (errorMessage) => {
                // parse error, ignore it.
            })
            .catch((err) => {
            // Start failed, handle it.
                console.log(err);
            });
        }).catch(err => {
            console.log(err);
        });
    </script>
@endsection
