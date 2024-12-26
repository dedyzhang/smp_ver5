@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Pengaturan Dimensi</h5>
        <p>Halaman untuk mengatur dimensi, elemen, subelemen dan pencapaian yang digunakan dalam proyek ini</p>
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5 mt-3">
        <p><b>Data Proyek</b></p>
        <table class="table table-striped fs-13">
            <tr>
                <td width="30%">Judul Proyek</td>
                <td width="5%">:</td>
                <td>{{$proyek->judul}}</td>
            </tr>
            <tr>
                <td>Untuk Tingkat</td>
                <td>:</td>
                <td>{{$proyek->tingkat}}</td>
            </tr>
            <tr>
                <td>Deskripsi</td>
                <td>:</td>
                <td class="fs-11">{{$proyek->deskripsi}}</td>
            </tr>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="body-contain-customize mt-3 col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex">
        <button class="btn btn-sm btn-warning text-warning-emphasis" data-bs-toggle="modal" data-bs-target="#modal-tambah">
            <i class="fas fa-plus"></i> Tambah Dimensi
        </button>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <td width="5%">No</td>
                    <td width="15%">Dimensi</td>
                    <td width="15%">Elemen</td>
                    <td width="25%" style="min-width:150px">Sub Elemen</td>
                    <td width="35%" style="min-width:200px">Pencapaian</td>
                    <td width="5%" style="min-width:70px">#</td>
                </tr>
                </thead>
                <tbody>
                    <tr>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade in" id="modal-tambah">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Tambah Dimensi di Proyek</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-0 p-0">
                        <div class="col-12 m-0 p-0 form-group">
                            <label for="dimensi">Dimensi</label>
                            <select class="form-select" id="dimensi">
                                <option value="1">Dimensi 1</option>
                                <option value="2">Dimensi 2</option>
                                <option value="3">Dimensi 3</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
