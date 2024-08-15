@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('perangkat')}}
    <div class="body-contain-customize col-12">
        <h5><b>Perangkat Pembelajaran</b></h5>
        <p>Halaman ini diperuntukkan admin dan kurikulum untuk memantau perangkat pembelajaran yang diupload oleh guru</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-grid d-lg-grid d-xl-grid">
        <a href="{{route('perangkat.create')}}" class="btn btn-sm btn-warning text-warning-emphasis">
            <i class="fas fa-plus"></i> Tambah List
        </a>
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
        <p>List Perangkat Pembelajaran yang diupload</p>
        <table class="table table-bordered fs-12" id="table-perangkat" style="width:100%">
            <thead>
                <tr>
                    <td>No</td>
                    <td style="min-width:150px">Perangkat Pembelajaran</td>
                    <td style="min-width:100px">#</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($perangkat_list as $perangkat)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$perangkat->perangkat}}</td>
                        <td>
                            <a href="{{route('perangkat.edit',$perangkat->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis">
                                <i class="fas fa-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger hapus-perangkat" data-uuid="{{$perangkat->uuid}}">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @foreach ($guru as $item)
        <div class="col-md-6 col-lg-4 col-xl-3 col-sm-6 col-12 mt-3 pe-2 ps-0">
            <div class="card border-light rounded-4">
                <div class="card-body">
                    @if($item->jk == "l")
                        <img src="{{asset('img/teacher-boy.png')}}" style="width: 50px; height:50px" />
                    @else
                        <img src="{{asset('img/teacher-girl.png')}}" style="width: 50px; height:50px" />
                    @endif
                    <p class="m-0 p-0 mt-2">{{Str::limit($item->nama,18)}}</p>
                    <p class="m-0 p-0 fs-12"><i><strong>{{$item->users->access}}</strong></i></p>
                    <p class="m-0 p-0 fs-14 mt-2">File Terupload : <b>{{isset($perangkat_array[$item->uuid]) ? $perangkat_array[$item->uuid] : "0"}}</b> File</p>

                    <div class="button-place mt-3">
                        <a href="{{route('perangkat.show',$item->uuid)}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-eye"></i></a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script>
        var table = new DataTable('#table-perangkat',{
            // scrollX : true,
            columns: [{ width: '10%' },{ width: '70%' },{ width: '20%' }],
            columnDefs:[ {
                className: "text-center",
                targets: [0],
            },
            {
                className: 'align-middle',
                targets:[0,1,2]
            }],
            "initComplete": function (settings, json) {
                $("#table-perangkat").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
        });
        $('#table-perangkat').on('click','.hapus-perangkat',function() {
            var uuid = $(this).data('uuid');

            var hapusPerangkat = () => {
                loading();
                var url = "{{route('perangkat.delete',':id')}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "delete",
                    url: url,
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        console.log(data);
                        if(data.success === true) {
                            cAlert("green","Sukses","Data Berhasil Dihapus",true);
                            removeLoading();
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }

            cConfirm("Perhatian","Yakin untuk Menghapus Perangkat ini",hapusPerangkat);
        });
    </script>
@endsection
