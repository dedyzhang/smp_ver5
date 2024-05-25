@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('kelas-histori')}}
    <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <p><b>Histori Pengajuan Rombel</b></p>
        <table id="datatable-rombel" class="dataTables table table-striped table-bordered text-center" style="width:100%">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">NIS</th>
                    <th width="35%">Nama Siswa</th>
                    <th width="5%">Kelas</th>
                    <th width="35%">Tanggal Penginputan</th>
                    <th width="10%">#</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rombel as $rombel)
                    <tr id="siswa.{{$rombel->uuid}}">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$rombel->siswa->nis}}</td>
                        <td>{{$rombel->siswa->nama}}</td>
                        <td>{{$rombel->kelas->tingkat.$rombel->kelas->kelas}}</td>
                        <td>{{date('d F Y H:i:s',strtotime($rombel->created_at))}}</td>
                        <td><button class="btn btn-sm btn-danger hapus-siswa" data-bs-toggle="tooltip" data-bs-position="top" data-bs-title="Hapus Pengajuan"><i class="fa-solid fa-trash-can"></i></button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        var table = new DataTable('#datatable-rombel',{
            // scrollX : true,
            "initComplete": function (settings, json) {
                $("#datatable-rombel").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
            "columnDefs": [
                {"className": "text-center", "targets": [0,1,3,5]},
                {"className": "text-start", "targets": [2,4]}
            ],
        });
        $('#datatable-rombel').on('click','.hapus-siswa',function() {
            var id = $(this).closest('tr').attr('id').split('.').pop();
            var hapusRombel = () => {
                loading();
                var url = "{{route('kelas.historiHapus',':id')}}";
                url = url.replace(':id',id);
                var token = '{{csrf_token()}}';
                $.ajax({
                    type:"post",
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN" : token
                    },
                    success: function(data) {
                        if(data.success === true) {
                            removeLoading();
                            cAlert("green","Berhasil","Data Berhasil Dihapus",true);
                        }
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                })
            }
            cConfirm("Perhatian","Yakin untuk menghapus rombel siswa berikut",hapusRombel);
        })
    </script>
@endsection
