@extends('layouts.main')

@section('container')
    @if (\Request::route()->getName() === 'penilaian.guru.proyek.index')
        {{Breadcrumbs::render('proyek-index-guru')}}
    @else
        {{Breadcrumbs::render('proyek-index')}}
    @endif

    <div class="body-contain-customize col-12">
        <h5>Projek P5</h5>
        <p>Halaman ini berguna untuk Pengelolaan Projek Penguatan Pancasila berfungsi sebagai pusat informasi, komunikasi, dan kolaborasi yang komprehensif.</p>
    </div>
    @if ($ifGuru == "no")
        <div class="body-contain-customize gap-2 col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex mt-3">
            <a href="{{route('penilaian.p5.atur')}}" class="btn btn-sm btn-warning text-warning-emphasis">
                <i class="fas fa-pencil"></i> Atur Dimensi
            </a>
            <a href="{{route('penilaian.p5.create')}}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah Projek
            </a>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Berhasil</strong> {{session('success')}}
        </div>
    @endif
    <div class="row m-0 p-0 mt-4">
        @foreach ($proyek as $item)
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 m-0 p-0">
                <div class="card m-2 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        @php
                            if($ifGuru == "yes") {
                                $proyekUUid = $item->uuid;
                                $fasilitatorFilter = $fasilitator->first(function($elem) use ($proyekUUid) {
                                    return $elem->id_proyek == $proyekUUid;
                                });
                            }
                        @endphp
                        <h6 class="card-title" data-bs-toggle="tooltip" data-bs-title="{{$item->judul}}" data-bs-placement="top"><b>{{Str::limit($item->judul,25,'...')}}</b></h6>
                        <p class="@if($item->tingkat == 7) bg-success-subtle @elseif($item->tingkat == 8) bg-warning-subtle @else bg-danger-subtle @endif pt-2 pb-2 ps-1 pe-1 rounded-3">
                        @if ($ifGuru == "yes")
                            {{$fasilitatorFilter->kelas->tingkat.$fasilitatorFilter->kelas->kelas}}
                        @elseif($ifGuru == "no")
                            Tingkat {{$item->tingkat}}
                        @endif</p>
                        <p class="card-text fs-12" data-bs-toggle="tooltip" data-bs-title="{{$item->deskripsi}}" data-bs-placement="top">{{Str::limit($item->deskripsi,50,'...')}}</p>

                        <div class="btn-place mt-3">
                            @if ($ifGuru == "no")
                                <a href="{{route('penilaian.p5.edit',$item->uuid)}}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-title="Edit Proyek" data-bs-placement="top">
                                    <i class="fas fa-pencil"></i>
                                </a>
                                <a href="{{route('penilaian.p5.fasilitator',$item->uuid)}}" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-title="Edit Fasilitator" data-bs-placement="top">
                                    <i class="fas fa-users"></i>
                                </a>
                                <a href="{{route('penilaian.p5.config',$item->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis" data-bs-toggle="tooltip" data-bs-title="Atur Dimensi" data-bs-placement="top">
                                    <i class="fas fa-gear"></i>
                                </a>
                            @endif
                            @if ($ifGuru == "no")
                                <button class="btn btn-sm btn-danger lihat-nilai" data-uuid="{{$item->uuid}}" data-bs-toggle="tooltip" data-bs-title="lihat Nilai" data-bs-placement="top">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @else
                                <a href="{{route('penilaian.guru.proyek.nilai',$fasilitatorFilter->uuid)}}" class="btn btn-sm btn-warning text-text-warning-emphasis" data-bs-toggle="tooltip" data-bs-title="lihat Nilai" data-bs-placement="top">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="modal fade in" id="lihatNilai">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Lihat Nilai Proyek</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">

                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($ifGuru == "no")
    <script>
        $('.lihat-nilai').click(function() {
            var uuid = $(this).data('uuid');
            var url = "{{route('penilaian.p5.fasilitator.get',':id')}}";
            url = url.replace(':id',uuid);
            var html = '';
            loading();
            $.ajax({
                type: "get",
                url : url,
                success: function(data) {
                    var fasilitator = data.fasilitator;

                    Object.keys(fasilitator).forEach(function(elemen) {
                        var link = "{{route('penilaian.p5.nilai',':id')}}";
                        link = link.replace(':id',fasilitator[elemen].uuid);
                        html += `<a href="${link}" class="list-group-item list-group-item-action">${fasilitator[elemen].kelas.tingkat+fasilitator[elemen].kelas.kelas} - ${fasilitator[elemen].guru.nama}</a>`;
                    });
                    $('#lihatNilai .modal-body .list-group').html(html);
                    removeLoading();
                    $('#lihatNilai').modal('show');
                },error: function(data) {
                    console.log(data.responseJSON.message);
                }
            });
        });
    </script>
    @endif
@endsection
