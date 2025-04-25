@php
    $jumlahTabel = 0;
    foreach ($proyek as $item) {
        if (isset($detail[$item->proyek->uuid])) {
            foreach ($detail[$item->proyek->uuid] as $elem) {
                    $jumlahTabel++;
            }
        }
        $jumlahTabel++;
    }
    $jumlahTabel = $jumlahTabel + 3;
    $totalPembagian = $jumlahTabel % 2 == 0 ? $jumlahTabel / 2 : ($jumlahTabel - 1) / 2;
@endphp
<table>
    <tr>
        <td style="font-size:13px" align="center" colspan="{{$jumlahTabel}}"><b>Daftar Rekapulasi Nilai Proyek Profil Penguatan Pelajar Pancasila (P5)</b></td>
    </tr>
    <tr>
        <td style="font-size:13px" align="center" colspan="{{$jumlahTabel}}"><b>Kelas {{$kelas->tingkat.$kelas->kelas}} Semester {{$semester->semester == 1 ? "Ganjil" : "Genap"}}</b></td>
    </tr>
    <tr>
        <td style="font-size:13px" align="center" colspan="{{$jumlahTabel}}"><b>Tahun Pelajaran {{$semester->tp}}</b></td>
    </tr>
</table>
<table>
    <tr>
        <td colspan="{{$totalPembagian}}">Kelas : {{$kelas->tingkat.$kelas->kelas}}</td>
    </tr>
    <tr>
        <td colspan="{{$totalPembagian}}">Semester : {{$semester->semester == 1 ? "Ganjil" : "Genap"}}</td>
        <td colspan="{{$totalPembagian}}">Tahun Pelajaran : {{$semester->tp}}</td>
    </tr>
</table>
<table class="table table-bordered nilai-table" style="font-size:10px">
    <thead>
        <tr>
            <td rowspan="3" width="5" align="center" valign="middle" style="border:1px solid #000000">No</td>
            <td rowspan="3" width="30" align="center" valign="middle" style="border:1px solid #000000">Nama</td>
            <td rowspan="3" width="10" align="center" valign="middle" style="border:1px solid #000000">NIS</td>
            @foreach ($proyek as $item)
                @php
                    if(isset($detail[$item->proyek->uuid])) {
                        $jumlah = count($detail[$item->proyek->uuid]) + 1;
                    } else {
                        $jumlah = 1;
                    }
                @endphp
                <td align="center" colspan={{$jumlah}} style="border:1px solid #000000">{{$item->proyek->judul}}</td>
            @endforeach
        </tr>
        <tr>
            @php
                $no = 1;
            @endphp
             @foreach ($proyek as $item)
                @if (isset($detail[$item->proyek->uuid]))
                    @foreach ($detail[$item->proyek->uuid] as $elem)
                        @if ($loop->last)
                            <td width="5" align="center" style="border:1px solid #000000">D-{{$no}}</td>
                            <td rowspan="2" align="center" valign="middle" width="50" style="border:1px solid #000000">Deskripsi</td>
                        @else
                            <td align="center" width="5" style="border:1px solid #000000">D-{{$no}}</td>
                        @endif
                        @php
                            $no++;
                        @endphp
                    @endforeach
                @endif
            @endforeach
        </tr>
        <tr>
            @php
                $no = 1;
            @endphp
             @foreach ($proyek as $item)
                @if (isset($detail[$item->proyek->uuid]))
                    @foreach ($detail[$item->proyek->uuid] as $elem)
                        <td align="center" style="border:1px solid #000000">C-{{$no}}</td>
                        @php
                            $no++;
                        @endphp
                    @endforeach
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($siswa as $item)
            <tr>
                <td align="center" valign="middle" style="border:1px solid #000000">{{$loop->iteration}}</td>
                <td valign="middle" style="border:1px solid #000000">{{$item->nama}}</td>
                <td align="center" valign="middle" style="border:1px solid #000000">{{$item->nis}}</td>
                @foreach ($proyek as $item2)
                @if (isset($detail[$item2->proyek->uuid]))
                    @foreach ($detail[$item2->proyek->uuid] as $elem)
                        @if (isset($nilai[$elem['id_detail'].".".$item->uuid]))
                            @if ($loop->last)
                                <td align="center" valign="middle" style="border:1px solid #000000">{{$nilai[$elem['id_detail'].".".$item->uuid]}}</td>
                                <td valign="middle" style="word-wrap:break-word;border:1px solid #000000">{{$deskripsi[$item2->proyek->uuid.".".$item->uuid] !== null ? $deskripsi[$item2->proyek->uuid.".".$item->uuid] : ""}}</td>
                            @else
                                <td align="center" valign="middle" style="border:1px solid #000000">{{$nilai[$elem['id_detail'].".".$item->uuid]}}</td>
                            @endif
                        @else
                            @if ($loop->last)
                                <td align="center" valign="middle" style="border:1px solid #000000"></td>
                                <td valign="middle" style="word-wrap:break-word;border:1px solid #000000"></td>
                            @else
                                <td align="center" valign="middle" style="border:1px solid #000000"></td>
                            @endif
                        @endif
                    @endforeach
                @endif
            @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
<table style="margin-top: 40px; font-size:11px">
    <tr>
        <td><i><b>Keterangan</b></i></td>
    </tr>
    <tr>
        <td>D-*</td>
        <td><i>Dimensi</i></td>
    </tr>
    <tr>
        <td>C-*</td>
        <td><i>Capaian</i></td>
    </tr>
</table>
<table style="margin-top: 40px; font-size:11px">
    @php
        $no = 1;
    @endphp
    @foreach ($proyek as $item)
        @if (isset($detail[$item->proyek->uuid]))
            @foreach ($detail[$item->proyek->uuid] as $elem)
                <tr>
                    <td>D-{{$no}}</td>
                    <td>{{$elem['dimensi']}}</td>
                </tr>
                <tr>
                    <td>C-{{$no}}</td>
                    <td>{{$elem['capaian']}}</td>
                </tr>
                @php
                    $no++;
                @endphp
            @endforeach
        @endif
    @endforeach
</table>
<table style="margin-top: 40px; font-size:11px">
    <tr>
        <td><b>Deskripsi Proyek</b></td>
    </tr>
    @foreach ($proyek as $item)
        <tr>
            <td>P{{$loop->iteration}}</td>
            <td>{{$item->proyek->deskripsi}}</td>
        </tr>
    @endforeach
</table>
