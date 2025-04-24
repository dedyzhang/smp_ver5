@extends('layouts.main')

@section('container')
    @if (\Request::route()->getName() === 'penilaian.guru.proyek.nilai')
        {{Breadcrumbs::render('proyek-nilai-guru',$proyek,$fasilitator)}}
    @else
        {{Breadcrumbs::render('proyek-nilai',$proyek,$fasilitator)}}
    @endif
    <div class="body-contain-customize col-12">
        <h5>Nilai Proyek</h5>
        <p>Halaman ini diperuntukkan guru untuk menginput fasilitator proyek</p>
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5 mt-3">
        <p><b>Data Proyek</b></p>
        <table class="table table-striped fs-13">
            <tr>
                <td width="30%">Judul Proyek</td>
                <td width="5%">:</td>
                <td>{{$proyek->judul}}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{$fasilitator->kelas->tingkat.$fasilitator->kelas->kelas}}</td>
            </tr>
            <tr>
                <td>Fasilitator</td>
                <td>:</td>
                <td>{{$fasilitator->guru->nama}}</td>
            </tr>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="body-contain-customize col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex mt-3">
        @if (!empty($arrayNilai))
            <button class="btn btn-sm btn-danger hapus-nilai">
                <i class="fas fa-trash-can"></i> Hapus Nilai
            </button>
        @else
            <button class="btn btn-sm btn-success tambah-nilai">
                <i class="fas fa-plus"></i> Tambah Nilai
            </button>
        @endif
    </div>
    <div class="clearfix"></div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-6 col-lg-5 col-xl-4 mt-3">
        <p><b>Kriteria Penilaian</b></p>
        <ul>
            <li>1 : {!! $rentang && $rentang[1] !== null ? $rentang[1]['singkat'] : "<i data-bs-toggle='tooltip' data-bs-title='Kalimat Rentang Penilaian Belum dibuat' class='text-danger fs-10'>Belum Disetting</i>" !!} ( {!! $rentang && $rentang[1] !== null ? $rentang[1]['rentang'] : "<i data-bs-toggle='tooltip' data-bs-title='Kalimat Rentang Penilaian Belum dibuat' class='text-danger fs-10'>Belum Disetting</i>" !!} )</li>
            <li>2 : {!! $rentang && $rentang[2] !== null ? $rentang[2]['singkat'] : "<i data-bs-toggle='tooltip' data-bs-title='Kalimat Rentang Penilaian Belum dibuat' class='text-danger fs-10'>Belum Disetting</i>" !!} ( {!! $rentang && $rentang[2] !== null ? $rentang[2]['rentang'] : "<i data-bs-toggle='tooltip' data-bs-title='Kalimat Rentang Penilaian Belum dibuat' class='text-danger fs-10'>Belum Disetting</i>" !!} )</li>
            <li>3 : {!! $rentang && $rentang[3] !== null ? $rentang[3]['singkat'] : "<i data-bs-toggle='tooltip' data-bs-title='Kalimat Rentang Penilaian Belum dibuat' class='text-danger fs-10'>Belum Disetting</i>" !!} ( {!! $rentang && $rentang[3] !== null ? $rentang[3]['rentang'] : "<i data-bs-toggle='tooltip' data-bs-title='Kalimat Rentang Penilaian Belum dibuat' class='text-danger fs-10'>Belum Disetting</i>" !!} )</li>
            <li>4 : {!! $rentang && $rentang[4] !== null ? $rentang[4]['singkat'] : "<i data-bs-toggle='tooltip' data-bs-title='Kalimat Rentang Penilaian Belum dibuat' class='text-danger fs-10'>Belum Disetting</i>" !!} ( {!! $rentang && $rentang[4] !== null ? $rentang[4]['rentang'] : "<i data-bs-toggle='tooltip' data-bs-title='Kalimat Rentang Penilaian Belum dibuat' class='text-danger fs-10'>Belum Disetting</i>" !!} )</li>
        </ul>
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
        <p><b>Penilaian Proyek</b></p>
        <div class="table-responsive">
            <table class="table table-bordered fs-12 nilai-table">
                <thead>
                    <tr class="text-center">
                        <td width="3%" rowspan="2" class="align-middle">No</td>
                        <td width="30%" rowspan="2" class="sticky align-middle" style="min-width: 150px">Siswa</td>
                        <td class="mainNilaiCell align-middle" colspan="{{$countDetail}}">Nilai</td>
                        <td class="align-middle" width="30%" rowspan="2" style="min-width: 200px">Catatan Proses</td>
                    </tr>
                    <tr class="text-center fs-10">
                        @foreach ($proyekDetail as $item)
                            <td width="5%" style="max-width:50px; word-wrap: break-word" class="open-close-tab tab_{{$item->uuid}} align-middle" data-bs-toggle="tooltip" data-bs-title="{{$item->subelemen->capaian}}" data-bs-placement="top">{{$item->subelemen->subelemen}}</td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $siswa)
                        <tr class="fs-10">
                            <td>{{$loop->iteration}}</td>
                            <td class="sticky">{{$siswa->nama}}</td>
                            @foreach ($proyekDetail as $item)
                                @if (!empty($arrayNilai[$siswa->uuid.".".$item->uuid]))
                                    <td contenteditable class="text-center @if ($arrayNilai[$siswa->uuid.".".$item->uuid]['nilai'] < 2) text-danger bg-danger-subtle @endif  nilai editable" data-uuid="{{$arrayNilai[$siswa->uuid.".".$item->uuid]['uuid']}}">{{$arrayNilai[$siswa->uuid.".".$item->uuid]['nilai']}}</td>
                                @else
                                    <td class="table-warning text-center">-</td>
                                @endif
                            @endforeach
                            @if (!empty($arrayDeskripsi[$siswa->uuid]))
                                <td contenteditable class="deskripsi editable" data-uuid="{{$arrayDeskripsi[$siswa->uuid]['uuid']}}">{{$arrayDeskripsi[$siswa->uuid]['deskripsi']}}</td>
                            @else
                                <td class="table-warning text-center">-</td>
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
        $('.tambah-nilai').click(function() {
            var confirmNilai = function() {
                loading();
                $.ajax({
                    type: 'post',
                    url : "{{route('penilaian.p5.nilai.tambah')}}",
                    data: { idKelas : "{{$fasilitator->id_kelas}}", idProyek : "{{$proyek->uuid}}"},
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        cAlert("green","Berhasil","Nilai proyek berhasil ditambahkan",true);
                    },
                    error: function(data) {
                        console.log(data.responseJSON);
                    }
                })
            }
            cConfirm("Perhatian","Apakah anda yakin untuk menambahkan nilai proyek?",confirmNilai);
        });
        $('.hapus-nilai').click(function() {
            var confirmNilai = function() {
                loading();
                $.ajax({
                    type: 'delete',
                    url : "{{route('penilaian.p5.nilai.hapus')}}",
                    data: { idKelas : "{{$fasilitator->id_kelas}}", idProyek : "{{$proyek->uuid}}"},
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        cAlert("green","Berhasil","Nilai proyek berhasil dihapus",true);
                    },
                    error: function(data) {
                        console.log(data.responseJSON);
                    }
                })
            }
            cConfirm("Perhatian","Apakah anda yakin untuk menghapus nilai proyek?",confirmNilai);
        });

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
            } else if(parseInt($(this).text()) > 4) {
                $(this).html(4);
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
        $('.deskripsi.editable').on('focus',function(e) {
            $(this).selectText();
        });
        $('.deskripsi.editable').on('keydown',function(e) {
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
        var doneTyping = function() {
            nilai = parseInt($(ini).text());
            var kkm = 2;

            //Hitung KKm
            if(nilai >= kkm) {
                $(ini).closest('td').removeClass('text-danger').removeClass('bg-danger-subtle');
            } else {
                $(ini).closest('td').addClass('text-danger').addClass('bg-danger-subtle');
            }
        };
        $('.simpan-nilai').click(function() {
            var nilai = [];
            var deskripsi = [];
            var valid = true;
            $('.nilai.editable').each(function() {
                if($(this).text() == '') {
                    valid = false;
                    $(this).focus();
                    return false;
                }
                nilai.push({uuid: $(this).data('uuid'), nilai: $(this).text()});
            });
            $('.deskripsi.editable').each(function() {
                deskripsi.push({uuid: $(this).data('uuid'), deskripsi: $(this).text()});
            });
            if(valid) {
                loading();
                $.ajax({
                    type: 'post',
                    url : "{{route('penilaian.p5.nilai.store')}}",
                    data: { nilai : nilai, deskripsi : deskripsi},
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        oAlert("green","Berhasil","Nilai proyek berhasil disimpan");
                        removeLoading();
                    },
                    error: function(data) {
                        console.log(data.responseJSON);
                    }
                });
            }
        });
    </script>
@endsection
