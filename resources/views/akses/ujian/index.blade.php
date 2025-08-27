@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-md-12 col-lg-12 col-sm-12 mt-3">
        <h5><b>Akses Ujian</b></h5>
        <p>Halaman ini diperuntukkan oleh admin, Kurikulum dan Kepala Sekolah untuk membuka akses Ujian Guru</p>
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex mt-3 gap-2">
        <button class="btn btn-sm btn-success buka-semua-akses">
            <i class="fas fa-door-open"></i> Buka Semua Akses
        </button>
        <button class="btn btn-sm btn-danger tutup-semua-akses">
            <i class="fas fa-door-closed"></i> Tutup Semua Akses
        </button>
    </div>
    <div class="clearfix"></div>
    @forelse ($guru as $item)
    @php
        $cek = in_array($item->uuid, $aksesGuru);
    @endphp
        <div class="col-md-6 col-lg-4 col-xl-3 col-sm-6 col-12 mt-3 pe-2 ps-0">
            <div class="card border-light rounded-4">
                <div class="card-body">
                    @if($item->jk == "l")
                        <img src="{{asset('img/teacher-boy.png')}}" style="width: 50px; height:50px" />
                    @else
                        <img src="{{asset('img/teacher-girl.png')}}" style="width: 50px; height:50px" />
                    @endif
                    <p class="m-0 p-0 mt-2">{{Str::limit($item->nama,18)}}</p>
                    <p class="m-0 p-0 fs-12"><i><strong>{{$item->users->access}}</strong></i></p>

                    <div class="button-place mt-3">
                        @if ($cek)
                            <button type="button" data-id="{{$item->uuid}}" class="btn btn-danger btn-sm tutup-akses"><i class="fa-solid fa-door-closed"></i></button>
                        @else
                            <button type="button" data-id="{{$item->uuid}}" class="btn btn-primary btn-sm buka-akses"><i class="fa-solid fa-door-open"></i></button>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @empty

    @endforelse

    <script>
        $('.buka-akses').click(function() {
            var id = $(this).data('id');
            var url = "{{ route('akses.ujian.buka',':id') }}";
            url = url.replace(':id', id);
            loading();
            $.ajax({
                type: "post",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                success : function(data) {
                    if(data.status == 'success') {
                        removeLoading();
                        location.reload();
                        // console.log(data);
                    } else {
                        oAlert('red','Error',data.message);
                        removeLoading();
                    }

                },error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        });
        $('.tutup-akses').click(function() {
            var id = $(this).data('id');
            var url = "{{ route('akses.ujian.tutup',':id') }}";
            url = url.replace(':id', id);
            loading();
            $.ajax({
                type: "post",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                success : function(data) {
                    if(data.status == 'success') {
                        removeLoading();
                        location.reload();
                    } else {
                        oAlert('red','Error',data.message);
                        removeLoading();
                    }

                },error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        });
        $('.buka-semua-akses').click(function() {
            var action = function() {
                loading();
                $.ajax({
                    type: "post",
                    url: "{{ route('akses.ujian.buka.semua') }}",
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success : function(data) {
                        // console.log(data);
                        removeLoading();
                        location.reload();
                    },error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }
            cConfirm("Perhatian","Apakah anda yakin ingin membuka semua akses ujian guru?",action);
        });
        $('.tutup-semua-akses').click(function() {
            var action = function() {
                loading();
                $.ajax({
                    type: "post",
                    url: "{{ route('akses.ujian.tutup.semua') }}",
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success : function(data) {
                        // console.log(data);
                        removeLoading();
                        location.reload();
                    },error: function(data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }
            cConfirm("Perhatian","Apakah anda yakin ingin menutup semua akses ujian guru?",action);
        });
    </script>
@endsection
