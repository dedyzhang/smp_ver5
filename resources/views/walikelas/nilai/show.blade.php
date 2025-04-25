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
                <div class="modal-body">
                    <div class="accordion" id="accordion-pelajaran">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.lihat-penilaian').click(function() {
            loading();
            var uuid = $(this).closest('.card').data('uuid');
            var url = "{{route('walikelas.nilai.harian.get',':id')}}";
            url = url.replace(':id',uuid);
            $.ajax({
                type: "get",
                url : url,
                success: function(data) {
                    alert(data);
                    var html = "";
                    if(data.success) {
                        var pelajaran = data.data;
                        pelajaran.sort(function(a,b){
                            var kelasA = a.kelas.tingkat+a.kelas.kelas;
                            var kelasB = b.kelas.tingkat+b.kelas.kelas;

                            if(kelasA == kelasB) {return 0;}
                            if(kelasA > kelasB) {
                                return 1;
                            } else {
                                return -1;
                            }
                        });
                        pelajaran.forEach(item => {
                            if(item.pelajaran.has_penjabaran == 1 || item.pelajaran.has_penjabaran == 2) {
                                var link = `
                                <a href="{{route('penilaian.admin.materi.show',':id')}}" class="list-group-item list-group-item-action">Materi</a>
                                <a href="{{route('penilaian.admin.formatif.show',':id')}}" class="list-group-item list-group-item-action">Nilai Formatif</a>
                                <a href="{{route('penilaian.admin.sumatif.show',':id')}}" class="list-group-item list-group-item-action">Nilai Sumatif</a>
                                <a href="{{route('penilaian.admin.pts.show',':id')}}" class="list-group-item list-group-item-action">Nilai PTS</a>
                                <a href="{{route('penilaian.admin.pas.show',':id')}}" class="list-group-item list-group-item-action">Nilai PAS</a>
                                <a href="{{route('penilaian.admin.rapor.show',':id')}}" class="list-group-item list-group-item-action">Nilai Rapor</a>
                                <a href="{{route('penilaian.admin.penjabaran.show',':id')}}" class="list-group-item list-group-item-action">Nilai Penjabaran</a>
                                `;
                            } else {
                                var link = `
                                <a href="{{route('penilaian.admin.materi.show',':id')}}" class="list-group-item list-group-item-action">Materi</a>
                                <a href="{{route('penilaian.admin.formatif.show',':id')}}" class="list-group-item list-group-item-action">Nilai Formatif</a>
                                <a href="{{route('penilaian.admin.sumatif.show',':id')}}" class="list-group-item list-group-item-action">Nilai Sumatif</a>
                                <a href="{{route('penilaian.admin.pts.show',':id')}}" class="list-group-item list-group-item-action">Nilai PTS</a>
                                <a href="{{route('penilaian.admin.pas.show',':id')}}" class="list-group-item list-group-item-action">Nilai PAS</a>
                                <a href="{{route('penilaian.admin.rapor.show',':id')}}" class="list-group-item list-group-item-action">Nilai Rapor</a>
                                `;
                                }
                            link = link.replaceAll(':id',item.uuid);
                            html += `
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse.${item.uuid}" aria-expanded="false" aria-controls="collapse.${item.uuid}">
                                    ${item.kelas.tingkat+item.kelas.kelas+" - "+item.guru.nama}
                                </button>
                                </h2>
                                <div id="collapse.${item.uuid}" class="accordion-collapse collapse" data-bs-parent="#accordion-pelajaran">
                                    <div class="accordion-body">
                                        <div class="list-group fs-13">
                                            ${link}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                        });
                        $('#accordion-pelajaran').html(html);
                        $('#modal-penilaian').modal("show");
                        removeLoading();
                    }
                },
                error: function(data) {
                    alert(data.responseJSON.message);
                    console.log(data.responseJSON.message);
                }
            })

        });
    </script>
@endsection
