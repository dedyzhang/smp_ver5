@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('siswa-create')}}
    <div class="body-contain-customize col-md-12 col-lg-12 col-sm-12">
        <p class="body-title">Form Penambahan Siswa</p>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert">
                <i class="bi flex-shrink-0 me-2 fa-solid fa-check" aria-label="Success:"></i>
                <div>
                    <strong>Sukses !</strong> {{session('success')}}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session('error'))

        @endif
        {{-- <hr /> --}}
        <div class="row mt-3 m-0">
            <form action="{{route('siswa.store')}}" method="post" class="p-0">
                @csrf
                <div class="form-group mb-3 col-12">
                    <label for="nis">NIS</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="nis" id="nis" placeholder="Masukkan NIS siswa" readonly class="form-control @error('nis') is-invalid @enderror" value="{{$nis->first_nis.".".$nis->second_nis.".".$nis->third_nis}}" />
                            @error('nis')
                            <div class="invalid-feedback">
                                NIS Siswa Wajib Diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="nama">Nama Siswa</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="nama" id="nama" placeholder="Masukkan nama siswa" class="form-control @error('nama') is-invalid @enderror" value="{{old('nama')}}" />
                            @error('nama')
                            <div class="invalid-feedback">
                                Nama Siswa Wajib Diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <p class="d-block">Jenis Kelamin</p>
                    <div class="form-check form-check-inline mt-1">
                        <input type="radio" class="form-check-input @error('jk') is-invalid @enderror" name="jk" id="jk-laki" value="l" @if (old('jk') == 'l') checked @endif>
                        <label class="form-check-label" for="jk-laki">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline mt-1">
                        <input type="radio" class="form-check-input @error('jk') is-invalid @enderror" name="jk" id="jk-pere" value="p" @if (old('jk') == 'p') checked @endif>
                        <label class="form-check-label" for="jk-pere">Perempuan</label>
                    </div>
                    @error('jk')
                        <div class="invalid-feedback d-block">
                            Jenis Kelamin Wajib Diisi
                        </div>
                    @enderror
                </div>
                <div class="col-12 button-place mt-4">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
