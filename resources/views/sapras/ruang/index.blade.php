@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Ruang</b></h5>
        <p>Halaman untuk menampilkan, menambahkan dan mengedit ruangan didalam sekolah</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex">
        <a href="{{route('ruang.create')}}" class="btn btn-sm btn-warning text-warning-emphasis">
            <i class="fas fa-plus"></i> Tambah Ruangan
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
    <div class="body-contain-customize mt-3 col-12">
        <table class="table table-bordered table-striped fs-12" id="datatable-ruangan" style="width:100%">
            <thead>
                <tr>
                    <td>No</td>
                    <td>Kode</td>
                    <td>Nama Ruangan</td>
                    <td>Warna</td>
                    <td>Umum</td>
                    <td>#</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($ruang as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->kode}}</td>
                        <td>{{$item->nama}}</td>
                        <td><span class="badge" style="background-color:{{$item->warna}}">{{$item->warna}}</span></td>
                        <td>{{$item->umum}}</td>
                        <td style="min-width:150px">
                            <a href="{{route('ruang.show',$item->uuid)}}" class="btn btn-sm btn-success">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{route('ruang.edit',$item->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger hapus-ruang" data-uuid="{{$item->uuid}}">
                                <i class="fa fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        var table = new DataTable('#datatable-ruangan',{
            // scrollX : true,
            columns: [{ width: '10%' },{ width: '15%' },{ width: '40%' },{ width: '10%' },{ width: '10%' },{ width: '15%' }],
            columnDefs: [
                { className: 'text-center', targets: [0,1,3,4,5] },
             ],
            "initComplete": function (settings, json) {
                $("#datatable-ruangan").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
        });
        $('.hapus-ruang').click(function() {
            var uuid = $(this).data('uuid');
            var hapusRuangan = () => {
                loading();
                var url = "{{route('ruang.destroy',':id')}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "delete",
                    url: url,
                    headers : {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        removeLoading();
                        cAlert("green","Sukses","Ruangan Berhasil Dihapus",true);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
            cConfirm("Perhatian","Yakin untuk menghapus ruangan ini <p class='fs-12'><b>Semua data yang sudah terintergrasi dengan Data Ruangan ini akan terhapus</b></p>",hapusRuangan);
        });
    </script>
@endsection
