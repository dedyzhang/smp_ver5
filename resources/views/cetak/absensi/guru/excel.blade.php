<h2>Absensi PTK</h2>
<h4><i>Dari Tanggal {{date('d F Y',strtotime($dari))}} sampai {{date('d F Y',strtotime($sampai))}}</i></h4>
<p><i>Dicetak oleh Aplikasi pada tanggal {{date('d F Y, H:i:s')}}</i></p>
<table>
    <thead>
        <tr height="20">
            <td bgcolor="#DBA979" rowspan="2" align="center" valign="middle" style="border:1px solid #000000">No</td>
            <td bgcolor="#DBA979" rowspan="2" align="center" valign="middle" width="30"  style="border:1px solid #000000">Nama</td>
            @foreach ($tanggal as $item)
                <td bgcolor="#DBA979" align="center" valign="middle" colspan="2"  style="border:1px solid #000000">{{date('d M Y',strtotime($item->tanggal))}}</td>
            @endforeach
            <td bgcolor="#DBA979" colspan="3" align="center" valign="middle" style="border:1px solid #000000">Keterangan</td>
        </tr>
        <tr>
            @foreach ($tanggal as $item)
                <td bgcolor="#ECCA9C" align="center" valign="middle" style="border:1px solid #000000">Datang</td>
                <td bgcolor="#ECCA9C" align="center" valign="middle" style="border:1px solid #000000">Pulang</td>
            @endforeach
            <td bgcolor="#ECCA9C" align="center" style="border:1px solid #000000" valign="middle">Terlambat</td>
            <td bgcolor="#ECCA9C" align="center" style="word-wrap:break-word; border:1px solid #000000">Tidak A.Datang</td>
            <td bgcolor="#ECCA9C" align="center" style="word-wrap:break-word; border:1px solid #000000">Tidak A.Pulang</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($guru as $item)
            @php
                $terlambatCount = 0;
                $tidakAbsenDatang = 0;
                $tidakAbsenPulang = 0;
            @endphp
            <tr>
                <td align="center" style="border:1px solid #000000">{{$loop->iteration}}</td>
                <td style="border:1px solid #000000">{{$item->nama}}</td>
                @foreach ($tanggal as $element)
                    @if(isset($absensi[$element->uuid.".".$item->uuid]) && isset($absensi[$element->uuid.".".$item->uuid]['datang']))
                        @php
                            $terlambat = strtotime('07:46:00');
                            $absensiGuru = strtotime($absensi[$element->uuid.".".$item->uuid]['datang']);
                            if($absensiGuru >= $terlambat) {
                                $kelas = '#f93e3e';
                                $terlambatCount++;
                            } else {
                                $kelas = '#000000';
                            }
                        @endphp
                        <td style="color:{{$kelas}}; border:1px solid #000000" align="center">{{$absensi[$element->uuid.".".$item->uuid]['datang']}}</td>
                    @else
                        <td style="border:1px solid #000000"></td>
                        @php
                            $tidakAbsenDatang++;
                        @endphp
                    @endif

                    @if(isset($absensi[$element->uuid.".".$item->uuid]) && isset($absensi[$element->uuid.".".$item->uuid]['pulang']))
                        <td style="border:1px solid #000000" align="center">{{$absensi[$element->uuid.".".$item->uuid]['pulang']}}</td>
                    @else
                        <td style="border:1px solid #000000"></td>
                        @php
                            $tidakAbsenPulang++;
                        @endphp
                    @endif
                @endforeach
                <td align="center" style="border:1px solid #000000">{{$terlambatCount}}</td>
                <td align="center" style="border:1px solid #000000">{{$tidakAbsenDatang}}</td>
                <td align="center" style="border:1px solid #000000">{{$tidakAbsenPulang}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
