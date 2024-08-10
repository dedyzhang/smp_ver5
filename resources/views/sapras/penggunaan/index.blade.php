@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('penggunaan')}}
    <div class="body-contain-customize col-12">
        <h5><b>Penggunaan Ruang</b></h5>
        <p>Halaman ini diperuntukkan user untuk meminjam penggunaan ruangan umum</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex">
        <a href="{{route('penggunaan.create')}}" class="btn btn-sm btn-warning text-warning-emphasis">
            <i class="fas fa-plus"></i> Ajukan Penggunaan
        </a>
    </div>
    <div class="body-contain-customize mt-3 col-12">
        <p><b>Jadwal Pemakaian Tanggal {{date('d F Y')}}</b></p>
        <div class="table-responsive">
            <table class="table table-bordered fs-11" id="table-jadwal-ruang">
                <thead>
                    <tr>
                        <th style="min-width:100px" width="10%">Waktu</th>
                        @foreach ($ruang as $item)
                            <th width="15%" style="min-width:150px" class="text-center">{{$item->nama}}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwalWaktu as $item)
                        <tr>
                            <td>{{date('H:i',strtotime($item->waktu_mulai))."-".date('H:i',strtotime($item->waktu_akhir))}}</td>
                            @foreach ($ruang as $ruangItem)
                                @if (isset($ruang_array[$ruangItem->uuid.".".$item->uuid]))
                                    <td style="background-color:{{$ruang_array[$ruangItem->uuid.".".$item->uuid]['ruang']['warna']}}">{{$ruang_array[$ruangItem->uuid.".".$item->uuid]['guru']." (".$ruang_array[$ruangItem->uuid.".".$item->uuid]['kelas']." ".$ruang_array[$ruangItem->uuid.".".$item->uuid]['pelajaran'].")"}}</td>
                                @else
                                    <td></td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="body-contain-customize mt-3 col-12">
        <table class="table table-bordered fs-12" id="table-ruang-histori">
            <thead>
                <tr>
                    <td>No</td>
                    <td>Tanggal</td>
                    <td>Waktu</td>
                    <td>Ruang</td>
                    <td>Pemakaian</td>
                    <td>#</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengRuang as $element)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{date('d F Y',strtotime($element->tanggal))}}</td>
                        <td>{{$element->waktu->waktu_mulai."-".$element->waktu->waktu_akhir}}</td>
                        <td>{{$element->ruang->nama}}</td>
                        <td>{{$element->guru->nama." (".$element->kelas->tingkat.$element->kelas->kelas." ".$element->pelajaran->pelajaran_singkat.")"}}</td>
                        <td>
                            <button class="btn btn-sm btn-danger hapus-penggunaan" data-uuid="{{$element->uuid}}">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function(){
            function MergeCommonRows(table) {
                var firstColumnBrakes = [];
                // iterate through the columns instead of passing each column as function parameter:
                for(var i=1; i<=table.find('th').length; i++){
                    var previous = null, cellToExtend = null, rowspan = 1;
                    table.find("td:nth-child(" + i + ")").each(function(index, e){
                        var jthis = $(this), content = jthis.text();
                        // check if current row "break" exist in the array. If not, then extend rowspan:
                        if (previous == content && content !== "") {
                            // hide the row instead of remove(), so the DOM index won't "move" inside loop.
                            jthis.addClass('hidden');
                            cellToExtend.attr("rowspan", (rowspan = rowspan+1));
                        }else{
                            // store row breaks only for the first column:
                            if(i === 1) firstColumnBrakes.push(index);
                            rowspan = 1;
                            previous = content;
                            cellToExtend = jthis;
                        }
                    });
                }
                // now remove hidden td's (or leave them hidden if you wish):
            }
            var table = $('#table-jadwal-ruang');
            MergeCommonRows(table);
        });
        var table = new DataTable('#table-ruang-histori',{
            // scrollX : true,
            columns: [{ width: '5%' },{ width: '15%' },{ width: '15%' },{ width: '15%' },{ width: '25%' },{ width: '15%' }],
            columnDefs: [
                { className: 'text-center', targets: [0,1,5] },
             ],
            "initComplete": function (settings, json) {
                $("#table-ruang-histori").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
        });
        $('.hapus-penggunaan').click(function() {
            var uuid = $(this).data('uuid');
            var deletePengajuan = () => {
                loading();
                var url = "{{route('penggunaan.delete',':id')}}";
                var deleteAll = $('#check-delete-all').is(':checked');
                url = url.replace(':id',uuid);
                $.ajax({
                    type: "delete",
                    url: url,
                    data: {'deleteAll' : deleteAll},
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        removeLoading();
                        cAlert("green","Sukses","Pengajuan Berhasil Dihapus",true);
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }
            cConfirm("Perhatian","<p>Yakin Untuk Delete Pengajuan</p><div class='form-check'><label for='check-delete-all'>Hapus Semua Pengajuan di Ruang Yang Sama</label><input type='checkbox' class='form-check-input' id='check-delete-all' value='yes'/></div>",deletePengajuan);
        });
    </script>
@endsection
