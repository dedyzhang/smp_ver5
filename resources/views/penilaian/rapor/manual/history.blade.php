@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Rapor Manual</b></h5>
        <p>Halaman ini diperuntukkan admin untuk menginput nilai siswa diluar dari aplikasi ( seperti nilai pendidikan Agama dan Budi Pekerti )</p>
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
    <div class="body-contain-customize col-12 mt-3">
        <table class="table table-bordered" id="tableRaporManual" style="width:100%">
            <thead>
                <tr>
                    <td>No</td>
                    <td>Pelajaran</td>
                    <td>Nama Siswa</td>
                    <td>Nilai</td>
                    <td>Deskripsi Positif</td>
                    <td>Deskripsi Negatif</td>
                    <td>#</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($manual as $item)
                    <tr class="fs-12">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->pelajaran->pelajaran_singkat}}</td>
                        <td>{{$item->siswa->nama}} ({{$item->siswa->kelas->tingkat.$item->siswa->kelas->kelas}})</td>
                        <td>{{$item->nilai}}</td>
                        <td>{{$item->deskripsi_positif}}</td>
                        <td>{{$item->deskripsi_negatif}}</td>
                        <td>
                            <a href="{{route('penilaian.admin.manual.edit',$item->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis">
                                <i class="fas fa-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger hapus-nilai" data-uuid="{{$item->uuid}}">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        var table = new DataTable('#tableRaporManual',{
            // scrollX : true,
            columns: [{ width: '5%' },{ width: '10%' },{ width: '20%' },{ width: '5%' },{ width: '20%' },{ width: '20%' },{ width: '10%' }],
            "initComplete": function (settings, json) {
                $("#tableRaporManual").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
            "columnDefs": [
                {"className": "text-center", "targets": [0,1,3,6]},
                {"className": "text-start", "targets": [4,5]}
            ],
        });
        $('#tableRaporManual').on('click','.hapus-nilai',function() {
            var uuid = $(this).data('uuid');

            var hapusNilaiManual = () => {
                loading();
                var url = "{{route('penilaian.admin.manual.delete',':id')}}";
                url = url.replace(':id',uuid);
                loading();
                $.ajax({
                    type: "delete",
                    url: url,
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        console.log(data);
                        removeLoading();
                        cAlert("green","Sukses","Nilai Berhasil Dihapus",true);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }

            cConfirm("Perhatian","Apakah anda yakin untuk menghapus nilai rapor manual tersebut",hapusNilaiManual);
        });
    </script>
@endsection
