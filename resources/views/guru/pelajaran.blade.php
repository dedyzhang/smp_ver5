@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('guru-pelajaran',$guru)}}
    <div class="body-contain-customize col-12">
        <h5><b>Pengaturan Ngajar</b></h5>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>Pilih Pelajaran</b></p>
        <div class="row">
            @foreach ($pelajaran as $plj)
                <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 gy-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="{{$plj->uuid}}" id="plj.{{$plj->uuid}}" name="pelajaran" />
                        <label class="form-check-label" for="plj.{{$plj->uuid}}"> {{$plj->pelajaran}} </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>Pilih Kelas</b></p>
        <div class="row">
            @foreach ($kelas as $kelas)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 gy-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{$kelas->uuid}}" id="kelas.{{$kelas->uuid}}" name="kelas" />
                        <label class="form-check-label" for="kelas.{{$kelas->uuid}}">Kelas {{$kelas->tingkat.$kelas->kelas}} </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <button class="btn btn-sm btn-warning tambah-ngajar"><i class="fa-solid fa-save"></i> Simpan Data Ngajar</button>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p><b>List Mengajar</b></p>
        <table id="datatable-ngajar" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pelajaran</th>
                    <th>Kelas</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ngajar as $ngajar)
                    <tr id="ngajar.{{$ngajar->uuid}}">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$ngajar->pelajaran->pelajaran}}</td>
                        <td>{{$ngajar->kelas->tingkat.$ngajar->kelas->kelas}}</td>
                        <td><button class="btn btn-sm btn-danger hapus-ngajar"><i class="fa-solid fa-trash-can"></i></button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        var table = new DataTable('#datatable-ngajar',{
            // scrollX : true,
            "initComplete": function (settings, json) {
                $("#datatable-ngajar").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
        });
        $('.tambah-ngajar').click(function() {
            loading();
            var dataArray = [];
            var pelajaran = $('input[name="pelajaran"]:checked').val();
            $('input[name="kelas"]:checked').each(function() {
                dataArray.push({
                    "id_guru" : "{{$guru->uuid}}",
                    "id_pelajaran" : pelajaran,
                    "id_kelas" : $(this).val(),
                    "kkm": 0
                });
            });
            var url = "{{route('guru.ngajar',':id')}}";
            url = url.replace(':id','{{$guru->uuid}}');
            if(pelajaran == "" || dataArray.length == 0) {
                removeLoading();
                oAlert("orange","Perhatian","Data Pelajaran dan Kelas tidak boleh kosong");
            } else {
                $.ajax({
                    type: "POST",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    },
                    data: {
                        dataArray : dataArray
                    },
                    success: function(data) {
                        removeLoading();
                        if(data.success == false) {
                            oAlert('orange','Perhatian',data.message);
                        } else {
                            cAlert("green","Sukses","Sukses Menambahkan Data Mengajar",true);
                        }
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors.message);
                    }

                });
            }
        });
        $('#datatable-ngajar').on('click','.hapus-ngajar',function() {
            var id = $(this).closest('tr').attr('id').split('.').pop();
            var hapusNgajar = () => {
                loading();
                var url = "{{route('guru.hapusNgajar',':id')}}";
                url = url.replace(':id',id);
                $.ajax({
                    type: "delete",
                    url: url,
                    data: {
                        "_token": '{{csrf_token()}}'
                    },
                    success: function(data) {
                        if(data.success) {
                            setTimeout(() => {
                                removeLoading();
                                cAlert("green","Berhasil",data.success,true);
                            }, 500);
                        }
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }

                })
            }
            cConfirm("Perhatian",'Yakin untuk menghapus data Mengajar ini',hapusNgajar);
        });
    </script>
@endsection
