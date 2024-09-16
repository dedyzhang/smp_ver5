@extends('layouts.main')

@section('container')
    <div class='body-contain-customize col-12'>
        <h5><b>Event Sekolah</b></h5>
        <p>Halaman ini diperuntukkan bagi warga sekolah dan berguna untuk melihat serta mengatur berbagai event di dalam sekolah, sehingga memudahkan pengelolaan kegiatan secara terorganisir.</p>
    </div>
    @if(session('success'))
        <div class="body-contain-customize col-12 mt-3">
            <div class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3" role="alert">
                <i class="bi flex-shrink-0 me-2 fa-solid fa-check" aria-label="Success:"></i>
                <div>
                    <strong>Sukses !</strong> {{session('success')}}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @elseif (session('error'))

    @endif
    <div class='body-contain-customize col-12 mt-3'>
        <p><b>Detail Event</b></p>
        @if($guru->uuid == $event->id_pengajuan)
            <div class="text-end gap-2">
                <a href="{{route('event.edit',$event->uuid)}}" class="btn btn-sm btn-warning"><i class="fas fa-pencil"></i></a>
                <button data-uuid="{{$event->uuid}}" class="btn btn-sm btn-danger delete-event"><i class="fa-regular fa-trash-can"></i></button>
            </div>
        @endif
        <div class="row m-0 p-0">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <p class="m-0"><b><i class="fa-solid fa-calendar me-1"></i> Judul Event</b></p>
                <p class="text-justify ms-4">{{$event->judul}}</p>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <p class="m-0"><b><i class="fa-solid fa-user me-1"></i> Nama Pengajuan</b></p>
                <p class="text-justify ms-4">{{$event->guru->nama}}</p>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <p class="m-0"><b><i class="fa-regular fa-clock me-1"></i> Waktu Mulai</b></p>
                <p class="text-justify ms-4">{{date('d F Y, H:i',strtotime($event->waktu_mulai))}}</p>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <p class="m-0"><b><i class="fa-solid fa-clock me-1"></i> Waktu Akhir</b></p>
                <p class="text-justify ms-4">{{date('d F Y, H:i',strtotime($event->waktu_akhir))}}</p>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <p class="m-0"><b><i class="fa-solid fa-clock me-1"></i> Ruangan</b></p>
                @php
                    $ruangan = explode(',',$event->id_ruang);
                @endphp
                <ol>
                    @foreach ($ruangan as $ruang)
                        <li class="ms-2">{{$ruang_array[$ruang]}}</li>
                    @endforeach
                </ol>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <p class="m-0"><b><i class="fa-solid fa-outdent me-1"></i> Deskripsi</b></p>
                <div class="ms-4">
                    {!! $event->deskripsi !!}
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.delete-event').click(function() {
            var uuid = $(this).data('uuid');

            var hapusEvent = () => {
                loading();
                var route = "{{route('event.delete',':id')}}",
                route = route.replace(':id',uuid);
                $.ajax({
                    type: "delete",
                    url: route,
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function(data) {
                        removeLoading();
                        var route2 = "{{route('event.index')}}";
                        cAlert("green","Sukses","Sukses Menghapus Event",false,route2);
                    }
                })
            }
            cConfirm("Perhatian","Apakah anda yakin untuk menghapus event ini",hapusEvent);
        });
    </script>
@endsection
