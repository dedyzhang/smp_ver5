<h1>Data Guru</h1>
<h3>SMPS Maitreyawira Tanjungpinang</h3>
<h5>Dicetak Pada {{date('d F Y H:i:s')}}</h5>

<table style="font-size:12px">
    <thead>
    <tr height="30" style="boder:1px solid #000000">
        <th bgcolor="#B1AFFF" width="5" valign="middle" align="center" style="border:1px solid #000000" >No</th>
        <th bgcolor="#B1AFFF" width="8" style="word-wrap:break-word; border:1px solid #000000" valign="middle" align="center">ID Website</th>
        <th bgcolor="#B1AFFF" width="25" valign="middle" align="center" style="border:1px solid #000000">Nama</th>
        <th bgcolor="#B1AFFF" width="15" valign="middle" align="center" style="border:1px solid #000000">NIK</th>
        <th bgcolor="#B1AFFF" width="5" valign="middle" align="center" style="border:1px solid #000000">JK</th>
        <th bgcolor="#B1AFFF" width="40" valign="middle" align="center" style="border:1px solid #000000">Tanggal / Tempat Lahir</th>
        <th bgcolor="#B1AFFF" width="10" valign="middle" align="center" style="border:1px solid #000000">Agama</th>
        <th bgcolor="#B1AFFF" width="50" valign="middle" align="center" style="border:1px solid #000000">Alamat</th>
        <th bgcolor="#B1AFFF" width="20" valign="middle" align="center" style="border:1px solid #000000">No Telepon</th>
        <th bgcolor="#A5DD9B" width="10" valign="middle" align="center" style="border:1px solid #000000">Tingkat Studi</th>
        <th bgcolor="#A5DD9B" width="40" valign="middle" align="center" style="border:1px solid #000000">Program Studi</th>
        <th bgcolor="#A5DD9B" width="40" valign="middle" align="center" style="border:1px solid #000000">Universitas</th>
        <th bgcolor="#A5DD9B" width="10" valign="middle" align="center" style="border:1px solid #000000">Tahun Tamat</th>
        <th bgcolor="#EF9C66" width="15" valign="middle" align="center" style="border:1px solid #000000">TMT Mengajar</th>
        <th bgcolor="#EF9C66" width="15" valign="middle" align="center" style="border:1px solid #000000">TMT di SMP</th>
    </tr>
    </thead>
    <tbody>
    @foreach($guru as $item)
        <tr>
            <td align="center" style="border:1px solid #000000">{{ $loop->iteration }}</td>
            <td style="border:1px solid #000000">{{ $item->uuid }}</td>
            <td style="border:1px solid #000000">{{ $item->nama }}</td>
            <td align="center" style="border:1px solid #000000">{{ $item->nik }}</td>
            <td align="center" style="border:1px solid #000000">{{ strtoupper($item->jk) }}</td>
            <td style="border:1px solid #000000">
                @if($item->tempat_lahir !== null) {{$item->tempat_lahir}} @endif / @if($item->tanggal_lahir !== null) {{date('d F Y',strtotime($item->tanggal_lahir))}} @endif
            </td>
            <td align="center" style="border:1px solid #000000">{{$item->agama}}</td>
            <td style="border:1px solid #000000">{{$item->alamat}}</td>
            <td align="center" style="border:1px solid #000000">{{$item->no_telp}}</td>
            <td align="center" style="border:1px solid #000000">{{$item->tingkat_studi}}</td>
            <td style="border:1px solid #000000">{{$item->program_studi}}</td>
            <td style="border:1px solid #000000">{{$item->universitas}}</td>
            <td align="center" style="border:1px solid #000000">{{$item->tahun_tamat}}</td>
            <td align="center" style="border:1px solid #000000">{{$item->tmt_ngajar}}</td>
            <td align="center" style="border:1px solid #000000">{{$item->tmt_smp}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
