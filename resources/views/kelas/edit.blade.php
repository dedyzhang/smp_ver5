@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('kelas-edit',$kelas)}}
    <div class="body-contain-customize col-md-12 col-lg-12 col-sm-12">
        <p class="body-title">Form Edit Data Kelas</p>
        {{-- <hr /> --}}
        <div class="row mt-3 m-0">
            <form action="{{route('kelas.update',$kelas->uuid)}}" method="post" class="p-0">
                @csrf
                @method('PUT')
                <div class="form-group mb-3 col-12">
                    <label for="tingkat">Tingkat</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="number" name="tingkat" id="tingkat" placeholder="Masukkan tingkat Kelas" class="form-control @error('tingkat') is-invalid @enderror" value="{{old('tingkat',$kelas->tingkat)}}" />
                         @error('tingkat')
                            <div class="invalid-feedback">
                                Tingkat Wajib Diisi, Diisi dengan Angka
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="kelas">Nama Kelas</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="kelas" id="kelas" placeholder="Masukkan Nama Kelas" class="form-control @error('kelas') is-invalid @enderror" value="{{old('kelas',$kelas->kelas)}}" />
                         @error('kelas')
                            <div class="invalid-feedback">
                                Nama Kelas Wajib Diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-12 button-place mt-3">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        Edit Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
