@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('penilaian')}}
    <div class="body-contain-customize col-12">
        <h5><b>Penilaian</b></h5>
        <p>Halaman ini berisi data penilaian yang diinput oleh guru pada masa semester tahun pelajaran berjalan</p>
    </div>
    @foreach ($pelajaran as $plj)
        <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mt-3">
            <div class="card border-light rounded-4" data-uuid="{{$plj->uuid}}">
                <div class="card-body">
                    <h5><b>{{$plj->pelajaran_singkat}}</b></h5>
                    <div class="button-place mt-5 d-flex justify-content-end">
                        <button class="btn btn-sm btn-warning text-warning-emphasis lihat-penilaian"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="modal fade in" id="modal-penilaian">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p><b>Data Penilaian</b></p>
                    <button class="btn btn-close btn-sm" data-bs-dismiss="modal"></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.lihat-penilaian').click(function() {
            var uuid = $(this).closest('.card').data('uuid');
            var url = "{{route('penilaian.get',':id')}}";
            url = url.replace(':id',uuid);
            $.ajax({
                type: "get",
                url : url,
                success: function(data) {
                    if(data.success) {
                        var pelajaran = data.data;
                        pelajaran.forEach(item => {

                        });
                    }
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
            $('#modal-penilaian').modal("show");
        })
    </script>
@endsection
