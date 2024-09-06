@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('absensi-create')}}
    <div class="body-contain-customize col-12">
        <h5><b>Tambah Absensi</b></h5>
        <p class="m-0">Halaman untuk menambahkan absensi siswa , guru dan staf</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>Atur Bulan dan Tahun</b></p>
        <div class="row m-0 gap-3 d-flex align-items-end">
            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group p-0">
                <label for="bulan">Bulan</label>
                <select name="bulan" id="bulan" class="form-control">
                    <option value="">Pilih Salah Satu</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                </select>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group p-0">
                <label for="tahun">Tahun</label>
                <input type="number" name="tahun" id="tahun" class="form-control" value="{{date('Y')}}" placeholder="masukkan Tahun">
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 button-place p-0 d-grid d-sm-grid d-md-block d-lg-block d-xl-block">
                <button class="btn btn-sm btn-warning text-warning-emphasis atur-tanggal"><i class="fas fa-calendar"></i> Tampilkan</button>
            </div>
        </div>
        <p>Atur Jenis Jadwal yang digunakan</p>
        <div class="row m-0 mt-3">
            <div class="col-12 m-0 p-0 form-group">
                <label for="jadwal">Versi Jadwal</label>
                <select name="jadwal" id="jadwal" class="form-control">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($jadwalVersi as $versi)
                        <option value="{{$versi->uuid}}">Versi {{$versi->versi}} (Status : {{$versi->status}} )</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3 absensi-tambah">
        <div class="calendar">
            <div class="week">
                <div>Mi</div>
                <div>Se</div>
                <div>Sa</div>
                <div>Ra</div>
                <div>Ka</div>
                <div>Ju</div>
                <div>Su</div>
            </div>
            <div class="days">

            </div>
        </div>
        <div class="button-place input mt-3">

        </div>
        <div class="instruction row m-0 mt-4 p-0 gap-1 fs-12">
            <p class="m-0 p-0">Keterangan warna</p>
            <div class="col-12 p-0">
                <i class="bg-success" style="padding:0 8px; border-radius:100%; margin-right: 15px"></i> centang warna merah untuk menambahkan tanggal
            </div>
            <div class="col-12 p-0">
                <i class="bg-danger" style="padding:0 8px; border-radius:100%; margin-right: 15px"></i> centang warna hijau jika di hari bersangkutan wajib mengisi agenda
            </div>
            <div class="col-12 p-0">
                <i class="bg-primary" style="padding:0 8px; border-radius:100%; margin-right: 15px"></i> centang warna biru jika siswa juga hadir di tanggal bersangkutan
            </div>
        </div>
    </div>
    <script>
        $('.atur-tanggal').click(function() {
            var bulan = $('#bulan').val();
            var tahun = $('#tahun').val();
            if(bulan == "" || tahun == "") {
                oAlert("blue","Perhatian","bulan dan tahun wajib diisi");
            } else {
                $.ajax({
                    type: "GET",
                    url: "{{route('absensi.get')}}",
                    data: {bulan: bulan, tahun: tahun},
                    success: function(data) {
                        tanggalAda = [];
                        data.forEach(element => {
                            var tanggal = element.tanggal.split('-').pop();
                            tanggalAda[tanggal] = [{
                                "agenda" : element.agenda,
                                "siswa" : element.ada_siswa
                            }];
                        });
                        let days = document.querySelector(".days");
                        days.innerHTML = "";
                        const firstDay = new Date(tahun+"-"+bulan);
                        const firstDayIndex = firstDay.getDay();

                        var bulanIni = bulan - 1;
                        const lastDay = new Date(tahun,bulanIni + 1,0);
                        const numberOfDays = lastDay.getDate();

                        for (let x = 1; x <= firstDayIndex; x++) {
                            let div = document.createElement("div");
                            div.innerHTML += "";
                            days.appendChild(div);
                        }

                        for(let i = 1; i <= numberOfDays; i++) {
                            let div = document.createElement('div');
                            let currentDate = new Date(tahun,bulan,i);
                            var tanggalArray = String(i).padStart(2,0);
                            if(tanggalAda[tanggalArray]) {
                                var tanggal = "checked disabled";
                                if(tanggalAda[tanggalArray][0]['agenda'] == 1) {
                                    var agenda = "checked";
                                } else {
                                    var agenda = "";
                                }
                                if(tanggalAda[tanggalArray][0]['siswa'] == 1) {
                                    var siswa = "checked";
                                } else {
                                    var siswa = "";
                                }
                            } else {
                                var agenda = "";
                                var siswa = "";
                                var tanggal = "";
                            }
                            let buttons = `
                                <div class="d-inline-block">
                                    <input class="form-check-input is-valid" type="checkbox" ${tanggal} id="tanggal-${i}" name="tanggal" value="${i}" aria-label="Tambah Tanggal">
                                    <input class="form-check-input is-invalid" type="checkbox" ${agenda} id="agenda-${i}" name="agenda" value="${i}" aria-label="Ada Agenda">
                                    <input class="form-check-input" type="checkbox" ${siswa} id="siswa-${i}" name="agenda" value="${i}" aria-label="Untuk Siswa">
                                </div>
                            `;

                            div.dataset.date = currentDate.toDateString();

                            content = "<div class='w-100'>"+i+"</div>"+buttons;
                            div.innerHTML += content;
                            days.appendChild(div);
                        }
                        $('.button-place.input').html('<button class="btn btn-sm btn-warning text-warning-emphasis tambah-tanggal"><i class="fas fa-plus"></i> Simpan Tanggal</button>')
                    },error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }
        });
        $('.button-place.input').on('click','.tambah-tanggal',function() {
            var tambahTanggal = () => {
                var jadwal = $('#jadwal').val();
                if(jadwal == "") {
                    oAlert("orange","Perhatian","Versi Jadwal tidak boleh kosong")
                } else {
                    BigLoading("Aplikasi sedang menambahkan tanggal. Mohon untuk tidak menutup aplikasi sebelum proses selesai");
                    var tanggalArray = [];
                    var bulan = $('#bulan').val();
                    var tahun = $('#tahun').val();
                    $('.form-check-input[name="tanggal"]:checked').each(function() {
                        var tanggal = $(this).val();
                        if($('#agenda-'+tanggal).is(':checked')) {
                            var agenda = 1;
                        } else {
                            var agenda = 0;
                        }
                        if($('#siswa-'+tanggal).is(':checked')) {
                            var siswa = 1;
                        } else {
                            var siswa = 0;
                        }
                        var formatTanggal = tahun+"-"+bulan.padStart(2,"0")+"-"+tanggal.padStart(2,"0");
                        tanggalArray.push({
                            tanggal : formatTanggal,
                            agenda : agenda,
                            ada_siswa : siswa,
                            id_jadwal : jadwal,
                            semester : "{{$semester}}"
                        });
                    });
                    console.log(tanggalArray);
                    var url = "{{route('absensi.store')}}";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {tanggal : tanggalArray,jadwal : jadwal},
                        headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                        success: function(data) {
                            removeLoadingBig();
                            cAlert('green',"Berhasil","Tanggal Berhasil Ditambahkan",true);
                        },
                        error: function(data) {
                            console.log(data.responseJSON.message);
                        }
                    })
                }
            }
            cConfirm("Perhatian","Yakin untuk menambahkan tanggal ?",tambahTanggal);
        });
    </script>
@endsection
