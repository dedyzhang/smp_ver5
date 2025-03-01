@extends('layouts.main')

@section('container')
    @if (\Request::route()->getName() === 'walikelas.poin.')
        {{Breadcrumbs::render('walikelas-poin-temp-create')}}
    @elseif(\Request::route()->getName() === 'poin.guru.create')
        {{Breadcrumbs::render('poin-guru-tambah')}}
    @else
        {{Breadcrumbs::render('sekretaris-poin-create')}}
    @endif
    <div class="body-contain-customize col-12">
        <h5><b>Tambah Pengajuan Poin</b></h5>
        <p>Halaman ini digunakan untuk menambahkan pengajuan poin Sementara</p>
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
        <p>Form Pengajuan Poin Sementara</p>
        <form action="{{route('walikelas.poin.temp.create')}}" method="post">
            @csrf
            <div class="row m-0 p-0">
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="siswa">Siswa</label>
                    <select name="siswa" id="siswa" class="form-group w-100 @error('siswa') is-invalid @enderror" data-toggle="select">
                        <option value="">Pilih Salah Satu</option>
                        @foreach ($siswa as $item)
                            <option @if(old('siswa') == $item->uuid) selected @endif value="{{$item->uuid}}">{{$item->nama}} - {{$item->nis}} @if(isset($item->kelas)) ({{$item->kelas->tingkat.$item->kelas->kelas}}) @else - @endif</option>
                        @endforeach
                    </select>
                    @error('siswa')
                        <div class="invalid-feedback">
                            Wajib Diisi
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <label for="tanggal">Tanggal Pengajuan</label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" id="tanggal" value="{{old('tanggal')}}">
                    @error('tanggal')
                        <div class="invalid-feedback">
                            Wajib Diisi
                        </div>
                    @enderror
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
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-sm btn-warning text-warning-emphasis">
                        <i class="fa fa-save"></i> Simpan
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
