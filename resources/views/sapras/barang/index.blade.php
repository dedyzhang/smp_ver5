@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Sarana dalam Ruang</b></h5>
        <p>Halaman ini untuk menampilkan, mengedit dan menghapus sarana dan prasarana dalam ruangan</p>
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex mt-3">
        <a href="{{route('barang.create',$id)}}" class="btn btn-sm btn-warning text-warning-emphasis">
            <i class="fas fa-plus"></i> Tambah Sarana dan Prasarana
        </a>
    </div>
    <div class="body-contain-customize mt-3">
        <table class="table table-bordered table-striped" id="datatables-barang" style="width:100%">

        </table>
    </div>
    <script>
        var table = new DataTable('#datatables-barang',{
            // scrollX : true,
            //columns: [{ width: '10%' },{ width: '15%' },{ width: '40%' },{ width: '10%' },{ width: '10%' },{ width: '15%' }],
            {{-- columnDefs: [
                { className: 'text-center', targets: [0,1,3,4,5] },
             ], --}}
            "initComplete": function (settings, json) {
                $("#datatable-ruangan").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
        });
    </script>
@endsection
