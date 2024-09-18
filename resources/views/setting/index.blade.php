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
                    <div class="col-12 col-sm-12 col-md-8 col-lg-6 col-xl-6">
                        <p><b>A. Semester dan Tahun Pelajaran</b></p>
                        <div class="form-group">
                            <label for="tp">Tahun Pelajaran</label>
                            <input type="text" name="tp" id="tp" class="form-control" value="{{$semester->tp}}" placeholder="Masukkan Tahun Pelajaran Berjalan">
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
                    <div class="col-12 col-sm-12 col-md-8 col-lg-7 col-xl-7 mt-4">
                        <p><b>B. Pengaturan NIS ( Nomor Induk Siswa )</b></p>
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
                                Poin Terlambat
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
