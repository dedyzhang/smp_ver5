@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('aturan-create')}}
    <div class="body-contain-customize col-12">
        <h5><b>Tambah Aturan</b></h5>
        <p>Halaman ini diperuntukkan admin dan kesiswaan untuk menambah poin aturan siswa</p>
    </div>
    <div class="body-contain-customize mt-3 col-12">
        <p><b>Form Aturan</b></p>
        <div class="row m-0 p-0">
            <form action="{{route('aturan.store')}}" method="post">
                @csrf
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <label for="jenis">Jenis</label>
                    <select class="form-control @error('jenis') is-invalid @enderror" data-toggle="select" name="jenis" id="select">
                        <option value="">Pilih Salah Satu</option>
                        <option value="tambah" {{old('jenis') == "tambah" ? "selected" : ""}}>Tambah</option>
                        <option value="kurang" {{old('jenis') == "kurang" ? "selected" : ""}}>Kurang</option>
                    </select>
                    @error('jenis')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mt-2">
                    <label for="kode">Kode Aturan</label>
                    <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror" placeholder="Masukkan Kode Aturan" value="{{old('kode')}}">
                    @error('kode')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-8 col-xl-8 mt-2">
                    <label for="aturan">Aturan</label>
                    <textarea class="form-control @error('aturan') is-invalid @enderror" rows="3" id="aturan" name="aturan">{{old('aturan')}}</textarea>
                    @error('aturan')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mt-2">
                    <label for="poin">Poin</label>
                    <input type="number" name="poin" id="poin" class="form-control @error('poin') is-invalid @enderror" placeholder="Masukkan Poin Aturan" value="{{old('poin')}}">
                    @error('poin')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-sm btn-warning text-warning-emphasis">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
