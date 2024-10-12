@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Data Ekskul</b></h5>
        <p>Halaman ini menyediakan platform mudah untuk mengelola data ekstrakurikuler, memungkinkan pengguna untuk menambah, mengedit, dan menghapus informasi kegiatan secara efisien.</p>
    </div>
    <div class="body-contain-customize gap-2 mt-3 col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-block col-lg-auto d-lg-block col-xl-auto d-xl-block">
        <a href="{{route('ekskul.create')}}" class="btn btn-sm btn-warning text-warning-emphasis">
            <i class="fas fa-voleyball"></i> tambah ekskul
        </a>
        <a href="{{route('ekskul.sort')}}" class="btn btn-sm btn-primary">
            <i class="fas fa-arrow-up-1-9"></i> Atur Urutan
        </a>
    </div>
    @if (session('success'))
    <div class="body-contain-customize col-12 mt-3">
        <div class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert" >
            <i class="bi flex-shrink-0 me-2 fa-solid fa-check" aria-label="Success:"></i>
            <div><strong>Sukses !</strong> {{ session("success") }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif
    <div class="row m-0 p-0">
        @foreach ($ekskul as $item)
            <div class="col-md-6 col-lg-4 col-xl-3 col-sm-6 col-12 mt-3 pe-2 ps-0">
                <div class="card border-light rounded-4">
                    <div class="card-body">
                        <h6><i class="fas fa-baseball me-2"></i> <b>{{Str::limit($item->ekskul,20,'...')}}</b></h6>
                        <p class="fs-12">{{Str::limit($item->guru->nama,25,'...')}}</p>

                        <div class="button-place mt-4 gap-2">
                            <a href="{{route('ekskul.edit',$item->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-pencil"></i></a>
                            <button class="btn btn-sm btn-danger delete-ekskul" data-ekskul="{{$item->uuid}}"><i class="fas fa-trash-can"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <script>
        $('.delete-ekskul').click(function() {
            var uuid = $(this).data('ekskul');
            var hapusEkskul = () => {
                loading();
                var url = "{{route('ekskul.destroy',':id')}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "delete",
                    url: url,
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        if(data.success == true) {
                            cAlert("green","Berhasil","Ekskul Berhasil Dihapus",true);
                            removeLoading();
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
            cConfirm("Perhatian","Apakah anda yakin untuk menghapus Ekskul ini <br /><i>Nilai yang sudah dihapus tidak bisa dikembalikan lagi</i>",hapusEkskul);
        });
    </script>
@endsection
