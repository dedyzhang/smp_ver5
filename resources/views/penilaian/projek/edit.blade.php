@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('proyek-edit',$proyek)}}
    <div class="body-contain-customize col-12">
        <h5>Edit Proyek</h5>
        <p>Halaman ini diperuntukkan admin dan kurikulum untuk mengedit data project per Tahun</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <form method="POST" action="{{route('penilaian.p5.update',$proyek->uuid)}}">
            @csrf
            @method('put')
            <div class="row m-0 p-0">
                <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 m-0 p-0 form-group fs-12">
                    <label for="tingkat">Tingkat</label>
                    <input type="text" name="tingkat" id="tingkat" value="{{old('tingkat',$proyek->tingkat)}}" class="form-control @error('tingkat') is-invalid @enderror" placeholder="Tingkat Proyek P5" readonly>
                    <i class="fs-11">Tingkat tidak bisa diedit, untuk mengganti tingkat dapat menghapus dan menambahkan ulang proyek</i>
                </div>
                <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 m-0 p-0 form-group fs-12 mt-2">
                    <label for="judul">Judul Proyek P5</label>
                    <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" placeholder="Judul Proyek P5" value="{{old('judul', $proyek->judul)}}">
                    <div class="invalid-feedback">Wajib Diisi</div>
                </div>
                <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 m-0 p-0 form-group fs-12 mt-2">
                    <label for="deskripsi">Deskripsi Proyek P5</label>
                    <textarea name="deskripsi" id="deskripsi" rows="5" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Deskripsi Proyek P5">{{old('deskripsi',$proyek->deskripsi)}}</textarea>
                    <div class="invalid-feedback">Wajib Diisi</div>
                </div>
                <div class="button-place m-0 p-0 mt-3">
                    <button class="btn btn-sm btn-warning text-warning-emphasis" type="submit">
                        <i class="fas fa-save"></i> Edit Proyek P5
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
