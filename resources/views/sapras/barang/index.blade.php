@extends('layouts.main')

@section('container')
    @if (\Request::route()->getName() === 'walikelas.ruang')
        {{Breadcrumbs::render('walikelas-ruang')}}
    @else
        {{Breadcrumbs::render('ruang-show',$ruang)}}
    @endif
    <div class="body-contain-customize col-12">
        <h5><b>Sarana dalam Ruang</b></h5>
        <p>Halaman ini untuk menampilkan, mengedit dan menghapus sarana dan prasarana dalam ruangan</p>
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5 mt-3">
        <p><b>Data Ngajar</b></p>
        <table class="table table-striped fs-12">
            <tr>
                <td width="30%">Kode Ruangan</td>
                <td width="5%">:</td>
                <td>{{$ruang->kode}}</td>
            </tr>
            <tr>
                <td>Nama Ruangan</td>
                <td>:</td>
                <td>{{$ruang->nama}}</td>
            </tr>
            <tr>
                <td>Fasilitas Umum</td>
                <td>:</td>
                <td>{{$ruang->umum}}</td>
            </tr>
        </table>
    </div>
    <div class="clearBoth"></div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex mt-3">
        @if (\Request::route()->getName() === 'walikelas.ruang')
            <a href="{{route('walikelas.ruang.create',$id)}}" class="btn btn-sm btn-warning text-warning-emphasis">
                <i class="fas fa-plus"></i> Tambah Sarana dan Prasarana
            </a>
        @else
            <a href="{{route('barang.create',$id)}}" class="btn btn-sm btn-warning text-warning-emphasis">
                <i class="fas fa-plus"></i> Tambah Sarana dan Prasarana
            </a>
        @endif
    </div>
    <div class="body-contain-customize mt-3">
        <table class="table table-bordered table-striped fs-12" id="datatables-barang" style="width:100%">
            <thead>
                <tr>
                    <td>No</td>
                    <td style="min-width:200px">Nama Barang</td>
                    <td>Jumlah</td>
                    <td style="min-width:150px">#</td>
                </tr>
            </thead>
            <tbody>
                @if (isset($ruang->barang))
                    @foreach ($ruang->barang as $barang)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$barang->barang}}</td>
                            <td>{{$barang->jumlah}}</td>
                            <td>
                                <button class="btn btn-success btn-sm lihat-barang" data-uuid="{{$barang->uuid}}">
                                    <i class="fa fa-eye"></i>
                                </button>
                                    @if (\Request::route()->getName() === 'walikelas.ruang')
                                        <a class="btn btn-sm btn-warning text-warning-emphasis" href="{{route('walikelas.ruang.edit',['uuid' => $id,'uuidBarang' => $barang->uuid])}}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    @else
                                        <a class="btn btn-sm btn-warning text-warning-emphasis" href="{{route('barang.edit',['uuid' => $id,'uuidBarang' => $barang->uuid])}}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    @endif
                                <button class="btn btn-sm btn-danger hapus-barang" data-uuid="{{$barang->uuid}}">
                                    <i class="fa fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="modal fade in" id="modal-barang-show">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Barang</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-0 p-0">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <p class="fs-12"><b>A. Data Identitas Barang</b></p>
                            <table class="table table-striped fs-12">
                                <tr>
                                    <td width="32%">Nama Barang</td>
                                    <td width="3%">:</td>
                                    <td width="55%" class="barang-nama"></td>
                                </tr>
                                <tr>
                                    <td width="32%">Merek Barang</td>
                                    <td width="3%">:</td>
                                    <td width="55%" class="barang-merk"></td>
                                </tr>
                                 <tr>
                                    <td width="32%" colspan="3">Deskripsi Barang</td>
                                </tr>
                                <tr>
                                    <td colspan="3">: <span class="barang-deskripsi"></span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <p class="fs-12"><b>B. Data Penyedia Barang</b></p>
                            <table class="table table-striped fs-12">
                                <tr>
                                    <td width="32%">Penyedia</td>
                                    <td width="3%">:</td>
                                    <td width="55%" class="barang-penyedia"></td>
                                </tr>
                                <tr>
                                    <td width="32%">Tanggal Diadakan</td>
                                    <td width="3%">:</td>
                                    <td width="55%" class="barang-tanggal"></td>
                                </tr>
                                 <tr>
                                    <td width="32%">Jumlah Barang</td>
                                    <td width="3%">:</td>
                                    <td width="55%" class="barang-jumlah"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var table = new DataTable('#datatables-barang',{
            // scrollX : true,
            columns: [{ width: '10%' },{ width: '60%' },{ width: '10%' },{ width: '20%' }],
            columnDefs: [
                { className: 'text-center', targets: [0,2,3] },
             ],
            "initComplete": function (settings, json) {
                $("#datatables-barang").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
        });
        $('#datatables-barang').on('click','.lihat-barang',function() {
            $('#modal-barang-show').modal("show");
            var uuid = $(this).data('uuid');
            var url = "{{route('barang.show',['uuid' => ':id','uuidBarang' => ':id2'])}}";
            url = url.replace(':id','{{$id}}').replace(':id2',uuid);
            loading();
            $.ajax({
                type: "get",
                url : url,
                success: function(data) {
                    removeLoading();
                    if(data.success === true) {
                        var barang = data.barang;
                        var tanggalPenyedia = moment(barang.tanggal).format('DD MMM YYYY');
                        $('.barang-nama').html(barang.barang);
                        $('.barang-merk').html(barang.merk);
                        $('.barang-deskripsi').html(barang.deskripsi);
                        $('.barang-penyedia').html(barang.penyedia);
                        $('.barang-tanggal').html(tanggalPenyedia);
                        $('.barang-jumlah').html(barang.jumlah);
                    }
                    console.log(data);
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }

            })
        });
        $('#datatables-barang').on('click','.hapus-barang',function() {
            var uuid = $(this).data('uuid');
            var hapusBarang = () => {
                loading();
                var url = "{{route('barang.show',['uuid' => ':id','uuidBarang' => ':id2'])}}";
                url = url.replace(':id','{{$id}}').replace(':id2',uuid);

                $.ajax({
                    type: "delete",
                    url: url,
                    headers: {'X-CSRF-TOKEN' : '{{csrf_token()}}'},
                    success: function(data) {
                        removeLoading();
                        cAlert("green","Berhasil","Data Berhasil Disimpan",true);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            }
            cConfirm("Perhatian","Yakin untuk menghapus barang ini",hapusBarang);
        });
    </script>
@endsection
