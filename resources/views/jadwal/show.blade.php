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
    <div class="body-contain-customize col-12 mt-3">
        <div class="row m-0 p-0">
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <p>Jenis Jadwal</p>
                <div class="form-check form-check-inline">
                    <label for="mapel" class="form-check-label">Mapel</label>
                    <input type="radio" name="jenis" id="mapel" value="mapel" class="form-check-input">
                </div>
                <div class="form-check form-check-inline">
                    <label for="spesial" class="form-check-label">Spesial</label>
                    <input type="radio" name="jenis" id="spesial" value="spesial" class="form-check-input">
                </div>
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mapel-opsi" style="display: none">
                <label for="pelajaran" class="mb-2">Pelajaran</label>
                <select class="form-control" name="pelajaran" id="pelajaran">
                    <option value="">Pilihlah Salah Satu</option>
                    @foreach ($pelajaran as $plj)
                        <option value="{{$plj->uuid}}">{{$plj->pelajaran_singkat}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 spesial-opsi" style="display: none">
                <label for="spesial" class="mb-2">Spesial</label>
                <input type="text" class="form-control" name="spesial" id="spesial" placeholder="Masukkan Deskripsi Mapel">
            </div>
        </div>
        <div class="row m-0 mt-3 p-0">
            <div class="col-12">
                <button class="btn btn-sm btn-warning simpan-mapel"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>
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
                                        <td><button data-kelas="{{$kelasItem->uuid}}" disabled class="btn btn-sm btn-success tambah-jadwal"><i class="fas fa-plus"></i></button></td>
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
        $('input[name="jenis"]').change(function() {
            var jenis = $('input[name="jenis"]:checked').val();
            // alert(jenis);
            if(jenis == "mapel") {
                $('.mapel-opsi').css("display","block");
                $('.spesial-opsi').css("display","none");
            } else {
                $('.mapel-opsi').css("display","none");
                $('.spesial-opsi').css('display','block');
            }
        });
        $('.simpan-mapel').click(function() {
            var jenis = $('input[name="jenis"]:checked').val();
            $('.tambah-jadwal').prop('disabled',true);
            if(jenis == "mapel") {
                var mapel = $('#pelajaran').val();
                if(mapel == "") {
                    oAlert("blue","Perhatian","pelajaran tidak boleh kosong");
                } else {
                    loading();
                    url = "{{route('jadwal.showNgajar',':id')}}";
                    url = url.replace(':id',mapel);
                    $.ajax({
                        type: "get",
                        url: url,
                        headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                        success: function(data) {
                            var pelajaran = data;
                            pelajaran.forEach(element => {
                                var ngajarKelas = element.kelas.uuid;
                                $('.tambah-jadwal').each(function() {
                                    var kelas = $(this).data('kelas');
                                    if(ngajarKelas == kelas) {
                                        $(this).prop('disabled',false);
                                    }
                                })
                            });
                            removeLoading();
                        },
                        error: function(data) {
                            console.log(data.responseJSON.message);
                        }
                    })
                }
            }
        });
        $('.tambah-jadwal').click(function() {
            var pelajaran = $('#pelajaran option:selected').text();
            var pelajaranID = $('#pelajaran').val();
            var jenis = $('input[name="jenis"]:checked').val();

            $(this).html(pelajaran).attr('class','btn btn-sm btn-danger hapus-jadwal');
        })
    </script>
@endsection
