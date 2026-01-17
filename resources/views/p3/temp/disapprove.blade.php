@extends('layouts.main')

@section('container')
    {{ Breadcrumbs::render('p3-temp-disapprove') }}
    <div class="body-contain-customize col-12">
        <h5>Pelanggaran, Prestasi dan Partisipasi</h5>
        <p>Halaman ini diperuntukkan Walikelas Melihat Pelanggaran, Prestasi dan Partisipasi Siswa yang sudah di Disapprove oleh Kesiswaan</p>
    </div>
    <div class="body-contain-customize mt-3 col-12">
        <table class="table-bordered table table-striped fs-12" id="table-pengajuan" style="width: 100%">
            <thead>
                <tr>
                    <td width="5%">No</td>
                    <td width="10%">Tanggal</td>
                    <td width="20%">Nama Siswa</td>
                    <td width="5%">Kelas</td>
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
                        <td>{{ $item->siswa && $item->siswa->nama ? $item->siswa->nama : "Deleted User" }}</td>
                        <td>{{ $item->siswa && $item->siswa->kelas ? $item->siswa->kelas->tingkat.$item->siswa->kelas->kelas : "-"}}</td>
                        <td><p class="m-0 p-0 @if($item->jenis == 'pelanggaran') text-danger @elseif($item->jenis == 'partisipasi') text-warning @else text-success @endif">{{ $item->jenis }}</p><span class="fs-10 font-italic">{{ $item->deskripsi }} - ({{ $item->poin }})</span></td>
                        <td><i data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="<p class='m-0 p-0 fs-11'><b>{{ ucfirst($item->yang_mengajukan) }}</b></p><i class='fs-10'>{{ $nama }}</i>" data-bs-placement="top" class="fs-18 fas @if($item->yang_mengajukan == "guru") fa-user-tie text-primary @else fa-user text-success @endif"></i></td>
                        <td>
                            @if ($item->status == "disapprove") <i class="fas fa-times text-danger"></i> @endif
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
                {width: '5%'},
                {width: '30%'},
                {width: '5%'},
                {width: '20%'},
            ],
            columnDefs: [
                { className: 'text-center', targets: [0,1,3,5,6] },
             ],
            "initComplete" : function(settings,json) {
                $('#table-pengajuan').wrap('<div style="overflow:auto; width:100%; position:relative"></div>');
            }
        });
    </script>
@endsection
