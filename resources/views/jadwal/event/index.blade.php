@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5><b>Event Sekolah</b></h5>
        <p>Halaman ini diperuntukkan bagi warga sekolah dan berguna untuk melihat serta mengatur berbagai event di dalam sekolah, sehingga memudahkan pengelolaan kegiatan secara terorganisir.</p>
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-block d-lg-block d-xl-block mt-3">
        <a href="{{route('event.create')}}" class="btn btn-sm btn-warning text-warning-emphasis">
            <i class="fas fa-calendar-plus"></i> Tambah Event
        </a>
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
    <div class="body-contain-customize col-12 mt-3">
        <p>Kalender Event Sekolah</p>
        <div id="eventsekolah"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('eventsekolah');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: @json($kegiatan),
                eventClick: function(info) {
                    var id = info.event.id;
                    var route = "{{route('event.show',':id')}}";
                    route = route.replace(':id',id);

                    window.location.href=route;
                },
            });
            calendar.render();
        });
    </script>
@endsection
