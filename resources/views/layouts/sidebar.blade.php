<div class="sidebar" ss-container>
    <div class="sidebar-contain">
        <div class="sidebar-logo-contain">
            <img src="{{ asset('img/logo-rounded.png') }}" class="logo-main">
            <h5 class="logo-title"><b>SMP</b> Maitreyawira</h5>
            <i class="app-version">5.0.0</i>
        </div>
        <ul class="menu">
            <li class="menu-list"><a href="/home"> <i class="fa-solid fa-home"></i> Dashboard</a></li>

            {{-- For Admin --}}
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
            @canany(['kurikulum', 'guru'])
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
                    </ul>
                </li>
            @endcan
            @canany(['admin', 'kurikulum'])
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
                        <li class="submenu-list"><a href="{{ route('penilaian.admin.index') }}"> Rapor</a></li>
                    </ul>
                </li>
            @endcan
        </ul>
    </div>
</div>
