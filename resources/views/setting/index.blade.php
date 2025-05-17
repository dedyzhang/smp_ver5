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
                    id="penilaian-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#penilaian"
                    type="button"
                    role="tab"
                    aria-controls="penilaian"
                    aria-selected="false"
                >
                    Penilaian
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link"
                    id="kelulusan-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#kelulusan"
                    type="button"
                    role="tab"
                    aria-controls="kelulusan"
                    aria-selected="false"
                >
                    Kelulusan
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
            {{-- Utama --}}
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
                        <div class="col-12 p-0 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    Sistem Aturan
                                </div>
                                <div class="card-body">
                                    @php
                                        $peraturan = $setting->first(function($item) {
                                            if($item->jenis == "jenis_aturan") {
                                                return $item;
                                            }
                                        });
                                    @endphp
                                    <p class="fs-12">Sistem Peraturan yang sekolah terapkan</p>
                                    <select name="peraturan" id="peraturan" class="form-control fs-12">
                                        <option value="">Pilih Salah Satu</option>
                                        <option value="poin" {{$peraturan && $peraturan->nilai !== null && $peraturan->nilai == "poin" ? "selected" : ""}}>Poin</option>
                                        <option value="p3" {{$peraturan && $peraturan->nilai !== null && $peraturan->nilai == "p3" ? "selected" : ""}}>Prestasi, Partisipasi dan Pelanggaran</option>
                                    </select>
                                    <button class="btn btn-sm btn-warning text-warning-emphasis simpan-aturan mt-2">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                    <script>
                                        $('.simpan-aturan').click(function() {
                                            var sistemAturan = $('#peraturan').val();
                                            if(sistemAturan == "") {
                                                oAlert("orange","Perhatian","Pemilihan Aturan Tidak Boleh Kosong");
                                            } else {
                                                loading();
                                                $.ajax({
                                                    type: "POST",
                                                    url: "{{route('setting.aturan.pemilihan')}}",
                                                    data: {peraturan : sistemAturan},
                                                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                                                    success: function(data) {
                                                        removeLoading();
                                                    },
                                                    error: function(data) {
                                                        console.log(data.responseJSON.message);
                                                    }
                                                });
                                            }
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{-- Sekolah --}}
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
            {{-- Penilaian --}}
            <div
                class="tab-pane p-2"
                id="penilaian"
                role="tabpanel"
                aria-labelledby="penilaian-tab"
            >
                <div class="row m-0 p-0 pt-2">
                    @php
                        $settingAksesWalikelas = $setting->first(function($elem) {
                            return $elem->jenis == "akses_harian_walikelas";
                        })
                    @endphp
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 p-0">
                        <div class="card">
                            <div class="card-header">
                                Nilai Harian Untuk Walikelas
                            </div>
                            <div class="card-body">
                                <p class="fs-12">Aktifkan fitur di bawah ini untuk memberikan akses kepada wali kelas agar dapat melihat nilai harian.</p>
                                <div class="row m-0 p-0">
                                    <div class="form-check form-switch col-12">
                                        <input class="form-check-input" @if($settingAksesWalikelas && $settingAksesWalikelas->nilai == 1) checked @endif name="harian-walikelas" type="checkbox" role="switch" id="harian-walikelas">
                                        <label class="form-check-label" for="harian-walikelas">Fitur Akses Walikelas</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Pengaturan Penjabaran yang ingin dirata-ratakan --}}
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 p-0 mt-3">
                        @php
                            $settingPenjabaranRata = $setting->first(function($elem) {
                                return $elem->jenis == "penjabaran_rata";
                            });
                            if($settingPenjabaranRata) {
                                $array_penjabaran = unserialize($settingPenjabaranRata->nilai);
                            } else {
                                $array_penjabaran = array();
                            }
                        @endphp
                        <div class="card">
                            <div class="card-header">
                                Perhitungan Rata-Rata Penjabaran
                            </div>
                            <div class="card-body">
                                <p class="fs-12">Centang nilai apa saja yang akan dirata-ratakan</p>
                                <div class="row m-0 p-0 mt-2">
                                    <b class="mb-2">Bahasa Inggris</b>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="inggris" id="ing-listening" value="listening" @if (isset($array_penjabaran['inggris']) && in_array('listening',$array_penjabaran['inggris'])) checked @endif/>
                                        <label for="ing-listening">Listening</label>
                                    </div>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="inggris" id="ing-speaking" value="speaking" @if (isset($array_penjabaran['inggris']) && in_array('speaking',$array_penjabaran['inggris'])) checked @endif/>
                                        <label for="ing-speaking">Speaking</label>
                                    </div>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="inggris" id="ing-writing" value="writing" @if (isset($array_penjabaran['inggris']) && in_array('writing',$array_penjabaran['inggris'])) checked @endif/>
                                        <label for="ing-writing">Writing</label>
                                    </div>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="inggris" id="ing-reading" value="reading" @if (isset($array_penjabaran['inggris']) && in_array('reading',$array_penjabaran['inggris'])) checked @endif/>
                                        <label for="ing-reading">Reading</label>
                                    </div>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="inggris" id="ing-grammar" value="grammar" @if (isset($array_penjabaran['inggris']) && in_array('grammar',$array_penjabaran['inggris'])) checked @endif/>
                                        <label for="ing-grammar">Grammar</label>
                                    </div>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="inggris" id="ing-vocabulary" value="vocabulary" @if (isset($array_penjabaran['inggris']) && in_array('vocabulary',$array_penjabaran['inggris'])) checked @endif/>
                                        <label for="ing-vocabulary">Vocabulary</label>
                                    </div>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="inggris" id="ing-singing" value="singing" @if (isset($array_penjabaran['inggris']) && in_array('singing',$array_penjabaran['inggris'])) checked @endif/>
                                        <label for="ing-singing">Singing</label>
                                    </div>
                                </div>
                                <div class="row m-0 p-0 mt-2">
                                    <b class="mb-2">Mandarin</b>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="mandarin" id="man-listening" value="listening" @if (isset($array_penjabaran['mandarin']) && in_array('listening',$array_penjabaran['mandarin'])) checked @endif/>
                                        <label for="man-listening">听力</label>
                                    </div>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="mandarin" id="man-speaking" value="speaking" @if (isset($array_penjabaran['mandarin']) && in_array('speaking',$array_penjabaran['mandarin'])) checked @endif/>
                                        <label for="man-speaking">会话</label>
                                    </div>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="mandarin" id="man-writing" value="writing" @if (isset($array_penjabaran['mandarin']) && in_array('writing',$array_penjabaran['mandarin'])) checked @endif/>
                                        <label for="man-writing">书写</label>
                                    </div>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="mandarin" id="man-reading" value="reading" @if (isset($array_penjabaran['mandarin']) && in_array('reading',$array_penjabaran['mandarin'])) checked @endif/>
                                        <label for="man-reading">阅读</label>
                                    </div>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="mandarin" id="man-vocabulary" value="vocabulary" @if (isset($array_penjabaran['mandarin']) && in_array('vocabulary',$array_penjabaran['mandarin'])) checked @endif/>
                                        <label for="man-vocabulary">词汇</label>
                                    </div>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="mandarin" id="man-singing" value="singing" @if (isset($array_penjabaran['mandarin']) && in_array('singing',$array_penjabaran['mandarin'])) checked @endif/>
                                        <label for="man-singing">唱歌</label>
                                    </div>
                                </div>
                                <div class="row m-0 p-0 mt-2">
                                    <b class="mb-2">Komputer</b>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="komputer" id="pengetahuan" value="pengetahuan" @if (isset($array_penjabaran['komputer']) && in_array('pengetahuan',$array_penjabaran['komputer'])) checked @endif/>
                                        <label for="pengetahuan">Pengetahuan</label>
                                    </div>
                                    <div class="form-check col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 form-check-inline">
                                        <input type="checkbox" name="komputer" id="keterampilan" value="keterampilan" @if (isset($array_penjabaran['komputer']) && in_array('keterampilan',$array_penjabaran['komputer'])) checked @endif />
                                        <label for="keterampilan">Keterampilan</label>
                                    </div>
                                </div>
                                <div class="row m-0 p-0 mt-2">
                                    <div class="col-12">
                                        <button class="btn btn-sm btn-warning text-warning-emphasis simpan-rata-penjabaran"><i class="fas fa-save"></i> Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Pengaturan Rumus Nilai Untuk Nilai Rapor --}}
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 p-0 mt-3">
                        @php
                            $settingRumusRapor = $setting->first(function($elem) {
                                return $elem->jenis == "rumus_rapor";
                            });
                        @endphp
                        <div class="card">
                            <div class="card-header">
                                Perhitungan Nilai Rapor
                            </div>
                            <div class="card-body">
                                <p class="fs-12">Centang Rumus nilai yang akan digunakan</p>
                                <div class="row m-0 p-0 mt-2">
                                    <div class="form-check col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-check-inline">
                                        <input type="radio" name="rumus" id="bagi4" value="bagi4" @if (isset($settingRumusRapor) && $settingRumusRapor->nilai == "bagi4") checked @endif>
                                        <label for="bagi4">((2 x Rata-Rata Formatif ) + Rata-Rata Sumatif + Nilai Sumatif Akhir Semester ) / 4</label>
                                    </div>
                                    <div class="form-check col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-check-inline">
                                        <input type="radio" name="rumus" id="bagi3" value="bagi3" @if (isset($settingRumusRapor) && $settingRumusRapor->nilai == "bagi3") checked @endif/>
                                        <label for="bagi3">(Rata-Rata Formatif + Rata-Rata Sumatif + Nilai Sumatif Akhir Semester ) / 3</label>
                                    </div>
                                    <div class="form-check col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-check-inline">
                                        <input type="radio" name="rumus" id="jumlahDulu" value="jumlahDulu" @if (isset($settingRumusRapor) && $settingRumusRapor->nilai == "jumlahDulu") checked @endif/>
                                        <label for="jumlahDulu">(Nilai Formatif + Nilai Sumatif + Nilai Sumatif Akhir Semester ) / Total Nilai</label>
                                    </div>
                                </div>

                                <div class="row m-0 p-0 mt-2">
                                    <div class="col-12">
                                        <button class="btn btn-sm btn-warning text-warning-emphasis simpan-rumus-nilai-rapor"><i class="fas fa-save"></i> Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Pengaturan Rentang Penilaian Proyek --}}
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 p-0 mt-3">
                        @php
                            $settingRentangProyek = $setting->first(function($elem) {
                                return $elem->jenis == "rentang_penilaian_proyek";
                            });
                            if($settingRentangProyek) {
                                $array_proyek = unserialize($settingRentangProyek->nilai);
                            } else {
                                $array_proyek = array();
                            }
                        @endphp
                        <div class="card">
                            <div class="card-header">
                               Pengaturan Bahasa Rentang Proyek
                            </div>
                            <div class="card-body">
                                <p class="fs-12">Masukkan Kalimat Rentang Penilaian Proyek</p>
                                <div class="row m-0 p-0 mt-2">
                                    <div class="input-group form-group fs-11 col-12 col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                        <span class="input-group-text" id="basic-addon1">1</span>
                                        <input type="text" class="form-control" placeholder="Kalimat Singkat" name="singkat-1" id="singkat-1" aria-describedby="basic-addon1" value="{{$array_proyek && $array_proyek[1] !== null ? $array_proyek[1]['singkat'] : ""}}">
                                        <input type="text" class="form-control" placeholder="Kalimat Rentang Penilaian" name="rentang-1" id="rentang-1" aria-describedby="basic-addon1" value="{{$array_proyek && $array_proyek[1] !== null ? $array_proyek[1]['rentang'] : ""}}">
                                    </div>
                                    <div class="form-group pt-0 mb-1 mb-4">
                                        <label for="deskripsi-1">Deskripsi Rentang 1</label>
                                        <input type="text" class="form-control" placeholder="Masukkan Deskripsi Rentang Nilai" name="deskripsi-1" id="deskripsi-1" value="{{$array_proyek && $array_proyek[1] !== null ? $array_proyek[1]['deskripsi'] : ""}}">
                                    </div>
                                    <div class="input-group form-group fs-11 col-12 col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                        <span class="input-group-text" id="basic-addon2">2</span>
                                        <input type="text" class="form-control" placeholder="Kalimat Singkat" name="singkat-2" id="singkat-2" aria-describedby="basic-addon2" value="{{$array_proyek && $array_proyek[2] !== null ? $array_proyek[2]['singkat'] : ""}}">
                                        <input type="text" class="form-control" placeholder="Kalimat Rentang Penilaian" name="rentang-2" id="rentang-2" aria-describedby="basic-addon2" value="{{$array_proyek && $array_proyek[2] !== null ? $array_proyek[2]['rentang'] : ""}}">
                                    </div>
                                    <div class="form-group pt-0 mb-1 mb-4">
                                        <label for="deskripsi-2">Deskripsi Rentang 2</label>
                                        <input type="text" class="form-control" placeholder="Masukkan Deskripsi Rentang Nilai" name="deskripsi-2" id="deskripsi-2" value="{{$array_proyek && $array_proyek[2] !== null ? $array_proyek[2]['deskripsi'] : ""}}">
                                    </div>
                                    <div class="input-group form-group fs-11 col-12 col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                        <span class="input-group-text" id="basic-addon3">3</span>
                                        <input type="text" class="form-control" placeholder="Kalimat Singkat" name="singkat-3" id="singkat-3" aria-describedby="basic-addon3" value="{{$array_proyek && $array_proyek[3] !== null ? $array_proyek[3]['singkat'] : ""}}">
                                        <input type="text" class="form-control" placeholder="Kalimat Rentang Penilaian" name="rentang-3" id="rentang-3" aria-describedby="basic-addon3" value="{{$array_proyek && $array_proyek[3] !== null ? $array_proyek[3]['rentang'] : ""}}">
                                    </div>
                                    <div class="form-group pt-0 mb-1 mb-4">
                                        <label for="deskripsi-3">Deskripsi Rentang 3</label>
                                        <input type="text" class="form-control" placeholder="Masukkan Deskripsi Rentang Nilai" name="deskripsi-3" id="deskripsi-3" value="{{$array_proyek && $array_proyek[3] !== null ? $array_proyek[3]['deskripsi'] : ""}}">
                                    </div>
                                    <div class="input-group form-group fs-11 col-12 col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                        <span class="input-group-text" id="basic-addon4">4</span>
                                        <input type="text" class="form-control" placeholder="Kalimat Singkat" name="singkat-4" id="singkat-4" aria-describedby="basic-addon4" value="{{$array_proyek && $array_proyek[4] !== null ? $array_proyek[4]['singkat'] : ""}}">
                                        <input type="text" class="form-control" placeholder="Kalimat Rentang Penilaian" name="rentang-4" id="rentang-4" aria-describedby="basic-addon4" value="{{$array_proyek && $array_proyek[4] !== null ? $array_proyek[4]['rentang'] : ""}}">
                                    </div>
                                     <div class="form-group pt-0 mb-1 mb-4">
                                        <label for="deskripsi-4">Deskripsi Rentang 4</label>
                                        <input type="text" class="form-control" placeholder="Masukkan Deskripsi Rentang Nilai" name="deskripsi-4" id="deskripsi-4" value="{{$array_proyek && $array_proyek[4] !== null ? $array_proyek[4]['deskripsi'] : ""}}">
                                    </div>
                                </div>

                                <div class="row m-0 p-0 mt-2">
                                    <div class="col-12">
                                        <button class="btn btn-sm btn-warning text-warning-emphasis simpan-rentang-penilaian-proyek"><i class="fas fa-save"></i> Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $('input[name="harian-walikelas"]').change(function() {
                        if($(this).is(':checked')) {
                            var akses = 1;
                        } else {
                            var akses = 0;
                        }
                        loading();
                        $.ajax({
                            type: "POST",
                            url: "{{route('setting.walikelas.harian')}}",
                            headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                            data: {akses},
                            success: function(data) {
                                removeLoading();
                            },
                            error: function(data) {
                                console.log(data.responseJSON.message);
                            }
                        })
                    });
                    $('.simpan-rata-penjabaran').click(function() {
                        var inggris = $('input[name="inggris"]:checked').map(function() {
                            return $(this).val();
                        }).get();
                        var mandarin = $('input[name="mandarin"]:checked').map(function() {
                            return $(this).val();
                        }).get();
                        var komputer = $('input[name="komputer"]:checked').map(function() {
                            return $(this).val();
                        }).get();

                        var url = "{{route('setting.penjabaran')}}";
                        loading();
                        $.ajax({
                            type: "post",
                            url: url,
                            data: {inggris: inggris, mandarin: mandarin, komputer: komputer},
                            headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                            success: function(data) {
                                removeLoading();
                            },
                            error: function(data) {
                                console.log(data.responseJSON.message);
                            }
                        })
                    })
                    $('.simpan-rumus-nilai-rapor').click(function() {
                        var rumus = $('input[name="rumus"]:checked').val();
                        loading();
                        $.ajax({
                            type: "post",
                            url: '{{route("setting.penilaian.rumus.rapor")}}',
                            data: {rumus: rumus},
                            headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                            success: function(data) {
                                removeLoading();
                            },
                            error: function(data) {
                                console.log(data.responseJSON.message);
                            }
                        });
                    });
                    $('.simpan-rentang-penilaian-proyek').click(function() {
                        var rentang1 = $('#rentang-1').val();
                        var rentang2 = $('#rentang-2').val();
                        var rentang3 = $('#rentang-3').val();
                        var rentang4 = $('#rentang-4').val();

                        var singkat1 = $('#singkat-1').val();
                        var singkat2 = $('#singkat-2').val();
                        var singkat3 = $('#singkat-3').val();
                        var singkat4 = $('#singkat-4').val();

                        var deskripsi1 = $('#deskripsi-1').val();
                        var deskripsi2 = $('#deskripsi-2').val();
                        var deskripsi3 = $('#deskripsi-3').val();
                        var deskripsi4 = $('#deskripsi-4').val();

                        loading();
                        $.ajax({
                            type: "post",
                            url: '{{route("setting.penilaian.rentang.proyek")}}',
                            data: {rentang1 : rentang1, rentang2 : rentang2, rentang3 : rentang3, rentang4 : rentang4, singkat1: singkat1, singkat2 : singkat2, singkat3 : singkat3, singkat4 : singkat4, deskripsi1 : deskripsi1, deskripsi2 : deskripsi2, deskripsi3 : deskripsi3, deskripsi4 : deskripsi4},
                            headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                            success: function(data) {
                                removeLoading();
                            },
                            error: function(data) {
                                console.log(data.responseJSON.message);
                            }
                        });
                    });
                </script>
            </div>
            {{-- Kelulusan --}}
            <div
                class="tab-pane p-2"
                id="kelulusan"
                role="tabpanel"
                aria-labelledby="kelulusan-tab"
            >
                <div class="row m-0 p-0 pt-2">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                        <div class="card">
                            <div class="card-header">
                                A. Kelulusan
                            </div>
                            <div class="card-body">
                                @php
                                    $tanggalKelulusan = $setting->first(function($elem) {
                                        return $elem->jenis == "tanggal_kelulusan";
                                    });
                                    if($tanggalKelulusan) {
                                        $array_tanggal_kelulusan = unserialize($tanggalKelulusan->nilai);
                                    } else {
                                        $array_tanggal_kelulusan = array();
                                    }
                                @endphp
                                <div class="form-group col-10">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="kelulusanswitch" {{$array_tanggal_kelulusan && isset($array_tanggal_kelulusan['tampil']) ? ($array_tanggal_kelulusan['tampil'] == "true" ? "checked" : "") : "" }}>
                                        <label class="form-check-label" for="kelulusanswitch">Tampilkan Kelulusan</label>
                                    </div>
                                </div>
                                <div class="form-group col-10">
                                    <label for="tanggal-kelulusan">Tanggal Kelulusan</label>
                                    <input type="datetime-local" class="form-control" name="tanggal-kelulusan" id="tanggal-kelulusan" placeholder="Masukkan Tanggal Kelulusan Anda" value="{{$array_tanggal_kelulusan && isset($array_tanggal_kelulusan['kelulusan']) ? $array_tanggal_kelulusan['kelulusan'] : "" }}">
                                </div>
                                <div class="form-group col-10">
                                    <label for="tanggal-rapat">Tanggal Rapat</label>
                                    <input type="date" class="form-control" name="tanggal-rapat" id="tanggal-rapat" placeholder="Masukkan Tanggal Rapat Anda" value="{{$array_tanggal_kelulusan && isset($array_tanggal_kelulusan['rapat']) ? $array_tanggal_kelulusan['rapat'] : "" }}">
                                </div>
                                <div class="button-place mt-3">
                                    <button class="btn btn-sm btn-warning text-warning-emphasis simpan-tanggal-kelulusan">
                                        <i class="fas fa-save"></i> Simpan Tanggal
                                    </button>
                                </div>
                            </div>
                        </div>
                        <script>
                            $('.simpan-tanggal-kelulusan').click(function(){
                                var tanggalKelulusan = $('#tanggal-kelulusan').val();
                                var tanggalRapat = $('#tanggal-rapat').val();
                                var kelulusanSwitch = $('#kelulusanswitch').is(':checked');

                                if(tanggalKelulusan == "" && tanggalRapat == "") {
                                    oAlert("yellow","Perhatian","Tanggal tidak boleh kosong");
                                } else {
                                    loading();
                                    $.ajax({
                                        type: "POST",
                                        url: "{{route('setting.tanggal.kelulusan')}}",
                                        headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                                        data: {kelulusan : tanggalKelulusan, rapat : tanggalRapat,kelulusanSwitch : kelulusanSwitch},
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
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 mt-3">
                        <div class="card">
                            <div class="card-header">
                                B. Pelajaran Yang Ditampilkan
                            </div>
                            <div class="card-body">
                                @php
                                    $pelajaran_kelulusan_array = $setting->first(function($item) {
                                        if($item->jenis == "pelajaran_kelulusan") {
                                            return $item;
                                        }
                                    });
                                    if($pelajaran_kelulusan_array !== null) {
                                        $kelulusan_array = explode(',',$pelajaran_kelulusan_array['nilai']);
                                    } else {
                                        $kelulusan_array = array();
                                    }
                                @endphp
                                <label for="mapelKelulusan">Mapel Kelulusan Yang Ditampilkan</label>
                                <select name="mapelKelulusan" id="mapelKelulusan" data-toggle="select" multiple="multiple">
                                    <option value="">Tidak Ada Mapel</option>
                                    @foreach ($pelajaran as $item)
                                        @php
                                            if(in_array($item->uuid,$kelulusan_array)) {
                                                $selected = "selected";
                                            } else {
                                                $selected = "";
                                            }
                                        @endphp
                                        <option {{$selected}} value="{{$item->uuid}}">{{$item->pelajaran_singkat}}</option>
                                    @endforeach
                                </select>
                                <div class="button-place mt-3">
                                    <button class="simpan-pelajaran-kelulusan btn btn-sm btn-warning text-warning-emphasis">
                                        <i class="fas fa-save"></i> Simpan Pelajaran
                                    </button>
                                </div>
                            </div>
                            <script>
                                $('.simpan-pelajaran-kelulusan').click(function() {
                                    var pelajaran = $('#mapelKelulusan').val();
                                    loading();
                                    $.ajax({
                                        type: "POST",
                                        url: "{{route('setting.mapel.kelulusan')}}",
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
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Backup --}}
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
