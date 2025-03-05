@extends('layouts.main')

@section('container')
    @if (\Request::route()->getName() === 'penilaian.admin.sumatif.show')
        {{Breadcrumbs::render('penilaian-admin-sumatif-show',$ngajar->pelajaran,$ngajar->kelas,$ngajar)}}
    @else
        {{Breadcrumbs::render('penilaian-sumatif-show',$ngajar->pelajaran,$ngajar->kelas,$ngajar)}}
    @endif
    <div class="body-contain-customize col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5">
        <p><b>Data Ngajar</b></p>
        <table class="table table-striped fs-13">
            <tr>
                <td width="30%">Pelajaran</td>
                <td width="5%">:</td>
                <td>{{$ngajar->pelajaran->pelajaran}}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{$ngajar->kelas->tingkat.$ngajar->kelas->kelas}}</td>
            </tr>
            <tr>
                <td>Guru</td>
                <td>:</td>
                <td>{{$ngajar->guru->nama}}</td>
            </tr>
            <tr>
                <td>KKTP</td>
                <td>:</td>
                <td>{{$ngajar->kkm}}</td>
            </tr>
        </table>
        @if ($ngajar->kkm == 0)
            <div
                class="alert alert-warning" role="alert">
                <strong><i class="fas fa-triangle-exclamation"></i> Perhatian</strong> KKTP untuk data ngajar masih belum diatur. Guru dapat mengatur KKTP dihalaman Buku Guru > KKTP
            </div>
        @endif
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
        <div class="alert alert-primary" role="alert">
            <h4 class="alert-heading">Info !</h4>
            <p>Untuk memudahkan dalam pengisian nilai maka, dapat menggunakan tombol <kbd><i class="fas fa-arrow-left"></i></kbd> <kbd><i class="fas fa-arrow-right"></i></kbd> <kbd><i class="fas fa-arrow-up"></i></kbd> <kbd><i class="fas fa-arrow-down"></i></kbd> <kbd>enter</kbd> dan <kbd>tab</kbd> untuk berpindah column maupun baris</p>
            <hr>
            <p class="mb-0">Terima kasih atas perhatiannya</p>
        </div>
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
        <p><b>Penilaian Sumatif</b></p>
        <div class="table-responsive">
            <table class="table table-bordered fs-12 nilai-table">
                <thead>
                    <tr class="text-center">
                        <td width="3%" rowspan="3">No</td>
                        <td width="30%" rowspan="3" class="sticky" style="min-width: 150px">Siswa</td>
                        <td class="mainNilaiCell" colspan="{{$count}}">Materi</td>
                    </tr>
                    <tr class="text-center">
                        @foreach ($materiArray as $item)
                            <td class="open-close-tab tab_{{$item['uuid']}}" data-bs-toggle="tooltip" data-bs-title="{{$item['materi']}}" data-bs-placement="top"  >M{{$loop->iteration}}</td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ngajar->siswa as $siswa)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class="sticky">{{$siswa->nama}}</td>
                            @foreach ($materiArray as $item)
                                @if (isset($sumatif_array[$item['uuid'].".".$siswa->uuid]))
                                    <td width="5%" data-sumatif="{{$sumatif_array[$item['uuid'].".".$siswa->uuid]['uuid']}}" contenteditable="true" class="text-center nilai_{{$item['uuid']}} nilai editable @if ($sumatif_array[$item['uuid'].".".$siswa->uuid]['nilai'] < $ngajar->kkm) text-danger bg-danger-subtle @endif">{{$sumatif_array[$item['uuid'].".".$siswa->uuid]['nilai']}}</td>
                                @elseif($item['show'] == 0)
                                    <td width="5%" class="text-center">-</td>
                                @else
                                    <td width="5%" class="text-center"><button data-siswa="{{$siswa->uuid}}" data-materi="{{$item['uuid']}}" class="btn btn-sm btn-success pt-0 pb-0 tambah-nilai"><i class="fas fa-plus fs-12"></i></button></td></td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="button-place mt-3 d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex">
            <button class="btn btn-sm btn-warning text-warning-emphasis simpan-nilai"><i class="fas fa-save"></i> Simpan Nilai</button>
        </div>
    </div>
    <script>
        var typingTimer;
        var ini;
        $('.nilai.editable').on('keyup',function(){
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function(){
                doneTyping();
            },100);
        });
        $('.nilai.editable').on('focus',function(e) {
            $(this).selectText();
        });
        $('.nilai.editable').on('focusout',function(){
            if($(this).is(':empty')) {
                $(this).html(0);
            } else if(parseInt($(this).text()) > 100) {
                $(this).html(100);
            }
        });
        $('.nilai.editable').on('keydown',function(e) {
            ini = this;
            clearTimeout(typingTimer);
            if(e.keyCode == 13 || e.keyCode == 40) {
                var cell = $(this);
                var index = cell.closest('td').index();

                cell.closest('tr').next().find('td').eq(index).focus();
                e.preventDefault();
            } else if(e.keyCode == 38) {
                var cell = $(this);
                var index = cell.closest('td').index();
                cell.closest('tr').prev().find('td').eq(index).focus();
                e.preventDefault();
            } else if(e.keyCode == 39) {
                var cell = $(this);
                var nextCell = cell.closest('td').index() + 1;
                if(cell.closest('tr').find('td').eq(nextCell).hasClass('editable')) {
                    cell.closest('tr').find('td').eq(nextCell).focus();
                } else {
                    nextCell = nextCell + 1;
                    cell.closest('tr').find('td').eq(nextCell).focus();
                }
                e.preventDefault();
            } else if(e.keyCode == 37) {
                var cell = $(this);
                var nextCell = cell.closest('td').index() - 1;
                if(cell.closest('tr').find('td').eq(nextCell).hasClass('editable')) {
                    cell.closest('tr').find('td').eq(nextCell).focus();
                } else {
                    nextCell = nextCell - 1;
                    cell.closest('tr').find('td').eq(nextCell).focus();
                }
                e.preventDefault();
            }
        });
        $('.nilai.editable').on('keypress',function(e) {
            if((e.which < 48 || e.which > 57)) {
                e.preventDefault();
            }
        });
        var doneTyping = function() {
            nilai = parseInt($(ini).text());
            var kkm = parseInt({{$ngajar->kkm}});

            //Hitung KKm
            if(nilai >= kkm) {
                $(ini).closest('td').removeClass('text-danger').removeClass('bg-danger-subtle');
            } else {
                $(ini).closest('td').addClass('text-danger').addClass('bg-danger-subtle');
            }

        };
        $('.simpan-nilai').click(function() {
            var alertLoading = $.alert({
                icon: "fas fa-gear fa-spin",
                title: "Loading",
                content: "Nilai Sedang Disimpan, Mohon untuk tidak menutup halaman sebelum nilai tersimpan dengan lengkap",
                backgroundDismiss: false,
                escapeKey: false,
                buttons : {
                    buttonA : {
                        text : "Tunggu"
                    }
                },
                onOpenBefore: function () {
                    this.buttons.buttonA.hide();
                }
            });
            var arrayNilai = [];
            $('.nilai').each(function() {
                var uuid = $(this).data('sumatif');
                var nilai = $(this).text();
                arrayNilai.push({
                    "uuid": uuid,
                    "nilai": nilai
                });
            });
            var url = "{{route('penilaian.sumatif.edit')}}";
            $.ajax({
                type: "put",
                url: url,
                headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                data: {
                    nilai: arrayNilai
                },
                success: function(data) {
                    setTimeout(() => {
                        alertLoading.close();
                    },500)
                },
                error: function(data) {
                    console.log(data.responseJSON);
                }
            })
        });
        $('.tambah-nilai').click(function() {
            var siswa = $(this).data('siswa');
            var materi = $(this).data('materi');

            loading();
            $.ajax({
                type: "POST",
                url : "{{route('penilaian.sumatif.tambah')}}",
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                data: {'siswa': siswa,'materi': materi},
                success: function(data) {
                    if(data.success) {
                        removeLoading();
                        location.reload();
                    }
                },error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        })
    </script>
@endsection
