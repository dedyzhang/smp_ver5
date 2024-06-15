@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Tambah Waktu</b></h5>
        <p>Penambahan Waktu dalam Jadwal Pelajaran</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert">
                <i class="bi flex-shrink-0 me-2 fa-solid fa-check" aria-label="Success:"></i>
                <div>
                    <strong>Sukses !</strong> Menambahkan Waktu Di Jadwal
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert">
                <i class="bi flex-shrink-0 me-2 fa-solid fa-triangle-exclamation" aria-label="Success:"></i>
                <div>
                    <strong>Error !</strong> {{session('error')}}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <form action="{{route('jadwal.waktu.store',$versi->uuid)}}" method="POST">
            @csrf
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                    <label for="waktu_mulai">Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control @error('waktu_mulai') is-invalid @enderror" value="{{old('waktu_mulai')}}" placeholder="Masukkan Waktu Mulai Jadwal">
                    @error('waktu_mulai')
                        <div class="invalid-feedback">Waktu Mulai Wajib Diisi</div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 mt-3">
                    <label for="waktu_akhir">Waktu Akhir</label>
                    <input type="time" name="waktu_akhir" id="waktu_akhir" class="form-control @error('waktu_akhir') is-invalid @enderror" value="{{old('waktu_akhir')}}" placeholder="Masukkan Waktu Akhir Jadwal">
                    @error('waktu_akhir')
                        <div class="invalid-feedback">Waktu Akhir Wajib Diisi</div>
                    @enderror
                </div>
                <div class="button-place col-12 d-grid col-sm-12 d-sm-grid col-sm-8 d-md-flex col-lg-8 d-lg-flex col-xl-8 d-xl-flex mt-3">
                    <button class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </div>
        </form>
    </div>
@endsection
