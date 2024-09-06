@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('absensi');}}
    <div class="body-contain-customize col-12">
        <h5><b>Absensi Guru, Staf dan Siswa</b></h5>
        <p class="m-0">Halaman untuk mengatur absensi siswa , guru dan staf serta melihat data absensi tersebut.</p>
    </div>
    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex gap-2">
        <a href="{{route('absensi.create')}}" class="btn btn-warning btn-sm text-warning-emphasis"><i class="fas fa-calendar-plus"></i> Tambah Tanggal</a>
    </div>
    <div class="body-contain-customize mt-3 col-12">
        <div id="calendarAbsensi"></div>
    </div>
    <div class="body-contain-customize mt-3 col-12">
        <div class="instruction row m-0 p-0 gap-1 fs-12">
            <p class="p-0 m-0"><b>Keterangan Kalendar</b></p>
            <ul class="ms-3">
                <li>S<i>(1 atau 2) - (Keterangan Semester)</i></li>
                <li>V (1,2,...) - (Keterangan Versi Jadwal yang digunakan)</li>
                <li>Keterangan Warna
                    <ul style="margin-left: -13px">
                        <li><i style="background-color:#FFC470 ;padding:0 8px; border-radius:100%; margin-right: 15px"></i> Ada Tanggal, Agenda dan Siswa</li>
                        <li><i style="background-color:#FA7070; padding:0 8px; border-radius:100%; margin-right: 15px"></i> Ada Agenda</li>
                        <li><i style="background-color:#7EA1FF; padding:0 8px; border-radius:100%; margin-right: 15px"></i> Ada Siswa</li>
                        <li><i style="background-color:#007F73; padding:0 8px; border-radius:100%; margin-right: 15px"></i> Ada Tanggal</li>
                    </ul>
                </li>
                <li><b><i>Klik Kotak di kalender untuk hapus tanggal</i></b></li>
            </ul>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendarAbsensi');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: @json($event),
                eventClick: function(info) {
                    var tanggal = info.event.start;
                    tanggalNew = moment(tanggal).format('YYYY-MM-DD');

                    var hapusTanggal = () => {
                        loading();
                        $.ajax({
                            type: "delete",
                            data: {tanggal: tanggalNew},
                            url: "{{route('absensi.destroy')}}",
                            headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                            success: function(data) {
                                removeLoading();
                                cAlert('green',"Berhasil","Tanggal berhasil dihapus",true);
                            },
                            error: function(data) {
                                console.log(data.responseJSON.message);
                            }
                        })
                    }

                    cConfirm("Perhatian","Yakin untuk menghapus tanggal bersangkutan",hapusTanggal);
                }
            });
            calendar.render();
        });
    </script>
@endsection
