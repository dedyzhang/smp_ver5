@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Pelanggaran, Prestasi dan Partisipasi</h5>
        <p>Halaman ini diperuntukkan Walikelas Mengajukan Pelanggaran, Prestasi dan Partisipasi Siswa dikelasnya untuk di tindaklanjuti oleh Kesiswaan</p>
    </div>
    <div class="body-contain-customize gap-2 mt-3 col-12 d-grid col-sm-12 d-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex">
        <a href="{{ route('p3.temp.approve.history') }}" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Lihat Approve P3</a>
        <a href="{{ route('p3.temp.disapprove.history') }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Lihat Disapprove P3</a>
    </div>
    <div class="clearfix"></div>
    <div class="body-contain-customize mt-3 col-12">
        <table class="table-bordered table table-striped fs-12" id="table-pengajuan" style="width: 100%">
            <thead>
                <tr>
                    <td width="5%">No</td>
                    <td width="10%">Tanggal</td>
                    <td width="20%">Nama Siswa</td>
                    <td width="30%">Jenis P3</td>
                    <td width="5%">User</td>
                    <td width="20%">#</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($p3_temp as $item)
                    @php
                        if($item->yang_mengajukan == "sekretaris") {
                            $nama_pengajuan = $all_siswa->first(function($elem) use($item) {
                                return $elem->uuid == $item->id_pengajuan;
                            });
                        } else {
                            $nama_pengajuan = $all_guru->first(function($elem) use($item) {
                                return $elem->uuid == $item->id_pengajuan;
                            });
                        }

                        if(isset($nama_pengajuan) && $nama_pengajuan->nama != "null") {
                            $nama = $nama_pengajuan->nama;
                        } else {
                            $nama = "Deleted User";
                        }
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d M Y',strtotime($item->tanggal)) }}</td>
                        <td>{{ $item->siswa->nama }}</td>
                        <td><p class="m-0 p-0 @if($item->jenis == 'pelanggaran') text-danger @elseif($item->jenis == 'partisipasi') text-warning @else text-success @endif">{{ $item->jenis }}</p><span class="fs-10 font-italic">{{ $item->deskripsi }}</span></td>
                        <td><i data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="<p class='m-0 p-0 fs-11'><b>{{ ucfirst($item->yang_mengajukan) }}</b></p><i class='fs-10'>{{ $nama }}</i>" data-bs-placement="top" class="fs-18 fas @if($item->yang_mengajukan == "guru") fa-user-tie text-primary @else fa-user text-success @endif"></i></td>
                        <td>
                            <button class="btn btn-sm btn-warning text-warning-emphasis hapus-temp" data-uuid="{{ $item->uuid }}"><i class="fas fa-trash"></i></button>
                            <button data-bs-toggle="tooltip" data-bs-title="Approve P3" data-bs-placement="top" class="btn btn-sm btn-success approve-p3" data-uuid="{{ $item->uuid }}"><i class="fas fa-check"></i></button>
                            <button data-bs-toggle="tooltip" data-bs-title="Disapprove P3" data-bs-placement="top" class="btn btn-sm btn-danger disapprove-p3" data-uuid="{{ $item->uuid }}"><i class="fas fa-times"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        var table = new DataTable('#table-pengajuan',{
            columns: [
                {width: '5%'},
                {width: '10%'},
                {width: '20%'},
                {width: '30%'},
                {width: '5%'},
                {width: '20%'},
            ],
            columnDefs: [
                { className: 'text-center', targets: [0,1,4,5] },
             ],
            "initComplete" : function(settings,json) {
                $('#table-pengajuan').wrap('<div style="overflow:auto; width:100%; position:relative"></div>');
            }
        });
        $('#table-pengajuan').on('click','.hapus-temp',function() {
            var uuid = $(this).data('uuid');

            var hapusPengajuan = function() {
                loading();
                var url = "{{ route('walikelas.p3.temp.destroy',':id') }}";
                url = url.replace(':id',uuid);
                $.ajax({
                    method: "DELETE",
                    url : url,
                    headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                    success: function(data) {
                        if(data.success == true) {
                            removeLoading();
                            cAlert("green","Sukses","Data pengajuan berhasil dihapus",true);
                        }
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
            cConfirm("Perhatian","Apakah anda yakin akan menghapus pengajuan ini?",hapusPengajuan);
        });
        $('#table-pengajuan').on('click','.approve-p3',function() {
            var uuid = $(this).data('uuid');
            var url = "{{ route('p3.temp.approve',':id') }}";
            url = url.replace(':id',uuid);
            var ini = this;
            loading();
            $.ajax({
                type: "put",
                url: url,
                headers: {"X-CSRF-TOKEN":"{{ csrf_token() }}"},
                success: function(data) {
                    if(data.success == true) {
                        removeLoading();
                        $(ini).parent().html("<i class='fas fa-check fs-18 text-success'></i>")
                    }
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        });
        $('#table-pengajuan').on('click','.disapprove-p3',function() {
            var uuid = $(this).data('uuid');
            var url = "{{ route('p3.temp.disapprove',':id') }}";
            url = url.replace(':id',uuid);
            var ini = this;
            loading();
            $.ajax({
                type: "put",
                url: url,
                headers: {"X-CSRF-TOKEN":"{{ csrf_token() }}"},
                success: function(data) {
                    if(data.success == true) {
                        removeLoading();
                        $(ini).parent().html("<i class='fas fa-times fs-18 text-danger'></i>")
                    }
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        });
    </script>
@endsection
