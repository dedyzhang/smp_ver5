@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('penggunaan-create')}}
    <div class="body-contain-customize col-12">
        <h5><b>Tambahkan Penggajuan</b></h5>
        <p>Halaman ini diperuntukkan user untuk meminjam penggunaan ruangan umum</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p>Form Pengajuan Penggunaan Ruang</p>
        <div class="row m-0 p-0">
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group">
                <label for="ruang">Nama Ruang</label>
                <select name="ruang" id="ruang" class="form-control" data-toggle="select">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($ruang as $item)
                        <option value="{{$item->uuid}}">{{$item->nama}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group">
                <label for="kelas">Kelas</label>
                <select name="kelas" id="kelas" class="form-control" data-toggle="select">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($kelas as $item)
                        <option value="{{$item->uuid}}">{{$item->tingkat.$item->kelas}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mt-3">
                <div class="table-responsive jadwal-place"></div>
                <button class="btn btn-sm btn-warning text-warning-emphasis tambahkan-pengajuan">
                    <i class="fas fa-save"></i> Simpan Pengajuan
                </button>
            </div>
        </div>
    </div>
    <script>
        $('#kelas').change(function() {
            var uuid = $(this).val();
            var url = "{{route('penggunaan.getJadwal',':id')}}";
            url = url.replace(':id',uuid);
            loading();
            $('.jadwal-place').html('');
            $.ajax({
                type: "GET",
                url: url,
                success: function(data) {
                    console.log(data);
                    if(data.success === true) {
                        var jadwal = data.jadwal;
                        var jadwalContent = "";
                        jadwal.forEach(element => {
                            if(element.jenis == "spesial") {
                                jadwalContent += `
                                    <tr>
                                        <td colspan="4" class="text-center">${element.spesial}</td>
                                    </tr>
                                `;
                            } else if(element.jenis == "mapel") {
                                jadwalContent += `
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="jadwal"
                                                data-pelajaran="${element.id_pelajaran}"
                                                data-guru="${element.id_guru}"
                                                data-waktu="${element.id_waktu}"
                                            class="form-check-input">
                                        </td>
                                        <td>${element.waktu.waktu_mulai} - ${element.waktu.waktu_akhir}</td>
                                        <td>${element.pelajaran !== null ? element.pelajaran.pelajaran : '-'}</td>
                                        <td>${element.guru !== null ? element.guru.nama : '-'}</td>
                                    </tr>
                                `;
                            } else {
                                jadwalContent += `
                                    <tr>
                                        <td></td>
                                        <td>${element.waktu.waktu_mulai} - ${element.waktu.waktu_akhir}</td>
                                        <td>${element.pelajaran !== null ? element.pelajaran.pelajaran : '-'}</td>
                                        <td>${element.guru !== null ? element.guru.nama : '-'}</td>
                                    </tr>
                                `;
                            };
                        });
                        var html = `
                            <table class="table table-bordered table-striped fs-12" width="100%">
                                <tr>
                                    <td>-</td>
                                    <td style="min-width:100px">Waktu</td>
                                    <td style="min-width:150px">Mata Pelajaran</td>
                                    <td style="min-width:150px">Guru Yang Mengajar</td>
                                </tr>
                                ${jadwalContent}
                            </table>
                        `;
                        $('.jadwal-place').html(html);
                        removeLoading();
                    }
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        });
        $('.tambahkan-pengajuan').click(function() {
            var idLoginGuru = "{{$user_array['uuid']}}";
            var accessGuru = "{{$user_array['access']}}";
            var idRuang = $('#ruang').val();
            var idKelas = $('#kelas').val();
            var error = 0;
            var dataJadwal = [];
            if(idRuang !== "") {
                $('input[name="jadwal"]:checked').each(function(){
                    var idGuru = $(this).data('guru');
                    var idPelajaran = $(this).data('pelajaran');
                    var waktu = $(this).data('waktu');
                    if(accessGuru == "admin" || accessGuru == "sapras") {
                        error = 0;
                        dataJadwal.push({
                            'id_guru': idGuru,
                            'id_pelajaran' : idPelajaran,
                            'waktu' : waktu
                        });
                    } else {
                        if(idLoginGuru != idGuru) {
                            error += 1;
                        } else {
                            error = 0;
                            dataJadwal.push({
                                'id_guru' : idGuru,
                                'id_pelajaran' : idPelajaran,
                                'waktu' : waktu
                            });
                        }
                    }
                });
                if(error > 0) {
                    oAlert("orange","Perhatian","Guru Tidak Boleh Mengajukan Penggunaan Ruang Diluar Dari Jam Pelajaran");
                } else {
                    loading();
                    $.ajax({
                        type: "POST",
                        url: "{{route('penggunaan.store')}}",
                        headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                        data: {
                            jadwal: dataJadwal,
                            idRuang: idRuang,
                            idKelas: idKelas
                        },
                        success: function(data) {
                            removeLoading();
                            if(data.success === false) {
                                oAlert("orange","Perhatian",data.message);
                            } else {
                                cAlert("green","Sukses","Ruang Berhasil Diajukan",false,"{{route('penggunaan.index')}}");
                            }
                        },
                        error: function(data) {
                            console.log(data.responseJSON.message);
                        }
                    });

                }
            } else {
                oAlert("orange","Perhatian","Data Ruang tidak boleh kosong");
            }
        });
    </script>
@endsection
