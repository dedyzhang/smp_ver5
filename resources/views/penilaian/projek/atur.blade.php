@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Projek P5</h5>
        <p>Halaman ini berguna untuk Pengelolaan Projek Penguatan Pancasila berfungsi sebagai pusat informasi, komunikasi, dan kolaborasi yang komprehensif.</p>
    </div>
    <div class="row m-0 p-0">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 m-0 ps-0 pt-2 pe-2">
            <div class="card border-0 rounded-3">
                <div class="card-header bg-warning-subtle text-warning-emphasis">
                    <p class="m-0 p-0"><b>Pengaturan Dimensi</b></p>
                    <p class="fs-11 lh-sm m-0 p-0">Mengatur Dimensi terlebih dahulu sebelum membuat elemen. Pengaturan dimensi sesuai dengan ketentuan dari Kurikulum Merdeka.</p>
                </div>
                <div class="card-body">
                    <div class="row m-0 p-0">
                        <div class="col-12 form-group p-0">
                            <label for="dimensi" class="fs-12">Dimensi</label>
                            <input type="text" name="dimensi" id="dimensi" class="form-control">
                            <div class="invalid-feedback">Tidak Boleh Kosong</div>
                        </div>
                        <div class="col-12 mt-2 p-0">
                            <button class="btn btn-sm btn-warning text-warning-emphasis simpan-dimensi">
                                <i class="fas fa-save"></i> Simpan dimensi
                            </button>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <p class="m-0 p-0 fs-12"><b>List Dimensi</b></p>
                        <hr />
                        <ol class="list-group list-group-numbered">
                            @foreach ($dimensi as $item)
                                <li class="fs-12 list-group-item list-group-item-primary d-flex justify-content-between align-items-center">
                                    <div class="ms-2 me-auto">
                                    {{$item->dimensi}}
                                    </div>
                                    <span class="badge text-bg-warning rounded-pill fs-13 hapus-dimensi" data-uuid="{{$item->uuid}}"><i class="fas fa-close"></i></span>
                                </li>    
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 m-0 ps-2 pt-2 pe-0">
            <div class="card border-0 rounded-3">
                <div class="card-header bg-warning-subtle text-warning-emphasis">
                    <p class="m-0 p-0"><b>Pengaturan Elemen</b></p>
                    <p class="fs-11 lh-sm m-0 p-0">Mengatur Elemen terlebih dahulu setelah mengatur dimensi. Pengaturan elemen sesuai dengan ketentuan dari kurikulum Merdeka</p>
                </div>
                <div class="card-body">
                    <div class="row m-0 p-0">
                        <div class="col-12 form-group p-0">
                            <label for="elemen-dimensi">Dimensi</label>
                            <select name="elemen-dimensi" id="elemen-dimensi" class="form-control" data-toggle="select">
                                <option value="">Pilih Salah Satu</option>
                                @foreach ($dimensi as $item)
                                    <option value="{{$item->uuid}}">{{$item->dimensi}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 form-group p-0">
                            <label for="elemen" class="fs-12">Elemen</label>
                            <input type="text" name="elemen" id="elemen" class="form-control">
                            <div class="invalid-feedback">Tidak Boleh Kosong</div>
                        </div>
                        <div class="col-12 mt-2 p-0">
                            <button class="btn btn-sm btn-warning text-warning-emphasis simpan-elemen">
                                <i class="fas fa-save"></i> Simpan elemen
                            </button>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="m-0 p-0 fs-12"><b>List Elemen</b></p>
                            <hr />
                            <div class="accordion" id="accordionElemen">
                                @foreach ($dimensi as $item)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fs-12" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-{{$item->uuid}}" aria-expanded="true" aria-controls="accordion-{{$item->uuid}}">
                                            {{$item->dimensi}}
                                        </button>
                                        </h2>
                                        <div id="accordion-{{$item->uuid}}" class="accordion-collapse collapse" data-bs-parent="#accordionElemen">
                                            <div class="accordion-body fs-12">
                                                
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.simpan-dimensi').click(function() {
            var dimensi = $('#dimensi').val();

            if(dimensi == "") {
                $('#dimensi').addClass('is-invalid').removeClass('is-valid');
            } else {
                $('#dimensi').addClass('is-valid').removeClass('is-invalid');
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{route('penilaian.p5.dimensi.tambah')}}",
                    data: {dimensi : dimensi},
                    headers: {'X-CSRF-TOKEN' : "{{csrf_token()}}"},
                    success: function(data) {
                        if(data.success == true) {
                            removeLoading();
                            cAlert("green","Sukses","Dimensi Berhasil Ditambah",true);
                        }
                    },
                    error: function (data) {console.log(data.responseJSON.message);  }
                })
            }
        });
        $('.hapus-dimensi').click(function() { 
            var uuid = $(this).data('uuid');           
            var hapusDimensi = () => {
                loading();
                var url = "{{route('penilaian.p5.dimensi.hapus',':id')}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "DELETE",
                    url: url,
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function (data) {
                        if(data.success == true) {
                            removeLoading();
                            cAlert("green","Berhasil","Dimensi Berhasil Dihapus",true);
                        }
                        
                    },
                    error: function (data) { console.log(data.responseJSON.message) }
                });
            }
            cConfirm("Peringatan","Sebelum menghapus dimensi, pastikan tidak ada elemen dan sub elemen dalam dimensi bersangkutan",hapusDimensi);
        });
    </script>
@endsection