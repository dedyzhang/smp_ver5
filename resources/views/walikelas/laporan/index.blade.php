@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Laporan Walikelas</b></h5>
        <p>Halaman ini berguna untuk mengakses, mengedit, dan menyelesaikan laporan walikelas dengan mudah. Pengguna dapat melihat data siswa, melihat rekap absensi, dan memastikan laporan sudah siap untuk disampaikan. </p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="row m-0 p-0 d-flex align-items-end">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <label for="bulan">Pilih Bulan Laporan</label>
                <select data-toggle="select" name="bulan" id="bulan" class="form-control">
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
                <div class="invalid-feedback">
                    Tidak Boleh Kosong
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <button class="btn btn-sm btn-warning text-warning-emphasis lihat-laporan">
                    <i class="fas fa-eye"></i> Lihat Laporan
                </button>
            </div>
        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p>Rekap Laporan Walikelas</p>
    </div>
    <script>
        $('.lihat-laporan').click(function() {
            var bulan = $('#bulan').val();

            if(bulan == "") {
                $('#bulan').addClass('is-invalid');
                $('#bulan').closest('.d-flex').removeClass('align-items-end').addClass('align-items-center');
            } else {
                $('#bulan').removeClass('is-invalid');
                $('#bulan').closest('.d-flex').addClass('align-items-end').removeClass('align-items-center');
                $.ajax({
                    type: "GET",
                    url: "{{route('walikelas.laporan.get')}}",
                    data: {bulan : bulan},
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        console.log(data);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
        });
    </script>
@endsection
