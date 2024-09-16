@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('pengajuan-poin-approve')}}
    <div class="body-contain-customize col-12">
        <h5><b>Approve Poin</b></h5>
        <p>Halaman ini untuk menampilkan poin yang sudah diapprove oleh waka kesiswaan</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <table class="table table-bordered table-striped" id="table-temp-poin" style="width:100%">
            <thead>
                <tr>
                    <td>No</td>
                    <td>Tanggal</td>
                    <td>Siswa</td>
                    <td>Kelas</td>
                    <td>Aturan</td>
                    <td>Poin</td>
                    <td>Diajukan Oleh</td>
                    <td>Status</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($temp as $item)
                    <tr class="fs-12">
                        <td>{{$loop->iteration}}</td>
                        <td>{{date('d M Y',strtotime($item->tanggal))}}</td>
                        <td>{{$item->siswa->nama}}</td>
                        <td>{{$item->siswa->kelas->tingkat.$item->siswa->kelas->kelas}}</td>
                        <td class="{{$item->aturan->jenis == "kurang" ? "text-danger" : "text-success"}}">{{$item->aturan->kode."-".$item->aturan->aturan}}</td>
                        <td>{{$item->aturan->poin}}</td>
                        <td>{{isset($all_name[$item->id_input]) ? $all_name[$item->id_input] : "" }}</td>
                        <td class="status-place">
                            @if ($item->status == "belum")
                                <i class="fa-regular fs-20 fa-face-meh" data-bs-toggle="tooltip" data-bs-title="Belum diapprove Kesiswaan" data-bs-placement="top"></i>
                            @elseif ($item->status == "approve")
                                <i class="fa-regular fs-20 fa-face-laugh text-success" data-bs-toggle="tooltip" data-bs-title="Sudah diapprove Kesiswaan" data-bs-placement="top"></i>
                            @elseif ($item->status == "disapprove")
                                <i class="fa-regular fs-20 fa-face-frown text-danger" data-bs-toggle="tooltip" data-bs-title="Tidak diapprove Kesiswaan" data-bs-placement="top"></i>
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
                {width: '15%'},
                {width: '5%'},
                {width: '25%'},
                {width: '5%'},
                {width: '15%'},
                {width: '5%'},
            ],
            columnDefs: [
                { className: 'text-center', targets: [0,1,3,4,5,7] },
             ],
            "initComplete" : function(settings,json) {
                $('#table-temp-poin').wrap('<div style="overflow:auto; width:100%; position:relative"></div>');
            }

        });
        $('#table-temp-poin').on('click','.pengajuan',function() {
            var jenis = $(this).data('jenis');
            var uuid = $(this).closest('.button-place').data('uuid');
            var ini = this;
            loading();
            var url = "{{route('temp.update',':id')}}";
            url = url.replace(':id',uuid);
            $.ajax({
                type: "post",
                url : url,
                data: {jenis : jenis},
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                success: function(data) {
                    removeLoading();
                    console.log(data);
                    if(data.jenis == "approve") {
                        var cont = `<i class="fa-regular fs-20 fa-face-laugh text-success" data-bs-toggle="tooltip" data-bs-title="Sudah diapprove Kesiswaan" data-bs-placement="top"></i>`;
                    } else {
                        var cont = `<i class="fa-regular fs-20 fa-face-frown text-danger" data-bs-toggle="tooltip" data-bs-title="Tidak diapprove Kesiswaan" data-bs-placement="top"></i>`;
                    }
                    $(ini).closest('tr').find('.status-place').html(cont);
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        });
    </script>
@endsection
