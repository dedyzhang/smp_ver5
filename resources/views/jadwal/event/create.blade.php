@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Tambah Event Sekolah</b></h5>
        <p>Halaman ini diperuntukkan bagi warga sekolah dan berguna untuk melihat serta mengatur berbagai event di dalam sekolah, sehingga memudahkan pengelolaan kegiatan secara terorganisir.</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p>Form Penambahan Event Sekolah</p>
        <form method="post" action="{{route('event.store')}}">
            @csrf
            <div class="row m-0 p-0">
                <div class="col-12 col-sm-12 col-md-10 col-lg-8 col-xl-8 form-group">
                    <label for="judul">Judul Event</label>
                    <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror" placeholder="Masukkan Judul Event" value="{{old('judul')}}">
                    @error('judul')
                        <div class="invalid-feedback">Tidak Boleh Kosong</div>
                    @enderror
                </div>
                <div class="col-12 col-sm-12 col-md-10 col-lg-8 col-xl-8 form-group">
                    <label for="ruangan">Ruangan</label>
                    <select class="form-control @error('ruangan') is-invalid @enderror" name="ruangan[]" id="ruangan" data-toggle="select" multiple value="{{old('ruangan')}}">
                        @foreach ($ruang as $item)
                            <option value="{{$item->uuid}}">{{$item->nama}}</option>
                        @endforeach
                    </select>
                    @error('ruangan')
                        <div class="invalid-feedback">Tidak Boleh Kosong</div>
                    @enderror
                </div>
                <div class="col-12 col-sm-12 col-md-10 col-lg-8 col-xl-8 form-group">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="datetime-local" id="tanggal_mulai" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" placeholder="Masukkan Judul Event" value="{{old('tanggal_mulai')}}">
                    @error('tanggal_mulai')
                        <div class="invalid-feedback">Tidak Boleh Kosong</div>
                    @enderror
                </div>
                <div class="col-12 col-sm-12 col-md-10 col-lg-8 col-xl-8 form-group">
                    <label for="tanggal_akhir">Tanggal Akhir</label>
                    <input type="datetime-local" id="tanggal_akhir" name="tanggal_akhir" class="form-control @error('tanggal_akhir') is-invalid @enderror" placeholder="Masukkan Judul Event" value="{{old('tanggal_akhir')}}">
                    @error('tanggal_akhir')
                        <div class='invalid-feedback'>Tidak Boleh Kosong</div>
                    @enderror
                </div>
                <div class="col-12 mt-2">
                    <label for="deskripsi">Deskripsi Event</label>
                    <textarea name="deskripsi" class="tinymce-select" id="deskripsi"></textarea>
                </div>
                <div class="col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-block d-lg-block d-xl-block mt-3">
                    <button type="submit" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </div>
        </form>
    </div>
@endsection
