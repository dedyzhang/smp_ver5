@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('walikelas-absensi')}}
    <div class="body-contain-customize col-12">
        <h5><b>Absensi Kelas</b></h5>
        <p>Halaman Walikelas untuk mengatur absensi siswa</p>
    </div>
    @if ($iswalikelas === false)
        <div class="body-contain-customize col-12 mt-3">
            <h4>ANDA BELUM TERJARING SEBAGAI WALIKELAS PADA TAHUN PELAJARAN INI</h4>
        </div>
    @else
        <div class="body-contain-customize col-12 mt-3">
            <a href="{{route('walikelas.absensi.create')}}" class="btn btn-sm btn-warning text-warning-emphasis">
                <i class="fas fa-plus"></i> Tambah Absensi
            </a>
        </div>
        <div class="body-contain-customize col-12 mt-3">
            <div class="row d-flex align-items-end m-0 p-0 mb-3">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <p>Pencarian Absensi berdasarkan Tanggal</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group fs-12">
                    <label for="tanggal-mulai">Tanggal Mulai</label>
                    <input type="date" class="form-control validate" id="tanggal-mulai" name="tanggal-mulai" />
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group fs-12">
                    <label for="tanggal-akhir">Tanggal Akhir</label>
                    <input type="date" class="form-control validate" id="tanggal-akhir" name="tanggal-akhir" value="{{date('Y-m-d')}}" />
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group fs-12 mt-2 mt-sm-2 mt-md-2 mt-lg-0 mt-xl-0">
                    <button class="btn btn-sm btn-warning text-warning-emphasis lihat-absensi">
                        <i class="fas fa-eye"></i> Lihat Absensi
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped fs-12" id="table-absensi">
                    <thead>
                        <tr class="text-center align-middle">
                            <td rowspan="2" width="5%">No</td>
                            <td rowspan="2" width="30%">Nama Siswa</td>
                            <td rowspan="2" width="10%">Jumlah Hari</td>
                            <td colspan="4">Jumlah Absensi</td>
                        </tr>
                        <tr class="text-center">
                            <td width="10%">Hadir</td>
                            <td width="10%">Sakit</td>
                            <td width="10%">Izin</td>
                            <td width="10%">Alpha</td>
                        </tr>
                    </thead>
                    <tbody class="absensi-table">
                        @foreach ($kelas->siswa as $siswa)
                            <tr class="text-center">
                                <td>{{$loop->iteration}}</td>
                                <td class="text-start">{{$siswa->nama}}</td>
                                <td>{{$jumlahAbsensi}}</td>
                                <td>{{isset($absensi_array[$siswa->uuid]['hadir']) ? $absensi_array[$siswa->uuid]['hadir'] : 0}}</td>
                                <td>{{isset($absensi_array[$siswa->uuid]['sakit']) ? $absensi_array[$siswa->uuid]['sakit'] : 0}}</td>
                                <td>{{isset($absensi_array[$siswa->uuid]['izin']) ? $absensi_array[$siswa->uuid]['izin'] : 0}}</td>
                                <td>{{isset($absensi_array[$siswa->uuid]['alpa']) ? $absensi_array[$siswa->uuid]['alpa'] : 0}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            $('.lihat-absensi').click(function() {
                var countError = 0;
                $('.validate').each(function() {
                    if($(this).val() == "") {
                        $(this).addClass('is-invalid');
                        if($(this).closest('.form-group').find('.invalid-feedback').length > 0) {
                            $(this).closest('.form-group').find('.invalid-feedback').html('Wajib Diisi');
                        } else {
                            $(this).closest('.form-group').append('<div class="invalid-feedback">Wajib Diisi</div>');
                        }
                        countError++;
                    }
                });
                if(countError == 0) {
                    let tanggalMulai = $('#tanggal-mulai').val();
                    let tanggalAkhir = $('#tanggal-akhir').val();
                    loading();
                    $.ajax({
                        url: '{{route("walikelas.absensi.getAbsen.byDate")}}',
                        type: 'GET',
                        data: {mulai : tanggalMulai, akhir: tanggalAkhir, kelas: '{{$kelas->uuid}}'},
                        success: function(data) {
                            var htmlSiswa = '';
                            var no = 1;
                            data.siswa.forEach(function(elem) {
                                var absensiswa = data.absensi[elem.uuid];
                                var absen = "";

                                htmlSiswa += `
                                    <tr class="text-center">
                                        <td>${no}</td>
                                        <td>${elem.nama}</td>
                                        <td>${data.jumlahAbsensi}</td>
                                        <td>${absensiswa && absensiswa.hadir ? absensiswa.hadir : 0}</td>
                                        <td>${absensiswa && absensiswa.sakit ? absensiswa.sakit : 0}</td>
                                        <td>${absensiswa && absensiswa.izin ? absensiswa.izin : 0}</td>
                                        <td>${absensiswa && absensiswa.alpa ? absensiswa.alpa : 0}</td>
                                    </tr>
                                `;
                                no++;
                            });
                            $('.absensi-table').html(htmlSiswa);
                            removeLoading();
                        },
                        error: function(data) {
                            removeLoading();
                            console.log(data);
                        }
                    });
                } else {
                    oAlert("red","Perhatian","Pastikan Tanggal Mulai dan Tanggal Akhir terisi dengan benar");
                }
            })
        </script>
    @endif

@endsection
