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
    <div class="body-contain-customize col-12 mt-3">
        <p>Kalender Event Sekolah</p>
        <div id="eventsekolah"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('eventsekolah');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
            });
            calendar.render();
        });
    </script>
@endsection
