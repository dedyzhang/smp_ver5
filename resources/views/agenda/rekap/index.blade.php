@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('agenda-rekap')}}
    <div class="body-contain-customize col-12">
        <h5>Rekap Agenda</h5>
        <p>Halaman untuk Admin, Kurikulum, Kepala Sekolah melihat rekap agenda yang diisi oleh Guru</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="row m-0 p-0">
            <div class="col-12 col-sm-12 col-md-8 col-lg-6 col-xl-6">
                <label for="minggu-ke">Minggu Ke</label>
                <select class="form-control" data-toggle="select" name="minggu-ke" id="minggu-ke">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($tanggal as $item => $value)
                        @php
                            $getVal = "";
                            $count = count($value);
                            $i = 0;
                            $tanggalVal = "";
                        @endphp
                        @foreach ($value as $elm)
                            @php
                                if($i === 0) {
                                    $tanggalVal .= date('d M Y', strtotime($elm->tanggal))." - ";
                                } else if($i == $count - 1) {
                                    $tanggalVal .= date('d M Y', strtotime($elm->tanggal));
                                }
                                $getVal .= $elm->uuid.",";
                                $i++;
                            @endphp
                        @endforeach
                        @php
                            $getVal = substr($getVal,0,-1);
                        @endphp
                        <option value="{{$getVal}}">{{$item}} ({{$tanggalVal}})</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p>Rekap Agenda Per Guru</p>
        <div class="table-responsive">
            <table class="table table-bordered table-striped fs-12">
                <thead>
                    <tr>
                        <td rowspan="2" width="5%">No</td>
                        <td rowspan="2" width="25%">Nama Guru</td>
                        <td colspan="5" width="60%" class="tanggal-table">Tanggal</td>
                        <td rowspan="2" width="10%">#</td>
                    </tr>
                    <tr id="table-tanggal">

                    </tr>
                </thead>
                <tbody id="table-body">

                </tbody>
            </table>
        </div>
    </div>
    <script>
        $('#minggu-ke').change(function() {
            var minggu = $(this).val();
            loading();
            $.ajax({
                type: "GET",
                url : "{{route('agenda.getTanggal')}}",
                data: {minggu : minggu},
                success: function(data) {
                    var tableBody = "";
                    var tableTanggal = "";
                    var agenda = data.agenda;
                    if(data.tanggal !== undefined) {
                        var tanggal = data.tanggal;
                        var jumlahTanggal = Object.keys(tanggal).length;
                        $('.tanggal-table').attr('colspan',jumlahTanggal);
                        tanggal.forEach(function(item) {
                            tableTanggal += `
                                <td width="5%">${moment(item.tanggal).format('ddd')}</td>
                            `;
                        });
                        $('#table-tanggal').html(tableTanggal);
                    }
                    if(data.guru !== undefined) {
                        var guru = data.guru;
                        var no = 1;
                        guru.forEach(function(item) {
                            var jumlahAgenda = "";
                            data.tanggal.forEach((element) => {

                                if(agenda[element.tanggal+"."+item.guru.uuid] !== undefined) {
                                    jumlahAgenda += "<td>"+Object.keys(agenda[element.tanggal+"."+item.guru.uuid]).length+"</td>";
                                } else {
                                    jumlahAgenda += "<td>0</td>";
                                }
                            });
                            tableBody += `
                                <tr>
                                    <td>${no}</td>
                                    <td>${item.guru.nama}</td>
                                    ${jumlahAgenda}
                                    <td><button class="btn btn-sm btn-warning lihat-agenda" data-guru='${item.guru.uuid}'>
                                        <i class="fas fa-arrow-right"></i>
                                    </button></td>
                                </tr>
                            `;
                            no++;
                        });
                        $('#table-body').html(tableBody);
                        removeLoading();
                    }
                },
                error: function(data) {
                    console.error(data.responseJSON);
                }
            })
        });
        $('.table').on('click','.lihat-agenda',function() {
            var uuid = $(this).data('guru');
            var mingguKe = $('#minggu-ke').val();

            var url = "{{route('agenda.rekap.guru',['idGuru' => ':id','idMinggu' => ':id2'])}}";
            url = url.replace(':id',uuid).replace(':id2',mingguKe);
            window.location.href = url;
        });
    </script>
@endsection
