@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Rapor Manual</b></h5>
        <p>Halaman ini diperuntukkan admin untuk menginput nilai siswa diluar dari aplikasi ( seperti nilai pendidikan Agama dan Budi Pekerti )</p>
    </div>
    <div class="body-contain-customize col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-block col-lg-auto d-lg-block col-xl-auto d-xl-block mt-3">
        <a href="{{route('penilaian.admin.manual.history')}}" class="btn btn-sm btn-warning text-warning-emphasis">
            <i class="fas fa-history"></i> Nilai Yang Sudah Diajukan
        </a>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>Form Penambahan Nilai Manual</b></p>
        <div class="row m-0 p-0">
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <label for="pelajaran">Pelajaran</label>
                <select name="pelajaran" id="pelajaran" class="form-control validate1" data-toggle="select">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($pelajaran as $item)
                        <option value="{{$item->uuid}}">{{$item->pelajaran}}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    Wajib Diisi
                </div>
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <label for="kelas">Kelas</label>
                <select name="kelas" id="kelas" class="form-control validate1" data-toggle="select">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($kelas as $item)
                        <option value="{{$item->uuid}}">{{$item->tingkat.$item->kelas}}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    Wajib Diisi
                </div>
            </div>
            <div class="button-place mt-3">
                <button class="btn btn-sm btn-warning text-warning-emphasis simpan-ngajar">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
            </div>
        </div>
    </div>
    <div id="formPlace"></div>
    <script>
        $('.simpan-ngajar').click(function() {
            var pelajaran = $('#pelajaran').val();
            var kelas = $('#kelas').val();

            var countError1 = 0;
            $('.validate1').each(function() {
                if($(this).val() == "") {
                    $(this).addClass('is-invalid').removeClass('is-valid');
                    countError1++;
                } else {
                    $(this).addClass('is-valid').removeClass('is-invalid');
                }
            });
            if(countError1 == 0) {
                loading();
                $.ajax({
                    type: "GET",
                    url: "{{route('penilaian.admin.manual.getNilai')}}",
                    data: {pelajaran : pelajaran,kelas : kelas},
                    success: function(data) {
                        var ngajar = data.ngajar;

                        if(ngajar == undefined) {
                            oAlert("orange","Perhatian","Data Ngajar Tidak Ditemukan");
                            $('#formPlace').html("");

                        } else {
                            var optionSiswa = "";
                            if(ngajar.siswa) {
                                ngajar.siswa.forEach(element => {
                                    optionSiswa += "<option value='"+element.uuid+"'>"+element.nama+"</option>";
                                });
                            }
                            if(ngajar.kkm == 0) {
                                var kkm = `
                                    <div
                                    class="alert alert-warning" role="alert">
                                        <strong><i class="fas fa-triangle-exclamation"></i> Perhatian</strong> KKTP untuk data ngajar masih belum diatur. Guru dapat mengatur KKTP dihalaman Buku Guru > KKTP
                                    </div>
                                `;
                            } else {
                                var kkm = "";
                            }
                            var html =  `
                                <div class="body-contain-customize nostrip mt-3 col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5">
                                    <p><b>Data Ngajar</b></p>
                                    <table class="table table-striped fs-13">
                                        <tr>
                                            <td width="30%">Pelajaran</td>
                                            <td width="5%">:</td>
                                            <td>${ngajar.pelajaran.pelajaran}</td>
                                        </tr>
                                        <tr>
                                            <td>Kelas</td>
                                            <td>:</td>
                                            <td>${ngajar.kelas.tingkat+ngajar.kelas.kelas}</td>
                                        </tr>
                                        <tr>
                                            <td>Guru</td>
                                            <td>:</td>
                                            <td>${ngajar.guru.nama}</td>
                                        </tr>
                                        <tr>
                                            <td>KKTP</td>
                                            <td>:</td>
                                            <td>${ngajar.kkm}</td>
                                        </tr>
                                    </table>
                                    ${kkm}
                                </div>
                                <div class="body-contain-customize nostrip col-12 mt-3" id="dataNgajar" data-ngajar="${ngajar.uuid}">
                                    <div class="row m-0 p-0 gap-3">
                                        <div class="form-group col-12 col-sm-12 col-md-8 col-lg-5 col-xl-5">
                                            <label for="siswa">Nama Siswa</label>
                                            <select class="form-control validate2" id="siswa" name="siswa" data-toggle="select">
                                                <option value="">Pilih Salah Satu</option>
                                                ${optionSiswa}
                                            </select>
                                            <div class="invalid-feedback">Tidak Boleh Kosong</div>
                                        </div>
                                        <div class="form-group col-12 col-sm-12 col-md-8 col-lg-5 col-xl-5">
                                            <label for="nilai">Nilai Siswa</label>
                                            <input type="number" class="form-control validate2" id="nilai" name="nilai" />
                                            <div class="invalid-feedback">Tidak Boleh Kosong</div>
                                        </div>
                                        <div class="form-group col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                            <label for="positif">Capaian Kompetensi Tertinggi</label>
                                            <textarea class="form-control validate2" id="positif" name="positif"></textarea>
                                            <div class="invalid-feedback">Tidak Boleh Kosong</div>
                                        </div>
                                        <div class="form-group col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                            <label for="negatif">Capaian Kompetensi Terendah</label>
                                            <textarea class="form-control validate2" id="negatif" name="negatif"></textarea>
                                            <div class="invalid-feedback">Tidak Boleh Kosong</div>
                                        </div>
                                        <div class="button-place col-12 mt-2">
                                            <button class="btn btn-sm btn-primary simpan-nilai">
                                                <i class="fas fa-save"></i> Simpan Nilai Manual
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            `;
                            $('#formPlace').html(html);
                            removeLoading();
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
        });
        $('#formPlace').on('click','.simpan-nilai',function(){
            var countError2 = 0;
            $('.validate2').each(function() {
                if($(this).val() == "") {
                    $(this).addClass('is-invalid').removeClass('is-valid');
                    countError++;
                } else {
                    $(this).addClass('is-valid').removeClass('is-invalid');
                }
            });
            if(countError2 == 0) {
                loading();
                var ngajar = $('#dataNgajar').data('ngajar');
                var siswa = $('#siswa').val();
                var nilai = $('#nilai').val();
                var positif = $('#positif').val();
                var negatif = $('#negatif').val();

                var url = "{{route('penilaian.admin.manual.create',':id')}}";
                url = url.replace(':id',ngajar);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {siswa : siswa, nilai : nilai, positif : positif, negatif : negatif},
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        removeLoading();
                        if(data.success == true) {
                            oAlert("green","Berhasil","Nilai Berhasil Disimpan");
                            $('#siswa').val("");
                            $('#nilai').val("");
                            $('#positif').val("");
                            $('#negatif').val("");
                            $('.validate2').removeClass('is-valid');
                        } else {
                            oAlert("orange","Perhatian","Siswa dengan data ngajar berikut sudah ada nilai rapor manual");
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }

        });
    </script>
@endsection
