@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Jadwal Ver {{$versi->versi}}</h5>
        <p>Pengaturan Jadwal seperti penambahan Waktu dan pengaturan Jam Mata Pelajaran</p>
    </div>
    <div class="body-contain-customize col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex mt-3 gap-2">
        <a href="{{route('jadwal.waktu.index',$versi->uuid)}}" class="btn btn-sm btn-warning text-warning-emphasis"><i class="fas fa-clock"></i> Atur Waktu</a>
        <a class="btn btn-sm btn-success generate-jadwal"><i class="fas fa-recycle"></i> Generate Jadwal</a>
    </div>
    <div class="body-contain-customize col-12">
        <ul>
        @foreach ($pelajaran as $item)
            <li>{{$item->pelajaran_singkat}}</li>
        @endforeach
        </ul>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        @foreach ($hari as $hariItem)
            <p class="fs-12">{{$hariItem->nama_hari}}</p>
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
                                        <td class="editable" data-kelas="{{$kelasItem->uuid}}" contenteditable="true"></td>
                                    @elseif (isset($array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]) && $array_jadwal[$hariItem->uuid.".".$waktuItem->uuid.".".$kelasItem->uuid]['id_pelajaran'] !== "")
                                        <td></td>
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
                        console.log(data);
                    },
                    error: function(data) {
                        removeLoading();
                        console.log(data.responseJSON);
                    }
                });

            };
            cConfirm("Perhatian","Yakin untuk generate Jadwal<p class='fs-12'>Setelah generate maka jadwal sudah tidak bisa menambahkan waktu lagi</p>",GenerateJadwal);
        });
        $('table').on('blur','.editable',function() {
            var pelajaran = $(this).text();
            $(this).css({
                'background':'url({{asset('img/loading.gif')}}) right no-repeat',
                'background-size': 'contain',
                'background-origin': 'content-box'
            });
            $.ajax({
                type: :"post",
            });
        });
    </script>
@endsection
