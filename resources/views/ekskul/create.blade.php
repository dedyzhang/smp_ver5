@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('ekskul-create')}}
    <div class="body-contain-customize col-12">
        <h5><b>Data Ekskul</b></h5>
        <p>Halaman ini menyediakan platform mudah untuk mengelola data ekstrakurikuler, memungkinkan pengguna untuk menambah, mengedit, dan menghapus informasi kegiatan secara efisien.</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p>Form Penambahan Ekskul</p>
        <form action="{{route('ekskul.store')}}" method="post">
            @csrf
            <div class="col-12 col-sm-12 col-md-10 col-lg-10 col-xl-10 form-group">
                <label for="ekskul">Nama Ekstrakurikuler</label>
                <input type="text" name="ekskul" id="ekskul" class="form-control @error('ekskul') is-invalid @enderror" placeholder="Masukkan Nama Ekskul" value="{{old('ekskul')}}">
                <div class="invalid-feedback">Kolom ini wajib diisi</div>
            </div>
            <div class="col-12 col-sm-12 col-md-10 col-lg-10 col-xl-10 form-group mt-2">
                <label for="guru">Guru Yang Mengajar</label>
                <select class="form-control @error('guru') is-invalid @enderror" name="guru" id="guru" data-toggle="select" value="{{old('guru')}}">
                    <option value="">Pilih salah satu</option>
                    @foreach ($guru as $item)
                        <option value="{{$item->uuid}}">{{$item->nama}}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Kolom ini wajib diisi</div>
            </div>
            <div class="col-12 col-sm-12 col-md-10 col-lg-10 col-xl-10 form-group mt-2">
                <label for="pelajaran">Nilai Diambil dari Pelajaran</label>
                <select class="form-control" name="pelajaran" id="pelajaran" data-toggle="select">
                    <option value="">Tidak ambil dari Pelajaran</option>
                    @foreach ($pelajaran as $item)
                        <option value="{{$item->uuid}}">{{$item->pelajaran}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-3 col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-block col-lg-auto d-lg-block col-xl-auto d-xl-block">
                <button type="submit" class="btn btn-sm btn-warning text-warning-emphasis">
                    <i class="fa-solid fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
