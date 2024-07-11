<div class="sidebar" ss-container>
    <div class="sidebar-contain">
        <div class="sidebar-logo-contain">
            <img src="{{ asset('img/logo-rounded.png') }}" class="logo-main">
            <h5 class="logo-title"><b>SMP</b> Maitreyawira</h5>
            <i class="app-version">5.0.0</i>
        </div>
        <ul class="menu">
            <li class="menu-list"><a href="/home"> <i class="fa-solid fa-home"></i> Dashboard</a></li>

            {{-- Menu Guru --}}
            @can('admin')
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-data" class="menu-title" data-bs-toggle="collapse">
                    <i class="fa-solid fa-database"></i>
                    Data Sekolah
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-data">
                    <li class="submenu-list"><a href="{{ route('guru.index') }}"> Data Guru & Staf</a></li>
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
                    <i class="fa-solid fa-calendar-check"></i>
                    Absensi PTK
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-absensi">
                    <li class="submenu-list"><a href="{{ route('absensi.kehadiran') }}"> Absen Kehadiran</a></li>
                    <li class="submenu-list"><a href="{{ route('absensi.kehadiran.histori') }}"> Lihat Absensi</a></li>
                    @canany(['admin','kurikulum','kepalasekolah'])
                    <li class="submenu-list"><a href="{{ route('absensi.index') }}"> Atur Tanggal</a></li>
                    @endcan
                </ul>
            </li>
            @endcanany
            @can('siswa')
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-absensi" class="menu-title" data-bs-toggle="collapse">
                    <i class="fa-solid fa-calendar-check"></i>
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
                    <i class="fa-solid fa-chalkboard-user"></i>
                    Buku Guru
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-guru">
                    <li class="submenu-list"><a href="{{ route('penilaian.kktp.index') }}"> KKTP</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.materi.index') }}"> Materi</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.formatif.index') }}"> Nilai Formatif</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.sumatif.index') }}"> Nilai Sumatif</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.pts.index') }}"> PTS</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.pas.index') }}"> PAS/T</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.rapor.index') }}"> Rapor</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.penjabaran.index') }}"> Penjabaran</a></li>
                </ul>
            </li>
            @endcan
            @canany(['admin','kurikulum','guru','kesiswaan','sapras'])
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-agenda" class="menu-title" data-bs-toggle="collapse">
                    <i class="fa-solid fa-address-book"></i>
                    Buku Agenda
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-agenda">
                    <li class="submenu-list"><a href="{{ route('agenda.index') }}">Lihat Agenda</a></li>
                </ul>
            </li>
            @endcan

            {{-- Penilaian --}}
            @canany(['admin', 'kurikulum','kepalasekolah'])
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-pelajaran" class="menu-title" data-bs-toggle="collapse">
                    <i class="fa-solid fa-book"></i>
                    Penilaian
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-pelajaran">
                    <li class="submenu-list"><a href="{{ route('penilaian.admin.index') }}"> Semua Nilai</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.admin.pts') }}"> PTS</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.admin.pas') }}"> PAS/T</a></li>
                    <li class="submenu-list"><a href="{{ route('penilaian.admin.rapor') }}"> Rapor</a></li>
                </ul>
            </li>
            @endcan

            {{-- Classroom --}}
            @canany(['guru','kesiswaan','sapras','kurikulum'])
            <li class="menu-list"><a href="{{route('classroom.index')}}"> <i class="fa-solid fa-landmark"></i> Classroom</a>
            </li>
            @endcan
            @can('siswa')
            <li class="menu-list"><a href="{{route('classroom.siswa.index')}}"> <i class="fa-solid fa-landmark"></i>
                    Classroom</a></li>
            @endcan

            {{-- Walikelas --}}
            @canany(['guru','kesiswaan','sapras','kurikulum'])
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-walikelas" class="menu-title" data-bs-toggle="collapse">
                    <i class="fa-solid fa-file-signature"></i>
                    Walikelas
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-walikelas">
                    <li class="submenu-list"><a href="{{ route('walikelas.absensi') }}">Absensi Siswa</a></li>
                </ul>
            </li>
            @endcan

            {{-- Jadwal --}}
            <li class="menu-list has-submenu" aria-expanded="false" aria-controls="collapse">
                <a href="#menu-jadwal" class="menu-title" data-bs-toggle="collapse">
                    <i class="fa-solid fa-clock"></i>
                    Jadwal Sekolah
                    <i class="indicator-icon fa-solid fa-chevron-right"></i>
                </a>
                <ul class="submenu collapse" id="menu-jadwal">
                    <li class="submenu-list"><a href="{{ route('jadwal.index') }}"> Jadwal Pelajaran</a></li>
                    {{-- <li class="submenu-list"><a href="{{ route('penilaian.admin.index') }}"> Jadwal Akademik</a>
                    </li> --}}
                </ul>
            </li>
        </ul>
    </div>
</div>
