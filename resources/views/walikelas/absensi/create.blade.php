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
            <table class="table table-bordered table-striped">
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
                        $(ini).addClass('is-invalid');
                        if($(ini).closest('.form-group').find('.invalid-feedback').length > 0) {
                            $(ini).closest('.form-group').find('.invalid-feedback').html(data.message);
                        } else {
                            $(ini).closest('.form-group').append('<div class="invalid-feedback">'+data.message+'</div>');
                        }
                    } else {
                        var siswa = data.siswa;
                        var absensi = data.absensi;
                        var htmlSiswa = "";
                        var no = 1;
                        siswa.forEach(element => {
                            var index = absensi.findIndex(el => el['id_siswa'] === element.uuid);
                            if(index === -1) {
                                htmlSiswa += `
                                    <tr>
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
                                        <td contenteditable="true"></td>
                                    </tr>
                                `;
                            } else {
                                if(absensi[index]['keterangan'] === null) {
                                    var keterangan = "";
                                } else {
                                    var keterangan = absensi[index]['keterangan'];
                                }
                                htmlSiswa += `
                                    <tr>
                                        <td>${no}</td>
                                        <td>${element.nama}</td>
                                        <td>${absensi[index]['absensi']}</td>
                                        <td>${absensi[index]['waktu']}</td>
                                        <td>${keterangan}</td>
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
    </script>
@endsection
