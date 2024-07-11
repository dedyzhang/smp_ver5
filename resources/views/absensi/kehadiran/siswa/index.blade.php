@extends('layouts.main')

@section('container')
{{Breadcrumbs::render('absensi-kehadiran-siswa')}}
<div class="body-contain-customize col-12">
    <h5><b>Absensi Kehadiran</b></h5>
    <p>Halaman ini peruntukkan untuk user melakukan absensi kehadiran dan pulang</p>
</div>
@if ($adaTanggal == "ada")
<div class="body-contain-customize col-12 mt-3">
    <p class="mb-0">Tanggal : {{date('d F Y')}}</p>
</div>
<div class="body-contain-customize col-12 mt-3 time-countdown text-center
@if ($kehadiran === "")
    bg-primary-subtle
@else
    bg-success-subtle
@endif">
    <h1 style="font-size: 50px"><b id="time">{{$kehadiran->waktu}}</b></h1>
</div>
<div class="body-contain-customize col-12 mt-3 d-grid d-sm-grid d-lg-none d-md-none d-xl-none">
    @if ($kehadiran === "")
    <a href="{{route('absensi.kehadiran.siswa.hadir','datang')}}" class="btn btn-sm btn-success">Absen Kehadiran</a>
    @else
    <i class="text-center">Kamu Sudah Melakukan Absensi Hari Ini. Terima Kasih</i>
    @endif
</div>
<script>
    @if ($kehadiran === "")
        $(document).ready(function() {
        function startTime() {
            const today = new Date();
            let h = today.getHours();
            let m = today.getMinutes();
            let s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            $('#time').html(h+":"+m+":"+s);
        }

        function checkTime(i) {
            if (i < 10) {i="0" + i};
            return i;
        }
        setInterval(startTime, 1000); })
    @endif

</script>
@else
<div class="body-contain-customize col-12 mt-3">
    <p>Maaf Tanggal belum ditambahkan oleh Admin, maupun Kurikulum. Contact Admin maupun Kurikulum untuk mengakses fitur
        absensi</p>
</div>
@endif

@endsection
