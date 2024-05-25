@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('penilaian-kktp')}}
    <div class="body-contain-customize col-12">
        <h3><b>KKTP</b></h3>
        <p>Kriteria Ketercapaian Tujuan Pembelajaran</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover fs-12">
                <thead>
                    <tr>
                        <td>No</td>
                        <td style="min-width: 200px">Pelajaran</td>
                        <td>Kelas</td>
                        <td>KKTP</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ngajar as $ngjr)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$ngjr->pelajaran->pelajaran}}</td>
                            <td>{{$ngjr->kelas->tingkat.$ngjr->kelas->kelas}}</td>
                            <td contenteditable="true" style="cursor: text" class="kktp" data-ngajar="{{$ngjr->uuid}}">{{$ngjr->kkm}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="button-place mt-3 d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex justify-content-lg-end justify-content-md-end justify-content-xl-end">
            <button class="btn btn-sm btn-warning text-warning-emphasis simpan-kktp"><i class="fas fa-save"></i> Simpan KKTP</button>
        </div>
    </div>
    <script>
        $('td[contenteditable="true"]').focus(function() {
            $(this).selectText();
        });
        $('td[contenteditable="true"]').on('focusout',function(){
            if($(this).is(':empty')) {
                $(this).html(0);
            } else if(parseInt($(this).text()) > 100) {
                $(this).html(100);
            }
        });
        $('.simpan-kktp').click(function() {
            var simpanKKTP = () => {
                loading();
                var kktpArray = [];
                var url = "{{route('penilaian.kktp.edit')}}";
                $('.kktp').each(function() {
                    var id = $(this).data('ngajar');
                    var kktp = $(this).text();
                    kktpArray.push({
                        "uuid" : id,
                        "kkm" : kktp,
                    });
                });
                $.ajax({
                    type: "post",
                    url: url,
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    data: {
                        kktp: kktpArray
                    },
                    success: function(data) {
                        setTimeout(() => {
                            removeLoading();
                            oAlert("green","Berhasil","Berhasil menyimpan KKTP");
                        }, 500);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
            cConfirm("Perhatian","Yakin untuk simpan data KKTP sesuai yang diinput",simpanKKTP);
        });
    </script>
@endsection
