@extends('layouts.main')

@section('container')
    {{ Breadcrumbs::render('p3'); }}
    <div class="col-12 body-contain-customize">
        <h5>Pelanggaran, Prestasi dan Partisipasi</h5>
        <p>Halaman ini diperuntukkan admin dan Wakil Kesiswaan untuk menginput Pelanggaran, Prestasi dan Partisipasi yang akan dipilih oleh guru bersangkutan</p>
    </div>

    <div class="col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex body-contain-customize mt-3">
        <a href="{{route('p3.create')}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-plus"></i> Tambah List</a>
    </div>
    <div class="col-12 body-contain-customize mt-3">
        <table
        id="datatable-p3"
        class="dataTables table table-striped table-bordered text-center"
        style="width: 100%"
        >
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Jenis</th>
                    <th width="35%">Deskripsi</th>
                    <th width="10%">Poin</th>
                    <th width="10%">#</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($p3 as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td class="{{$item->jenis == "prestasi" ? "text-success" : ($item->jenis == "pelanggaran" ? "text-warning" : "text-danger")}}">{{$item->jenis}}</td>
                        <td>{{$item->deskripsi}}</td>
                        <td>{{$item->poin}}</td>
                        <td>
                            <a class="btn btn-sm btn-warning text-warning-emphasis" href="{{route('p3.edit',$item->uuid)}}"><i class="fas fa-pencil"></i></a>
                            <button class="btn btn-sm btn-danger hapus-p3" data-uuid="{{$item->uuid}}"><i class="fas fa-trash-can"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        var table = new DataTable("#datatable-p3", {
            // scrollX : true,
            initComplete: function (settings, json) {
                $("#datatable-p3").wrap(
                    "<div style='overflow:auto; width:100%;position:relative;'></div>"
                );
            },
            columnDefs: [
                {
                    className: "text-center",
                    targets: [0, 1, 3],
                },
                {
                    className: "text-start",
                    targets: [2],
                },
            ],
        });
        $('.hapus-p3').click(function() {
            var uuid = $(this).data('uuid');
            var HapusP5 = function() {
                loading();
                var url = "{{route('p3.destroy',':id')}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "DELETE",
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    url: url,
                    success: function(data) {
                        removeLoading();
                        cAlert("green","Sukses","Data Berhasil Dihapus",true);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
            cConfirm("Perhatian","Apakah Anda Yakin Untuk Menghapus Data ini", HapusP5);
        })
    </script>
@endsection
