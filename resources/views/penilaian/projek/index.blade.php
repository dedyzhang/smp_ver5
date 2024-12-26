@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Projek P5</h5>
        <p>Halaman ini berguna untuk Pengelolaan Projek Penguatan Pancasila berfungsi sebagai pusat informasi, komunikasi, dan kolaborasi yang komprehensif.</p>
    </div>
    <div class="body-contain-customize gap-2 col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex mt-3">
        <a href="{{route('penilaian.p5.atur')}}" class="btn btn-sm btn-warning text-warning-emphasis">
            <i class="fas fa-pencil"></i> Atur Dimensi
        </a>
        <a href="{{route('penilaian.p5.create')}}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Projek
        </a>
    </div>
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
                        <h6 class="card-title" data-bs-toggle="tooltip" data-bs-title="{{$item->judul}}" data-bs-placement="top"><b>{{Str::limit($item->judul,25,'...')}}</b></h6>
                        <p class="@if($item->tingkat == 7) bg-success-subtle @elseif($item->tingkat == 8) bg-warning-subtle @else bg-danger-subtle @endif pt-2 pb-2 ps-1 pe-1 rounded-3">Tingkat {{$item->tingkat}}</p>
                        <p class="card-text fs-12" data-bs-toggle="tooltip" data-bs-title="{{$item->deskripsi}}" data-bs-placement="top">{{Str::limit($item->deskripsi,50,'...')}}</p>

                        <div class="btn-place mt-3">
                            <a href="{{route('penilaian.p5.edit',$item->uuid)}}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-title="Edit Proyek" data-bs-placement="top">
                                <i class="fas fa-pencil"></i>
                            </a>
                            <a href="{{route('penilaian.p5.config',$item->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis" data-bs-toggle="tooltip" data-bs-title="Atur Dimensi" data-bs-placement="top">
                                <i class="fas fa-gear"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
