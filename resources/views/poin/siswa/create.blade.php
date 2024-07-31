@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('poin-create',$siswa)}}
    <div class="body-contain-customize col-12">
        <h5><b>Tambah Poin</b></h5>
        <p>Halaman ini diperuntukkutean untuk menambahkan poin siswa pada kesiswaan maupun admin</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <form action="{{route('poin.store',$uuid)}}" method="post">
            @csrf
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{old('tanggal')}}">
                    @error('tanggal')
                        <div class="invalid-feedback">
                            Wajib Diisi
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5 mt-2">
                    <label for="jenis">Jenis Poin</label>
                    <select class="form-control @error('jenis') is-invalid @enderror" data-toggle="select" name="jenis" id="jenis" value="{{old('jenis')}}">
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
                <div class="button-place col-12 mt-4">
                    <button class="btn btn-sm btn-warning text-warning-emphasis">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
    <script>
        $('select[name="jenis"]').change(function() {
            var jenis = $(this).val();
            $('#aturan').html('<option value="">Pilih Salah Satu</option>');
            loading();
            $.ajax({
                type: "get",
                url: "{{route('poin.getAturan')}}",
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
