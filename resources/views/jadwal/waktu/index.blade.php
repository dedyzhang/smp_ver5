@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Pengaturan Waktu</b></h5>
        <p>Pengaturan Waktu dalam Jadwal Pelajaran</p>
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex mt-3">
        <a href="{{route('jadwal.waktu.create',$versi->uuid)}}" class="btn btn-warning btn-sm text-warning-emphasis"><i class="fas fa-plus"></i> Tambah</a>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td>No</td>
                        <td>Waktu Mulai</td>
                        <td>Waktu Akhir</td>
                        <td style="min-width: 100px">#</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($waktu as $waktu)
                        <tr id="waktu.{{$waktu->uuid}}">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$waktu->waktu_mulai}}</td>
                            <td>{{$waktu->waktu_akhir}}</td>
                            <td>
                                <a href="{{route('jadwal.waktu.edit',['uuid' => $versi->uuid,'waktuUUID' => $waktu->uuid])}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-pencil"></i></a>
                                <button class="btn btn-sm btn-danger hapus-jadwal"><i class="fas fa-trash-can"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $('.hapus-jadwal').click(function(){
            var uuid = $(this).closest('tr').attr('id').split('.').pop();
            var deleteData = () => {
                var url = "{{route('jadwal.waktu.delete',['uuid' => ':id','waktuUUID' => ':id2'])}}";
                url = url.replace(':id',"{{$versi->uuid}}").replace(':id2',uuid);
                loading();
                $.ajax({
                    type: "delete",
                    url : url,
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        if(data.success === true) {
                            removeLoading();
                            location.reload();
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
            cConfirm("Perhatian","Yakin untuk menghapus data ini",deleteData);
        });
    </script>

@endsection
