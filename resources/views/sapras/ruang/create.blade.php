@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('ruang-create')}}
    <div class="body-contain-customize col-12">
        <h5><b>Tambah Ruang</b></h5>
        <p>Halaman untuk menambahkan ruangan didalam sekolah</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p>Form Penambahan Ruangan</p>
        <form method="post" action="{{route('ruang.store')}}">
            @csrf
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-5 col-lg-3 col-xl-3 mb-2">
                    <label for="kode">Kode Ruangan</label>
                    <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror" value="{{old('kode')}}">
                    @error('kode')
                        <div class="invalid-feedback">
                            Wajib Diisi
                        </div>
                    @enderror
                </div>
                <div class="clearBoth"></div>
                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8 mb-2">
                    <label for="nama">Nama Ruangan</label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{old('nama')}}">
                    @error('nama')
                        <div class="invalid-feedback">
                            Wajib Diisi
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 mb-2">
                    <label for="warna">Warna</label>
                    <p class="fs-11 mb-2">Warna untuk membedakan jenis ruangan</p>
                    <div class="col-3 col-sm-2 col-md-2 col-lg-2 col-xl-1">
                        <input type="color" name="warna" id="warna" class="form-control @error('warna') is-invalid @enderror" value="{{old('warna')}}">
                    </div>
                    @error('warna')
                        <div class="invalid-feedback">
                            Wajib Diisi
                        </div>
                    @enderror
                </div>
                <div class="clearBoth"></div>
                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 mb-2">
                    <label for="umum">Fasilitas Umum</label>
                    <select name="umum" id="umum" class="form-control" data-toggle="select">
                        <option value="">Pilih Salah Satu</option>
                        <option value="iya" @if(old('umum') == "iya") selected @endif>Iya</option>
                        <option value="tidak" @if(old('umum') == "tidak") selected @endif>Tidak</option>
                    </select>
                    @error('umum')
                        <div class="invalid-feedback">
                            Wajib Diisi
                        </div>
                    @enderror
                </div>
                <div class="col-12 button-place mt-3">
                    <button type="submit" class="btn btn-sm btn-warning text-warning-emphasis">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
