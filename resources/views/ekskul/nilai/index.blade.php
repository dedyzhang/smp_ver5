@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('ekskul-nilai')}}
    <div class="body-contain-customize col-12">
        <h5><b>Nilai Ekskul</b></h5>
        <p>Halaman ini berisi informasi penting mengenai penilaian ekstrakurikuler, termasuk kriteria penilaian, tujuan kegiatan, dan dampaknya terhadap pengembangan keterampilan siswa.</p>
    </div>
    <div class="row m-0 p-0">
        @foreach ($ekskul as $item)
            <div class="col-md-6 col-lg-4 col-xl-3 col-sm-6 col-12 mt-3 pe-2 ps-0">
                <div class="card border-light rounded-4">
                    <div class="card-body">
                        <h6><i class="fas fa-baseball me-2"></i> <b>{{Str::limit($item->ekskul,20,'...')}}</b></h6>
                        <p class="fs-12">{{Str::limit($item->guru->nama,25,'...')}}</p>

                        <div class="button-place mt-4 gap-2">
                           <a href="{{route('ekskul.nilai.show',$item->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-eye"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
