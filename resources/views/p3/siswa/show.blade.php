@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Pelanggaran, Prestasi dan Partisipasi</h5>
        <p>Halaman ini diperuntukkan admin dan Wakil Kesiswaan Mengatur Pelanggaran, Prestasi dan Partisipasi Siswa</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-5 col-xl-5">
        <table class="table table-striped fs-12">
            <tr>
                <td width="35%">Nama</td>
                <td width="3%">:</td>
                <td width="62%">{{$siswa->nama}}</td>
            </tr>
            <tr>
                <td width="35%">NIS</td>
                <td width="3%">:</td>
                <td width="62%">{{$siswa->nis}}</td>
            </tr>
            <tr>
                <td width="35%">Kelas</td>
                <td width="3%">:</td>
                <td width="62%">{{$siswa->kelas ? $siswa->kelas->tingkat.$siswa->kelas->kelas : "-"}}</td>
            </tr>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="body-contain-customize mt-3 col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto col-lg-flex col-xl-auto col-xl-flex">
        <a href="{{ route('p3.siswa.create',$siswa->uuid) }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Tambah P3</a>
    </div>
    @if(session('success')) 
        <div class="body-contain-customize mt-3 col-12">
            <div
                class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3"
                role="alert"
            >
                <i
                    class="bi flex-shrink-0 me-2 fa-solid fa-check"
                    aria-label="Success:"
                ></i>
                <div><strong>Sukses !</strong> {{ session("success") }}</div>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                ></button>
            </div>
        </div>
    @endif
    <div class="body-contain-customize mt-3 col-12">
        <table class="table table-bordered table-striped fs-11" id="datatables-p3">
            <thead>
                <tr>
                    <td>No</td>
                    <td>Tanggal</td>
                    <td>Jenis</td>
                    <td>Keterangan</td>
                    <td>#</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($p3 as $elem)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d M Y',strtotime($elem->tanggal)) }}</td>
                        <td class="@if ($elem->jenis == "pelanggaran") text-danger @elseif ($elem->jenis == "partisipasi") text-warning @else text-success @endif">{{ $elem->jenis }}</td>
                        <td>{{ $elem->deskripsi }}</td>
                        <td>
                            <a href="{{ route('p3.siswa.edit',$elem->uuid) }}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-pencil"></i></a>
                            <button class="btn btn-sm btn-danger hapus-poin" data-uuid="{{ $elem->uuid }}"><i class="fas fa-trash-can"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>   
    </div>
    <script>
        var table = new DataTable('#datatables-p3',{
            columns: [
                {width: '5%'},
                {width: '15%'},
                {width: '20%'},
                {width: '40%'},
                {width: '15%'},
            ],
            columnDefs: [
                { className: 'text-center', targets: [0,1,2,4] },
             ],
            "initComplete" : function(settings,json) {
                $('#datatables-p3').wrap('<div style="overflow:auto; width:100%; position:relative"></div>');
            }
        });
        $('.hapus-poin').click(function() {
            var uuid = $(this).data('uuid');
            var hapusPoin = () => {
                loading();
                var link = "{{ route('p3.siswa.delete',':id') }}";
                link = link.replace(':id',uuid);
                $.ajax({
                    type: "DELETE",
                    url: link,
                    headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                    success : function(data) {
                        if(data.success == true) {
                            cAlert("green","Berhasil","Data Berhasil Dihapus",true);
                            removeLoading();
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
            cConfirm("Perhatian","Apakah anda yakin untuk menghapus poin ini ?",hapusPoin);
        });
    </script>
@endsection