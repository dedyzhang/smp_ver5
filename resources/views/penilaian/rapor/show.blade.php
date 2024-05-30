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
        <p><b>Penilaian Rapor Semester</b></p>
        <div class="table-responsive">
            <table class="table table-bordered fs-12 nilai-table align-middle">
                <thead>
                    <tr class="text-center">
                        <td width="3%">No</td>
                        <td width="15%" style="min-width: 150px">Siswa</td>
                        <td width="5%" data-bs-toggle="tooltip" data-bs-title="Rata-rata nilai formatif" data-bs-placement="top">RF</td>
                        <td width="5%" data-bs-toggle="tooltip" data-bs-title="Rata-rata nilai Sumatif" data-bs-placement="top">RS</td>
                        <td width="5%" data-bs-toggle="tooltip" data-bs-title="Nilai PAS / PAT" data-bs-placement="top">PAS</td>
                        <td width="5%" data-bs-toggle="tooltip" data-bs-title="Nilai Rapor" data-bs-placement="top">NR</td>
                        <td width="30%" style="min-width: 250px">Deskripsi Rapor</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ngajar->siswa as $siswa)
                        <tr data-siswa="{{$siswa->uuid}}" data-ngajar="{{$ngajar->uuid}}">
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
                                        "uuid" => $tupe['uuid'],
                                        "tupe" => $tupe['tupe'],
                                        "nilai" => $formatif_array[$tupe['uuid'].".".$siswa->uuid]['nilai']
                                    ));
                                    $jumlah++;
                                }
                                $rata2Formatif = round($nilaiFormatif/$jumlah,0);
                                //Mencari Deskripsi Tertinggi dan terendah
                                array_multisort(array_column($array_list_nilai, 'nilai'), SORT_ASC, ($array_list_nilai));
                                //Mencari deskripsi temp
                                // $desk_positif_temp = array_values(array_filter($temp_array,function($var) use ($id_siswa) {
                                //     return ($var['id_siswa'] == $id_siswa && $var['jenis'] == 'deskripsi_positif');
                                // }));
                                // $ada_temp_desk_positif = false;
                                // if(isset($desk_positif_temp[0])) {
                                //     $ = $nilai_temp[0]['perubahan'];
                                //     $ada_temp_nilai = true;
                                // }
                                //mencari rentang deskripsi
                                $maxNilai = end($array_list_nilai)['nilai'];
                                $maxUUID = end($array_list_nilai)['uuid'];
                                $maxDeskripsi = rtrim(lcfirst(end($array_list_nilai)['tupe']),'.');
                                $minNilai = $array_list_nilai[0]['nilai'];
                                $minUUID = $array_list_nilai[0]['uuid'];
                                $minDeskripsi = rtrim(lcfirst($array_list_nilai[0]['tupe']),'.');
                                if($maxNilai < $Cdown) {
                                    $max_keterangan = "Perlu bimbingan dalam ".$maxDeskripsi.".";
                                    $max_predikat = "d";
                                } else if($maxNilai >= $Cdown && $maxNilai <= $Cup) {
                                    $max_keterangan = "Menunjukkan penguasaan yang cukup baik dalam ".$maxDeskripsi.".";
                                    $max_predikat = "c";
                                } else if($maxNilai >= $Bdown && $maxNilai <= $Bup) {
                                    $max_keterangan = "Menunjukkan penguasaan yang baik dalam ".$maxDeskripsi.".";
                                    $max_predikat = "b";
                                } else if($maxNilai >= $Adown && $maxNilai <= $Aup) {
                                    $max_keterangan = "Menunjukkan penguasaan yang amat baik dalam ".$maxDeskripsi.".";
                                    $max_predikat = "a";
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

                                //Mengambil Nilai Dari Temp
                                $id_siswa = $siswa->uuid;
                                $nilai_temp = array_values(array_filter($temp_array,function($var) use ($id_siswa) {
                                    return ($var['id_siswa'] == $id_siswa && $var['jenis'] == 'nilai');
                                }));
                                $ada_temp_nilai = false;
                                if(isset($nilai_temp[0])) {
                                    $totalRapor = $nilai_temp[0]['perubahan'];
                                    $ada_temp_nilai = true;
                                }
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
                            <td rowspan="2" class="text-center ganti-nilai @if ($totalRapor < $ngajar->kkm)
                                bg-danger-subtle text-danger
                            @endif @if ($ada_temp_nilai) ada-perubahan @endif" style="cursor: pointer">{{$totalRapor}}</td>
                            <td class="fs-12 ganti-deskripsi-positif" data-uuid="{{$maxUUID}}" data-predikat={{$max_predikat}} style="cursor: pointer">{{$max_keterangan}}</td>
                        </tr>
                        <tr>
                            <td class="fs-12" style="cursor: pointer">{{$min_keterangan}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="button-place mt-3 d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex">
            <button class="btn btn-sm btn-warning text-warning-emphasis simpan-nilai"><i class="fas fa-save"></i> Konfirmasi Nilai</button>
        </div>
    </div>
    <div class="modal fade in" id="modal-ganti-nilai">
        <div class="modal-dialog-centered modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Ubah Nilai Rapor</h6>
                    <button class="btn btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-0">
                        <div class="col-12 p-0 form-group">
                            <p class="fs-13">Perubahan Nilai dilakukan jika nilai sudah tidak bisa didongkrak lagi dan harus diubah secara manual</p>
                            <input type="hidden" id="id_siswa">
                            <input type="hidden" id="id_ngajar">
                            <input type="text" class="form-control" style="font-size: 14px !important" name="nilai" id="nilai" placeholder="Masukkan Nilai baru">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-warning simpan-nilai"><i class="fas fa-save"></i> simpan</button>
                    <button class="btn btn-sm btn-danger hapus-nilai" style="display: none"><i class="fas fa-trash-can"></i> Hapus</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade in" id="modal-ganti-desc-positif">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p><b>Ganti Deskripsi Siswa</b></p>
                    <button class="btn btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-0 p-0 form-group align-items-center">
                        <div class="col-auto">
                            Memiliki penguasaan yang
                        </div>
                        <div class="col-auto">
                            <input type="hidden" id="id_siswa_desc_positif">
                            <input type="hidden" id="id_ngajar_desc_positif">
                            <select class="form-control" style="font-size: 14px !important" id="predikat" name="predikat">
                                <option value="a">amat baik</option>
                                <option value="b">baik</option>
                                <option value="c">cukup baik</option>
                                <option value="d">perlu bimbingan</option>
                            </select>
                        </div>
                        <div class="col-auto">dalam</div>
                    </div>
                    <div class="row m-0 mt-3 p-0">
                        <div class="col-12">
                            <select class="form-control" style="font-size:14px !important" id="deskripsi-positif" name="deskripsi-positif">
                                @foreach ($tupeArray as $tupe)
                                    <option value="{{$tupe['uuid']}}">{{$tupe['tupe']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-warning text-warning-emphasis simpan-deskripsi-positif"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.ganti-nilai').click(function() {
            $('.hapus-nilai').hide();
            var nilai = $(this).text();
            var idSiswa = $(this).closest('tr').data('siswa');
            var idNgajar = $(this).closest('tr').data('ngajar');
            $('#nilai').val(nilai);
            $('#id_siswa').val(idSiswa);
            $('#id_ngajar').val(idNgajar);
            if($(this).hasClass('ada-perubahan')) {
                $('.hapus-nilai').show();
            }
            $('#modal-ganti-nilai').modal("show");
        });
        $('.simpan-nilai').click(function() {
            var nilai = $('#nilai').val();
            var idSiswa = $('#id_siswa').val();
            var idNgajar = $('#id_ngajar').val();

            if(nilai == "") {
                oAlert("orange","Perhatian","Nilai tidak boleh kosong");
            } else {
                var ubahNilai = () => {
                    BigLoading("Nilai sedang diubah, mohon tidak menutup aplikasi sebelum nilai berhasil diubah");
                    var url = "{{route('penilaian.rapor.edit',':id')}}";
                    url = url.replace(':id',idNgajar);
                    $.ajax({
                        type: "POST",
                        url: url,
                        headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                        data: {
                            "idNgajar" : idNgajar,
                            "idSiswa": idSiswa,
                            "perubahan": nilai,
                            "jenis": "nilai"
                        },
                        success: function (data) {
                            removeLoadingBig();
                            cAlert('green','Sukses','Nilai berhasil disimpan',true);
                        },
                        error: function (data) {
                            console.log(data.responseJSON.message);
                        }
                    });
                }

                cConfirm("Perhatian","Yakin untuk mengubah nilai siswa bersangkutan",ubahNilai);
            }
        })
        $('.ganti-deskripsi-positif').click(function() {
            var uuid = $(this).data('uuid');
            var predikat = $(this).data('predikat');
            var idSiswa = $(this).closest('tr').data('siswa');
            var idNgajar = $(this).closest('tr').data('ngajar');
            $('#id_siswa_desc_positif').val(idSiswa);
            $('#id_ngajar_desc_positif').val(idNgajar);
            $('#deskripsi-positif').val(uuid);
            $('#predikat').val(predikat);
            $('#modal-ganti-desc-positif').modal("show");
        });
        $('.simpan-deskripsi-positif').click(function() {
            var predikat = $('#predikat').val();
            var deskripsi = $('#deskripsi-positif').val();
            var idSiswa = $('#id_siswa_desc_positif').val();
            var idNgajar = $('#id_ngajar_desc_positif').val();
            var perubahan = predikat+"."+deskripsi;
            var gantiDeskripsiPositif = () => {
                BigLoading("Nilai sedang diubah, mohon tidak menutup aplikasi sebelum nilai berhasil diubah");
                var url = "{{route('penilaian.rapor.edit',':id')}}";
                url = url.replace(':id',idNgajar);
                $.ajax({
                    type: "POST",
                    url: url,
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    data: {
                        "idNgajar" : idNgajar,
                        "idSiswa": idSiswa,
                        "perubahan": perubahan,
                        "jenis": "deskripsi_positif"
                    },
                    success: function (data) {
                        removeLoadingBig();
                        cAlert('green','Sukses','Deskripsi berhasil disimpan',true);
                    },
                    error: function (data) {
                        console.log(data.responseJSON.message);
                    }
                });
            }
            cConfirm("Perhatian","Yakin untuk ganti deskripsi rapor bersangkutan",gantiDeskripsiPositif);
        })
    </script>
@endsection
