@extends('layouts.main') @section('container')
{{-- {{Breadcrumbs::render('penilaian-pts')}} --}}
<div class="body-contain-customize col-12">
    <h5 class="m-0 mb-1"><b>Classroom</b></h5>
    <p>
        Classroom adalah halaman bagi admin untuk mengecek data classroom yang sudah diassign oleh guru dan jawaban yang sudah dijawab oleh siswa.
    </p>
</div>
@foreach ($kelas as $kls)
    <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mt-3">
        <div class="card border-light rounded-4" data-uuid="{{ $kls->uuid }}">
            <div class="card-body">
                <h5><b>{{ $kls->tingkat . $kls->kelas }}</b></h5>
                <div class="button-place mt-5 d-flex justify-content-end">
                    <a href="{{ route('penilaian.classroom.show', $kls->uuid) }}"
                        class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-eye"></i></a>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
