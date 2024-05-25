@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('guru-create')}}
    <div class="body-contain-customize col-md-12 col-lg-12 col-sm-12">
        <p class="body-title">Form Penambahan Guru</p>
        {{-- <hr /> --}}
        <div class="row mt-3 m-0">
            <form action="{{route('guru.store')}}" method="post" class="p-0">
                @csrf
                <div class="form-group mb-3 col-12">
                    <label for="nama">Nama PTK</label>
                    <div class="input-group has-validation mt-1">
                        <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Guru" class="form-control @error('nama') is-invalid @enderror" value="{{old('nama')}}" />
                         @error('nama')
                            <div class="invalid-feedback">
                                Nama PTK Wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="nik">NIK (Nomor Induk Karyawan)</label>
                    <div class="input-group has-validation mt-1">
                        <input type="text" name="nik" id="nik" placeholder="Masukkan NIK Guru" class="form-control @error('nik') is-invalid @enderror" value="{{old('nik')}}" />
                        @error('nik')
                            <div class="invalid-feedback">
                                {{$message}}
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
                <div class="form-group mb-3 col-12">
                    <label for="role">Role</label>
                    <div class="input-group has-validation mt-1">
                        <select name="role" class="form-control @error('role') is-invalid @enderror" id="role">
                            <option value="">Pilihlah Salah Satu</option>
                            <option value="admin" {{ old('role') == 'admin' ? "selected" : "" }}>Admin</option>
                            <option value="kepala" {{ old('role') == 'kepala' ? "selected" : "" }}>Kepala Sekolah</option>
                            <option value="kurikulum" {{ old('role') == 'kurikulum' ? "selected" : "" }}>Kurikulum</option>
                            <option value="kesiswaan" {{ old('role') == 'kesiswaan' ? "selected" : "" }}>Kesiswaan</option>
                            <option value="sapras" {{ old('role') == 'sapras' ? "selected" : "" }}>Sapras</option>
                            <option value="guru" {{ old('role') == 'guru' ? "selected" : "" }}>Guru</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">
                                Akses Wajib Diisi
                            </div>
                        @enderror
                    </div>

                </div>
                <div class="col-12 button-place mt-3">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Guru
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
