@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        @if ($jenis == 'materi')
            <h5><b>Tambah Materi Pembelajaran</b></h5>
        @else
            <h5><b>Tambah Latihan Pembelajaran</b></h5>
        @endif
    </div>
@endsection
