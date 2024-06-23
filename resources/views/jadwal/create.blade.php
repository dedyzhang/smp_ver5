@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('jadwal-create')}}
    <div class="body-contain-customize col-12">
        <p><b>Tambahkan Versi Jadwal</b></p>
        <form action="{{route('jadwal.store')}}" method="post">
            @csrf
            <div class="row m-0">
                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 m-0 p-0 form-group">
                    <label for="versi">No Urut Versi</label>
                    <input type="text" class="form-control" readonly name="versi" id="versi" value="{{old('versi',$versiTerbaru)}}" placeholder="Masukkan Nama Versi">
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 m-0 p-0 form-group mt-3">
                    <label for="versi">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskirpsi" rows="5" placeholder="Masukkan Deskripsi Jadwal">{{old('deskripsi')}}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">
                            Deskripsi Wajib Diisi
                        </div>
                    @enderror
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 p-0 form-group mt-3">
                    <button type="submit" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </div>
        </form>
    </div>
@endsection
