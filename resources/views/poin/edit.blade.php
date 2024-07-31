@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('aturan-edit',$aturan)}}
    <div class="body-contain-customize col-12">
        <h5><b>Edit Aturan</b></h5>
        <p>Halaman ini diperuntukkan admin dan kesiswaan untuk mengedit poin aturan siswa</p>
    </div>
    <div class="body-contain-customize mt-3 col-12">
        <p><b>Form Edit Aturan</b></p>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert">
                <i class="bi flex-shrink-0 me-2 fa-solid fa-check" aria-label="Success:"></i>
                <div>
                    <strong>Sukses !</strong> {{session('success')}}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row m-0 p-0">
            <form action="{{route('aturan.update',$aturan->uuid)}}" method="post">
                @csrf
                @method('put')
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <label for="jenis">Jenis</label>
                    <select class="form-control @error('jenis') is-invalid @enderror" data-toggle="select" name="jenis" id="select">
                        <option value="">Pilih Salah Satu</option>
                        <option value="tambah" {{old('jenis',$aturan->jenis) == "tambah" ? "selected" : ""}}>Tambah</option>
                        <option value="kurang" {{old('jenis',$aturan->jenis) == "kurang" ? "selected" : ""}}>Kurang</option>
                    </select>
                    @error('jenis')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mt-2">
                    <label for="kode">Kode Aturan</label>
                    <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror" placeholder="Masukkan Kode Aturan" value="{{old('kode',$aturan->kode)}}">
                    @error('kode')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-8 col-xl-8 mt-2">
                    <label for="aturan">Aturan</label>
                    <textarea class="form-control @error('aturan') is-invalid @enderror" rows="3" id="aturan" name="aturan">{{old('aturan',$aturan->aturan)}}</textarea>
                    @error('aturan')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mt-2">
                    <label for="poin">Poin</label>
                    <input type="number" name="poin" id="poin" class="form-control @error('poin') is-invalid @enderror" placeholder="Masukkan Poin Aturan" value="{{old('poin',$aturan->poin)}}">
                    @error('poin')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-sm btn-warning text-warning-emphasis">
                        <i class="fas fa-save"></i> Edit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
