@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('profile')}}
    <div class="col-12 body-contain-customize">
        <h5>Profile</h5>
        <p>Halaman Profile berisi Biodata Lengkap Pengguna</p>
    </div>
    @if ($user->access == "siswa" || $user->access == "orangtua")
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 mt-3">
            <div class="card rounded-4 border-0">
                <div class="card-body">
                    <div class="w-100 d-flex justify-content-center">
                        <img src="{{$account->jk == "l" ? asset('img/student-boy.png') : asset('img/student-girl.png')}}" width="100" class="rounded-5" alt="">
                    </div>
                    <h5 class=" m-0 mt-3 text-center"><b>{{$account->nama}}</b></h5>
                    <p class="m-0 text-dark-emphasis fs-12 text-center">{{$account->nis}}</p>
                    <p class="m-0 mt-3 fs-12 text-center">
                        <b class="{{$account->jk == "l" ? "text-primary" : "text-danger"}}">
                            @if ($account->jk == 'l')
                                <i class="fa fa-mars"></i> Laki-laki
                            @else
                                <i class="fa fa-venus"></i> Perempuan
                            @endif

                        </b>
                        <span class="ms-2">.</span>
                        <span class="ms-2"><b>Kelas {{$account->kelas ?$account->kelas->tingkat.$account->kelas->kelas : "Belum Ada Kelas"}}</b></span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-8 col-xl-9 mt-3">
            <div class="card rounded-4 border-0 fs-12">
                <div class="card-body">
                    <div class="row m-0 p-0">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            Tempat/Tanggal Lahir
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{$account->tempat_lahir." / ".date('d F Y',strtotime($account->tanggal_lahir))}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            Agama
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{ucfirst($account->agama)}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            Alamat
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{$account->alamat}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            No Telepon
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{$account->no_handphone}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card rounded-4 border-0 fs-12 mt-3">
                <div class="card-body">
                    <div class="row m-0 p-0">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            NISN
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{$account->nisn}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            Sekolah Asal
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{$account->sekolah_asal}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            Nama Ijazah
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{$account->nama_ijazah}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            Ortu Di Ijazah
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{$account->ortu_ijazah}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            TTL Ijazah
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{$account->tempat_lahir_ijazah." / ".date('d F Y',strtotime($account->tanggal_lahir_ijazah))}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mt-3">
            <div class="card rounded-4 border-0 fs-12">
                <div class="card-body">
                    <div class="row m-0 p-0">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-5 text-body-tertiary">
                            Nama Ayah
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-7">
                            {{$account->nama_ayah ? $account->nama_ayah : "-"}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-5 text-body-tertiary">
                            Pekerjaan Ayah
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-7">
                            {{$account->pekerjaan_ayah ? $account->pekerjaan_ayah : "-"}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-5 text-body-tertiary">
                            No Telp Ayah
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-7">
                            {{$account->no_telp_ayah ? $account->no_telp_ayah : "-" }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mt-3">
            <div class="card rounded-4 border-0 fs-12">
                <div class="card-body">
                    <div class="row m-0 p-0">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-5 text-body-tertiary">
                            Nama Ibu
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-7">
                            {{$account->nama_ibu ? $account->nama_ibu : "-"}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-5 text-body-tertiary">
                            Pekerjaan Ibu
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-7">
                            {{$account->pekerjaan_ibu ? $account->pekerjaan_ibu : "-"}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-5 text-body-tertiary">
                            No Telp Ibu
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-7">
                            {{$account->no_telp_ibu ? $account->no_telp_ibu : "-" }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mt-3">
            <div class="card rounded-4 border-0 fs-12">
                <div class="card-body">
                    <div class="row m-0 p-0">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-5 text-body-tertiary">
                            Nama Wali
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-7">
                            {{$account->nama_wali ? $account->nama_wali : "-"}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-5 text-body-tertiary">
                            Pekerjaan Wali
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-7">
                            {{$account->pekerjaan_wali ? $account->pekerjaan_wali : "-"}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-5 text-body-tertiary">
                            No Telp Wali
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-7">
                            {{$account->no_telp_wali ? $account->no_telp_wali : "-" }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 mt-3">
            <div class="card rounded-4 border-0">
                <div class="card-body">
                    <div class="w-100 d-flex justify-content-center">
                        <img src="{{$account->jk == "l" ? asset('img/teacher-boy.png') : asset('img/teacher-girl.png')}}" width="100" class="rounded-5" alt="">
                    </div>
                    <h5 class=" m-0 mt-3 text-center"><b>{{$account->nama}}</b></h5>
                    <p class="m-0 text-dark-emphasis fs-12 text-center">{{$account->nik}}</p>
                    <p class="m-0 mt-3 fs-12 text-center">
                        <b class="{{$account->jk == "l" ? "text-primary" : "text-danger"}}">
                            @if ($account->jk == 'l')
                                <i class="fa fa-mars"></i> Laki-laki
                            @else
                                <i class="fa fa-venus"></i> Perempuan
                            @endif

                        </b>
                        <span class="ms-2">.</span>
                        <span class="ms-2">{{$account->users->access}}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-8 col-xl-9 mt-3">
            <div class="card rounded-4 border-0 fs-12">
                <div class="card-body">
                    <div class="row m-0 p-0">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            Tempat/Tanggal Lahir
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{$account->tempat_lahir." / ".date('d F Y',strtotime($account->tanggal_lahir))}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            Agama
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{ucfirst($account->agama)}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            Alamat
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{$account->alamat}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            No Telepon
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{$account->no_telp}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card rounded-4 border-0 fs-12 mt-3">
                <div class="card-body">
                    <div class="row m-0 p-0">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            Tingkat Studi
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{$account->tingkat_studi}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            Program Studi
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{$account->program_studi}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            Satuan Pendidikan
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{$account->universitas}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            TMT di Yayaysan
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{date('d F Y',strtotime($account->tmt_ngajar))}}
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-3 text-body-tertiary">
                            TMT di Sekolah
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-9">
                            {{date('d F Y',strtotime($account->tmt_smp))}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 d-grid d-sm-grid d-md-block d-xl-block d-lg-block col-md-12 col-lg-12 col-xl-12 mt-3 body-contain-customize nostrip">
            <a href="{{route('profile.edit')}}" class="btn btn-sm btn-warning text-warning-emphasis">
                <i class="fa fa-save"></i> Edit Profile
            </a>
        </div>
    @endif

@endsection
