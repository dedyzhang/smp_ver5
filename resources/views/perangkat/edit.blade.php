@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('perangkat-edit',$perangkat)}}
    <div class="body-contain-customize col-12">
        <h5><b>Perangkat Pembelajaran</b></h5>
        <p>Halaman ini diperuntukkan admin dan kurikulum untuk memantau perangkat pembelajaran yang diupload oleh guru</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p>Form Penambahan Perangkat Pembelajaran yang akan diupload</p>
        <form method="post" action="{{route('perangkat.update',$perangkat->uuid)}}">
            @csrf
            @method('put')
            <div class="form-group col-10">
                <label for="perangkat">Nama Perangkat</label>
                <input type="text" class="form-control @error('perangkat') is-invalid @enderror" name="perangkat" id="perangkat" value="{{old('perangkat',$perangkat->perangkat)}}" placeholder="Masukkan Nama Perangkat">

                @error('perangkat')
                    <div class="invalid-feedback">
                        Wajib Diisi
                    </div>
                @enderror
            </div>
            <div class="button-place mt-3">
                <button type="submit" class="btn btn-sm btn-warning text-warning-emphasis">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
