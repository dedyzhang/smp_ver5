<table class="table table-bordered fs-12 nilai-table">
    <thead>
        <tr class="text-center">
            <td width="3" rowspan="3" align="center" valign="middle" style="border:1px solid #000;" bgcolor="#ffedb0">No</td>
            <td width="25" rowspan="3" align="center" valign="middle" style="border:1px solid #000;" bgcolor="#ffedb0">Siswa</td>
            <td class="mainNilaiCell" colspan="{{$countFormatif}}" align="center" style="border:1px solid #000;" bgcolor="#c2ffb0">Formatif</td>
            <td class="mainNilaiCell" colspan="{{$countSumatif}}" align="center" style="border:1px solid #000;" bgcolor="#b0beff">Sumatif</td>
        </tr>
        <tr class="text-center">
            @foreach ($materiArray as $item)
                <td colspan="{{$item['jumlahTupe'] + 1}}" align="center" style="border:1px solid #000;" bgcolor="#dfffd6">M{{$loop->iteration}}</td>
            @endforeach
            @foreach ($materiArray as $item)
                <td rowspan="2" align="center" valign="middle" style="border:1px solid #000;" bgcolor="#d6ddff">M{{$loop->iteration}}</td>
            @endforeach
        </tr>
        <tr class="text-center">
            @foreach ($materiArray as $materi)
                @foreach ($tupeArray as $tupe)
                    @if ($tupe['id_materi'] === $materi['uuid'])
                        <td width="7" align="center" style="border:1px solid #000;" bgcolor="#dfffd6">TP{{$loop->iteration}}</td>
                    @endif
                @endforeach
                <td width="7" align="center" style="border:1px solid #000;" bgcolor="#dfffd6">NA</td>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($ngajar->siswa as $siswa)
            <tr>
                <td align="center" style="border:1px solid #000;">{{$loop->iteration}}</td>
                <td style="border:1px solid #000;">{{$siswa->nama}}</td>
                @foreach ($materiArray as $item)
                    @php
                        $countMateri = 0;
                        $jumlah = 0;
                    @endphp
                    @foreach ($tupeArray as $tupe)
                        @if ($tupe['id_materi'] === $item['uuid'])
                            @php
                                $jumlah += $formatif_array[$tupe['uuid'].".".$siswa->uuid]['nilai'];
                                $countMateri += 1;
                            @endphp
                            <td width="7" align="center" @if ($formatif_array[$tupe['uuid'].".".$siswa->uuid]['nilai'] < $ngajar->kkm) bgcolor="#ffc6c6" @endif style="border:1px solid #000;">{{$formatif_array[$tupe['uuid'].".".$siswa->uuid]['nilai']}}</td>
                        @endif
                    @endforeach
                        <td width="7" align="center" @if (round($jumlah / $countMateri ,0) < $ngajar->kkm) bgcolor="#ffc6c6" @endif style="border:1px solid #000;">{{round($jumlah / $countMateri,0)}}</td>
                @endforeach
                @foreach ($materiArray as $item)
                    <td width="7" align="center" @if ($sumatif_array[$item['uuid'].".".$siswa->uuid]['nilai'] < $ngajar->kkm) bgcolor="#ffc6c6" @endif style="border:1px solid #000;">{{$sumatif_array[$item['uuid'].".".$siswa->uuid]['nilai']}}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
