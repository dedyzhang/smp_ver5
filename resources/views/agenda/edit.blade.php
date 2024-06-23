@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('agenda-edit',$agenda,$agenda->jadwal->kelas,$agenda->jadwal->pelajaran,$agenda->jadwal->waktu)}}
    <div class="body-contain-customize col-12">
        <h5><b>Edit Agenda</b></h5>
        <p>Halaman Edit Agenda diperuntukkan guru mengedit agenda harian mengajar, proses selama mengajar serta hasil dan tindak lanjut dalam mengajar</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>A.Tanggal dan Jadwal</b></p>
        <div class="row m-0 p-0 section-a">
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2">
                <label for="tanggal">tanggal</label>
                <input type="date" name="tanggal" id="tanggal" disabled class="form-control need-validasi" value="{{$agenda->tanggal}}">
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2">
                <label for="jadwal">Jadwal</label>
                <input type="text" name="jadwal" id="jadwal" class="form-control" value="{{$agenda->jadwal->kelas->tingkat.$agenda->jadwal->kelas->kelas." - ".$agenda->jadwal->pelajaran->pelajaran_singkat."( ".$agenda->jadwal->waktu->waktu_mulai." )"}}" disabled>
            </div>
        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>B. Agenda</b></p>
        <div class="row m-0 p-0">
            <div class="form-group col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 p-2">
                <label for="pembahasan">Pembahasan</label>
                <textarea class="form-control need-validasi" name="pembahasan" id="pembahasan" placeholder="masukkan pembahasan dalam pembelajaran" rows="5">{{$agenda->pembahasan}}</textarea>
            </div>
            <div class="form-group col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 p-2">
                <label for="metode">Metode Pembelajaran</label>
                <textarea class="form-control need-validasi" name="metode" id="metode" placeholder="Metode dalam pembelajaran" rows="5">{{$agenda->metode}}</textarea>
            </div>
        </div>
        <div class="row m-0 p-0">
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2">
                <label for="proses">Proses Pembelajaran</label>
                <select class="form-control need-validasi" value="{{$agenda->proses}}" data-toggle="select" name="proses" id="proses" data-width="100%">
                    <option value="belum">Belum</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
        </div>
        <div class="row m-0 p-0">
            <div class="form-group col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 p-2">
                <label for="kegiatan">Kegiatan</label>
                <textarea class="form-control need-validasi"  name="kegiatan" id="kegiatan" placeholder="masukkan kegiatan dalam pembelajaran" rows="5">{{$agenda->kegiatan}}</textarea>
            </div>
            <div class="form-group col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 p-2">
                <label for="kendala">kendala</label>
                <textarea class="form-control need-validasi" name="kendala" id="kendala" placeholder="Kendala dalam pembelajaran" rows="5">{{$agenda->kendala}}</textarea>
            </div>
        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>C.Absensi Siswa</b></p>
        <div class="row m-0 p-0">
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2">
                <label for="siswa">Nama Siswa</label>
                <select class="form-control siswa" data-toggle="select" name="siswa" id="siswa" data-width="100%">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($agenda->jadwal->kelas->siswa as $siswa)
                        <option value="{{$siswa->uuid}}">{{$siswa->nama}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2">
                <label for="absensi">Absensi</label>
                <select class="form-control" data-toggle="select" name="absensi" id="absensi" data-width="100%">
                    <option value="">Pilih Salah Satu</option>
                    <option value="S">Sakit</option>
                    <option value="I">Izin</option>
                    <option value="A">Alpha</option>
                </select>
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2">
                <label for="keterangan">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan" placeholder="Masukkan keterangan absensi" class="form-control">
            </div>
        </div>
        <div class="row m-0 p-0">
            <div class="col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto">
                <button class="btn btn-sm btn-success simpan-absensi"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>
        <div class="row m-0 p-0 mt-3">
            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-siswa fs-12 text-center">
                        <thead>
                            <tr>
                                <td width="35%" style="min-width: 100px">Nama</td>
                                <td width="25%" style="min-width: 100px">Absensi</td>
                                <td width="25%" style="min-width: 100px">Keterangan</td>
                                <td width="15%">#</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agenda->absensi as $absensi)
                                <tr data-uuid="{{$absensi->uuid}}">
                                    <td>{{$absensi->siswa->nama}}</td>
                                    <td>{{$absensi->absensi}}</td>
                                    <td>{{$absensi->keterangan}}</td>
                                    <td><button class="btn btn-sm btn-danger hapus-absensi"><i class="fas fa-trash-can"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>D.Penilaian Profil Pancasila</b></p>
        <div class="row m-0 p-0">
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2">
                <label for="siswa">Nama Siswa</label>
                <select class="form-control siswa" data-toggle="select" name="siswa-profil" id="siswa-profil" data-width="100%">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($agenda->jadwal->kelas->siswa as $siswa)
                        <option value="{{$siswa->uuid}}">{{$siswa->nama}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2">
                <label for="pancasila">Dimensi</label>
                <select class="form-control" data-toggle="select" name="pancasila" id="pancasila" data-width="100%">
                    <option value="">Pilih Salah Satu</option>
                    <option value="1">Beriman, bertakwa kepada Tuhan Yang Maha Esa, dan berakhlak mulia</option>
                    <option value="2">Mandiri</option>
                    <option value="3">Bergotong-royong</option>
                    <option value="4">Berkebhinekaan global</option>
                    <option value="5">Bernalar Kritis</option>
                    <option value="6">Kreatif</option>
                </select>
            </div>
            <div class="form-group col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 p-2">
                <label for="keterangan">Keterangan</label>
                <textarea class="form-control" name="keterangan-pancasila" id="keterangan-pancasila" placeholder="masukkan Keterangan dalam penilaian" rows="5"></textarea>
            </div>
        </div>
        <div class="row m-0 p-0 mt-3">
            <div class="col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto">
                <button class="btn btn-sm btn-success simpan-pancasila"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>
        <div class="row m-0 p-0 mt-3">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-pancasila fs-12 text-center">
                        <thead>
                            <tr>
                                <td style="min-width: 100px" width="25%">Nama</td>
                                <td style="min-width: 100px" width="35%">Dimensi</td>
                                <td style="min-width: 100px" width="25%">Keterangan</td>
                                <td width="15%">#</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $pancasila_array = array(
                                    1 => "Beriman, bertakwa kepada Tuhan Yang Maha Esa, dan berakhlak mulia",
                                    2 => "Mandiri",
                                    3 => "Bergotong-royong",
                                    4 => "Berkebinekaan global",
                                    5 => "Bernalar kritis",
                                    6 => "Kreatif",
                                );
                            @endphp
                            @foreach ($agenda->pancasila as $pancasila)
                                <tr data-uuid="{{$pancasila->uuid}}">
                                    <td>{{$pancasila->siswa->nama}}</td>
                                    <td>{{$pancasila_array[$pancasila->dimensi]}}</td>
                                    <td>{{$pancasila->keterangan}}</td>
                                    <td><button class="btn btn-sm btn-danger hapus-pancasila"><i class="fas fa-trash-can"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="body-contain-customize col-12 d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex mt-3">
        <button type="button" class="btn btn-sm btn-success simpan-agenda"><i class="fas fa-save"></i> Simpan Agenda</button>
    </div>
    <script>
        $('.simpan-absensi').click(function() {
            var siswa = $('#siswa').find(':selected').text();
            var siswaID = $('#siswa').val();
            var absensi = $('#absensi').val();
            var keterangan = $('#keterangan').val();

            if(siswaID != "" && absensi != "") {
                loading();
                var url = "{{route('agenda.store.absensi',':id')}}";
                url = url.replace(':id',"{{$agenda->uuid}}");
                $.ajax({
                    type: "POST",
                    url : url,
                    data: {siswa: siswaID, absensi: absensi, keterangan: keterangan},
                    headers: {"X-CSRF-TOKEN": "{{csrf_token()}}"},
                    success: function(data) {
                        if(data.success == true) {
                            cAlert("green","Berhasil","Absensi Berhasil Ditambahkan",true);
                        } else {
                            oAlert("orange","Perhatian", data.message);
                        }
                        removeLoading();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })

            } else {
                oAlert("blue","Perhatian","Siswa dan absensi tidak boleh kosong");
            }
        });
        $('.hapus-absensi').click(function() {
            var uuid = $(this).closest('tr').data('uuid');
            var deleteAbsensi = () => {
                loading();
                var url = "{{route('agenda.delete.absensi',':id')}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "DELETE",
                    url : url,
                    headers: {"X-CSRF-TOKEN": "{{csrf_token()}}"},
                    success: function(data) {
                        removeLoading();
                        cAlert("green","Berhasil","Berhasil menghapus absensi siswa",true);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            };
            cConfirm("Perhatian","Yakin untuk menghapus absensi ini",deleteAbsensi);
        });
        $('.simpan-pancasila').click(function() {
            var siswa = $('#siswa-profil').find(':selected').text();
            var siswaID = $('#siswa-profil').val();
            var pancasila = $('#pancasila').val();
            var pancasilaText = $('#pancasila').find(':selected').text();
            var keterangan = $('#keterangan-pancasila').val();

            if(siswaID != "" && pancasila != "") {
                loading();
                var url = "{{route('agenda.store.pancasila',':id')}}";
                url = url.replace(':id',"{{$agenda->uuid}}");
                $.ajax({
                    type: "POST",
                    url : url,
                    data: {siswa: siswaID, pancasila: pancasila, keterangan: keterangan},
                    headers: {"X-CSRF-TOKEN": "{{csrf_token()}}"},
                    success: function(data) {
                        if(data.success == true) {
                            cAlert("green","Berhasil","Penilaian Profil Pancasila Berhasil Ditambahkan",true);
                        } else {
                            oAlert("orange","Perhatian", data.message);
                        }
                        removeLoading();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            } else {
                oAlert("blue","Perhatian","Siswa dan dimensi tidak boleh kosong");
            }
        });
        $('.hapus-pancasila').click(function() {
            var uuid = $(this).closest('tr').data('uuid');
            var deletePancasila = () => {
                loading();
                var url = "{{route('agenda.delete.pancasila',':id')}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "DELETE",
                    url : url,
                    headers: {"X-CSRF-TOKEN": "{{csrf_token()}}"},
                    success: function(data) {
                        removeLoading();
                        cAlert("green","Berhasil","Berhasil menghapus penilaian pancasila siswa",true);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            };
            cConfirm("Perhatian","Yakin untuk menghapus penilaian pancasila ini",deletePancasila);
        });
        $('.simpan-agenda').click(function() {
            var countError = 0;
            $('.need-validasi').each(function() {
                if($(this).val() == "") {
                    $(this).addClass('is-invalid');
                    if($(this).closest('.form-group').find('.invalid-feedback').length > 0) {
                        $(this).closest('.form-group').find('.invalid-feedback').html('Wajib Diisi');
                    } else {
                        $(this).closest('.form-group').append('<div class="invalid-feedback">Wajib Diisi</div>');
                    }
                    countError++;
                } else {
                    if($(this).hasClass('is-invalid')) {
                        $(this).removeClass('is-invalid');
                        $(this).closest('.invalid-feedback').remove();
                    }
                }
            });
            if(countError == 0) {
                BigLoading("Agenda sedang diedit");
                formData = new FormData();

                formData.append('pembahasan',$('#pembahasan').val());
                formData.append('metode',$('#metode').val());
                formData.append('proses',$('#proses').val());
                formData.append('kegiatan',$('#kegiatan').val());
                formData.append('kendala',$('#kendala').val());
                var url = "{{route('agenda.update',':id')}}";
                url = url.replace(':id','{{$agenda->uuid}}');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        removeLoadingBig();
                        cAlert('green','Sukses','Data Berhasil Disimpan',false,'{{route("agenda.index")}}');
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            } else {
                oAlert('orange','Perhatian','Formulir poin A dan B wajib diisi');
            }
        });
    </script>
@endsection
