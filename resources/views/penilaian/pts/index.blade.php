@extends('layouts.main') @section('container')
{{Breadcrumbs::render('penilaian-pts')}}
<div class="body-contain-customize col-12">
    <h5 class="m-0 mb-1"><b>PTS</b></h5>
    <p>
        Penilaian Tengah Semester adalah evaluasi yang dilakukan untuk mengukur
        pencapaian hasil belajar siswa selama setengah semester. PTS merupakan
        salah satu alat penting untuk memastikan bahwa standar pendidikan yang
        ditetapkan oleh sekolah tetap tercapai.
    </p>
</div>
@foreach ($ngajar as $ngajar)
<div class="col-md-6 col-lg-4 col-xl-3 col-sm-6 col-12 mt-3 pe-2 ps-0">
    <div class="card border-light rounded-4">
        <div class="card-body">
            <h6 class="m-0 p-0 mt-2 fs-18">
                <b>{{$ngajar->pelajaran_singkat}}</b>
            </h6>
            <p
                class="m-0 p-0 mt-3 p-2 rounded-3 @if($ngajar->tingkat == 7) bg-success-subtle @elseif($ngajar->tingkat == 8) bg-warning-subtle @else bg-info-subtle @endif"
            >
                {{$ngajar->tingkat.$ngajar->kelas}}
            </p>
            <div class="button-place mt-3">
                <a
                    href="{{route('penilaian.pts.show',$ngajar->uuid)}}"
                    class="btn btn-sm btn-warning text-warning-emphasis"
                    data-bs-toggle="tooltip"
                    data-bs-title="Lihat PTS"
                    data-bs-placement="top"
                    ><i class="fas fa-eye"></i
                ></a>
            </div>
        </div>
    </div>
</div>
@endforeach @endsection
