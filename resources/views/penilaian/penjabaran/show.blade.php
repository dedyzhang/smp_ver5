@extends('layouts.main')

@section('container')
    @if (\Request::route()->getName() === 'penilaian.admin.penjabaran.show')
        {{Breadcrumbs::render('penilaian-admin-penjabaran-show',$ngajar->pelajaran,$ngajar->kelas,$ngajar)}}
    @else
        {{Breadcrumbs::render('penilaian-penjabaran-show',$ngajar->pelajaran,$ngajar->kelas,$ngajar)}}
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
    <div class="body-contain-customize d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto mt-3">
        @if (count($penjabaran) === 0)
            <button class="btn btn-success btn-sm tambah-nilai"><i class="fas fa-plus"></i> Tambah Nilai Penjabaran</button>
        @else
            <button class="btn btn-danger btn-sm hapus-nilai"><i class="fas fa-plus"></i> Hapus Nilai Penjabaran</button>
        @endif
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
        <p><b>Penjabaran @if ($jabaran == "inggris") Bahasa Inggris @else Mandarin @endif</b></p>
        <div class="table-responsive">
            <table class="table table-bordered fs-12 nilai-table">
                <thead>
                    <tr class="text-center">
                        <td rowspan="2" width="3%">No</td>
                        <td rowspan="2" width="50%" class="sticky" style="min-width: 150px">Siswa</td>
                        @if (count($penjabaran) !== 0)
                            <td class="mainNilaiCell" colspan="@if ($jabaran == "inggris") 7 @else 6 @endif">Nilai</td>
                        @endif
                    </tr>
                    <tr class="text-center">
                        @if (count($penjabaran) !== 0)
                            @if ($jabaran == "inggris")
                                <td width="5%" data-bs-title="Listening" data-bs-placement="top" data-bs-toggle="tooltip">P1</td>
                                <td width="5%" data-bs-title="Speaking" data-bs-placement="top" data-bs-toggle="tooltip">P2</td>
                                <td width="5%" data-bs-title="Writing" data-bs-placement="top" data-bs-toggle="tooltip">P3</td>
                                <td width="5%" data-bs-title="Reading" data-bs-placement="top" data-bs-toggle="tooltip">P4</td>
                                <td width="5%" data-bs-title="Grammar" data-bs-placement="top" data-bs-toggle="tooltip">P5</td>
                                <td width="5%" data-bs-title="Vocabulary" data-bs-placement="top" data-bs-toggle="tooltip">P6</td>
                                <td width="5%" data-bs-title="Singing" data-bs-placement="top" data-bs-toggle="tooltip">P7</td>
                            @else
                                <td width="5%" data-bs-title="听力" data-bs-placement="top" data-bs-toggle="tooltip">P1</td>
                                <td width="5%" data-bs-title="会话" data-bs-placement="top" data-bs-toggle="tooltip">P2</td>
                                <td width="5%" data-bs-title="书写" data-bs-placement="top" data-bs-toggle="tooltip">P3</td>
                                <td width="5%" data-bs-title="阅读" data-bs-placement="top" data-bs-toggle="tooltip">P4</td>
                                <td width="5%" data-bs-title="词汇" data-bs-placement="top" data-bs-toggle="tooltip">P5</td>
                                <td width="5%" data-bs-title="唱歌" data-bs-placement="top" data-bs-toggle="tooltip">P6</td>
                            @endif
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ngajar->siswa as $siswa)
                        <tr class="siswa" data-ngajar="{{$ngajar->uuid}}" data-siswa="{{$siswa->uuid}}">
                            <td>{{$loop->iteration}}</td>
                            <td class="sticky">{{$siswa->nama}}</td>
                            @if (isset($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]))
                                <td
                                    data-penjabaran="{{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['uuid']}}"
                                    class="nilai editable text-center
                                    @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['listening'] < $ngajar->kkm) text-danger bg-danger-subtle @endif" contenteditable="true">
                                    {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['listening']}}
                                </td>
                                <td
                                    class="nilai editable text-center
                                    @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['speaking'] < $ngajar->kkm) text-danger bg-danger-subtle @endif" contenteditable="true">
                                    {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['speaking']}}
                                </td>
                                <td
                                    class="nilai editable text-center
                                    @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['writing'] < $ngajar->kkm) text-danger bg-danger-subtle @endif" contenteditable="true">
                                    {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['writing']}}
                                </td>
                                <td
                                    class="nilai editable text-center
                                    @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['reading'] < $ngajar->kkm) text-danger bg-danger-subtle @endif" contenteditable="true">
                                    {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['reading']}}
                                </td>
                                @if ($jabaran == "inggris")
                                    <td
                                        class="nilai editable text-center
                                        @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['grammar'] < $ngajar->kkm) text-danger bg-danger-subtle @endif" contenteditable="true">
                                        {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['grammar']}}
                                    </td>
                                @endif
                                <td
                                    class="nilai editable text-center
                                    @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['vocabulary'] < $ngajar->kkm) text-danger bg-danger-subtle @endif" contenteditable="true">
                                    {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['vocabulary']}}
                                </td>
                                <td
                                    class="nilai editable text-center
                                    @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['singing'] < $ngajar->kkm) text-danger bg-danger-subtle @endif" contenteditable="true">
                                    {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['singing']}}
                                </td>
                            @endif
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
        $('.tambah-nilai').click(function() {
            var tambahpenjabaran = () => {
                BigLoading('Nilai Sedang Ditambahkan, Mohon untuk tidak menutup halaman sebelum nilai tersimpan dengan lengkap');
                var uuid = "{{$ngajar->uuid}}";
                var url = "{{route('penilaian.penjabaran.store',':id')}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "post",
                    url: url,
                    headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                    data : {'penjabaran': '{{$jabaran}}'},
                    success: function (data) {
                        setTimeout(() => {
                            removeLoadingBig();
                            cAlert('green','Berhasil','Nilai Berhasil Ditambah',true);
                        },500);
                        console.log(data);
                    },
                    error: function(data) {
                        console.log(data.responseJSON);
                    }
                })
            }
            cConfirm("Perhatian","Tambahkan Nilai Penjabaran untuk semester ini?",tambahpenjabaran);
        });
        $('.hapus-nilai').click(function() {
            var hapusPenjabaran = () => {
                BigLoading('Nilai Sedang Dihapus, Mohon untuk tidak menutup halaman sebelum nilai dihapus dengan lengkap');
                var uuid = "{{$ngajar->uuid}}";
                var url = "{{route('penilaian.penjabaran.destroy',':id')}}";
                var penjabaran = "{{$jabaran}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "delete",
                    url: url,
                    headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                    data: {
                        penjabaran : penjabaran
                    },
                    success: function (data) {
                        setTimeout(() => {
                            removeLoadingBig();
                            cAlert('green','Berhasil','Nilai berhasil dihapus',true);
                        },500);
                    },
                    error: function(data) {
                        console.log(data.responseJSON);
                    }
                })
            }
            cConfirm("Perhatian","Hapus Nilai Penjabaran untuk semester ini?",hapusPenjabaran);
        });
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
            var jabaran = "{{$jabaran}}";
            if(jabaran == "inggris") {
                $('.siswa').each(function() {
                    var uuid = $(this).children().eq(2).data('penjabaran');
                    var listening = $(this).children().eq(2).text();
                    var speaking = $(this).children().eq(3).text();
                    var writing = $(this).children().eq(4).text();
                    var reading = $(this).children().eq(5).text();
                    var grammar = $(this).children().eq(6).text();
                    var vocabulary = $(this).children().eq(7).text();
                    var singing = $(this).children().eq(8).text();
                    arrayNilai.push({
                        "uuid": uuid,
                        "listening": listening,
                        "speaking": speaking,
                        "writing": writing,
                        "reading": reading,
                        "grammar": grammar,
                        "vocabulary": vocabulary,
                        "singing": singing,
                    });
                });
            } else {
                $('.siswa').each(function() {
                    var uuid = $(this).children().eq(2).data('penjabaran');
                    var listening = $(this).children().eq(2).text();
                    var speaking = $(this).children().eq(3).text();
                    var writing = $(this).children().eq(4).text();
                    var reading = $(this).children().eq(5).text();
                    var vocabulary = $(this).children().eq(6).text();
                    var singing = $(this).children().eq(7).text();
                    arrayNilai.push({
                        "uuid": uuid,
                        "listening": listening,
                        "speaking": speaking,
                        "writing": writing,
                        "reading": reading,
                        "vocabulary": vocabulary,
                        "singing": singing,
                    });
                });
            }
            console.log(arrayNilai);
            var url = "{{route('penilaian.penjabaran.edit')}}";
            $.ajax({
                type: "put",
                url: url,
                headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                data: {
                    nilai: arrayNilai,
                    penjabaran : jabaran
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
    </script>
@endsection