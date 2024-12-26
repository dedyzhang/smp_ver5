@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Tambah Proyek</h5>
        <p>Halaman ini diperuntukkan admin dan kurikulum untuk menambahkan data project per Tahun</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <form method="POST" action="{{route('penilaian.p5.store')}}">
            @csrf
            <div class="row m-0 p-0">
                <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 m-0 p-0 form-group fs-12">
                    <label for="tingkat">Tingkat</label>
                    <select name="tingkat" id="tingkat" class="form-control @error('tingkat') is-invalid @enderror">
                        <option value="">Pilih Salah Satu</option>
                        @foreach ($tingkat as $key => $value)
                            <option value="{{$key}}" @if (old('tingkat') == $key) selected @endif>{{$key}}</option>
                        @endforeach
                        <div class="invalid-feedback">Wajib Diisi</div>
                    </select>
                </div>
                <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 m-0 p-0 form-group fs-12 mt-2">
                    <label for="judul">Judul Proyek P5</label>
                    <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" placeholder="Judul Proyek P5" value="{{old('judul')}}">
                    <div class="invalid-feedback">Wajib Diisi</div>
                </div>
                <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 m-0 p-0 form-group fs-12 mt-2">
                    <label for="deskripsi">Deskripsi Proyek P5</label>
                    <textarea name="deskripsi" id="deskripsi" rows="5" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Deskripsi Proyek P5">{{old('deskripsi')}}</textarea>
                    <div class="invalid-feedback">Wajib Diisi</div>
                </div>
                <div class="button-place m-0 p-0 mt-3">
                    <button class="btn btn-sm btn-warning text-warning-emphasis" type="submit">
                        <i class="fas fa-plus"></i> Tambah Proyek P5
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
