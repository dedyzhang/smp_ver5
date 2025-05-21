<div class="sidebar">
    <div class="sidebar-contain">
        <div class="sidebar-logo-contain">
            <img src="{{ asset('img/logo-rounded.png') }}" class="logo-main">
            @php
                if($setting && $setting->jenis == "nama_sekolah") {
                    $namaSekolah = explode(' ',$setting->nilai);
                    $firstName = $namaSekolah[0];
                    $secondName = implode(' ',array_slice($namaSekolah, 1));
                }
            @endphp
            <h5 class="logo-title"><b>{{$firstName}}</b> {{$secondName}}</h5>
            <i class="app-version">5.1.4</i>
        </div>
        <ul class="menu">
            <li class="menu-list"><a href="/home"> <ion-icon src="{{asset('img/icons/home.svg')}}"></ion-icon> Dashboard</a></li>

            {{-- Menu Guru --}}
            @can('admin')
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-data" class="menu-title" data-bs-toggle="collapse">
                    <ion-icon src="{{asset('img/icons/folder-open.svg')}}"></ion-icon>
                    Data Sekolah
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-data">
                    <li class="submenu-list"><a href="{{ route('guru.index') }}"> Data PTK</a></li>
                    <li class="submenu-list"><a href="{{ route('kelas.index') }}"> Data Kelas</a></li>
                    <li class="submenu-list"><a href="{{ route('pelajaran.index') }}"> Data Pelajaran</a></li>
                    <li class="submenu-list"><a href="{{ route('siswa.index') }}"> Data Siswa</a></li>
                </ul>
            </li>
            @endcan

            {{-- Absensi --}}
            @canany(['admin','kurikulum','guru','kesiswaan','sapras','kepalasekolah'])
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-absensi" class="menu-title" data-bs-toggle="collapse">
                    <ion-icon src="{{asset('img/icons/calendar.svg')}}"></ion-icon>
                    Absensi PTK
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-absensi">
                    <li class="submenu-list"><a href="{{ route('absensi.kehadiran') }}"> Absen Kehadiran</a></li>
                    <li class="submenu-list"><a href="{{ route('absensi.kehadiran.histori') }}"> Lihat Absensi</a></li>
                    @canany(['admin','kurikulum'])
                    <li class="submenu-list"><a href="{{ route('absensi.index') }}"> Atur Tanggal</a></li>
                    @endcan
                </ul>
            </li>
            @endcanany
            @can('siswa')
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-absensi" class="menu-title" data-bs-toggle="collapse">
                    <ion-icon src="{{asset('img/icons/calendar.svg')}}"></ion-icon>
                    Absensi Siswa
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-absensi">
                    <li class="submenu-list"><a href="{{ route('absensi.siswa.kehadiran') }}"> Absen Kehadiran</a></li>
                    <li class="submenu-list"><a href="{{ route('absensi.siswa.kehadiran.histori') }}"> Lihat Absensi</a>
                    </li>
                </ul>
            </li>
            @endcan

            {{-- Buku Guru --}}
            @canany(['kurikulum', 'guru','kesiswaan','sapras'])
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-guru" class="menu-title" data-bs-toggle="collapse">
                    <ion-icon src="{{asset('img/icons/book-2.svg')}}"></ion-icon>
                    Buku Guru
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-guru">
                    <li class="submenu-list"><a href="{{ route('penilaian.perangkat.index') }}"> Perangkat Pembelajaran</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.kktp.index') }}"> KKTP</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.materi.index') }}"> Materi</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.formatif.index') }}"> Nilai Formatif</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.sumatif.index') }}"> Nilai Sumatif</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.pts.index') }}"> PTS</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.pas.index') }}"> PAS/T</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.rapor.index') }}"> Rapor</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.penjabaran.index') }}"> Penjabaran</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.guru.proyek.index') }}"> P5</a></li>
                    <li class="submenu-list"><a href="{{ route('poin.guru.index') }}"> Pengajuan Poin</a></li>
                </ul>
            </li>
            @endcan
            @canany(['admin','kurikulum','guru','kesiswaan','sapras','kepalasekolah'])
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-agenda" class="menu-title" data-bs-toggle="collapse">
                    <ion-icon src="{{asset('img/icons/notebook.svg')}}"></ion-icon>
                    Buku Agenda
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-agenda">
                    @canany(['kurikulum','guru','kesiswaan','sapras'])
                        <li class="submenu-list"><a href="{{ route('agenda.index') }}">Tambah Agenda</a></li>
                        <li class="submenu-list"><a href="{{ route('agenda.history') }}">Lihat Agenda</a></li>
                    @endcan
                    @canany(['admin','kurikulum','kepalasekolah'])
                        <li class="submenu-list"><a href="{{ route('agenda.rekap') }}">Rekap Agenda</a></li>
                        <li class="submenu-list"><a href="{{ route('agenda.batas') }}">Buku Batas</a></li>
                    @endcan
                </ul>
            </li>
            @endcan
            {{--Ujian--}}
            @canany(['admin','kurikulum','guru','kesiswaan','sapras','kepalasekolah','siswa'])
                <li class="menu-list"><a href="{{route('ujian.index')}}"> <ion-icon src="{{asset('img/icons/test.svg')}}"></ion-icon> Go To Ujian</a></li>
            @endcan
            {{-- Penilaian --}}
            @canany(['admin', 'kurikulum','kepalasekolah'])
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-pelajaran" class="menu-title" data-bs-toggle="collapse">
                    <ion-icon src="{{asset('img/icons/book-2.svg')}}"></ion-icon>
                    Penilaian
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-pelajaran">
                    <li class="submenu-list"><a href="{{ route('penilaian.admin.index') }}"> Semua Nilai</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.admin.pts') }}"> PTS</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.admin.pas') }}"> PAS/T</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.admin.rapor') }}"> Rapor</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.admin.manual') }}"> Rapor Manual</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.p5.index') }}"> Projek P5</a></li>
                    <li class="submenu-list"><a href="{{ route('perangkat.index') }}"> Perangkat Pembelajaran</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.classroom.index') }}"> Classroom</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.kelulusan.index') }}"> Kelulusan</a></li>
                </ul>
            </li>
            @endcan
            {{-- Ekskul --}}
            @canany(['admin','kurikulum','kepalasekolah'])
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-ekskul" class="menu-title" data-bs-toggle="collapse">
                    <ion-icon src="{{asset('img/icons/basketball.svg')}}"></ion-icon>
                    Ekskul
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-ekskul">
                    <li class="submenu-list"><a href="{{ route('ekskul.index') }}"> Data Ekskul</a></li>
                    <li class="submenu-list"><a href="{{ route('ekskul.nilai.index') }}"> Nilai Ekskul</a></li>
                </ul>
            </li>
            @endcan
            @canany(['guru','kesiswaan','sapras'])
            <li class="menu-list"><a href="{{route('ekskul.nilai.index')}}"> <ion-icon src="{{asset('img/icons/basketball.svg')}}"></ion-icon> Ekskul</a></li>
            @endcan
            {{-- Aturan --}}
            @canany(['admin','kesiswaan'])
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-aturan" class="menu-title" data-bs-toggle="collapse">
                    <ion-icon src="{{asset('img/icons/shield.svg')}}"></ion-icon>
                    Aturan
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-aturan">
                    @if($pemilihanAturan && $pemilihanAturan->jenis == "jenis_aturan" && $pemilihanAturan->nilai == "p3")
                        <li class="submenu-list"><a href="{{ route('p3.index') }}"> List P3</a></li>
                    @else
                        <li class="submenu-list"><a href="{{ route('aturan.index') }}"> Aturan</a></li>
                        <li class="submenu-list"><a href="{{ route('poin.index') }}"> Poin Siswa</a></li>
                        <li class="submenu-list"><a href="{{ route('temp.index') }}"> Pengajuan</a></li>
                    @endif
                </ul>
            </li>
            @endcan
            {{-- Sarana dan Prasarana --}}
            @canany(['admin','sapras'])
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                 <a href="#menu-sapras" class="menu-title" data-bs-toggle="collapse">
                    <ion-icon src="{{asset('img/icons/box.svg')}}"></ion-icon>
                    Sapras
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-sapras">
                    <li class="submenu-list"><a href="{{ route('ruang.index') }}"> Ruangan</a></li>
                    <li class="submenu-list"><a href="{{ route('penggunaan.index') }}"> Penggunaan Ruang</a></li>
                </ul>
            </li>
            @endcan
            @canany(['guru','kesiswaan','kepalasekolah','kurikulum'])
                <li class="menu-list"><a href="{{route('penggunaan.index')}}"> <ion-icon src="{{asset('img/icons/box.svg')}}"></ion-icon> Penggunaan Ruang</a>
            </li>
            @endcan

            {{-- Detail Informasi Siswa --}}
            @canany(['siswa','orangtua'])
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                 <a href="#menu-sapras" class="menu-title" data-bs-toggle="collapse">
                    <ion-icon src="{{asset('img/icons/info-square.svg')}}"></ion-icon>
                    Informasi Sekolah
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-sapras">
                    <li class="submenu-list"><a href="{{ route('detail.absensi.index') }}"> Absensi Siswa</a></li>
                    <li class="submenu-list"><a href="{{ route('detail.poin.index') }}"> Poin Siswa</a></li>
                    <li class="submenu-list"><a href="{{ route('detail.nilai.index') }}"> Penilaian</a></li>
                </ul>
            </li>
            @endcan

            {{-- Classroom --}}
            @canany(['guru','kesiswaan','sapras','kurikulum'])
            <li class="menu-list"><a href="{{route('classroom.index')}}"> <ion-icon src="{{asset('img/icons/ruler-pen.svg')}}"></ion-icon> Classroom</a>
            </li>
            @endcan
            @can('siswa')
            <li class="menu-list"><a href="{{route('classroom.siswa.index')}}"> <ion-icon src="{{asset('img/icons/ruler-pen.svg')}}"></ion-icon>
                    Classroom</a></li>
            @endcan

            {{-- Walikelas --}}
            @canany(['guru','kesiswaan','sapras','kurikulum'])
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-walikelas" class="menu-title" data-bs-toggle="collapse">
                    <ion-icon src="{{asset('img/icons/people-nearby.svg')}}"></ion-icon>
                    Walikelas
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-walikelas">
                    <li class="submenu-list"><a href="{{ route('walikelas.siswa') }}">Data Siswa</a></li>
                    <li class="submenu-list"><a href="{{ route('walikelas.absensi') }}">Absensi Siswa</a></li>
                    <li class="submenu-list"><a href="{{ route('walikelas.poin') }}">Poin Siswa</a></li>
                    <li class="submenu-list"><a href="{{ route('walikelas.classroom') }}">Classroom</a></li>
                    <li class="submenu-list"><a href="{{ route('walikelas.ruang') }}">Ruang Kelas</a></li>
                    <li class="submenu-list"><a href="{{ route('walikelas.nilai') }}">Nilai</a></li>
                    <li class="submenu-list"><a href="{{ route('walikelas.rapor') }}">Rapor Semester</a></li>
                </ul>
            </li>
            @endcan

            {{-- Sekretaris --}}
            @can('sekretaris')
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-aturan" class="menu-title" data-bs-toggle="collapse">
                    <ion-icon src="{{asset('img/icons/keyboard.svg')}}"></ion-icon>
                    Sekretaris
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-aturan">
                    <li class="submenu-list"><a href="{{ route('sekretaris.absensi') }}"> Absensi</a></li>
                    <li class="submenu-list"><a href="{{ route('sekretaris.poin') }}"> Poin Siswa</a></li>
                </ul>
            </li>
            @endcan

            {{-- Jadwal --}}
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-jadwal" class="menu-title" data-bs-toggle="collapse">
                    <ion-icon src="{{asset('img/icons/clock-circle.svg')}}"></ion-icon>
                    Jadwal Sekolah
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-jadwal">
                    <li class="submenu-list"><a href="{{ route('jadwal.index') }}"> Jadwal Pelajaran</a></li>
                    @canany(['admin','guru','kepalasekolah','kesiswaan','kurikulum','sapras'])
                        <li class="submenu-list"><a href="{{route('event.index')}}"> Event Sekolah</a></li>
                    @endcan
                    {{-- <li class="submenu-list"><a href="{{ route('penilaian.admin.index') }}"> Jadwal Akademik</a>
                    </li> --}}
                </ul>
            </li>

            @can('admin')
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-cetak" class="menu-title" data-bs-toggle="collapse">
                    <ion-icon src="{{asset('img/icons/printer.svg')}}"></ion-icon>
                    Cetak Data
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-cetak">
                    <li class="submenu-list"><a href="{{ route('cetak.siswa.index') }}"> Data Siswa</a></li>
                    <li class="submenu-list"><a href="{{ route('cetak.absensi.guru.index') }}"> Absensi Guru</a></li>
                    <li class="submenu-list"><a href="{{ route('cetak.harian.index') }}"> Nilai Harian</a></li>
                    <li class="submenu-list"><a href="{{ route('cetak.olahan.index') }}"> Nilai Olahan</a></li>
                    <li class="submenu-list"><a href="{{ route('cetak.pts.index') }}"> Nilai PTS</a></li>
                    <li class="submenu-list"><a href="{{ route('cetak.pas.index') }}"> Nilai SAS</a></li>
                    <li class="submenu-list"><a href="{{ route('cetak.rapor.index') }}"> Nilai Rapor</a></li>
                    <li class="submenu-list"><a href="{{ route('cetak.proyek.index') }}"> Nilai P5</a></li>
                    <li class="submenu-list"><a href="{{ route('cetak.penjabaran.index') }}"> Nilai Penjabaran</a></li>
                </ul>
            </li>
            <li class="menu-list"><a href="{{route('setting.index')}}"> <ion-icon src="{{asset('img/icons/settings.svg')}}"></ion-icon> Setting</a>
            </li>
            @endcan
            {{-- Kelulusan --}}
            @canany(['siswa','orangtua'])
            <li class="menu-list"><a href="{{route('kelulusan.siswa.index')}}"> <ion-icon src="{{asset('img/icons/graduation.svg')}}"></ion-icon>
                    Kelulusan</a></li>
            @endcan
        </ul>
    </div>
</div>
