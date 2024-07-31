@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('poin-show',$siswa)}}
    <div class="body-contain-customize col-12">
        <h5><b>Lihat Poin</b></h5>
        <p>Halaman untuk menampilkan data siswa dan poin siswa bersangkutan</p>
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
            @php
                $sisa = 100;
            @endphp
            @foreach ($poin as $item)
                @php
                    if($item->aturan->jenis == 'kurang') {
                        $sisa -= $item->aturan->poin;
                    } else {
                        $sisa += $item->aturan->poin;
                    }
                @endphp
            @endforeach
            <tr>
                <td width="35%">Poin</td>
                <td width="3%">:</td>
                <td width="62%">{{$sisa}}</td>
            </tr>
            <tr>
            @php
                if($sisa < 75 && $sisa >= 50) {
                    $peringatan = "Peringatan 1";
                } else if($sisa < 50 && $sisa >= 25) {
                    $peringatan = "Peringatan 2";
                } else if($sisa < 25) {
                    $peringatan = "Peringatan 3";
                } else {
                    $peringatan = "-";
                }
            @endphp
                <td width="45%">Peringatan</td>
                <td width="3%">:</td>
                <td width="52%">{{$peringatan}}</td>
            </tr>
        </table>
    </div>
    <div class="clearBoth"></div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-d-xl-flex gap-2">
        <a href="{{route('poin.create',$siswa->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis">
            <i class="fas fa-plus"></i> Tambah Poin
        </a>
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
        <div class="table-responsive">
            <table class="table table-bordered fs-12">
                <tr>
                    <td width="5%">No</td>
                    <td width="15%">Tanggal</td>
                    <td width="10%">Jenis</td>
                    <td width="40%">Aturan</td>
                    <td width="10%">Poin</td>
                    <td width="10%">Sisa</td>
                    <td width="10%">#</td>
                </tr>
                <tr class="table-secondary">
                    <td colspan="4"><b>Poin Awal</b></td>
                    <td></td>
                    <td class="text-center">100</td>
                    <td>-</td>
                </tr>
                @php
                    $sisa = 100;
                @endphp
                @foreach ($poin as $item)
                    @php
                        if($item->aturan->jenis == 'kurang') {
                            $sisa -= $item->aturan->poin;
                        } else {
                            $sisa += $item->aturan->poin;
                        }
                    @endphp
                    <tr id="poin.{{$item->uuid}}">
                        <td>{{$loop->iteration}}</td>
                        <td>{{date('d M Y',strtotime($item->tanggal))}}</td>
                        <td class="@if ($item->aturan->jenis == "kurang") text-danger @else text-success @endif">{{$item->aturan->jenis}}</td>
                        <td>{{$item->aturan->aturan}}</td>
                        <td class="text-center @if ($item->aturan->jenis == "kurang") text-danger @else text-success @endif">{{$item->aturan->poin}}</td>
                        <td class="text-center @if ($item->aturan->jenis == "kurang") text-danger @else text-success @endif">{{$sisa}}</td>
                        <td><button class="btn btn-sm btn-danger hapus-poin">
                            <i class="fas fa-trash-can"></i>
                        </button></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <script>
        $('.hapus-poin').click(function() {
            var uuid = $(this).closest('tr').attr('id').split('.').pop();
            var HapusPoin = () => {
                loading();
                var url = "{{route('poin.delete',':id')}}";
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "post",
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    url: url,
                    success : function(data) {
                        removeLoading();
                        cAlert("green","Sukses","Data Poin Berhasil Dihapus",true);
                        console.log(data);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });

            }
            cConfirm("Perhatian","Yakin untuk menghapus poin ini",HapusPoin);
        });
    </script>
@endsection
