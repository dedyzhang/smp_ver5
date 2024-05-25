@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('guru-edit',$guru)}}
    <div class="body-contain-customize col-md-12 col-lg-12 col-sm-12">
        <p class="body-title">Form Penambahan Guru</p>
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
            <form action="{{route('guru.update',$guru->uuid)}}" method="POST" enctype="multipart/form-data" class="p-0">
                @csrf
                @method('PUT')
                <p><strong><i>A. Identitas Pribadi</i></strong></p>
                <div class="form-group mb-3 col-12">
                    <label for="nama">Nama PTK</label>
                    <div class="input-group has-validation mt-1">
                        <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Guru" class="form-control @error('nama') is-invalid @enderror" value="{{old('nama',$guru->nama)}}" />
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
                        <input type="text" name="nik" id="nik" placeholder="Masukkan NIK Guru" class="form-control @error('nik') is-invalid @enderror" value="{{old('nik',$guru->nik)}}" />
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
                        <input type="radio" class="form-check-input @error('jk') is-invalid @enderror" name="jk" id="jk-laki" value="l" @if (old('jk') == 'l' || $guru->jk == 'l') checked @endif>
                        <label class="form-check-label" for="jk-laki">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline mt-1">
                        <input type="radio" class="form-check-input @error('jk') is-invalid @enderror" name="jk" id="jk-pere" value="p" @if (old('jk') == 'p' || $guru->jk == 'p') checked @endif>
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
                            <option value="admin" {{ old('role') == 'admin' || $guru->users->access == 'admin' ? "selected" : "" }}>Admin</option>
                            <option value="kepala" {{ old('role') == 'kepala' || $guru->users->access == 'kepala' ? "selected" : "" }}>Kepala Sekolah</option>
                            <option value="kurikulum" {{ old('role') == 'kurikulum' || $guru->users->access == 'kurikulum' ? "selected" : "" }}>Kurikulum</option>
                            <option value="kesiswaan" {{ old('role') == 'kesiswaan' || $guru->users->access == 'kesiswaan' ? "selected" : "" }}>Kesiswaan</option>
                            <option value="sapras" {{ old('role') == 'sapras' || $guru->users->access == 'sapras' ? "selected" : "" }}>Sapras</option>
                            <option value="guru" {{ old('role') == 'guru' || $guru->users->access == 'guru' ? "selected" : "" }}>Guru</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">
                                Akses Wajib Diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="agama">Agama</label>
                    <div class="input-group has-validation mt-1">
                        <select name="agama" class="form-control @error('agama') is-invalid @enderror" id="agama">
                            <option value="">Pilihlah Salah Satu</option>
                            <option value="islam" {{ old('agama') == 'islam' || $guru->agama == 'islam' ? "selected" : "" }}>Islam</option>
                            <option value="buddha" {{ old('agama') == 'buddha' || $guru->agama == 'buddha' ? "selected" : "" }}>Buddha</option>
                            <option value="protestan" {{ old('agama') == 'protestan' || $guru->agama == 'protestan' ? "selected" : "" }}>Protestan</option>
                            <option value="katolik" {{ old('agama') == 'katolik' || $guru->agama == 'katolik' ? "selected" : "" }}>Katolik</option>
                            <option value="hindu" {{ old('agama') == 'hindu' || $guru->agama == 'hindu' ? "selected" : "" }}>Hindu</option>
                            <option value="konghucu" {{ old('agama') == 'konghucu' || $guru->agama == 'konghucu' ? "selected" : "" }}>Konghucu</option>
                        </select>
                        @error('agama')
                            <div class="invalid-feedback">
                                Agama Wajib Diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="tempat_lahir">Tempat Lahir</label>
                    <div class="input-group mt-1">
                        <input type="text" name="tempat_lahir" id="tempat_lahir" placeholder="Masukkan Tempat Lahir Guru" class="form-control" value="{{old('tempat_lahir',$guru->tempat_lahir)}}" />
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <div class="input-group mt-1">
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{old('tanggal_lahir',$guru->tanggal_lahir)}}" />
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="no_telp">No Telepon</label>
                    <div class="input-group mt-1">
                        <input type="text" name="no_telp" id="no_telp" placeholder="Masukkan No Telepon Guru" class="form-control" value="{{old('no_telp',$guru->no_telp)}}" />
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="no_telp">Alamat</label>
                    <div class="input-group mt-1">
                        <textarea name="alamat" rows="3" id="alamat" placeholder="Masukkan Alamat Anda" class="form-control">{{old('alamat',$guru->alamat)}}</textarea>
                    </div>
                </div>
                <div class="col-12 mt-5">
                    <p><strong><i>B. Identitas Pendidikan</i></strong></p>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="tingkat_studi">Jenjang Terakhir</label>
                    <div class="input-group has-validation mt-1">
                        <select name="tingkat_studi" class="form-control @error('tingkat_studi') is-invalid @enderror" id="tingkat_studi">
                            <option value="">Pilihlah Salah Satu</option>
                            <option value="SD" {{ old('tingkat_studi') == 'SD' || $guru->tingkat_studi == 'SD' ? "selected" : "" }}>SD</option>
                            <option value="SMP" {{ old('tingkat_studi') == 'SMP' || $guru->tingkat_studi == 'SMP' ? "selected" : "" }}>SMP</option>
                            <option value="SMA/K" {{ old('tingkat_studi') == 'SMA/K' || $guru->tingkat_studi == 'SMA/K' ? "selected" : "" }}>SMA/K</option>
                            <option value="S1" {{ old('tingkat_studi') == 'S1' || $guru->tingkat_studi == 'S1' ? "selected" : "" }}>S1</option>
                        </select>
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="program_studi">Program Studi</label>
                    <div class="input-group mt-1">
                        <input type="text" name="program_studi" id="program_studi" class="form-control" value="{{old('program_studi',$guru->program_studi)}}" placeholder="Masukkan Program Studi" />
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="universitas">Universitas</label>
                    <div class="input-group mt-1">
                        <input type="text" name="universitas" id="universitas" class="form-control" value="{{old('universitas',$guru->universitas)}}" placeholder="Masukkan Universitas" />
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="tahun_tamat">Tahun Tamat</label>
                    <div class="input-group mt-1">
                        <input type="text" name="tahun_tamat" id="tahun_tamat" class="form-control" value="{{old('tahun_tamat',$guru->tahun_tamat)}}" placeholder="Masukkan Tahun Tamat" />
                    </div>
                </div>
                <div class="col-12 mt-5">
                    <p><strong><i>C. Identitas Sekolah</i></strong></p>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="tmt_ngajar">TMT Mengajar</label>
                    <div class="input-group mt-1">
                        <input type="date" name="tmt_ngajar" id="tmt_ngajar" class="form-control" value="{{old('tmt_ngajar',$guru->tmt_ngajar)}}" />
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="tmt_smp">TMT SMP</label>
                    <div class="input-group mt-1">
                        <input type="date" name="tmt_smp" id="tmt_smp" class="form-control" value="{{old('tmt_smp',$guru->tmt_smp)}}" />
                    </div>
                </div>
                <div class="col-12 button-place mt-3">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-pencil"></i>
                        edit Guru
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
