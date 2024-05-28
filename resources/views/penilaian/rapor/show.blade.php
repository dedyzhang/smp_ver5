@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('penilaian-rapor-show',$ngajar->pelajaran,$ngajar->kelas,$ngajar)}}
    <div class="body-contain-customize col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5">
        <p><b>Data Ngajar</b></p>
        <table class="table table-striped fs-13">
            <tr>
                <td width="30%">Pelajaran</td>
                <td width="5%">:</td>
                <td>{{$ngajar->pelajaran->pelajaran}}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{$ngajar->kelas->tingkat.$ngajar->kelas->kelas}}</td>
            </tr>
            <tr>
                <td>Guru</td>
                <td>:</td>
                <td>{{$ngajar->guru->nama}}</td>
            </tr>
            <tr>
                <td>KKTP</td>
                <td>:</td>
                <td>{{$ngajar->kkm}}</td>
            </tr>
        </table>
        @if ($ngajar->kkm == 0)
            <div
                class="alert alert-warning" role="alert">
                <strong><i class="fas fa-triangle-exclamation"></i> Perhatian</strong> KKTP untuk data ngajar masih belum diatur. Guru dapat mengatur KKTP dihalaman Buku Guru > KKTP
            </div>
        @endif
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
        {{-- <div class="alert alert-primary" role="alert">
            <h4 class="alert-heading">Info !</h4>
            <p>Untuk memudahkan dalam pengisian nilai maka, dapat menggunakan tombol <kbd><i class="fas fa-arrow-left"></i></kbd> <kbd><i class="fas fa-arrow-right"></i></kbd> <kbd><i class="fas fa-arrow-up"></i></kbd> <kbd><i class="fas fa-arrow-down"></i></kbd> <kbd>enter</kbd> dan <kbd>tab</kbd> untuk berpindah column maupun baris</p>
            <hr>
            <p class="mb-0">Terima kasih atas perhatiannya</p>
        </div> --}}
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
        <p><b>Penilain Rapor Semester</b></p>
        <div class="table-responsive">
            <table class="table table-bordered fs-12 nilai-table align-middle">
                <thead>
                    <tr class="text-center">
                        <td width="3%">No</td>
                        <td width="30%" class="sticky" style="min-width: 150px">Siswa</td>
                        <td width="5%" data-bs-toggle="tooltip" data-bs-title="Rata-rata nilai formatif" data-bs-placement="top">RF</td>
                        <td width="5%" data-bs-toggle="tooltip" data-bs-title="Rata-rata nilai Sumatif" data-bs-placement="top">RS</td>
                        <td width="5%" data-bs-toggle="tooltip" data-bs-title="Nilai PAS / PAT" data-bs-placement="top">PAS</td>
                        <td width="5%" data-bs-toggle="tooltip" data-bs-title="Nilai Rapor" data-bs-placement="top">NR</td>
                        <td width="30%">Deskripsi Rapor</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ngajar->siswa as $siswa)
                        <tr>
                            <td rowspan="2">{{$loop->iteration}}</td>
                            <td rowspan="2">{{$siswa->nama}}</td>
                            @php
                                $nilaiFormatif = 0;
                                $nilaiSumatif = 0;
                                $jumlah = 0;
                                $kkm = $ngajar->kkm;
                                //Menghitung rentan Nilai
                                $interval = round((100-$kkm)/3,0);
                                $Cdown = $kkm;
                                $Cup = ($kkm + $interval) - 1;
                                $Bdown = $Cup + 1;
                                $Bup = ($Bdown + $interval) - 1;
                                $Adown = $Bup + 1;
                                $Aup = 100;

                                //Menghitung rata rata formatif
                                $array_list_nilai = array();
                                foreach ($tupeArray as $tupe) {
                                    $nilaiFormatif += $formatif_array[$tupe['uuid'].".".$siswa->uuid]['nilai'];
                                    array_push($array_list_nilai, array(
                                        "tupe" => $tupe['tupe'],
                                        "nilai" => $formatif_array[$tupe['uuid'].".".$siswa->uuid]['nilai']
                                    ));
                                    $jumlah++;
                                }
                                $rata2Formatif = round($nilaiFormatif/$jumlah,0);
                                //Mencari Deskripsi Tertinggi dan terendah
                                array_multisort(array_column($array_list_nilai, 'nilai'), SORT_ASC, ($array_list_nilai));
                                //mencari rentang deskripsi
                                $maxNilai = end($array_list_nilai)['nilai'];
                                $maxDeskripsi = rtrim(lcfirst(end($array_list_nilai)['tupe']),'.');
                                $minNilai = $array_list_nilai[0]['nilai'];
                                $minDeskripsi = rtrim(lcfirst($array_list_nilai[0]['tupe']),'.');
                                if($maxNilai < $Cdown) {
                                    $max_keterangan = "Perlu bimbingan dalam ".$maxDeskripsi.".";
                                } else if($maxNilai >= $Cdown && $maxNilai <= $Cup) {
                                    $max_keterangan = "Menunjukkan penguasaan yang cukup baik dalam ".$maxDeskripsi.".";
                                } else if($maxNilai >= $Bdown && $maxNilai <= $Bup) {
                                    $max_keterangan = "Menunjukkan penguasaan yang baik dalam ".$maxDeskripsi.".";
                                } else if($maxNilai >= $Adown && $maxNilai <= $Aup) {
                                    $max_keterangan = "Menunjukkan penguasaan yang amat baik dalam ".$maxDeskripsi.".";
                                }
                                $min_keterangan = 'Perlu ditingkatkan dalam '.$minDeskripsi.'.';


                                $jumlah = 0;
                                //menghitung rata rata sumatif
                                foreach ($materiArray as $item) {
                                    $nilaiSumatif += $sumatif_array[$item['uuid'].".".$siswa->uuid]['nilai'];
                                    $jumlah++; 
                                }
                                $rata2Sumatif = round($nilaiSumatif/$jumlah,0);
                                //Mengambil Nilai PAS
                                if(isset($pas_array[$ngajar->uuid.".".$siswa->uuid])) {
                                    $rata2Pas = $pas_array[$ngajar->uuid.".".$siswa->uuid]['nilai'];
                                } else {
                                    $rata2Pas = 0;
                                }
                                //Menghitung Nilai Rapor
                                $totalRapor = round(((2*$rata2Formatif)+$rata2Sumatif+$rata2Pas)/4,0);
                            @endphp
                            <td rowspan="2" class="text-center @if ($rata2Formatif < $ngajar->kkm)
                                bg-danger-subtle text-danger
                            @endif">{{$rata2Formatif}}</td>
                            <td rowspan="2" class="text-center @if ($rata2Sumatif < $ngajar->kkm)
                                bg-danger-subtle text-danger
                            @endif">{{$rata2Sumatif}}</td>
                            <td rowspan="2" class="text-center @if ($rata2Pas < $ngajar->kkm)
                                bg-danger-subtle text-danger
                            @endif">{{$rata2Pas}}</td>
                            <td rowspan="2" class="text-center @if ($totalRapor < $ngajar->kkm)
                                bg-danger-subtle text-danger
                            @endif">{{$totalRapor}}</td>
                            <td class="fs-12">{{$max_keterangan}}</td>
                        </tr>
                        <tr>
                            <td class="fs-12">{{$min_keterangan}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="button-place mt-3 d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex">
            <button class="btn btn-sm btn-warning text-warning-emphasis simpan-nilai"><i class="fas fa-save"></i> Konfirmasi Nilai</button>
        </div>
    </div>
@endsection
