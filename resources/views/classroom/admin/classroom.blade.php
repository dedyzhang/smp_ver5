@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Classroom</b></h5>
         <p>Classroom adalah halaman bagi admin untuk mengecek data classroom yang sudah diassign oleh guru dan jawaban yang sudah dijawab oleh siswa.</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5">
        <table class="table table-striped fs-13">
            <tr>
                <td width="30%">Pelajaran</td>
                <td width="5%">:</td>
                <td>{{$ngajar->pelajaran->pelajaran}}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{$ngajar->kelas->tingkat.$ngajar->kelas->kelas}}</td>
            </tr>
            <tr>
                <td>Guru</td>
                <td>:</td>
                <td>{{$ngajar->guru->nama}}</td>
            </tr>
            <tr>
                <td>KKTP</td>
                <td>:</td>
                <td>{{$ngajar->kkm}}</td>
            </tr>
        </table>
    </div>
    <div class="row m-0 p-0">
        <div class="body-contain-customize mt-3 col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex col-xl-auto d-xl-flex gap-2">
            <a href="{{route('walikelas.classroom.archived',$ngajar->uuid)}}" class="btn btn-sm btn-danger"><i class="fas fa-box-archive"></i> Lihat Arsip</a>
        </div>
    </div>
    <div id="aspect-content">
        @foreach ($classroom as $item)
            <div class="aspect-tab @if ($item->status == "save")
                bg-warning-subtle
            @endif">
                <input id="item-{{$item->uuid}}" type="checkbox" class="aspect-input" name="aspect">
                <label for="item-{{$item->uuid}}" class="aspect-label"></label>
                <div class="aspect-content">
                    <div class="aspect-info">
                        <div class="chart-pie">
                            <span class="chart-pie-count text-primary-emphasis fs-25"><i class="fa-solid fa-book"></i></span>
                        </div>
                        <div class="aspect-name">
                            <p class="m-0 text-secondary"><b>{{ucwords($item->jenis)}}</b></p>
                            <p class="m-0">{{$item->judul}}</p>
                        </div>
                    </div>
                </div>
                <div class="aspect-tab-content">
                    <div class="sentiment-wrapper">
                        <div>
                            <div>
                                <div class="opinion-header fs-10 text-danger mb-0">TANGGAL POST : {{date('d F Y, H:i:s',strtotime($item->tanggal_post))}}</div>
                                @if($item->tanggal_due !== null)
                                <div class="opinion-header fs-10 text-success">TANGGAL DUE : {{date('d F Y, H:i:s',strtotime($item->tanggal_due))}}</div>
                                @endif
                                <div class="mt-3">
                                    <div>{{$item->deskripsi}}</div>
                                    <div class="token-place mt-3">
                                        TOKEN : <span class="token-cycle">{{$item->token}}</span>
                                    </div>
                                    <div class="button-place mt-3 gap-2 d-grid d-sm-grid d-md-flex d-xl-flex d-lg-flex">
                                        <a href="{{route('penilaian.classroom.preview',['uuid' => $ngajar->uuid,'uuidClassroom' => $item->uuid])}}" class="btn btn-sm btn-warning text-warning-emphasis">
                                            <i class="fas fa-pencil"></i> Preview
                                        </a>
                                        <button class="btn btn-sm btn-primary reset-token" data-uuid="{{$item->uuid}}">
                                            <i class="fas fa-recycle"></i> Reset Token
                                        </button>
                                        <button class="btn btn-sm btn-danger tutup-token" data-uuid="{{$item->uuid}}">
                                            <i class="fas fa-times"></i> Tutup Token
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <script>
            $('.reset-token').click(function() {
                var uuid = $(this).data('uuid');
                var url = "{{route('classroom.resetToken',':id')}}";
                url = url.replace(':id',uuid);
                loading();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {token : 'reset'},
                    headers: {'X-CSRF-TOKEN' : '{{csrf_token()}}'},
                    success: function(data) {
                        $('.token-cycle').text(data.token);
                        removeLoading();
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            });
            $('.tutup-token').click(function() {
                var uuid = $(this).data('uuid');
                var url = "{{route('classroom.resetToken',':id')}}";
                url = url.replace(':id',uuid);
                loading();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {token : 'tutup'},
                    headers: {'X-CSRF-TOKEN' : '{{csrf_token()}}'},
                    success: function(data) {
                        $('.token-cycle').text(data.token);
                        removeLoading();
                    },
                    error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                })
            });
        </script>
    </div>
@endsection
