@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('aturan')}}
    <div class="body-contain-customize col-12">
        <h5><b>Poin Aturan</b></h5>
        <p>Halaman ini diperuntukkan admin dan kesiswaan untuk mengatur poin aturan siswa</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex">
        <a href="{{route('aturan.create')}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-plus"></i> Tambah Aturan</a>
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
        <table class="table table-bordered fs-12" id="table-aturan" style="width:100%">
            <thead>
                <tr>
                    <td>No</td>
                    <td>Jenis</td>
                    <td>Kode</td>
                    <td>Aturan</td>
                    <td>Poin</td>
                    <td>#</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($aturan as $item)
                    <tr id="aturan.{{$item->uuid}}">
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center">{{$item->jenis}}</td>
                        <td class="text-center">{{$item->kode}}</td>
                        <td>{{$item->aturan}}</td>
                        <td class="text-center">{{$item->poin}}</td>
                        <td>
                            <a href="{{route('aturan.edit',$item->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis">
                                <i class="fas fa-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger hapus-file">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
         var table = new DataTable('#table-aturan',{
            // scrollX : true,
            columns: [{ width: '5%' },{ width: '10%' },{ width: '10%' },{ width: '50%' },{ width: '10%' }, { width: '15%' }],
            "initComplete": function (settings, json) {
                $("#table-aturan").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
        });
        $('.hapus-file').click(function() {
            var uuid = $(this).closest('tr').attr('id').split('.').pop();
            var hapusAturan = () => {
                loading();
                var url = "{{route('aturan.destroy',':id')}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "delete",
                    url: url,
                    headers: {"X-CSRF-TOKEN": '{{csrf_token()}}'},
                    success: function(data) {
                        console.log(data);
                        if(data.success === true) {
                            removeLoading();
                            cAlert("green","Berhasil","Aturan Berhasil Dihapus",true);
                        }

                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            };

            cConfirm("Perhatian","Yakin untuk hapus Aturan ini",hapusAturan);
        });
    </script>
@endsection
