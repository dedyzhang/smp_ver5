@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('pelajaran-create')}}
    <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <p><b>Form Penambahan Pelajaran</b></p>

        <div class="row mt-3">
            <form action="{{route('pelajaran.store')}}" method="POST" class="p-0">
                @csrf
                <div class="form-group mb-3 col-12">
                    <label for="pelajaran">Pelajaran</label>
                    <input type="text" id="pelajaran" name="pelajaran" class="form-control @error('pelajaran') is-invalid @enderror" placeholder="Masukkan Nama Pelajaran" value="{{old('pelajaran')}}">
                    @error('pelajaran')
                        <div class="invalid-feedback">
                            Pelajaran Wajib Diisi
                        </div>
                    @enderror
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="pelajaran_singkat">Nama Singkat Pelajaran</label>
                    <input type="text" id="pelajaran_singkat" name="pelajaran_singkat" class="form-control @error('pelajaran_singkat') is-invalid @enderror" placeholder="Masukkan Nama Singkat Pelajaran" value="{{old('pelajaran_singkat')}}">
                    @error('pelajaran_singkat')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="button-place">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa-solid fa-save"></i> Tambah Pelajaran</button>
                </div>
            </form>
        </div>
    </div>
@endsection
