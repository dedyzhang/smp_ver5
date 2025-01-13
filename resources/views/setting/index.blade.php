@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Settings</h5>
        <p>Halaman Untuk Admin mengatur segala pengaturan yang ada didalam apilkasi</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="tab-setting" role="tablist">
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link active"
                    id="main-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#main"
                    type="button"
                    role="tab"
                    aria-controls="main"
                    aria-selected="true"
                >
                    Utama
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link"
                    id="sekolah-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#sekolah"
                    type="button"
                    role="tab"
                    aria-controls="sekolah"
                    aria-selected="false"
                >
                    Sekolah
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link"
                    id="profile-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#profile"
                    type="button"
                    role="tab"
                    aria-controls="profile"
                    aria-selected="false"
                >
                    Backup
                </button>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div
                class="tab-pane active p-2"
                id="main"
                role="tabpanel"
                aria-labelledby="main-tab"
            >
                <div class="row m-0 p-0 pt-2">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                        <div class="card">
                            <div class="card-header">
                                A. Semester dan Tahun Pelajaran
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="tp">Tahun Pelajaran ( Format Harus xxxx/xxxx )</label>
                                    <input type="text" name="tp" id="tp" class="form-control" value="{{$semester->tp}}" placeholder="Masukkan Tahun Pelajaran Berjalan" placeholder="XXXX/XXXX">
                                </div>
                                <p class="mt-3 fs-12 mb-0">Semester</p>
                                <div class="form-group form-check-inline mt-1">
                                    <input type="radio" name="semester" id="ganjil" {{$semester->semester == 1 ? "checked" : ""}} class="form-check-input" value="1">
                                    <label class="form-check-label me-4" for="ganjil">Ganjil</label>
                                    <input type="radio" name="semester" id="genap" {{$semester->semester == 2 ? "checked" : ""}} class="form-check-input" value="2">
                                    <label class="form-check-label" for="genap">Genap</label>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-warning text-warning-emphasis simpan-tp">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $('.simpan-tp').click(function() {
                            var tp = $('#tp').val();
                            var semester = $('input[name="semester"]:checked').val();

                            if(tp == "" || semester == "") {
                                oAlert('red','perhatian', 'Tidak boleh ada form yang kosong');
                            } else {
                                loading();
                                $.ajax({
                                    type: "post",
                                    url: "{{route('setting.semester')}}",
                                    data: {tp: tp, semester: semester},
                                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                                    success: function(data) {
                                        removeLoading();
                                    },
                                    error: function(data) {
                                        console.log(data.responseJSON.message);
                                    }
                                })
                            }
                        });
                    </script>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 mt-4">
                        <div class="card">
                            <div class="card-header">
                                B. Pengaturan NIS ( Nomor Induk Siswa )
                            </div>
                            <div class="card-body">
                                <div class="row m-0 p-0">
                                    <div class="form-group col-4">
                                        <label for="first_nis" class="fs-10">NIS 1</label>
                                        <input type="text" name="first_nis" id="first_nis" class="form-control" value="{{$nis->first_nis}}">
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="second_nis" class="fs-10">NIS 2</label>
                                        <input type="text" name="second_nis" id="second_nis" class="form-control" value="{{$nis->second_nis}}">
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="third_nis" class="fs-10">NIS 3</label>
                                        <input type="text" name="third_nis" id="third_nis" class="form-control" value="{{$nis->third_nis}}">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-warning text-warning-emphasis simpan-nis">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $('.simpan-nis').click(function() {
                            var nis1 = $('#first_nis').val();
                            var nis2 = $('#second_nis').val();
                            var nis3 = $('#third_nis').val();

                            if(nis1 == "" || nis2 == "" || nis3 == "") {
                                oAlert('red','perhatian', 'Tidak boleh ada form yang kosong');
                            } else {
                                loading();
                                $.ajax({
                                    type: "post",
                                    url: "{{route('setting.nis')}}",
                                    data: {first_nis: nis1, second_nis: nis2, third_nis: nis3},
                                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                                    success: function(data) {
                                        removeLoading();
                                    },
                                    error: function(data) {
                                        console.log(data.responseJSON.message);
                                    }
                                })
                            }
                        });
                    </script>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 mt-4">
                        <div class="card">
                            <div class="card-header">
                                B. Pengaturan Absensi Kehadiran Guru
                            </div>
                            <div class="card-body">
                                @php
                                    $absensiGuru = $setting->first(function($item) {
                                        if($item->jenis == "absensi_guru") {
                                            return $item;
                                        }
                                    });
                                    $tokenAbsensi = $setting->first(function($item) {
                                        if($item->jenis == "absensi_token") {
                                            return $item;
                                        }
                                    });
                                    if($tokenAbsensi) {
                                        $split = explode('|',$tokenAbsensi->nilai);
                                        $datang = $split[0];
                                        $pulang = $split[1];
                                    } else {
                                        $datang = "";
                                        $pulang = "";
                                    }
                                @endphp
                                <div class="row form-group m-0 p-0">
                                    <div class="col-12 m-0 p-0">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="pilihan-absensi-guru" id="cetak-sendiri" @if ($absensiGuru && $absensiGuru->nilai == "cetak-sendiri") checked @endif value="cetak-sendiri"/>
                                            <label class="form-check-label" for="cetak-sendiri"> <b>Absensi Qrcode Cetak</b></label>
                                        </div>
                                    </div>
                                    <div class="col-12 m-0 p-0 mb-4 cetak-barcode @if ($absensiGuru && $absensiGuru->nilai == "cetak-sendiri") d-block @else d-none @endif">
                                        <label for="datang">Absensi Datang</label>
                                        <input type="text" name="datang" id="datang" value="{{$datang}}" disabled class="form-control">
                                        <label for="pulang">Absensi Pulang</label>
                                        <input type="text" name="pulang" id="pulang" value="{{$pulang}}" disabled class="form-control">
                                        <div class="col-12 d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex m-0 mt-3 p-0 gap-2">
                                            <button class="btn btn-sm btn-success generate-barcode">
                                                <i class="fas fa-recycle"></i> Generate Qrcode
                                            </button>
                                            <button class="btn btn-sm btn-warning print-barcode">
                                                <i class="fas fa-print"></i> Print Qrcode
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12 m-0 p-0">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="pilihan-absensi-guru" id="barcode-harian" @if ($absensiGuru && $absensiGuru->nilai == "automatis") checked @endif value="automatis" />
                                            <label class="form-check-label" for="barcode-harian"> <b>Absensi Qrcode Otomatis</b></label>
                                        </div>
                                        <p class="fs-12">Qrcode Absensi Akan berubah setiap harinya. Akses Qrcode di link <a href="{{env('APP_URL')."qrcode"}}">{{env('APP_URL')."qrcode"}}</a> ini untuk mengakses barcodenya. <i>Link QR Code diharapkan agar dapat dirahasiakan demi menjaga keamanan dalam proses Absensi.</i></p>
                                    </div>
                                    <div class="modal fade in m-0 p-0" id="modal-print-barcode">
                                        <div class="modal-dialog modal-fullscreen">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <p class="modal-title">Qr Code Absensi</p>
                                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="printable-absensi-qrcode">
                                                        <img src="{{asset('img/barcode-absensi.jpg')}}" width="100%" height="auto">
                                                        <div id="qrcode-datang"></div>
                                                        <div id="qrcode-pulang"></div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-sm btn-warning text-warning-emphasis" onclick="window.print()">
                                                        <i class="fas fa-print"></i> Print Qr Code
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        $('input[name="pilihan-absensi-guru"]').change(function() {
                                            var pilihan = $('input[name="pilihan-absensi-guru"]:checked').val();
                                            loading();
                                            $.ajax({
                                                type: "post",
                                                url: "{{route('setting.absensi.method')}}",
                                                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                                                data: {method : pilihan},
                                                success: function(data) {
                                                    removeLoading();
                                                    if(pilihan == "cetak-sendiri") {
                                                        $('.cetak-barcode').removeClass('d-none').addClass('d-block');
                                                    } else {
                                                        $('.cetak-barcode').removeClass('d-block').addClass('d-none');
                                                    }
                                                },error: function(data) {
                                                    console.log(data.responseJSON.message);
                                                }
                                            })
                                        });
                                        $('.generate-barcode').click(function() {
                                            var generateConfirm = function() {
                                                loading();
                                                $.ajax({
                                                    type: "get",
                                                    url: "{{route('setting.absensi.generateBarcode')}}",
                                                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                                                    success: function(data) {
                                                        removeLoading();
                                                        $('#datang').val(data.datang);
                                                        $('#pulang').val(data.pulang);
                                                    }
                                                })
                                            };
                                            cConfirm("Perhatian","Apakah anda yakin untuk mengenerate ulang barcode?",generateConfirm);
                                        });
                                        $('.print-barcode').click(function() {
                                            $('#qrcode-datang').html('');
                                            $('#qrcode-pulang').html('');
                                            var datang = $('#datang').val();
                                            var pulang = $('#pulang').val();
                                            $('#qrcode-datang').qrcode({width: 300, height:300, text: datang});
                                            $('#qrcode-pulang').qrcode({width: 300, height:300, text: pulang});
                                            $('#modal-print-barcode').modal("show");
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="tab-pane p-2"
                id="sekolah"
                role="tabpanel"
                aria-labelledby="sekolah-tab"
            >
                <div class="row m-0 p-0 pt-2">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 p-0">
                        <div class="card">
                            <div class="card-header">
                                Data Sekolah
                            </div>
                            <div class="card-body">
                                <p class="fs-12">Setting ini untuk mengatur data identitas sekolah</p>
                                <div class="col-12 form-group">
                                    @php
                                        $identitasSekolah = $setting->first(function($item) {
                                            if($item->jenis == "nama_sekolah") {
                                                return $item;
                                            }
                                        });
                                    @endphp
                                    <label for="school">Sekolah</label>
                                    <input type="text" class="form-control validasi-identitas" name="sekolah" id="school" placeholder="Masukkan Nama Sekolah Anda" value="{{$identitasSekolah && $identitasSekolah->nilai ? $identitasSekolah->nilai : ""}}"/>
                                    <div class="invalid-feedback">Tidak Boleh Kosong</div>
                                </div>
                                <div class="col-12 form-group mt-2">
                                    @php
                                        $identitasKepala = $setting->first(function($item) {
                                            if($item->jenis == "kepala_sekolah") {
                                                return $item;
                                            }
                                        });
                                    @endphp
                                    <label for="kepala">Kepala Sekolah</label>
                                    <select name="kepala" id="kepala" data-toggle="select" class="form-control validasi-identitas">
                                        <option value="">Tidak Ada Kepala Sekolah</option>
                                        @php
                                            $guruArray = $guru->filter(function($item) {
                                                if($item->users->access == "kepala") {
                                                    return $item;
                                                }
                                            });
                                        @endphp
                                        @foreach ($guruArray as $item)
                                            <option value="{{$item->uuid}}" {{$identitasKepala && $identitasKepala->nilai == $item->uuid ? "selected" : ""}}>{{$item->nama}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Tidak Boleh Kosong</div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-sm btn-warning text-warning-emphasis mt-3 simpan-data-sekolah"><i class="fa-regular fa-save"></i> Simpan Biodata</button>
                                </div>
                                <script>
                                    $('.simpan-data-sekolah').click(function() {
                                        var sekolah = $('#school').val();
                                        var kepala = $('#kepala').val();
                                        error = 0;
                                        $('.validasi-identitas').each(function() {
                                            if($(this).val() == "") {
                                                error++;
                                                $(this).addClass('is-invalid').removeClass('is-valid');
                                            } else {
                                                $(this).addClass('is-valid').removeClass('is-invalid');
                                            }
                                            if(error == 0) {
                                                $.ajax({
                                                    type: "POST",
                                                    url: "{{route('setting.identitas.sekolah')}}",
                                                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                                                    data: {sekolah : sekolah, kepala : kepala},
                                                    success: function(data) {
                                                        console.log(data);
                                                    },
                                                    error: function(data) {
                                                        console.log(data.responseJSON.message);
                                                    }
                                                })
                                            }
                                        });

                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 p-0 mt-3">
                        <div class="card">
                            <div class="card-header">
                                Poin & Waktu Terlambat Siswa
                            </div>
                            <div class="card-body">
                                <p class="fs-12">Setting ini untuk mengatur poin yang akan dikenakan siswa jika terlambat melakukan absensi</p>
                                <div class="col-12 form-group">
                                    <label for="poinTerlambat">poin</label>
                                    @php
                                        $settingTerlambat = $setting->first(function($item) {
                                            if($item->jenis == "poin_terlambat") {
                                                return $item;
                                            }
                                        });
                                    @endphp
                                    <select name="poinTerlambat" id="poinTerlambat" data-toggle="select">
                                        <option value="">Tidak Ada Pengurangan</option>
                                        @foreach ($aturan as $item)
                                            <option value="{{$item->uuid}}" @if ($settingTerlambat && $settingTerlambat->nilai == $item->uuid) selected @endif>({{$item->kode}}) {{$item->aturan}}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-sm btn-warning text-warning-emphasis mt-3 simpan-poin-terlambat"><i class="fa-regular fa-save"></i> Simpan Aturan</button>
                                </div>
                                <div class="col-12 form-group mt-3">
                                    <label for="waktu_terlambat">Waktu Jika Terlambat</label>
                                    @php
                                        $waktuTerlambat = $setting->first(function($item) {
                                            if($item->jenis == "waktu_terlambat_siswa") {
                                                return $item;
                                            }
                                        });
                                    @endphp
                                    <input type="time" class="form-control" name="waktu_terlambat" id="waktu_terlambat" placeholder="Masukkan waktu siswa jika terlambat" value="{{$waktuTerlambat && $waktuTerlambat->nilai ? $waktuTerlambat->nilai : ""}}" />
                                    <div class="invalid-feedback">Tidak Boleh Kosong</div>
                                    <button class="btn btn-sm btn-warning text-warning-emphasis mt-3 simpan-waktu-terlambat"><i class="fa-regular fa-save"></i> Simpan Waktu</button>
                                </div>
                                <script>
                                    $('.simpan-poin-terlambat').click(function() {
                                        var poin = $('#poinTerlambat').val();
                                        var aturPoin = function() {
                                            var url = "{{route('setting.poinTerlambat')}}";
                                            loading();
                                            $.ajax({
                                                type: "POST",
                                                url : url,
                                                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                                                data: {poin: poin},
                                                success: function(data) {
                                                    removeLoading();
                                                    oAlert("green","sukses","Aturan berhasil disimpan");
                                                },
                                                error: function(data) {
                                                    var error = data.responseJSON.message;
                                                    console.log(error);
                                                }
                                            })
                                        }
                                        cConfirm("Perhatian","Apakah anda yakin untuk mengatur poin aturan ini sebagai poin yang dikenakan siswa pada saat terlambat ?",aturPoin);
                                    });
                                    $('.simpan-waktu-terlambat').click(function() {
                                        var waktu = $('#waktu_terlambat').val();
                                        if(waktu == "") {
                                            $('#waktu_terlambat').addClass('is-invalid').removeClass('is-valid');
                                        } else {
                                            $('#waktu_terlambat').removeClass('is-invalid').addClass('is-valid');
                                            var aturWaktu = function() {
                                                var url = "{{route('setting.waktuTerlambat')}}";
                                                loading();
                                                $.ajax({
                                                    type: "POST",
                                                    url : url,
                                                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                                                    data: {waktu: waktu},
                                                    success: function(data) {
                                                        removeLoading();
                                                        oAlert("green","sukses","Waktu Terlambat berhasil disimpan");
                                                    },
                                                    error: function(data) {
                                                        var error = data.responseJSON.message;
                                                        console.log(error);
                                                    }
                                                })
                                            }
                                            cConfirm("Perhatian","Apakah anda yakin untuk mengatur waktu terlambat siswa ?",aturWaktu);
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 p-0 mt-3">
                        <div class="card">
                            <div class="card-header">
                                Setting Rapor
                            </div>
                            <div class="card-body">
                                {{-- Form Setting Mapel yang ingin ditampilkan di rapor --}}
                                <p class="fs-12">Setting ini untuk cetak rapor semester</p>
                                <div class="col-12 form-group">
                                    @php
                                        $pelajaran_rapor_array = $setting->first(function($item) {
                                            if($item->jenis == "pelajaran_rapor") {
                                                return $item;
                                            }
                                        });
                                        if($pelajaran_rapor_array !== null) {
                                            $pelajaran_array = explode(',',$pelajaran_rapor_array['nilai']);
                                        } else {
                                            $pelajaran_array = array();
                                        }
                                    @endphp
                                    <label for="mapel">Mapel Yang Ditampilkan</label>
                                    <select name="mapel" id="mapel" data-toggle="select" multiple="multiple">
                                        <option value="">Tidak Ada Mapel</option>
                                        @foreach ($pelajaran as $item)
                                            @php
                                                if(in_array($item->uuid,$pelajaran_array)) {
                                                    $selected = "selected";
                                                } else {
                                                    $selected = "";
                                                }
                                            @endphp
                                            <option {{$selected}} value="{{$item->uuid}}">{{$item->pelajaran_singkat}}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-sm btn-warning text-warning-emphasis mt-3 simpan-pelajaran"><i class="fa-regular fa-save"></i> Simpan Pelajaran</button>
                                </div>
                                {{-- Form Tanggal Rapor --}}
                                <div class="col-12 form-group mt-3">
                                    @php
                                        $tanggal_rapor_array = $setting->first(function($item) {
                                            if($item->jenis == "tanggal_rapor") {
                                                return $item;
                                            }
                                        });
                                    @endphp
                                    <label for="tanggal_rapor">Tanggal Rapor</label>
                                    <input type="date" class="form-control" id="tanggal_rapor" name="tanggal_rapor" value="{{$tanggal_rapor_array && $tanggal_rapor_array->nilai ? $tanggal_rapor_array->nilai : ""}}">
                                    <button class="btn btn-sm btn-warning text-warning-emphasis mt-3 simpan-tanggal-rapor"><i class="fa-regular fa-save"></i> Simpan Tanggal Rapor</button>
                                </div>
                                <div class="col-12 form-group mt-5">
                                    @php
                                        $kop_rapor_array = $setting->first(function($item) {
                                            if($item->jenis == "kop_rapor") {
                                                return $item;
                                            }
                                        });
                                    @endphp
                                    <p class="fs-12">Setting Kop Rapor</p>
                                    <textarea class="tinymce-rapor" id="kopRapor">{{$kop_rapor_array && $kop_rapor_array->nilai ? $kop_rapor_array->nilai : ""}}</textarea>
                                    <button class="btn btn-sm btn-warning text-warning-emphasis mt-3 simpan-kop-surat">
                                        <i class="fas fa-save"></i> Simpan Kop Rapor
                                    </button>
                                </div>
                                <div class="col-12 form-group mt-5">
                                    <p><b>Fase Rapor Kurikulum Merdeka</b></p>
                                    @php
                                        $fase_rapor = $setting->first(function($item) {
                                            if($item->jenis == "fase_rapor") {
                                                return $item;
                                            }
                                        });

                                        if(isset($fase_rapor)) {
                                            $fase_rapor_array = unserialize($fase_rapor->nilai);
                                        } else {
                                            $fase_rapor_array = array();
                                        }
                                    @endphp
                                    @foreach ($kelas as $item)
                                        <div class="row m-0 p-0 mt-1">
                                            <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                                                <label for="kelas-{{$item->tingkat}}">Tingkat {{$item->tingkat}}</label>
                                            </div>
                                            <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                                                <select name="kelas-{{$item->tingkat}}" id="kelas-{{$item->tingkat}}" class="form-control faseKelas" data-tingkat="{{$item->tingkat}}">
                                                    <option value="A" {{isset($fase_rapor_array[$item->tingkat]) ? $fase_rapor_array[$item->tingkat] == "A" ? "selected" : "" : ""}}>A</option>
                                                    <option value="B" {{isset($fase_rapor_array[$item->tingkat]) ? $fase_rapor_array[$item->tingkat] == "B" ? "selected" : "" : ""}}>B</option>
                                                    <option value="C" {{isset($fase_rapor_array[$item->tingkat]) ? $fase_rapor_array[$item->tingkat] == "C" ? "selected" : "" : ""}}>C</option>
                                                    <option value="D" {{isset($fase_rapor_array[$item->tingkat]) ? $fase_rapor_array[$item->tingkat] == "D" ? "selected" : "" : ""}}>D</option>
                                                    <option value="E" {{isset($fase_rapor_array[$item->tingkat]) ? $fase_rapor_array[$item->tingkat] == "E" ? "selected" : "" : ""}}>E</option>
                                                    <option value="F" {{isset($fase_rapor_array[$item->tingkat]) ? $fase_rapor_array[$item->tingkat] == "F" ? "selected" : "" : ""}}>F</option>
                                                </select>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row m-0 p-0 mt-1">
                                        <div class="form-group col-12">
                                            <button class="btn btn-sm btn-warning text-warning-emphasis simpan-fase-rapor">
                                                <i class="fas fa-save"></i> Simpan Fase Rapor
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $('.simpan-pelajaran').click(function() {
                                        var pelajaran = $('#mapel').val();
                                        loading();
                                        $.ajax({
                                            type: "POST",
                                            url: "{{route('setting.rapor.pelajaran')}}",
                                            data: {pelajaran: pelajaran},
                                            headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                                            success: function(data) {
                                                removeLoading();
                                                console.log(data);
                                            },
                                            error: function(data) {
                                                console.log(data.responseJSON.message);
                                            }
                                        })
                                    });
                                    $('.simpan-tanggal-rapor').click(function() {
                                        var tanggalRapor = $('#tanggal_rapor').val();
                                        if(tanggalRapor == "") {
                                            oAlert("orange","Perhatian","Tanggal rapor tidak boleh kosong");
                                        } else {
                                            loading();
                                            $.ajax({
                                                type: "POST",
                                                url: "{{route('setting.rapor.tanggal')}}",
                                                data: {tanggal : tanggalRapor},
                                                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                                                success: function(data) {
                                                    removeLoading();
                                                },
                                                error: function(data) {
                                                    console.log(data.responseJSON.message);
                                                }
                                            })
                                        }
                                    });
                                    $('.simpan-kop-surat').click(function() {
                                        var kopSurat = tinymce.get("kopRapor").getContent();
                                        if(kopSurat == "") {
                                            oAlert("orange","Perhatian","Kop rapor tidak boleh kosong");
                                        } else {
                                            loading();
                                            $.ajax({
                                                type: "POST",
                                                url: "{{route('setting.rapor.kop')}}",
                                                data: {kop : kopSurat},
                                                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                                                success: function(data) {
                                                    removeLoading();
                                                },
                                                error: function(data) {
                                                    console.log(data.responseJSON.message);
                                                }
                                            })
                                        }
                                    });
                                    $('.simpan-fase-rapor').click(function() {
                                        var faseArray = [];

                                        $('.faseKelas').each(function() {
                                            var value = $(this).val();
                                            var data = $(this).data('tingkat');

                                            faseArray[data] = value;
                                        });
                                        loading();
                                        $.ajax({
                                            type: "POST",
                                            url: "{{route('setting.rapor.fase')}}",
                                            data: {fase : faseArray},
                                            headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                                            success: function(data) {
                                                removeLoading();
                                            },
                                            error: function(data) {
                                                console.log(data.responseJSON.message);
                                            }
                                        })
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="tab-pane p-2"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                 Backup
            </div>
        </div>

    </div>
@endsection
