@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Tambah Pengajuan Poin</b></h5>
        <p>Halaman ini digunakan untuk menambahkan pengajuan poin Sementara</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p>Form Pengajuan Poin Sementara</p>
        <div class="row m-0 p-0">
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <label for="siswa">Siswa</label>
                <select name="siswa" id="siswa" class="form-group w-100" data-toggle="select">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($siswa as $item)
                        <option @if(old('siswa') == $item->uuid) selected @endif value="{{$item->uuid}}">{{$item->nama}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <label for="tanggal">Tanggal Pengajuan</label>
                <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{old('tanggal')}}">
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5 mt-2">
                <label for="jenis">Jenis Poin</label>
                <select class="form-control @error('jenis') is-invalid @enderror" data-toggle="select" name="jenis" id="jenis">
                    <option value="">Pilih Salah Satu</option>
                    <option value="kurang">Kurang</option>
                    <option value="tambah">Tambah</option>
                </select>
                @error('jenis')
                    <div class="invalid-feedback">
                        Wajib Diisi
                    </div>
                @enderror
            </div>
            <div class="form-group col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 mt-2">
                <label for="aturan">Aturan</label>
                <select class="form-control @error('aturan') is-invalid @enderror" data-toggle="select" name="aturan" id="aturan" value="{{old('aturan')}}">
                    <option value="">Pilih Salah Satu</option>
                </select>
                @error('aturan')
                    <div class="invalid-feedback">
                        Wajib Diisi
                    </div>
                @enderror
            </div>
        </div>
    </div>
    <script>
        $('select[name="jenis"]').change(function() {
            var jenis = $(this).val();
            $('#aturan').html('<option value="">Pilih Salah Satu</option>');
            loading();
            $.ajax({
                type: "get",
                url: "{{route('walikelas.poin.temp.getAturan')}}",
                data: {jenis: jenis},
                success: function(data) {
                    var aturan = data.aturan;
                    aturan.forEach(element => {
                        var newOption = new Option(element.kode+"-"+element.aturan,element.uuid,false,false);
                        $('#aturan').append(newOption).trigger('change');
                    });
                    removeLoading();
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        })
    </script>
@endsection
