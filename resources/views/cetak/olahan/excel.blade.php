<table>
    <tr>
        <td style="font-size:13px" colspan="8" align="center"><b>Daftar Rekapulasi Nilai Olahan {{$setting->nilai ?? ""}}</b></td>
    </tr>
    <tr>
        <td style="font-size:13px" colspan="8" align="center"><b>Kelas {{$kelas->tingkat.$kelas->kelas}} Semester {{$semester->semester == 1 ? "Ganjil" : "Genap"}}</b></td>
    </tr>
    <tr>
        <td style="font-size:13px" colspan="8" align="center"><b>Tahun Pelajaran {{$semester->tp}}</b></td>
    </tr>
</table>
<table>
    <tr>
        <td colspan="7">Nama Pendidik : {{$ngajar->guru->nama}}</td>
        <td>Kelas : {{$ngajar->kelas->tingkat.$ngajar->kelas->kelas}}</td>
    </tr>
    <tr>
        <td colspan="7">Semester : {{$semester->semester == 1 ? "Ganjil" : "Genap"}}</td>
        <td>Tahun Pelajaran : {{$semester->tp}}</td>
    </tr>
</table>
<table class="table table-bordered fs-12 nilai-table align-middle">
    <thead>
        <tr class="text-center" height="35">
            <td width="6" align="center" valign="middle" style="border:1px solid #000">No</td>
            <td width="30" align="center" valign="middle" style="border:1px solid #000">Siswa</td>
            <td width="15" align="center" valign="middle" style="border:1px solid #000">NIS</td>
            <td width="8" align="center" valign="middle" style="border:1px solid #000">Formatif</td>
            <td width="8" align="center" valign="middle" style="border:1px solid #000">Sumatif</td>
            <td width="8" align="center" valign="middle" style="border:1px solid #000">PAS</td>
            <td width="8" align="center" valign="middle" style="border:1px solid #000">Rapor</td>
            <td width="80" align="center" valign="middle" style="border:1px solid #000">Deskripsi Rapor</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($ngajar->siswa as $siswa)
            <tr height="33">
                <td rowspan="2" align="center" valign="middle" style="border:1px solid #000">{{ $loop->iteration }}</td>
                <td rowspan="2" valign="middle" style="border:1px solid #000">{{ $siswa->nama }}</td>
                <td rowspan="2" align="center" valign="middle" style="border:1px solid #000">{{ $siswa->nis }}</td>
                @php
                    $nilaiFormatif = 0;
                    $nilaiSumatif = 0;
                    $jumlah = 0;
                    $kkm = $ngajar->kkm;
                    //Menghitung rentan Nilai
                    $interval = round((100 - $kkm) / 3, 0);
                    $Cdown = $kkm;
                    $Cup = $kkm + $interval - 1;
                    $Bdown = $Cup + 1;
                    $Bup = $Bdown + $interval - 1;
                    $Adown = $Bup + 1;
                    $Aup = 100;

                    //Menghitung rata rata formatif
                    $array_list_nilai = [];
                    foreach ($tupeArray as $tupe) {
                        $nilaiFormatif += $formatif_array[$tupe['uuid'] . '.' . $siswa->uuid]['nilai'];
                        array_push($array_list_nilai, [
                            'uuid' => $tupe['uuid'],
                            'tupe' => $tupe['tupe'],
                            'nilai' => $formatif_array[$tupe['uuid'] . '.' . $siswa->uuid]['nilai'],
                        ]);
                        $jumlah++;
                    }
                    if (count($tupeArray) > 0) {
                        $rata2Formatif = round($nilaiFormatif / $jumlah, 0);
                        //Mencari Deskripsi Tertinggi dan terendah
                        array_multisort(
                            array_column($array_list_nilai, 'nilai'),
                            SORT_ASC,
                            $array_list_nilai,
                        );

                        $maxNilai = end($array_list_nilai)['nilai'];
                        $maxUUID = end($array_list_nilai)['uuid'];
                        $minNilai = $array_list_nilai[0]['nilai'];
                        $minUUID = $array_list_nilai[0]['uuid'];

                        //Mencari deskripsi temp Positif
                        $id_siswa = $siswa->uuid;
                        $desk_positif_temp = array_values(
                            array_filter($temp_array, function ($var) use ($id_siswa) {
                                return $var['id_siswa'] == $id_siswa &&
                                    $var['jenis'] == 'deskripsi_positif';
                            }),
                        );
                        $ada_temp_desk_positif = false;
                        if (isset($desk_positif_temp[0])) {
                            $maxDeskripsi_array = explode('.', $desk_positif_temp[0]['perubahan']);
                            $maxDeskripsi_key = array_search(
                                $maxDeskripsi_array[1],
                                array_column($tupeArray, 'uuid'),
                            );
                            $maxDeskripsi = rtrim(lcfirst($tupeArray[$maxDeskripsi_key]['tupe']), '.');
                            $maxUUID = $maxDeskripsi_array[1];
                            $ada_temp_desk_positif = true;

                            if ($maxDeskripsi_array[0] == 'd') {
                                $max_keterangan = 'Perlu bimbingan dalam ' . $maxDeskripsi . '.';
                                $max_predikat = 'd';
                            } elseif ($maxDeskripsi_array[0] == 'c') {
                                $max_keterangan =
                                    'Menunjukkan penguasaan yang cukup baik dalam ' . $maxDeskripsi . '.';
                                $max_predikat = 'c';
                            } elseif ($maxDeskripsi_array[0] == 'b') {
                                $max_keterangan =
                                    'Menunjukkan penguasaan yang baik dalam ' . $maxDeskripsi . '.';
                                $max_predikat = 'b';
                            } elseif ($maxDeskripsi_array[0] == 'a') {
                                $max_keterangan =
                                    'Menunjukkan penguasaan yang amat baik dalam ' . $maxDeskripsi . '.';
                                $max_predikat = 'a';
                            }
                            $minDeskripsi = rtrim(lcfirst($array_list_nilai[0]['tupe']), '.');

                            $min_keterangan = 'Perlu ditingkatkan dalam ' . $minDeskripsi . '.';
                        } else {
                            //mencari rentang deskripsi
                            $maxDeskripsi = rtrim(lcfirst(end($array_list_nilai)['tupe']), '.');
                            if ($maxNilai < $Cdown) {
                                $max_keterangan = 'Perlu bimbingan dalam ' . $maxDeskripsi . '.';
                                $max_predikat = 'd';
                            } elseif ($maxNilai >= $Cdown && $maxNilai <= $Cup) {
                                $max_keterangan =
                                    'Menunjukkan penguasaan yang cukup baik dalam ' . $maxDeskripsi . '.';
                                $max_predikat = 'c';
                            } elseif ($maxNilai >= $Bdown && $maxNilai <= $Bup) {
                                $max_keterangan =
                                    'Menunjukkan penguasaan yang baik dalam ' . $maxDeskripsi . '.';
                                $max_predikat = 'b';
                            } elseif ($maxNilai >= $Adown && $maxNilai <= $Aup) {
                                $max_keterangan =
                                    'Menunjukkan penguasaan yang amat baik dalam ' . $maxDeskripsi . '.';
                                $max_predikat = 'a';
                            }
                        }

                        //Mencari Deskripsi temp Negatif
                        $desk_negatif_temp = array_values(
                            array_filter($temp_array, function ($var) use ($id_siswa) {
                                return $var['id_siswa'] == $id_siswa &&
                                    $var['jenis'] == 'deskripsi_negatif';
                            }),
                        );

                        $ada_temp_desk_negatif = false;
                        if (isset($desk_negatif_temp[0])) {
                            $minDeskripsi_key = array_search(
                                $desk_negatif_temp[0]['perubahan'],
                                array_column($tupeArray, 'uuid'),
                            );
                            $minDeskripsi = rtrim(lcfirst($tupeArray[$minDeskripsi_key]['tupe']), '.');
                            $minUUID = $desk_negatif_temp[0]['perubahan'];
                            $ada_temp_desk_negatif = true;
                        } else {
                            $minNilai = $array_list_nilai[0]['nilai'];
                            $minDeskripsi = rtrim(lcfirst($array_list_nilai[0]['tupe']), '.');
                        }
                        $min_keterangan = 'Perlu ditingkatkan dalam ' . $minDeskripsi . '.';
                    } else {
                        $rata2Formatif = 0;
                        $ada_temp_desk_positif = false;
                        $ada_temp_desk_negatif = false;
                        $maxUUID = 0;
                        $minUUID = 0;
                        $max_predikat = 'D';
                        $min_predikat = 'D';
                        $max_keterangan = '';
                        $min_keterangan = '';
                    }

                    $jumlah = 0;
                    //menghitung rata rata sumatif
                    foreach ($materiArray as $item) {
                        $nilaiSumatif += $sumatif_array[$item['uuid'] . '.' . $siswa->uuid]['nilai'];
                        $jumlah++;
                    }
                    if (count($materiArray) > 0) {
                        $rata2Sumatif = round($nilaiSumatif / $jumlah, 0);
                    } else {
                        $rata2Sumatif = 0;
                    }
                    //Mengambil Nilai PAS
                    if (isset($pas_array[$ngajar->uuid . '.' . $siswa->uuid])) {
                        $rata2Pas = $pas_array[$ngajar->uuid . '.' . $siswa->uuid]['nilai'];
                    } else {
                        $rata2Pas = 0;
                    }
                    //Menghitung Nilai Rapor
                    $totalRapor = round((2 * $rata2Formatif + $rata2Sumatif + $rata2Pas) / 4, 0);

                    //Mengambil Nilai Dari Temp
                    $id_siswa = $siswa->uuid;
                    $nilai_temp = array_values(
                        array_filter($temp_array, function ($var) use ($id_siswa) {
                            return $var['id_siswa'] == $id_siswa && $var['jenis'] == 'nilai';
                        }),
                    );
                    $ada_temp_nilai = false;
                    if (isset($nilai_temp[0])) {
                        $totalRapor = $nilai_temp[0]['perubahan'];
                        $ada_temp_nilai = true;
                    }
                    $nilai_manual = array_values(
                        array_filter($manual_array, function ($var) use ($id_siswa) {
                            return $var['id_siswa'] == $id_siswa;
                        }),
                    );
                    $ada_nilai_manual = false;
                    if(isset($nilai_manual[0])) {
                        $totalRapor = $nilai_manual[0]['nilai'];
                        $ada_nilai_manual = true;
                    }
                @endphp
                <td rowspan="2" align="center" valign="middle" style="border:1px solid #000"
                    @if ($rata2Formatif < $ngajar->kkm) bgcolor="#ffc6c6" @endif>
                    {{ $rata2Formatif }}</td>
                <td rowspan="2" align="center" valign="middle" style="border:1px solid #000"
                    @if ($rata2Sumatif < $ngajar->kkm) bgcolor="#ffc6c6" @endif>
                    {{ $rata2Sumatif }}</td>
                <td rowspan="2" align="center" valign="middle" style="border:1px solid #000"
                    @if ($rata2Pas < $ngajar->kkm) bgcolor="#ffc6c6" @endif>
                    {{ $rata2Pas }}</td>
                <td rowspan="2" align="center" valign="middle" style="border:1px solid #000"
                    @if ($totalRapor < $ngajar->kkm) bgcolor="#ffc6c6" @endif
                    >{{ $totalRapor }}</td>
                <td style="word-wrap:break-word; font-size:9px; border:1px solid #000" valign="middle">
                    {{$ada_nilai_manual ? $nilai_manual[0]['positif'] : $max_keterangan }}
                </td>
            </tr>
            <tr height="33">
                <td style="word-wrap:break-word; font-size:9px; border:1px solid #000" valign="middle">
                    {{$ada_nilai_manual ? $nilai_manual[0]['negatif'] : $min_keterangan }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
