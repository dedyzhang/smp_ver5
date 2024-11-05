@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Projek P5</h5>
        <p>Halaman ini berguna untuk Pengelolaan Projek Penguatan Pancasila berfungsi sebagai pusat informasi, komunikasi, dan kolaborasi yang komprehensif.</p>
    </div>
    <div class="body-contain-customize col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex mt-3">
        <a href="{{route('penilaian.p5.atur')}}" class="btn btn-sm btn-warning text-warning-emphasis">
            <i class="fas fa-pencil"></i> Atur Dimensi
        </a>
    </div>
@endsection