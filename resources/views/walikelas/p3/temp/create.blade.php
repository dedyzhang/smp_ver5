@extends('layouts.main')

@section('container')
    @if (\Request::route()->getName() === 'walikelas.p3.temp.create')
        {{ Breadcrumbs::render('walikelas-p3-temp-create') }}

    @elseif(\Request::route()->getName() === 'p3.guru.create')
        {{ Breadcrumbs::render('p3-guru-create') }}
    @else
        {{ Breadcrumbs::render('sekretaris-p3-create') }}
    @endif
    <div class="body-contain-customize col-12">
        <h5>Pelanggaran, Prestasi dan Partisipasi</h5>
        <p>Halaman ini diperuntukkan Walikelas untuk mengajukan Sementara Pelanggaran, Prestasi dan Partisipasi Siswa</p>
    </div>
    @if(session('success'))
        <div class="body-contain-customize mt-3 col-12">
            <div
                class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3"
                role="alert"
            >
                <i
                    class="bi flex-shrink-0 me-2 fa-solid fa-check"
                    aria-label="Success:"
                ></i>
                <div><strong>Sukses !</strong> {{ session("success") }}</div>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                ></button>
            </div>
        </div>
    @endif
    <div class="body-contain-customize col-12 mt-3">
        <p><b>Form Pengajuan sementara Poin Pelanggaran, Prestasi dan Partisipasi</b></p>
        <form method="post" action="{{ route('walikelas.p3.temp.store') }}">
            @csrf
            <div class="row m-0 p-0">
                <div class="m-0 p-0 mt-2 col-12 col-sm-12 col-md-10 col-lg-6 col-xl-6 form-group">
                    <label for="nama">Nama Siswa</label>
                    <select class="form-select @error('nama') is-invalid @enderror" name="nama" id="nama" data-toggle="select">
                        <option value="">Pilih Salah Satu</option>
                        @foreach ($siswa as $item)
                            <option value="{{ $item->uuid }}" @if(old('nama') == $item->uuid) selected @endif>{{ $item->nama }} ({{ $item->kelas ? $item->kelas->tingkat.$item->kelas->kelas : "-" }})</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Wajib Diisi</div>
                </div>
                <div class="clearfix"></div>
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
                    url: "{{ route('walikelas.p3.kategori.get') }}",
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
