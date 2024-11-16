<table>
    <tr>
        <td style="font-size:13px" colspan="{{($ngajar->count() * 3) + 5}}" align="center"><b>Daftar Rekapulasi Nilai Rapor {{$setting->nilai ?? ""}}</b></td>
    </tr>
    <tr>
        <td style="font-size:13px" colspan="{{($ngajar->count() * 3) + 5}}" align="center"><b>Kelas {{$kelas->tingkat.$kelas->kelas}} Semester {{$semester->semester == 1 ? "Ganjil" : "Genap"}}</b></td>
    </tr>
    <tr>
        <td style="font-size:13px" colspan="{{($ngajar->count() * 3) + 5}}" align="center"><b>Tahun Pelajaran {{$semester->tp}}</b></td>
    </tr>
</table>

<p>Walikelas : {{$kelas->walikelas[0]->nama}}</p>
<table class="table table-bordered nilai-rapor" style="font-size:12px">
    <thead>
        <tr class="text-center align-middle">
            <td bgcolor="#FBD288" rowspan="3" width="5" align="center" valign="middle" style="border:1px solid #000000">No</td>
            <td bgcolor="#FBD288" rowspan="3" width="5" align="center" valign="middle" style="border:1px solid #000000">NIS</td>
            <td bgcolor="#FBD288" rowspan="3" width="25" align="center" valign="middle" style="border:1px solid #000000">
                Nama
            </td>
            <td bgcolor="#FBD288" colspan="{{ $ngajar->count() * 3 }}" align="center" valign="middle" style="border:1px solid #000000">
                Nilai
            </td>
            <td bgcolor="#FBD288" rowspan="3" width="10" align="center" valign="middle" style="border:1px solid #000000">Jumlah</td>
            <td bgcolor="#FBD288" rowspan="3" width="15" align="center" valign="middle" style="border:1px solid #000000">Rata-Rata</td>
        </tr>
        <tr>
            @foreach ($ngajar as $item)
                <td bgcolor="#ffefd0" width="5" colspan="3" align="center" valign="middle" style="border:1px solid #000000">
                    {{ $item->pelajaran_singkat }}
                </td>
            @endforeach
        </tr>
        <tr>
            @foreach ($ngajar as $item)
                <td width="5" colspan="3" align="center" valign="middle" style="border:1px solid #000000">
                    {{ $item->kkm }}
                </td>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($siswa as $siswa)
            @php $jumlah = 0; @endphp
            <tr>
                <td align="center" valign="middle" style="border:1px solid #000000">{{ $loop->iteration }}</td>
                <td align="center" width="10" valign="middle" style="border:1px solid #000000">{{ $siswa->nis }}</td>
                <td style="border:1px solid #000000">{{ $siswa->nama }}</td>
                @foreach ($ngajar as $item)
                    @if (isset($rapor_array[$item->uuid . '.' . $siswa->uuid]))
                        <td width="5" @if ($rapor_array[$item->uuid . '.' . $siswa->uuid]['nilai'] < $item->kkm) bgcolor="#ffd2d2" @endif align="center" style="border:1px solid #000000">
                            {{ $rapor_array[$item->uuid . '.' . $siswa->uuid]['nilai'] }}
                        </td>
                        @php $jumlah += $rapor_array[$item->uuid . '.' . $siswa->uuid]['nilai']; @endphp
                        <td width="5" style="border:1px solid #000000">
                            {{ $rapor_array[$item->uuid . '.' . $siswa->uuid]['positif'] }}
                        </td>
                        <td width="5" style="border:1px solid #000000">
                            {{ $rapor_array[$item->uuid . '.' . $siswa->uuid]['negatif'] }}
                        </td>
                    @else
                        <td width="5" align="center" style="border:1px solid #000000">
                            0
                        </td>
                        <td width="5" align="center" style="border:1px solid #000000">
                            -
                        </td>
                        <td width="5" align="center" style="border:1px solid #000000">
                            -
                        </td>
                        @php $jumlah += 0; @endphp
                    @endif
                @endforeach
                <td align="center" style="border:1px solid #000000">{{ $jumlah }}</td>
                <td align="center" style="border:1px solid #000000">
                    {{ round($jumlah / $ngajar->count(), 2) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<h5>Halaman ini Dicetak melalui aplikasi Pada {{date('d F Y H:i:s')}}</h5>
