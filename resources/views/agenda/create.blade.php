@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Tambah Agenda</b></h5>
        <p>Halaman Tambah Agenda diperuntukkan guru menambahkan agenda harian mengajar, proses selama mengajar serta hasil dan tindak lanjut dalam mengajar</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>A.Tanggal dan Jadwal</b></p>
        <div class="row m-0 p-0 gap-2">
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 p-0">
                <label for="tanggal">tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control">
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 p-0">
                <label for="jadwal">Jadwal</label>
                <select class="form-control" data-toggle="select" name="jadwal" id="jadwal" data-width="100%">
                    <option value="">Pilih Salah Satu</option>
                </select>
            </div>
        </div>
    </div>
    <script>
        $('#tanggal').on('blur',function() {
            var tanggal = $(this).val();
            var ini = this;
            if($(this).closest('.form-group').find('.invalid-feedback').length > 0) {
                $(this).closest('.form-group').find('.invalid-feedback').remove();
            }
            if (new Date(tanggal) != "Invalid Date") {
                var url = "{{route('agenda.cekTanggal')}}";
                $.ajax({
                    type: "get",
                    url: url,
                    data: {tanggal : tanggal},
                    success: function(data) {
                        if(data.success == true) {
                            if($(ini).hasClass('is-invalid')) {
                                $(ini).removeClass('is-invalid');
                                $(ini).closest('.form-group').find('.invalid-feedback').remove();
                            }
                            console.log(data);
                        } else {
                            $(ini).addClass('is-invalid');
                            $(ini).closest('.form-group').append('<div class="invalid-feedback">'+data.message+'</div>')
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }
        });
    </script>
@endsection
