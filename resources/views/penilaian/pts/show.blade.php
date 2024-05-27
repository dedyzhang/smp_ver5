@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('penilaian-pts-show',$ngajar->pelajaran,$ngajar->kelas,$ngajar)}}
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
        <button class="btn btn-success btn-sm tambah-nilai"><i class="fas fa-plus"></i>Tambah Nilai PTS</button>
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
        <p><b>Penilaian Tengah Semester</b></p>
        <div class="table-responsive">
            <table class="table table-bordered fs-12 nilai-table">
                <thead>
                    <tr class="text-center">
                        <td width="3%">No</td>
                        <td width="80%" class="sticky" style="min-width: 150px">Siswa</td>
                        <td class="mainNilaiCell">Nilai</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ngajar->siswa as $siswa)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class="sticky">{{$siswa->nama}}</td>
                            @if (isset($pts_array[$ngajar->uuid.".".$siswa->uuid]))
                                <td>{{$pts_array[$ngajar->uuid.".".$siswa->uuid]}}</td>
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
            var tambahpts = () => {
                BigLoading('Nilai Sedang Ditambahkan, Mohon untuk tidak menutup halaman sebelum nilai tersimpan dengan lengkap');
                var uuid = "{{$ngajar->uuid}}";
                var url = "{{route('penilaian.pts.store',':id')}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "post",
                    url: url,
                    headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                    success: function (data) {
                        setTimeout(() => {
                            removeLoadingBig();
                            cAlert('green','Berhasil','Nilai Berhasil Ditambah',true);
                        },500)
                        console.log(data);
                    },
                    error: function(data) {
                        console.log(data.responseJSON);
                    }
                })
            }
            cConfirm("Perhatian","Tambahkan Nilai PTS untuk semester ini?",tambahpts);
        })
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
    </script>
@endsection
