@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Jadwal Ver {{$versi->versi}}</h5>
        <p>Pengaturan Jadwal seperti penambahan Waktu dan pengaturan Jam Mata Pelajaran</p>
    </div>
    <div class="body-contain-customize col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex mt-3 gap-2">
        <a href="{{route('jadwal.waktu.index',$versi->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-clock"></i> Atur Waktu</a>
        <button class="btn btn-sm btn-success generate-jadwal"><i class="fas fa-recycle"></i> Generate Jadwal</button>
        <button class="btn btn-sm btn-primary toggle-pengisian tutup"><i class="fas fa-lock-open"></i> Buka Pengisian</button>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <H6>Petunjuk Pengisian</H6>
        <ol>
            <li>Pastikan untuk mengatur waktu jadwal sebelum memulai proses penginputan jadwal</li>
            <li>Klik Tombol Generate Jadwal untuk mengatur jadwal sesuai dengan waktu yang sudah diinput. Mengenerate Jadwal setelah menginput jadwal dapat menghapus jadwal yang sudah diinput.</li>
            <li>Klik Tombol Buka Pengisian untuk mengisi jadwal</li>
            <li>Ketik tabel jadwal pelajaran sesuai dengan List Mata Pelajaran yang tertera dibawah ini</li>
            <li>Untuk Jadwal Spesial seperti upacara,senam, dll. Ketik "S." disusul dengan keterangan jadwal spesial berikut. Contohnya <i><b>S.Upacara</b></i></li>
        </ol>
        <p class="mt-2 mb-1"><b>List Mata Pelajaran</b></p>
        <ul class="d-inline-flex m-0 p-0">
        @foreach ($pelajaran as $item)
            <li class="d-block" style="width: 80px">{{$item->pelajaran_singkat}}</li>
        @endforeach
        </ul>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        @foreach ($hari as $hariItem)
            <p class="fs-14"><b>{{$hariItem->nama_hari}}</b></p>
            <div class="table-responsive">
                <table class="table table-bordered fs-11">
                    <thead>
                        <tr>
                            <td width="3%" style="min-width: 30px">No</td>
                            <td width="10%" style="min-width: 100px">Waktu</td>
                            @foreach ($kelas as $kelasItem)
                            <td width="5%" style="min-width: 50px">{{$kelasItem->tingkat.$kelasItem->kelas}}</td>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($waktu as $waktuItem)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$waktuItem->waktu_mulai."-".$waktuItem->waktu_akhir}}</td>
                                @foreach ($kelas as $kelasItem)
                                    @if(isset($array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]) && $array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['id_pelajaran'] === "")
                                        @if ($array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['jenis'] === "spesial")
                                            <td class="editable spesial bg-info-subtle text-center" data-spesial="{{$array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['spesial']}}" data-kelas="{{$kelasItem->uuid}}" data-uuid="{{$array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['uuid']}}" contenteditable="false">{{$array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['spesial']}}</td>
                                        @else
                                            <td class="editable" data-kelas="{{$kelasItem->uuid}}" data-uuid="{{$array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['uuid']}}" contenteditable="false"></td>
                                        @endif

                                    @elseif (isset($array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]) && $array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['id_pelajaran'] !== "")
                                        <td class="editable" data-bs-toggle="tooltip" data-bs-title="{{$array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['guru']}}" data-bs-placement="top" data-kelas="{{$kelasItem->uuid}}" data-uuid="{{$array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['uuid']}}" contenteditable="false">{{$array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['pelajaran_singkat']}}</td>
                                    @else
                                        <td></td>
                                    @endif

                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach

    </div>
    <script>
        var count = 0;
        var td = "";
        var spesial = "";
        $(document).ready(function() {
            $('.spesial').each(function() {
                if(count == 0) {
                    spesial = $(this).data('spesial');
                    td = this;
                    count++;
                } else {
                    if(spesial == $(this).data('spesial')) {
                        count++;
                        $(td).attr('colspan',count);
                        $(this).hide();
                    } else {
                        spesial = $(this).data('spesial');
                        count = 1;
                        td = this;
                    }
                }
            });
        });
        $('.toggle-pengisian').click(function() {
            if($(this).hasClass('tutup')) {
                $('.editable').attr('contenteditable',true);
                $(this).removeClass('tutup btn-info').addClass('buka btn-danger');
                $(this).html('<i class="fas fa-lock"></i> Tutup Pengisian');
            } else {
                $('.editable').attr('contenteditable',false);
                $(this).addClass('tutup btn-info').removeClass('buka btn-danger');
                $(this).html('<i class="fas fa-lock-open"></i> Buka Pengisian');
            }
        })
        $('.generate-jadwal').click(function() {
            var GenerateJadwal = () => {
                var url = "{{route('jadwal.generate',':id')}}";
                url = url.replace(':id','{{$versi->uuid}}');
                loading();
                $.ajax({
                    type: "post",
                    url: url,
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        removeLoading();
                        cAlert('green',"Berhasil","Jadwal berhasil digenerate",true);
                    },
                    error: function(data) {
                        removeLoading();
                        console.log(data.responseJSON);
                    }
                });

            };
            cConfirm("Perhatian","Yakin untuk generate Jadwal<p class='fs-12'>Setelah generate maka jadwal sudah tidak bisa menambahkan waktu lagi. Data Jadwal Lama akan dihapus</p>",GenerateJadwal);
        });
        var textAwal;
        $('table').on('focus','.editable',function(){
            textAwal = $(this).text();
        });
        $('table').on('blur','.editable',function() {
            $('.editable').prop('contenteditable',false);
            var ini = this;
            var pelajaran = $(this).text();
            var uuid = $(this).data('uuid');
            var url = "{{route('jadwal.update',':id')}}";
            url = url.replace(':id',"{{$versi->uuid}}");
            if(textAwal != pelajaran) {
                $(this).css({
                    'background':'url({{asset('img/loading.gif')}}) right no-repeat',
                    'background-size': 'contain',
                    'background-origin': 'content-box'
                });
                $.ajax({
                    type:"put",
                    url: url,
                    data : {pelajaran : pelajaran,uuid: uuid,old: textAwal},
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        $('.editable').prop('contenteditable',true);
                        if(data.success == false) {
                            $(ini).css({
                                'background':'url({{asset('img/error.png')}}) right no-repeat',
                                'background-size': 'contain',
                                'background-origin': 'content-box',
                            });
                            $(ini).addClass('bg-danger-subtle text-danger');
                            $(ini).attr('data-bs-toggle','tooltip').attr('data-bs-title',data.message).attr('data-bs-placement','top');
                            const tooltip = new bootstrap.Tooltip(ini);
                        } else if(data.success == true) {
                            $(ini).css({
                                'background':'none',
                            });
                            $(ini).removeClass('bg-danger-subtle text-danger bg-info-subtle');
                            if(data.spesial !== undefined) {
                                $(ini).text(data.spesial);
                                $(ini).addClass('bg-info-subtle');
                                if(bootstrap.Tooltip.getInstance(ini)) {
                                    const tooltip = bootstrap.Tooltip.getInstance(ini);
                                    tooltip.dispose();
                                }
                            } else if(data.reload !== undefined) {
                                cAlert("green","berhasil","Jadwal Spesial berhasil dihapus",true);
                            } else {
                                if(pelajaran == "") {
                                    if(bootstrap.Tooltip.getInstance(ini)) {
                                        const tooltip = bootstrap.Tooltip.getInstance(ini);
                                        tooltip.dispose();
                                    }
                                } else {
                                    if(bootstrap.Tooltip.getInstance(ini)) {
                                        const tooltip = bootstrap.Tooltip.getInstance(ini);
                                        tooltip.setContent({ '.tooltip-inner': data.guru });
                                    } else {
                                        $(ini).attr('data-bs-toggle','tooltip').attr('data-bs-title',data.guru).attr('data-bs-placement','top');
                                        const tooltip = new bootstrap.Tooltip(ini);
                                    }
                                }
                            }
                        }

                    },
                    error: function(data) {
                        console.log(data.responseJSON);
                        $('.editable').prop('contenteditable',true);
                    }
                });
            } else {
                $('.editable').prop('contenteditable',true);
            }
        });
        $('table').on('keydown','.editable',function(e){
            if(e.keyCode == 13) {
                e.preventDefault();
            }
        });
    </script>
@endsection
