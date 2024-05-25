@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('siswa-edit',$siswa)}}
    <div class="body-contain-customize col-md-12 col-lg-12 col-sm-12">
        <p class="body-title">Form Edit Data Siswa</p>
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
            <form action="{{route('siswa.update',$siswa->uuid)}}" method="post" class="p-0 pt-2">
                @csrf
                @method('PUT')
                <p class="fs-12"><b>A. Identitas Pribadi</b></p>
                <div class="form-group mb-3 col-12">
                    <label for="nama">Nama Siswa</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="nama" id="nama" placeholder="Masukkan nama siswa" class="form-control @error('nama') is-invalid @enderror" value="{{old('nama',$siswa->nama)}}" />
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
                        <input type="radio" class="form-check-input @error('jk') is-invalid @enderror" name="jk" id="jk-laki" value="l" @if (old('jk') == 'l' || $siswa->jk == 'l') checked @endif>
                        <label class="form-check-label" for="jk-laki">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline mt-1">
                        <input type="radio" class="form-check-input @error('jk') is-invalid @enderror" name="jk" id="jk-pere" value="p" @if (old('jk') == 'p' || $siswa->jk == 'p') checked @endif>
                        <label class="form-check-label" for="jk-pere">Perempuan</label>
                    </div>
                    @error('jk')
                        <div class="invalid-feedback d-block">
                            Jenis Kelamin Wajib Diisi
                        </div>
                    @enderror
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="tempat_lahir">Tempat Lahir</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="tempat_lahir" id="tempat_lahir" placeholder="Masukkan tempat lahir siswa" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{old('tempat_lahir',$siswa->tempat_lahir)}}" />
                            @error('tempat_lahir')
                            <div class="invalid-feedback">
                                tempat lahir siswa wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" placeholder="Masukkan tanggal lahir siswa" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{old('tanggal_lahir',$siswa->tanggal_lahir)}}" />
                            @error('tanggal_lahir')
                            <div class="invalid-feedback">
                                Tanggal lahir siswa wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="agama">Agama</label>
                    <div class="input-group has-validation mt-1">
                        <select name="agama" class="form-control @error('agama') is-invalid @enderror" id="agama">
                            <option value="">Pilihlah Salah Satu</option>
                            <option value="islam" {{ old('agama') == 'islam' || $siswa->agama == 'islam' ? "selected" : "" }}>Islam</option>
                            <option value="buddha" {{ old('agama') == 'buddha' || $siswa->agama == 'buddha' ? "selected" : "" }}>Buddha</option>
                            <option value="protestan" {{ old('agama') == 'protestan' || $siswa->agama == 'protestan' ? "selected" : "" }}>Protestan</option>
                            <option value="katolik" {{ old('agama') == 'katolik' || $siswa->agama == 'katolik' ? "selected" : "" }}>Katolik</option>
                            <option value="hindu" {{ old('agama') == 'hindu' || $siswa->agama == 'hindu' ? "selected" : "" }}>Hindu</option>
                            <option value="konghucu" {{ old('agama') == 'konghucu' || $siswa->agama == 'konghucu' ? "selected" : "" }}>Konghucu</option>
                        </select>
                        @error('agama')
                            <div class="invalid-feedback">
                                Agama Wajib Diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="alamat">Alamat</label>
                    <div class="input-group has-validation mt-1 ">
                        <textarea name="alamat" id="alamat" rows="3" placeholder="Masukkan Alamat siswa" class="form-control @error('alamat') is-invalid @enderror">{{old('alamat',$siswa->alamat)}}</textarea>
                            @error('alamat')
                            <div class="invalid-feedback">
                                Alamat siswa wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="no_handphone">No Handphone</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="no_handphone" id="no_handphone" placeholder="Masukkan No handphone siswa" class="no-handphone-formatter form-control @error('no_handphone') is-invalid @enderror" value="{{old('no_handphone',$siswa->no_handphone)}}" />
                            @error('no_handphone')
                            <div class="invalid-feedback">
                                No Handphone siswa wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <p class="fs-12"><b>B. Identitas Ayah</b></p>
                <div class="form-group mb-3 col-12">
                    <label for="nama_ayah">Nama Ayah</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="nama_ayah" id="nama_ayah" placeholder="Masukkan nama ayah" class="form-control @error('nama_ayah') is-invalid @enderror" value="{{old('nama_ayah',$siswa->nama_ayah)}}" />
                            @error('nama_ayah')
                            <div class="invalid-feedback">
                                Nama ayah wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="pekerjaan_ayah">Pekerjaan Ayah</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" placeholder="Masukkan pekerjaan ayah" class="form-control @error('pekerjaan_ayah') is-invalid @enderror" value="{{old('pekerjaan_ayah',$siswa->pekerjaan_ayah)}}" />
                            @error('pekerjaan_ayah')
                            <div class="invalid-feedback">
                                Pekerjaan ayah wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="no_telp_ayah">No Handphone Ayah</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="no_telp_ayah" id="no_telp_ayah" placeholder="Masukkan No handphone ayah" class="no-handphone-formatter form-control @error('no_telp_ayah') is-invalid @enderror" value="{{old('no_telp_ayah',$siswa->no_telp_ayah)}}" />
                            @error('no_telp_ayah')
                            <div class="invalid-feedback">
                                No Telepon ayah wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <p class="fs-12"><b>C. Identitas Ibu</b></p>
                <div class="form-group mb-3 col-12">
                    <label for="nama_ibu">Nama Ibu</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="nama_ibu" id="nama_ibu" placeholder="Masukkan nama ibu" class="form-control @error('nama_ibu') is-invalid @enderror" value="{{old('nama_ibu',$siswa->nama_ibu)}}" />
                            @error('nama_ibu')
                            <div class="invalid-feedback">
                                Nama ibu wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="pekerjaan_ibu">Pekerjaan Ibu</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" placeholder="Masukkan pekerjaan ibu" class="form-control @error('pekerjaan_ibu') is-invalid @enderror" value="{{old('pekerjaan_ibu',$siswa->pekerjaan_ibu)}}" />
                            @error('pekerjaan_ibu')
                            <div class="invalid-feedback">
                                Pekerjaan ibu wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="no_telp_ibu">No Handphone Ibu</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="no_telp_ibu" id="no_telp_ibu" placeholder="Masukkan No handphone ibu" class="no-handphone-formatter form-control @error('no_telp_ibu') is-invalid @enderror" value="{{old('no_telp_ibu',$siswa->no_telp_ibu)}}" />
                            @error('no_telp_ibu')
                            <div class="invalid-feedback">
                                No Telepon ibu wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <p class="fs-12"><b>D. Identitas Wali</b></p>
                <div class="form-group mb-3 col-12">
                    <label for="nama_wali">Nama Wali</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="nama_wali" id="nama_wali" placeholder="Masukkan nama wali" class="form-control @error('nama_wali') is-invalid @enderror" value="{{old('nama_wali',$siswa->nama_wali)}}" />
                            @error('nama_wali')
                            <div class="invalid-feedback">
                                Nama wali wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="pekerjaan_wali">Pekerjaan Wali</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="pekerjaan_wali" id="pekerjaan_wali" placeholder="Masukkan pekerjaan wali" class="form-control @error('pekerjaan_wali') is-invalid @enderror" value="{{old('pekerjaan_wali',$siswa->pekerjaan_wali)}}" />
                            @error('pekerjaan_wali')
                            <div class="invalid-feedback">
                                Pekerjaan wali wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="no_telp_wali">No Handphone Wali</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="no_telp_wali" id="no_telp_wali" placeholder="Masukkan No handphone wali" class="no-handphone-formatter form-control @error('no_telp_wali') is-invalid @enderror" value="{{old('no_telp_wali',$siswa->no_telp_wali)}}" />
                            @error('no_telp_wali')
                            <div class="invalid-feedback">
                                No Telepon wali wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <p class="fs-12"><b>E. Identitas Sekolah</b></p>
                <div class="form-group mb-3 col-12">
                    <label for="sekolah_asal">Asal Sekolah</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="sekolah_asal" id="sekolah_asal" placeholder="Masukkan asal sekolah siswa" class="form-control @error('sekolah_asal') is-invalid @enderror" value="{{old('sekolah_asal',$siswa->sekolah_asal)}}" />
                            @error('sekolah_asal')
                            <div class="invalid-feedback">
                                Asal sekolah siswa wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="nisn">NISN</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="nisn" id="nisn" placeholder="Masukkan NISN siswa" class="form-control @error('nisn') is-invalid @enderror" value="{{old('nisn',$siswa->nisn)}}" />
                            @error('nisn')
                            <div class="invalid-feedback">
                                NISN siswa wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="nama_ijazah">Nama Siswa di Ijazah</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="nama_ijazah" id="nama_ijazah" placeholder="Masukkan siswa di ijazah" class="form-control @error('nama_ijazah') is-invalid @enderror" value="{{old('nama_ijazah',$siswa->nama_ijazah)}}" />
                            @error('nama_ijazah')
                            <div class="invalid-feedback">
                                Nama Siswa di Ijazah Wajib Diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="ortu_ijazah">Nama Orang Tua di Ijazah</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="ortu_ijazah" id="ortu_ijazah" placeholder="Masukkan Nama Orang tua di ijazah" class="form-control @error('ortu_ijazah') is-invalid @enderror" value="{{old('ortu_ijazah',$siswa->ortu_ijazah)}}" />
                            @error('ortu_ijazah')
                            <div class="invalid-feedback">
                                Nama Orang Tua di Ijazah Wajib Diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="tempat_lahir_ijazah">Tempat Lahir di Ijazah</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="text" name="tempat_lahir_ijazah" id="tempat_lahir_ijazah" placeholder="Masukkan tempat lahir di ijazah siswa" class="form-control @error('tempat_lahir_ijazah') is-invalid @enderror" value="{{old('tempat_lahir_ijazah',$siswa->tempat_lahir_ijazah)}}" />
                            @error('tempat_lahir_ijazah')
                            <div class="invalid-feedback">
                                tempat lahir siswa di ijazah wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3 col-12">
                    <label for="tanggal_lahir_ijazah">Tanggal Lahir di Ijazah</label>
                    <div class="input-group has-validation mt-1 ">
                        <input type="date" name="tanggal_lahir_ijazah" id="tanggal_lahir_ijazah" placeholder="Masukkan tanggal lahir di ijazah siswa" class="form-control @error('tanggal_lahir_ijazah') is-invalid @enderror" value="{{old('tanggal_lahir_ijazah',$siswa->tanggal_lahir_ijazah)}}" />
                            @error('tanggal_lahir_ijazah')
                            <div class="invalid-feedback">
                                Tanggal lahir siswa di ijazah wajib diisi
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-12 button-place mt-4">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        Edit Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
