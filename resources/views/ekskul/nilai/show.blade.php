@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('ekskul-nilai-show',$ekskul)}}
    <div class="body-contain-customize col-12">
        <h5><b>Nilai Ekskul</b></h5>
        <p>Halaman ini berisi informasi penting mengenai penilaian ekstrakurikuler, termasuk kriteria penilaian, tujuan kegiatan, dan dampaknya terhadap pengembangan keterampilan siswa.</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5">
        <table class="table table-striped fs-13">
            <tr>
                <td width="30%">Nama Ekskul</td>
                <td width="5%">:</td>
                <td>{{$ekskul->ekskul}}</td>
            </tr>
            <tr>
                <td>Nama Pelatih</td>
                <td>:</td>
                <td>{{$ekskul->guru->nama}}</td>
            </tr>
            <tr>
                <td>Semester / TP</td>
                <td>:</td>
                <td>{{$semester->semester == 1 ? "Ganjil" : "Genap"}} / {{$semester->tp}}</td>
            </tr>
        </table>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p class="fs-12"><b>Form Penambahan Nilai Ekstrakurikuler</b></p>
        <div class="row m-0 p-0">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <label for="kelas">kelas</label>
                <select name="kelas" id="kelas" class="form-control" data-toggle="select">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($kelas as $item)
                        <option value="{{$item->uuid}}">{{$item->tingkat.$item->kelas}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mt-3">
                <div class="table-responsive">
                    <table class="table table-bordered fs-12" style="width:100%">
                        <thead>
                            <tr>
                                <td width="5%">No</td>
                                <td width="25%" style="min-width:150px">Nama</td>
                                <td width="10%" style="min-width:100px">NIS</td>
                                <td width="5%">JK</td>
                                <td width="55%" style="min-width:200px">Deskripsi</td>
                            </tr>
                        </thead>
                        <tbody id="tabel-nilai-ekskul">

                        </tbody>
                    </table>
                </div>
                <div class='row m-0 p-0 tombol-place d-none'>
                    <div class="p-0 col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-block col-lg-auto d-lg-block col-xl-auto d-xl-block">
                        <button class='btn btn-sm btn-warning simpan-nilai'>
                            <i class='fas fa-save'></i> Simpan Deskripsi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#kelas').change(function(){
            var kelas = $(this).val();
            var url = "{{route('ekskul.nilai.get')}}";
            url = url.replace(':id',kelas);
            loading();
            $.ajax({
                type: "GET",
                url: url,
                data : {kelas : kelas, ekskul : '{{$ekskul->uuid}}'},
                success: function(data) {
                    if(data.success == true) {
                        var siswa = data.siswa;
                        var html = "";
                        var nilai = data.nilai;
                        var no = 1;
                        siswa.forEach(function(element) {
                            const idSiswa = element.uuid;
                            const nilaiSiswa = nilai.find(item => item.id_siswa == idSiswa);
                            html += `
                                <tr class="siswa" data-siswa="${element.uuid}" data-ekskul="${nilaiSiswa && nilaiSiswa.uuid ? nilaiSiswa.uuid : ""}">
                                    <td>${no}</td>
                                    <td>${element.nama}</td>
                                    <td>${element.nis}</td>
                                    <td class="${element.jk == 'l' ? 'text-primary' : 'text-danger'}">${element.jk}</td>
                                    <td class="deskripsi" contenteditable="true">${nilaiSiswa && nilaiSiswa.deskripsi ? nilaiSiswa.deskripsi : ""}</td>
                                </tr>
                            `;
                            no++;
                        });
                        $('#tabel-nilai-ekskul').html(html);
                        if(!$('#tabel-nilai-ekskul').is(':empty')) {
                            $('.tombol-place').addClass('d-flex').removeClass('d-none');
                        } else {
                            $('.tombol-place').addClass('d-none').removeClass('d-flex');
                        }
                        removeLoading();
                    }
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        });
        $('.simpan-nilai').click(function() {
            var simpanDeskripsi = () => {
                var arrayNilai = [];
                $('.siswa').each(function() {
                    var idSiswa = $(this).data('siswa');
                    var uuidEkskul = $(this).data('ekskul');
                    var deskripsi = $(this).find('.deskripsi').text();
                    if(deskripsi != "" || uuidEkskul != "") {
                        if(uuidEkskul == "") {
                            arrayNilai.push({
                                'id_siswa' : idSiswa,
                                'id_ekskul' : "{{$ekskul->uuid}}",
                                'deskripsi' : deskripsi,
                                'semester' : "{{$semester->semester}}",
                            });
                        } else {
                            arrayNilai.push({
                                'uuid' : uuidEkskul,
                                'id_siswa' : idSiswa,
                                'id_ekskul' : "{{$ekskul->uuid}}",
                                'deskripsi' : deskripsi,
                                'semester' : "{{$semester->semester}}",
                            });
                        }
                    }
                });
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{route('ekskul.nilai.create')}}",
                    data: {nilai : arrayNilai},
                    headers: {'X-CSRF-TOKEN' : '{{csrf_token()}}'},
                    success: function(data) {
                        if(data.success == true) {
                            removeLoading();
                            oAlert("green","Berhasil","Deskripsi Ekskul Berhasil Disimpan");
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            };
            cConfirm("Perhatian","Yakin untuk menyimpan deskripsi ekskul siswa", simpanDeskripsi);
        });
    </script>
@endsection
