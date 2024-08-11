@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('cetak-absensi-guru')}}
    <div class="body-contain-customize col-12">
        <h5><b>Download Absensi</b></h5>
        <p>Halaman ini berguna untuk mendownload hasil rekapan absensi guru</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="row m-0 p-0">
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <label for="dari">Dari Tanggal</label>
                <input type="date" class="form-control" id="dari" name="dari">
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <label for="sampai">Sampai Tanggal</label>
                <input type="date" class="form-control" id="sampai" name="sampai" value="{{date('Y-m-d')}}">
            </div>
            <div class="button-place col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
                <button class="btn btn-sm btn-warning text-warning-emphasis lihat-hasil">
                    <i class="fas fa-eye"></i> Lihat Hasil
                </button>
            </div>
        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="table-responsive" id="table-place">

        </div>
        <div class="table-button-place mt-3"></div>
    </div>
    <script>
        $('.lihat-hasil').click(function() {
            var dari = $('#dari').val();
            var sampai = $('#sampai').val();

            var url = "{{route('absensi.guru.rekap')}}";

            $.ajax({
                type: "POST",
                url : url,
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                data: {dari: dari, sampai: sampai},
                success: function(data) {
                    var absensi = data.absensi;
                    var tanggal = data.tanggal;
                    var guru = data.guru;

                    var guruContent = "";
                    var terlambat = new Date("July 18, 2024 07:46");

                    var no = 1;
                    guru.forEach(element => {
                        var idGuru = element.uuid;
                        var absensiContent = "";
                        tanggal.forEach(item => {
                            if(absensi[item.uuid+"."+idGuru]) {
                                if(absensi[item.uuid+"."+idGuru]['datang']) {
                                    var kehadiran = new Date("July 18, 2024 "+absensi[item.uuid+"."+idGuru]['datang']);

                                    if(kehadiran >= terlambat) {
                                        var kelas ="text-danger";
                                    } else {
                                        var kelas = "";
                                    }
                                    absensiContent += "<td class='"+kelas+"'>"+absensi[item.uuid+"."+idGuru]['datang']+"</td>";
                                } else {
                                    absensiContent += "<td></td>";
                                }
                                if(absensi[item.uuid+"."+idGuru]['pulang']) {
                                    absensiContent += "<td>"+absensi[item.uuid+"."+idGuru]['pulang']+"</td>";
                                } else {
                                    absensiContent += "<td></td>";
                                }
                            } else {
                                absensiContent += "<td></td><td></td>"
                            }
                        });
                        guruContent += `
                            <tr class="text-center">
                                <td>${no}</td>
                                <td style="min-width:170px" class="text-start">${element.nama}</td>
                                ${absensiContent}
                            </tr>
                        `;
                        no++;
                    })
                    var tanggalContent = "";
                    var deskripsiTanggal = "";
                    tanggal.forEach(element => {
                        var tanggalNow = moment(element.tanggal).format('DD-MM-YYYY');
                        tanggalContent += `
                            <td style="min-width:100px" colspan="2" class="text-center">${tanggalNow}</td>
                        `;
                        deskripsiTanggal += "<td>Datang</td><td>Pulang</td>";
                    });
                    var html = `
                        <table class='table table-bordered table-hover fs-10'>
                            <thead>
                                <tr>
                                    <td rowspan="2">No</td>
                                    <td rowspan="2">Nama</td>
                                    ${tanggalContent}
                                </tr>
                                <tr class="text-center">
                                    ${deskripsiTanggal}
                                </tr>
                            </thead>
                            <tbody>
                                ${guruContent}
                            </tbody>
                        </table>
                    `;
                    $('#table-place').html(html);

                    if(tanggalContent != "") {
                        var url = "{{route('cetak.absensi.guru.excel',['dari' => ':id','sampai' => ':id2'])}}";
                        url = url.replace(':id',dari).replace(':id2',sampai);
                        $('.table-button-place').html('<a href="'+url+'" class="btn btn-sm btn-success download"><i class="fa fa-file-excel"></i> Download Excel</a>')
                    } else {
                        $('.table-button-place').html('');
                    }

                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        });
    </script>
@endsection
