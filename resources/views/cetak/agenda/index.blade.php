@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Cetak Agenda Guru</b></h5>
        <p>Halaman Admin untuk mencetak Agenda Guru</p>
    </div>
    <div class="col-12 body-contain-customize mt-3">
        <div class="row m-0 p-0">
            <div class="col-12">
                <p>Download Agenda Guru berdasarkan Kelas</p>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group">
                <label for="select-agenda-bulan" class="form-label">Pilih Bulan</label>
                <select class="form-select" id="select-agenda-bulan" data-toggle="select">
                    <option value="" selected disabled>Pilih Salah Satu</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group">
                <label for="select-agenda-kelas" class="form-label">Pilih Kelas</label>
                <select class="form-select" id="select-agenda-kelas" data-toggle="select">
                    <option value="" selected disabled>Pilih Salah Satu</option>
                    @foreach ($kelas as $item)
                        <option value="{{ $item->uuid }}">{{$item->tingkat.$item->kelas}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group mt-3">
                <button class="btn btn-primary btn-sm" id="btn-download-agenda-kelas">Download Agenda Kelas</button>
            </div>
        </div>
    </div>
    <div class="col-12 body-contain-customize mt-3">
        <div class="row m-0 p-0">
            <div class="col-12">
                <p>Download Agenda Guru berdasarkan Guru</p>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group">
                <label for="select-agenda-bulan" class="form-label">Pilih Bulan</label>
                <select class="form-select" id="select-agenda-bulan" data-toggle="select">
                    <option value="" selected disabled>Pilih Salah Satu</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group">
                <label for="select-agenda-kelas" class="form-label">Pilih Guru</label>
                <select class="form-select" id="select-agenda-kelas" data-toggle="select">
                    <option value="" selected disabled>Pilih Salah Satu</option>
                    @foreach ($guru as $item)
                        <option value="{{ $item->uuid }}">{{$item->nama}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group mt-3">
                <button class="btn btn-primary btn-sm" id="btn-download-agenda-guru">Download Agenda Guru</button>
            </div>
        </div>
    </div>
@endsection