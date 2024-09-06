@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('agenda-create')}}
    <div class="body-contain-customize col-12">
        <h5><b>Tambah Agenda</b></h5>
        <p>Halaman Tambah Agenda diperuntukkan guru menambahkan agenda harian mengajar, proses selama mengajar serta hasil dan tindak lanjut dalam mengajar</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>A.Tanggal dan Jadwal</b></p>
        <div class="row m-0 p-0 section-a">
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2">
                <label for="tanggal">tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control need-validasi">
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2">
                <label for="jadwal">Jadwal</label>
                <select class="form-control need-validasi" data-toggle="select" name="jadwal" id="jadwal" data-width="100%">
                    <option value="">Pilih Salah Satu</option>
                </select>
            </div>
        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>B. Agenda</b></p>
        <div class="row m-0 p-0">
            <div class="form-group col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 p-2">
                <label for="pembahasan">Pembahasan</label>
                <textarea class="form-control need-validasi" name="pembahasan" id="pembahasan" placeholder="masukkan pembahasan dalam pembelajaran" rows="5">@if (\Request::route()->getName() === 'agenda.createCopy') {{$agenda->pembahasan}} @endif</textarea>
            </div>
            <div class="form-group col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 p-2">
                <label for="metode">Metode Pembelajaran</label>
                <textarea class="form-control need-validasi" name="metode" id="metode" placeholder="Metode dalam pembelajaran" rows="5">@if (\Request::route()->getName() === 'agenda.createCopy') {{$agenda->metode}} @endif</textarea>
            </div>
        </div>
        <div class="row m-0 p-0">
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2">
                <label for="proses">Proses Pembelajaran</label>
                <select class="form-control need-validasi" data-toggle="select" name="proses" id="proses" data-width="100%">
                    <option value="belum" @selected(\Request::route()->getName() === 'agenda.createCopy' && $agenda->proses == "belum")>Belum</option>
                    <option value="selesai" @selected(\Request::route()->getName() === 'agenda.createCopy' && $agenda->proses == "selesai")>Selesai</option>
                </select>
            </div>
        </div>
        <div class="row m-0 p-0">
            <div class="form-group col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 p-2">
                <label for="kegiatan">Kegiatan</label>
                <textarea class="form-control need-validasi" name="kegiatan" id="kegiatan" placeholder="masukkan kegiatan dalam pembelajaran" rows="5">@if (\Request::route()->getName() === 'agenda.createCopy') {{$agenda->kegiatan}} @endif</textarea>
            </div>
            <div class="form-group col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 p-2">
                <label for="kendala">kendala</label>
                <textarea class="form-control need-validasi" name="kendala" id="kendala" placeholder="Kendala dalam pembelajaran" rows="5">@if (\Request::route()->getName() === 'agenda.createCopy') {{$agenda->kendala}} @endif</textarea>
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
        $('#tanggal').on('change',function() {
            var tanggal = $(this).val();
            var ini = this;
            if($(this).closest('.form-group').find('.invalid-feedback').length > 0) {
                $(this).closest('.form-group').find('.invalid-feedback').remove();
            }
            if (new Date(tanggal) != "Invalid Date") {
                loading();
                var url = "{{route('agenda.cekTanggal')}}";
                $.ajax({
                    type: "get",
                    url: url,
                    data: {tanggal : tanggal},
                    success: function(data) {
                        if(data.success == true) {
                            $('#jadwal').html('<option value="">Pilih Salah Satu</option>');
                            if($(ini).hasClass('is-invalid')) {
                                $(ini).removeClass('is-invalid');
                                $(ini).closest('.form-group').find('.invalid-feedback').remove();
                            }
                            var pelajaran = data.jadwal;

                            pelajaran.forEach(element => {
                                var jadwalText = element.kelas.tingkat+element.kelas.kelas+"-"+element.pelajaran.pelajaran_singkat+"( "+element.waktu.waktu_mulai+" )";
                                var newOption = new Option(jadwalText,element.uuid,false,false);
                                $('#jadwal').append(newOption).trigger('change');
                            });
                            removeLoading();
                        } else {
                            $(ini).addClass('is-invalid');
                            $(ini).closest('.form-group').append('<div class="invalid-feedback">'+data.message+'</div>');
                            removeLoading();
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }
        });
        $('#jadwal').on('select2:select',function() {
            var jadwal = $(this).val();
            var ini = this;
            $('.siswa').html('<option value="">Pilih Salah Satu</option>');
            if(jadwal !== "") {
                loading();
                var url = "{{route('agenda.cekJadwal')}}";
                $.ajax({
                    type: "GET",
                    url : url,
                    data: {idJadwal : jadwal},
                    success: function(data) {
                        if(data.success == true) {
                            removeLoading();
                            var siswa = data.siswa;
                            siswa.forEach(element => {
                                var newOption = new Option(element.nama,element.uuid,false,false);
                                $('.siswa').append(newOption).trigger('change');
                            });
                        } else {
                            removeLoading();
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            }
        });
        $('.simpan-absensi').click(function() {
            var siswa = $('#siswa').find(':selected').text();
            var siswaID = $('#siswa').val();
            var absensi = $('#absensi').val();
            var keterangan = $('#keterangan').val();

            if(siswaID != "" && absensi != "") {
                var html = `
                    <tr class="absensi-item" data-uuid="${siswaID}">
                        <td>${siswa}</td>
                        <td>${absensi}</td>
                        <td>${keterangan}</td>
                        <td><button class="btn btn-sm btn-danger hapus-siswa"><i class="fas fa-trash-can"></i></button></td>
                    </tr>
                `;
                $('.table-siswa').find('tbody').append(html);
                $('#siswa').val('').trigger('change');
                $('#absensi').val('').trigger('change');
                $('#keterangan').val('');
            } else {
                oAlert("blue","Perhatian","Siswa dan absensi tidak boleh kosong");
            }

            $('.hapus-siswa').click(function() {
                $(this).closest('tr').remove();
            });
        });
        $('.simpan-pancasila').click(function() {
            var siswa = $('#siswa-profil').find(':selected').text();
            var siswaID = $('#siswa-profil').val();
            var pancasila = $('#pancasila').val();
            var pancasilaText = $('#pancasila').find(':selected').text();
            var keterangan = $('#keterangan-pancasila').val();

            if(siswaID != "" && pancasila != "") {
                var html = `
                    <tr class="pancasila-item" data-uuid="${siswaID}">
                        <td>${siswa}</td>
                        <td class="dimensi-item" data-dimensi="${pancasila}">${pancasilaText}</td>
                        <td>${keterangan}</td>
                        <td><button class="btn btn-sm btn-danger hapus-pancasila"><i class="fas fa-trash-can"></i></button></td>
                    </tr>
                `;
                $('.table-pancasila').find('tbody').append(html);
                $('#siswa-profil').val('').trigger('change');
                $('#pancasila').val('').trigger('change');
                $('#keterangan-pancasila').val('');
            } else {
                oAlert("blue","Perhatian","Siswa dan dimensi tidak boleh kosong");
            }

            $('.hapus-pancasila').click(function() {
                $(this).closest('tr').remove();
            });
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
                BigLoading("Agenda sedang disimpan");
                formData = new FormData();

                formData.append('tanggal',$('#tanggal').val());
                formData.append('jadwal',$('#jadwal').val());
                formData.append('pembahasan',$('#pembahasan').val());
                formData.append('metode',$('#metode').val());
                formData.append('proses',$('#proses').val());
                formData.append('kegiatan',$('#kegiatan').val());
                formData.append('kendala',$('#kendala').val());

                if($('.table-siswa').find('.absensi-item').length > 0) {
                    var absensiArray = [];
                    $('.absensi-item').each(function() {
                        var idSiswa = $(this).data('uuid');
                        var absensi = $(this).children().eq(1).text();
                        var keterangan = $(this).children().eq(2).text();
                        absensiArray.push({
                            "siswa" : idSiswa,
                            "absensi": absensi,
                            "keterangan": keterangan
                        });
                    });
                    formData.append('absensi',JSON.stringify(absensiArray));
                }
                if($('.table-pancasila').find('.pancasila-item').length > 0) {
                    var pancasilaArray = [];
                    $('.pancasila-item').each(function() {
                        var idSiswa = $(this).data('uuid');
                        var dimensi = $(this).children('.dimensi-item').data('dimensi');
                        var keterangan = $(this).children().eq(2).text();
                        pancasilaArray.push({
                            "siswa": idSiswa,
                            "dimensi": dimensi,
                            "keterangan": keterangan
                        });
                    });
                    formData.append('pancasila',JSON.stringify(pancasilaArray));
                }

                $.ajax({
                    type: "POST",
                    url: "{{route('agenda.store')}}",
                    data: formData,
                    headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log(data);
                        removeLoadingBig();
                        if(data.success == false) {
                            oAlert('red','Perhatian',data.message);
                        } else {
                            cAlert('green','Sukses','Data Berhasil Disimpan',false,'{{route("agenda.index")}}');
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            } else {
                oAlert('orange','Perhatian','Formulir poin A dan B wajib diisi');
            }
        });
        @if (\Request::route()->getName() === 'agenda.createID')
            $('document').ready(function() {
                // var today = '2024-06-17';
                var today = moment().format('YYYY-MM-DD');
                $('#tanggal').val(today);
                $('#tanggal').trigger('change');
                setTimeout(() => {
                    if($('#tanggal').closest('.form-group').find('.invalid-feedback').length == 0) {
                        var uuid = String("{{request('uuid')}}");
                        setTimeout(() => {
                            $('#jadwal').val(uuid).trigger('change');
                            $('#jadwal').trigger('select2:select');
                        }, 500);
                    }
                },500);
            });
        @endif
    </script>
@endsection
