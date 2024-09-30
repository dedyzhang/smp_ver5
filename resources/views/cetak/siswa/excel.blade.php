<h1>Data Siswa</h1>
<h3>{{$setting->nilai ?? ""}}</h3>
<h5>Dicetak Pada {{date('d F Y H:i:s')}}</h5>

<table style="font-size:12px">
    <thead>
    <tr height="30" style="boder:1px solid #000000">
        <th bgcolor="#B1AFFF" width="5" valign="middle" align="center" style="border:1px solid #000000" >No</th>
        <th bgcolor="#B1AFFF" width="8" style="word-wrap:break-word; border:1px solid #000000" valign="middle" align="center">ID Website</th>
        <th bgcolor="#B1AFFF" width="25" valign="middle" align="center" style="border:1px solid #000000">Nama</th>
        <th bgcolor="#B1AFFF" width="10" valign="middle" align="center" style="border:1px solid #000000">NIS</th>
        <th bgcolor="#B1AFFF" width="5" valign="middle" align="center" style="border:1px solid #000000">JK</th>
        <th bgcolor="#B1AFFF" width="10" valign="middle" align="center" style="border:1px solid #000000">Kelas</th>
        <th bgcolor="#B1AFFF" width="40" valign="middle" align="center" style="border:1px solid #000000">Tanggal / Tempat Lahir</th>
        <th bgcolor="#B1AFFF" width="10" valign="middle" align="center" style="border:1px solid #000000">Agama</th>
        <th bgcolor="#FCDC94" width="25" valign="middle" align="center" style="border:1px solid #000000">Nama Ayah</th>
        <th bgcolor="#FCDC94" width="25" valign="middle" align="center" style="border:1px solid #000000">Pekerjaan Ayah</th>
        <th bgcolor="#FCDC94" width="25" valign="middle" align="center" style="border:1px solid #000000">No Telp Ayah</th>
        <th bgcolor="#FCDC94" width="25" valign="middle" align="center" style="border:1px solid #000000">Nama Ibu</th>
        <th bgcolor="#FCDC94" width="25" valign="middle" align="center" style="border:1px solid #000000">Pekerjaan Ibu</th>
        <th bgcolor="#FCDC94" width="25" valign="middle" align="center" style="border:1px solid #000000">No Telp Ibu</th>
        <th bgcolor="#FCDC94" width="25" valign="middle" align="center" style="border:1px solid #000000">Nama Wali</th>
        <th bgcolor="#FCDC94" width="25" valign="middle" align="center" style="border:1px solid #000000">Pekerjaan Wali</th>
        <th bgcolor="#FCDC94" width="25" valign="middle" align="center" style="border:1px solid #000000">No Telp Wali</th>
        <th bgcolor="#A5DD9B" width="45" valign="middle" align="center" style="border:1px solid #000000">Alamat</th>
        <th bgcolor="#A5DD9B" width="20" valign="middle" align="center" style="border:1px solid #000000">Telp Rumah</th>
        <th bgcolor="#E7D4B5" width="15" valign="middle" align="center" style="border:1px solid #000000">NISN</th>
        <th bgcolor="#E7D4B5" width="30" valign="middle" align="center" style="border:1px solid #000000">Sekolah Asal</th>
        <th bgcolor="#E7D4B5" width="25" valign="middle" align="center" style="border:1px solid #000000">Nama Ijazah</th>
        <th bgcolor="#E7D4B5" width="30" valign="middle" align="center" style="border:1px solid #000000">Ortu Ijazah</th>
        <th bgcolor="#E7D4B5" width="40" valign="middle" align="center" style="border:1px solid #000000">Tanggal / Tempat Lahir Ijazah</th>
    </tr>
    </thead>
    <tbody>
    @foreach($siswa as $item)
        <tr>
            <td align="center" style="border:1px solid #000000">{{ $loop->iteration }}</td>
            <td style="border:1px solid #000000">{{ $item->uuid }}</td>
            <td style="border:1px solid #000000">{{ $item->nama }}</td>
            <td align="center" style="border:1px solid #000000">{{ $item->nis }}</td>
            <td align="center" style="border:1px solid #000000">{{ strtoupper($item->jk) }}</td>
            <td align="center" style="border:1px solid #000000">@if($item->kelas !== null) {{$item->kelas->tingkat.$item->kelas->kelas}} @endif </td>
            <td style="border:1px solid #000000">
                @if($item->tempat_lahir !== null) {{$item->tempat_lahir}} @endif / @if($item->tanggal_lahir !== null) {{date('d F Y',strtotime($item->tanggal_lahir))}} @endif
            </td>
            <td align="center" style="border:1px solid #000000">{{$item->agama}}</td>
            <td style="border:1px solid #000000">{{$item->nama_ayah}}</td>
            <td style="border:1px solid #000000">{{$item->pekerjaan_ayah}}</td>
            <td style="border:1px solid #000000">{{$item->no_telp_ayah}}</td>
            <td style="border:1px solid #000000">{{$item->nama_ibu}}</td>
            <td style="border:1px solid #000000">{{$item->pekerjaan_ibu}}</td>
            <td style="border:1px solid #000000">{{$item->no_telp_ibu}}</td>
            <td style="border:1px solid #000000">{{$item->nama_wali}}</td>
            <td style="border:1px solid #000000">{{$item->pekerjaan_wali}}</td>
            <td style="border:1px solid #000000">{{$item->no_telp_wali}}</td>
            <td style="border:1px solid #000000">{{$item->alamat}}</td>
            <td style="border:1px solid #000000">{{$item->no_handphone}}</td>
            <td align="center" style="border:1px solid #000000">{{$item->nisn}}</td>
            <td style="border:1px solid #000000">{{$item->sekolah_asal}}</td>
            <td style="border:1px solid #000000">{{$item->nama_ijazah}}</td>
            <td style="border:1px solid #000000">{{$item->ortu_ijazah}}</td>
            <td style="border:1px solid #000000">
                @if($item->tempat_lahir_ijazah !== null) {{$item->tempat_lahir_ijazah}} @endif / @if($item->tanggal_lahir_ijazah !== null) {{date('d F Y',strtotime($item->tanggal_lahir_ijazah))}} @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
