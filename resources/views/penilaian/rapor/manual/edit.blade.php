@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Rapor Manual</b></h5>
        <p>Halaman ini diperuntukkan admin untuk menginput nilai siswa diluar dari aplikasi ( seperti nilai pendidikan Agama dan Budi Pekerti )</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <form action="{{route('penilaian.admin.manual.update',$manual->uuid)}}" method="post">
            @csrf
            @method('put')
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                    <label for="siswa">Siswa</label>
                    <input type="text" readonly class="form-control @error('siswa') is-invalid @enderror" name="siswa" id="siswa" value="{{old('siswa',$manual->siswa->nama)}}" />
                    <div class="invalid-feedback">Tidak Boleh Kosong</div>
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                    <label for="nilai">Nilai</label>
                    <input type="number" class="form-control @error('nilai') is-invalid @enderror" name="nilai" id="nilai" value="{{old('nilai',$manual->nilai)}}" />
                    <div class="invalid-feedback">Tidak Boleh Kosong</div>
                </div>
                <div class="form-group col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 mt-2">
                    <label for="positif">Capaian Kompetensi Tertinggi</label>
                    <textarea name="positif" id="positif" class="form-control @error('positif') is-invalid @enderror">{{old('positif',$manual->deskripsi_positif)}}</textarea>
                    <div class="invalid-feedback">Tidak Boleh Kosong</div>
                </div>
                <div class="form-group col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 mt-2">
                    <label for="negatif">Capaian Kompetensi Terendah</label>
                    <textarea name="negatif" id="negatif" class="form-control @error('negatif') is-invalid @enderror">{{old('negatif',$manual->deskripsi_negatif)}}</textarea>
                    <div class="invalid-feedback">Tidak Boleh Kosong</div>
                </div>
                <div class="button-place mt-3 col-12 d-grid col-sm-12 d-sm-grid col-md-12 d-md-block col-lg-12 d-lg-block col-xl-12 d-xl-block">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-save"></i> Simpan Nilai
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
