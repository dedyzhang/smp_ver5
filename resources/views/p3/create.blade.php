@extends('layouts.main')

@section('container')
    {{ Breadcrumbs::render('p3-create'); }}
    <div class="col-12 body-contain-customize">
        <h5>Pelanggaran, Prestasi dan Partisipasi</h5>
        <p>Halaman ini diperuntukkan admin dan Wakil Kesiswaan untuk menginput Pelanggaran, Prestasi dan Partisipasi yang akan dipilih oleh guru bersangkutan</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert">
            <i class="bi flex-shrink-0 me-2 fa-solid fa-check" aria-label="Success:"></i>
            <div>
                <strong>Sukses !</strong> {{session('success')}}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="col-12 body-contain-customize mt-3">
        <p class="fs-12"><b>Form Penambahan P3 ( Pelanggaran, Prestasi dan Partisipasi )</b></p>
        <div class="row m-0 p-0">
            <form action="{{route('p3.store')}}" method="post">
                @csrf
                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 form-group m-0 p-0">
                    <label for="jenis">Jenis</label>
                    <select name="jenis" id="jenis" class="form-select @error('jenis') is-invalid @enderror">
                        <option value="">Pilih Salah Satu</option>
                        <option {{old('jenis') == "pelanggaran" ? "selected" : "" }} value="pelanggaran">Pelanggaran</option>
                        <option {{old('jenis') == "prestasi" ? "selected" : "" }} value="prestasi">Prestasi</option>
                        <option {{old('jenis') == "partisipasi" ? "selected" : "" }} value="partisipasi">Partisipasi</option>
                    </select>
                    @error('jenis')
                        <div class="invalid-feedback">
                            Jenis Wajib Diisi
                        </div>
                    @enderror
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 form-group m-0 p-0">
                    <label for="deskripsi">Deskripsi</label>
                    <input type="text" name="deskripsi" id="deskripsi" placeholder="Masukkan Deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" value="{{old('deskripsi')}}" />
                    @error('deskripsi')
                        <div class="invalid-feedback">
                            Deskripsi Wajib Diisi
                        </div>
                    @enderror
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 form-group m-0 p-0">
                    <label for="poin">Poin</label>
                    <input type="number" name="poin" id="poin" placeholder="Masukkan Poin" class="form-control @error('poin') is-invalid @enderror" value="{{old('poin')}}" />
                    @error('poin')
                        <div class="invalid-feedback">
                            Poin Wajib Diisi
                        </div>
                    @enderror
                </div>
                <div class="col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex mt-3">
                    <button type="submit" class="btn btn-sm btn-warning text-warning-emphasis">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
