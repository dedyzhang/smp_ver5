@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Tambah Absensi</b></h5>
        <p>Halaman Walikelas untuk mengatur absensi siswa</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="row m-0 p-0">
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <input type="date" class="form-control" id="tanggal" name="tanggal" />
            </div>

        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td width="5%">No</td>
                        <td width="20%" style="min-width:150px">Nama Siswa</td>
                        <td width="10%" style="min-width:100px">Absensi</td>
                        <td width="15%">Waktu</td>
                        <td width="25%">Keterangan</td>
                    </tr>
                </thead>
                <tbody id="absen-place">

                </tbody>
            </table>
        </div>
    </div>
    <div class="body-contain-customize col-12 d-grid col-sm-12 d-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex mt-3">
        <button class="btn btn-sm btn-warning text-warning-emphasis simpan-absensi">
            <i class="fas fa-save"></i> Simpan Absensi
        </button>
    </div>
    <script>
        $('#tanggal').on('focusout',function() {
            var tanggal = $(this).val();
            var ini = this;
            loading();
            var url = "{{route('walikelas.absensi.getAbsen')}}";
            $.ajax({
                type: "GET",
                url: url,
                data: {tanggal: tanggal, kelas : "{{$guru->walikelas->id_kelas}}"},
                success: function(data) {
                    console.log(data);
                    if(data.success == false) {
                        $('#absen-place').html('');
                        $(ini).addClass('is-invalid');
                        if($(ini).closest('.form-group').find('.invalid-feedback').length > 0) {
                            $(ini).closest('.form-group').find('.invalid-feedback').html(data.message);
                        } else {
                            $(ini).closest('.form-group').append('<div class="invalid-feedback">'+data.message+'</div>');
                        }
                    } else {
                        var idTanggal = data.id_tanggal;
                        var siswa = data.siswa;
                        var absensi = data.absensi;
                        var htmlSiswa = "";
                        var no = 1;

                        $('#absen-place').html("").attr('data-tanggal',idTanggal);
                        siswa.forEach(element => {
                            var index = absensi.findIndex(el => el['id_siswa'] === element.uuid);
                            if(index === -1) {
                                htmlSiswa += `
                                    <tr class="siswa-element" data-uuid="${element.uuid}">
                                        <td>${no}</td>
                                        <td>${element.nama}</td>
                                        <td>
                                            <select class="form-control fs-12" data-toggle="select" data-width="100%">
                                                <option value="">Pilih Salah Satu</option>
                                                <option value="hadir">Hadir</option>
                                                <option value="sakit">Sakit</option>
                                                <option value="izin">Izin</option>
                                                <option value="alpha">Alpha</option>
                                            </select>
                                        </td>
                                        <td></td>
                                        <td contenteditable="true" class="deskripsi"></td>
                                    </tr>
                                `;
                            } else {
                                if(absensi[index]['keterangan'] === null) {
                                    var keterangan = "";
                                } else {
                                    var keterangan = absensi[index]['keterangan'];
                                }
                                htmlSiswa += `
                                    <tr class="siswa-element-update ${absensi[index]['absensi'] == "hadir" && "table-info"}"
                                        data-uuid="${absensi[index]['uuid']}"
                                        data-siswa="${absensi[index]['id_siswa']}"
                                        data-tanggal="${absensi[index]['id_tanggal']}"
                                    >
                                        <td>${no}</td>
                                        <td>${element.nama}</td>
                                        <td>
                                            <select class="form-control fs-12" data-toggle="select" data-width="100%">
                                                <option value="">Pilih Salah Satu</option>
                                                <option value="hadir" ${absensi[index]['absensi'] == "hadir" && "selected"}>Hadir</option>
                                                <option value="sakit" ${absensi[index]['absensi'] == "sakit" && "selected"}>Sakit</option>
                                                <option value="izin" ${absensi[index]['absensi'] == "izin" && "selected"}>Izin</option>
                                                <option value="alpa" ${absensi[index]['absensi'] == "alpa" && "selected"}>Alpa</option>
                                            </select>
                                        </td>
                                        <td class="waktu">${absensi[index]['waktu'] == null ? "" : absensi[index]['waktu']}</td>
                                        <td class="deskripsi" contenteditable="true">${keterangan}</td>
                                    </tr>
                                `;
                            }

                            no += 1;
                        });
                        $('#absen-place').html(htmlSiswa);
                    }
                    removeLoading();
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        });

        $('.simpan-absensi').click(function() {
            var storeAbsensi = () => {
                loading();
                var absen_array = [];
                var idTanggal = $('#absen-place').data('tanggal');
                $('.siswa-element').each(function() {
                    var idSiswa = $(this).data('uuid');
                    var absensi = $(this).find('select').val();
                    var deskripsi = $(this).find('.deskripsi').html();
                    if(absensi != "") {
                        absen_array.push({
                            id_tanggal: idTanggal,
                            id_siswa : idSiswa,
                            absensi : absensi,
                            waktu: "",
                            keterangan : deskripsi,
                        });
                    }
                });
                $('.siswa-element-update').each(function() {
                    var uuid = $(this).data('uuid');
                    var tanggal = $(this).data('tanggal');
                    var idSiswa = $(this).data('siswa');
                    var absensi = $(this).find('select').val();
                    if(absensi !== "hadir") {
                        var waktu = "";
                        var deskripsi = $(this).find('.deskripsi').html();
                    } else {
                        var waktu = $(this).find('.waktu').html();
                        var deskripsi = "";
                    }
                    if(absensi != "") {
                        absen_array.push({
                            id_tanggal: tanggal,
                            id_siswa : idSiswa,
                            absensi : absensi,
                            waktu: waktu,
                            uuid: uuid,
                            keterangan : deskripsi,
                        });
                    }
                });
                $.ajax({
                    type: "post",
                    data: {input : absen_array},
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    url: "{{route('walikelas.absensi.create')}}",
                    success: function(data) {
                        removeLoading();
                        cAlert("green","Berhasil","Berhasil mengupdate Absensi",true);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }

            cConfirm("Perhatian","Yakin untuk mengubah data Absensi Siswa",storeAbsensi);
        });
    </script>
@endsection
