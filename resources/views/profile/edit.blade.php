@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Edit Profile</h5>
        <p>Halaman ini untuk mengedit data profile guru</p>
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
        <p>Form Edit Profile Guru</p>
        <form action="" method="post">
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{old('nama',$account->nama)}}" placeholder="Masukkan Nama Akun">
                </div>
            </div>
        </form>
    </div>
    <div class="col-12 col-sm-12 d-grid d-sm-grid d-md-block d-xl-block d-lg-block col-md-12 col-lg-12 col-xl-12 mt-3 body-contain-customize nostrip">
        <a href="" class="btn btn-sm btn-warning text-warning-emphasis">
            <i class="fa fa-save"></i> Edit Profile
        </a>
    </div>
@endsection
