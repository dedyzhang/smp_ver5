@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('walikelas-poin-temp')}}
    <div class="body-contain-customize col-12">
        <h5><b>Pengajuan Poin</b></h5>
        <p>Halaman ini untuk menampilkan poin yang sudah diajukan walikelas ataupun sekretaris</p>
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
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex">
        @if (\Request::route()->getName() === 'walikelas.poin.temp')
            <a href="{{route('walikelas.poin.temp.create')}}" class="btn btn-sm btn-warning text-warning-emphasis">
                <i class="fa fa-plus"></i> Tambah Pengajuan
            </a>
        @else
           <a href="{{route('sekretaris.poin.create')}}" class="btn btn-sm btn-warning text-warning-emphasis">
                <i class="fa fa-plus"></i> Tambah Pengajuan
            </a>
        @endif

    </div>
    <div class="body-contain-customize col-12 mt-3">
        <table class="table table-bordered table-striped" id="table-temp-poin" style="width:100%">
            <thead>
                <tr>
                    <td>No</td>
                    <td>Tanggal</td>
                    <td>Siswa</td>
                    <td>Jenis</td>
                    <td>Poin</td>
                    <td>Diajukan Oleh</td>
                    <td>Status</td>
                    <td>#</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($poin_temp as $item)
                    <tr class="fs-12">
                        <td>{{$loop->iteration}}</td>
                        <td>{{date('d M Y',strtotime($item->tanggal))}}</td>
                        <td>{{$item->siswa->nama}}</td>
                        <td>{{$item->aturan->jenis}}</td>
                        <td>{{$item->aturan->poin}}</td>
                        <td>{{isset($all_name[$item->id_input]) ? $all_name[$item->id_input] : "" }}</td>
                        <td>
                            @if ($item->status == "belum")
                                <i class="fa-regular fs-20 fa-face-meh" data-bs-toggle="tooltip" data-bs-title="Belum diapprove Kesiswaan" data-bs-placement="top"></i>
                            @elseif ($item->status == "approve")
                                <i class="fa-regular fs-20 fa-face-laugh text-success" data-bs-toggle="tooltip" data-bs-title="Sudah diapprove Kesiswaan" data-bs-placement="top"></i>
                            @elseif ($item->status == "disapprove")
                                <i class="fa-regular fs-20 fa-face-frown text-danger" data-bs-toggle="tooltip" data-bs-title="Tidak diapprove Kesiswaan" data-bs-placement="top"></i>
                            @endif
                        </td>
                        <td>
                            @if ($item->status == "belum")
                                <button class="btn btn-sm btn-danger hapus-pengajuan" data-uuid="{{$item->uuid}}">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        var table = new DataTable('#table-temp-poin',{
            columns: [
                {width: '5%'},
                {width: '10%'},
                {width: '25%'},
                {width: '10%'},
                {width: '5%'},
                {width: '30%'},
                {width: '5%'},
                {width: '10%'},
            ],
            columnDefs: [
                { className: 'text-center', targets: [0,1,3,4,6,7] },
             ],
            "initComplete" : function(settings,json) {
                $('#table-temp-poin').wrap('<div style="overflow:auto; width:100%; position:relative"></div>');
            }

        });
        $('#table-temp-poin').on('click','.hapus-pengajuan',function(){
            var uuid = $(this).data('uuid');
            var hapusPengajuan = () => {
                loading();
                $.ajax({
                    type: "delete",
                    url: "{{route('walikelas.poin.temp.delete')}}",
                    data: {uuid : uuid},
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        removeLoading();
                        cAlert("green","Sukses","Pengajuan Berhasil Dihapus",true);
                    },
                    error: function(data) {
                        data.responseJSON.message;
                    }
                })
            };
            cConfirm("Perhatian","Yakin untuk menghapus pengajuan ini ?",hapusPengajuan);
        });
    </script>
@endsection
