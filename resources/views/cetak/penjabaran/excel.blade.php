@php
    $totalKolumn = $jabaran == "inggris" ? 9 : 7 ;
    $totalPembagian = $totalKolumn % 2 == 0 ? $totalKolumn / 2 : ($totalKolumn - 1) / 2;
@endphp
<table>
    <tr>
        <td style="font-size:13px" colspan="{{$totalKolumn}}" align="center"><b>Daftar Rekapulasi @if ($jabaran == 'inggris') Penjabaran Inggris @else Penjabaran Mandarin @endif {{$setting->nilai ?? ""}}</b></td>
    </tr>
    <tr>
        <td style="font-size:13px" colspan="{{$totalKolumn}}" align="center"><b>Kelas {{$kelas->tingkat.$kelas->kelas}} Semester {{$semester->semester == 1 ? "Ganjil" : "Genap"}}</b></td>
    </tr>
    <tr>
        <td style="font-size:13px" colspan="{{$totalKolumn}}" align="center"><b>Tahun Pelajaran {{$semester->tp}}</b></td>
    </tr>
</table>
<table>
    <tr>
        <td colspan="{{$totalPembagian}}">Nama Pendidik : {{$ngajar->guru->nama}}</td>
        <td colspan="{{$totalPembagian}}">Kelas : {{$ngajar->kelas->tingkat.$ngajar->kelas->kelas}}</td>
    </tr>
    <tr>
        <td colspan="{{$totalPembagian}}">Semester : {{$semester->semester == 1 ? "Ganjil" : "Genap"}}</td>
        <td colspan="{{$totalPembagian}}">Tahun Pelajaran : {{$semester->tp}}</td>
    </tr>
    <tr>
        <td colspan="{{$totalPembagian}}">KKM : {{$ngajar->kkm}}</td>
    </tr>
</table>
<table class="table table-bordered fs-12 nilai-table">
    <thead>
        <tr class="text-center">
            <td rowspan="2" width="5" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">No</td>
            <td rowspan="2" width="30" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">Siswa</td>
            @if (count($penjabaran) !== 0)
                <td bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000" colspan="@if ($jabaran == "inggris") 7 @else 6 @endif">Nilai</td>
            @endif
        </tr>
        <tr class="text-center">
            @if (count($penjabaran) !== 0)
                @if ($jabaran == "inggris")
                    <td width="10" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">Listening</td>
                    <td width="10" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">Speaking</td>
                    <td width="10" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">Writing</td>
                    <td width="10" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">Reading</td>
                    <td width="10" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">Grammar</td>
                    <td width="10" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">Vocabulary</td>
                    <td width="10" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">Singing</td>
                @else
                    <td width="10" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">听力</td>
                    <td width="10" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">会话</td>
                    <td width="10" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">书写</td>
                    <td width="10" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">阅读</td>
                    <td width="10" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">词汇</td>
                    <td width="10" bgcolor="#B1AFFF" align="center" valign="middle" style="border:1px solid #000000">唱歌</td>
                @endif
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($ngajar->siswa as $siswa)
            <tr class="siswa" data-ngajar="{{$ngajar->uuid}}" data-siswa="{{$siswa->uuid}}">
                <td align="center" valign="middle" style="border:1px solid #000000">{{$loop->iteration}}</td>
                <td valign="middle" style="border:1px solid #000000">{{$siswa->nama}}</td>
                @if (isset($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]))
                    <td align="center" valign="middle" style="border:1px solid #000000"
                        @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['listening'] < $ngajar->kkm) bgcolor="#ffd2d2" @endif>
                        {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['listening']}}
                    </td>
                    <td align="center" valign="middle" style="border:1px solid #000000"
                        @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['speaking'] < $ngajar->kkm) bgcolor="#ffd2d2" @endif>
                        {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['speaking']}}
                    </td>
                    <td align="center" valign="middle" style="border:1px solid #000000"
                        @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['writing'] < $ngajar->kkm) bgcolor="#ffd2d2" @endif>
                        {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['writing']}}
                    </td>
                    <td align="center" valign="middle" style="border:1px solid #000000"
                        @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['reading'] < $ngajar->kkm) bgcolor="#ffd2d2" @endif>
                        {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['reading']}}
                    </td>
                    @if ($jabaran == "inggris")
                        <td align="center" valign="middle" style="border:1px solid #000000"

                            @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['grammar'] < $ngajar->kkm) bgcolor="#ffd2d2" @endif>
                            {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['grammar']}}
                        </td>
                    @endif
                    <td align="center" valign="middle" style="border:1px solid #000000"
                        @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['vocabulary'] < $ngajar->kkm) bgcolor="#ffd2d2" @endif>
                        {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['vocabulary']}}
                    </td>
                    <td align="center" valign="middle" style="border:1px solid #000000"
                        @if ($penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['singing'] < $ngajar->kkm) bgcolor="#ffd2d2" @endif>
                        {{$penjabaran_array[$ngajar->uuid.".".$siswa->uuid]['singing']}}
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
