@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Pelanggaran, Prestasi dan Partisipasi</h5>
        <p>Halaman ini diperuntukkan admin dan Wakil Kesiswaan Mengatur Pelanggaran, Prestasi dan Partisipasi Siswa</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-5 col-xl-5">
        <table class="table table-striped fs-12">
            <tr>
                <td width="35%">Nama</td>
                <td width="3%">:</td>
                <td width="62%">{{$siswa->nama}}</td>
            </tr>
            <tr>
                <td width="35%">NIS</td>
                <td width="3%">:</td>
                <td width="62%">{{$siswa->nis}}</td>
            </tr>
            <tr>
                <td width="35%">Kelas</td>
                <td width="3%">:</td>
                <td width="62%">{{$siswa->kelas ? $siswa->kelas->tingkat.$siswa->kelas->kelas : "-"}}</td>
            </tr>
        </table>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>Form Penambahan Poin Pelanggaran, Prestasi dan Partisipasi</b></p>
        <form method="post" action="{{ route('p3.siswa.store',$siswa->uuid) }}">
            @csrf
            <div class="row m-0 p-0">
                <div class="m-0 p-0 mt-2 col-12 col-sm-12 col-md-10 col-lg-4 col-xl-4 form-group">
                    <label for="tanggal">Tanggal Penambahan P3</label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" id="tanggal" placeholder="Tanggal Penambahan P3" value="{{ old('tanggal') }}" />
                    @error('tanggal')
                        <div class="invalid-feedback">Wajib Diisi</div>
                    @enderror
                </div>
                <div class="clearfix"></div>
                <div class="m-0 p-0 mt-2 col-12 col-sm-12 col-md-10 col-lg-6 col-xl-6 form-group">
                    <label for="jenis">Jenis Penambahan P3</label>
                    <select class="form-select @error('jenis') is-invalid @enderror" name="jenis" id="jenis" placeholder="Jenis Penambahan p3" >
                        <option value="">Pilih Salah Satu</option>
                        <option value="pelanggaran" @if(old('jenis') == "pelanggaran") selected @endif >Pelanggaran</option>
                        <option value="prestasi" @if(old('jenis') == "prestasi") selected @endif >Prestasi</option>
                        <option value="partisipasi" @if(old('jenis') == "partisipasi") selected @endif >Partisipasi</option>
                    </select>
                    @error('jenis')
                        <div class="invalid-feedback">Wajib Diisi</div>
                    @enderror
                </div>
                <div class="m-0 p-0 mt-2 col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 form-group">
                    <label for="kategori">Kategori Yang Bisa Dipilih <i class="text-danger" style="font-size:10px">*Dapat Dipilih Setelah Jenis P3 Dipilih</i></label>
                    <select class="form-control" name="kategori" id="kategori" disabled >

                    </select>
                </div>
                <div class="m-0 p-0 mt-2 col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 form-group">
                    <label for="deskripsi">Deskripsi Poin P3</label>
                    <p class="text-danger m-0 p-0 fs-10">Deskripsi Bisa mengikuti Bahasa di Kategori Maupun di tambah ataupun diedit</p>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskripsi" placeholder="Deskripsi Poin P3">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">Wajib Diisi</div>
                    @enderror
                </div>
                <div class="button-place m-0 p-0 mt-3">
                    <button type="submit" class="btn btn-warning text-warning-emphasis btn-sm">
                        <i class="fas fa-plus"></i> Tambah Poin P3
                    </button>
                </div>
            </div>
        </form>
    </div>
    <script>
        $('#jenis').change(function() {
            loading();
            var jenis = $(this).val();

            if(jenis == "") {
                removeLoading();
            } else {
                $.ajax({
                    type: "GET",
                    url: "{{ route('p3.kategori.get') }}",
                    data: {jenis: jenis},
                    success: function(data) {
                        if(data.message == "success") {
                            var options = "";
                            options += "<option>Pilih Salah Satu</option>"
                            data.kategori.forEach(function(elem) {
                                options += "<option value='"+elem.id+"'>"+elem.deskripsi+"</option>"
                            });

                            $('#kategori').html(options);
                            $('#kategori').attr('disabled',false);
                        }
                        removeLoading();
                    },error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }
        });
        $('#kategori').change(function() {
            var kategori = $(this).find('option:selected').text();
            if(kategori != "" && kategori != "Pilih Salah Satu") {
                $('#deskripsi').val(kategori);
            }
        })
    </script>
@endsection
