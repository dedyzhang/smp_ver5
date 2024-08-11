@extends('layouts.main')

@section('container')
    @if (\Request::route()->getName() === 'walikelas.ruang.edit')
        {{Breadcrumbs::render('walikelas-ruang-edit',$uuid,$barang)}}
    @else
        {{Breadcrumbs::render('barang-edit',$uuid,$barang)}}
    @endif

    <div class="body-contain-customize col-12">
        <h5><b>Edit Sarana dan Prasarana</b></h5>
        <p>Halaman ini untuk Mengedit data sarana dan prasarana dalam ruangan</p>
    </div>
    @if (session('success'))
    <div class="body-contain-customize mt-3 col-12">
        <div class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert">
            <i class="bi flex-shrink-0 me-2 fa-solid fa-check" aria-label="Success:"></i>
            <div>
                <strong>Sukses !</strong> {{session('success')}}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif
    <div class="body-contain-customize col-12 mt-3">
        <p>Form Edit Data Sarana dan Prasarana</p>
        <form action="{{route('barang.update',['uuid' => $uuid, 'uuidBarang' => $barang->uuid])}}" method="post">
            @csrf
            @method('put')
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-10 col-lg-8 col-xl-8">
                    <label for="barang">Nama Barang</label>
                    <input type="text" class="form-control @error('barang') is-invalid @enderror" id="barang" name="barang" value="{{old('barang',$barang->barang)}}" placeholder="Masukkan Nama Barang">
                    @error('barang')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-10 col-lg-8 col-xl-8">
                    <label for="merk">Merk Barang</label>
                    <input type="text" class="form-control @error('merk') is-invalid @enderror" id="merk" name="merk" value="{{old('merk',$barang->merk)}}" placeholder="Masukkan Merk dalam Barang">
                    @error('merk')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-10 col-lg-8 col-xl-8">
                    <label for="penyedia">Penyedia Barang</label>
                    <input type="text" class="form-control @error('penyedia') is-invalid @enderror" id="penyedia" name="penyedia" value="{{old('penyedia',$barang->penyedia)}}" placeholder="Masukkan Badan yang menyediakan Barang">
                    @error('penyedia')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                    <label for="tanggal">Tanggal Menyediakan</label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{old('tanggal',$barang->tanggal)}}"  placeholder="Masukkan Tanggal diadakannya Barang">
                    @error('tanggal')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                    <label for="deskripsi">Deskripsi Barang</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" placeholder="Masukkan Deskripsi dari Barang">{{old('deskripsi',$barang->deskripsi)}}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                    <label for="jumlah">Jumlah Barang</label>
                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{old('jumlah',$barang->jumlah)}}"  placeholder="Masukkan Jumlah Barang Didalam ruang">
                    @error('jumlah')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="button-place mt-3">
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </form>

    </div>
@endsection
