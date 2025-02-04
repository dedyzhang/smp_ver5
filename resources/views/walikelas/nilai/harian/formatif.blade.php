@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('walikelas-nilai-harian-formatif',$ngajar)}}
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
        <p><b>Penilaian Formatif</b></p>
        <div class="table-responsive">
            <table class="table table-bordered fs-12 nilai-table">
                <thead>
                    <tr class="text-center">
                        <td width="3%" rowspan="3">No</td>
                        <td width="30%" rowspan="3" class="sticky" style="min-width: 150px">Siswa</td>
                        <td class="mainNilaiCell" colspan="{{$count}}">Materi</td>
                    </tr>
                    <tr class="text-center">
                        @foreach ($materiArray as $item)
                            <td class="open-close-tab tab_{{$item['uuid']}} bg-warning-subtle" data-bs-toggle="tooltip" data-bs-title="{{$item['materi']}}" data-bs-placement="top" data-tupe="{{$item['jumlahTupe']}}" colspan="1">M{{$loop->iteration}}</td>
                        @endforeach
                    </tr>
                    <tr class="text-center">
                        @foreach ($materiArray as $materi)
                            @foreach ($tupeArray as $tupe)
                                @if ($tupe['id_materi'] === $materi['uuid'])
                                    <td class="subtab subtab_{{$tupe['id_materi']}}" width="5%" data-bs-toggle="tooltip" @if($tupe['tupe'] !== null ) data-bs-title="{{$tupe['tupe']}}" @endif data-bs-placement="top">TP{{$loop->iteration}}</td>
                                @endif
                            @endforeach
                            <td class="end-cell" width="5%" data-bs-title="rata-rata" data-bs-toggle="tooltip" data-bs-placement="top">NA</td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ngajar->siswa as $siswa)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class="sticky">{{$siswa->nama}}</td>
                            @foreach ($materiArray as $item)
                                @php
                                    $countMateri = 0;
                                    $jumlah = 0;
                                @endphp
                                @foreach ($tupeArray as $tupe)
                                    @if ($tupe['id_materi'] === $item['uuid'])
                                        @if (isset($formatif_array[$tupe['uuid'].".".$siswa->uuid]))
                                            @php
                                                $jumlah += $formatif_array[$tupe['uuid'].".".$siswa->uuid]['nilai'];
                                                $countMateri += 1;
                                            @endphp
                                            <td width="5%" data-formatif="{{$formatif_array[$tupe['uuid'].".".$siswa->uuid]['uuid']}}" contenteditable="true" class="text-center subtab subtab_{{$tupe['id_materi']}} nilai_{{$tupe['id_materi']}} nilai editable @if ($formatif_array[$tupe['uuid'].".".$siswa->uuid]['nilai'] < $ngajar->kkm) text-danger bg-danger-subtle @endif">{{$formatif_array[$tupe['uuid'].".".$siswa->uuid]['nilai']}}</td>
                                        @else
                                            <td width="5%" class="text-center subtab subtab_{{$tupe['id_materi']}} null-nilai">-</td>
                                        @endif
                                    @endif
                                @endforeach
                                    @if ($countMateri > 0)
                                        <td width="5%" class="text-center nilai_{{$item['uuid']}} end-cell @if (round($jumlah / $countMateri ,0) < $ngajar->kkm)
                                        text-danger bg-danger-subtle
                                        @endif">{{round($jumlah / $countMateri,0)}}</td>
                                    @else
                                        <td width="5%">-</td>
                                    @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $('.open-close-tab').click(function(){
            var show = $(this).attr('class').split(' ');
            var showNo = show[1].split('_').pop();
            var jumlahColspan = $(this).data('tupe');
            $('.subtab').css('display','none');
            $('.open-close-tab').attr('colspan','1');
            $(this).attr('colspan','1');
            // $('.save').attr('colspan','1');
            if($(this).hasClass('bg-warning-subtle')) {
                $('.open-close-tab').removeClass('bg-success-subtle').addClass('bg-warning-subtle');
                $(this).removeClass('bg-warning-subtle').addClass('bg-success-subtle');
                // $(this).removeClass('text-danger').addClass('text-primary');
                // $(this).html('<i class="mdi mdi-18px mdi-minus-box"></i>');

                $(this).attr('colspan',jumlahColspan + 1);
                $('.mainNilaiCell').attr('colspan',$('.mainNilaiCell').attr('colspan') + jumlahColspan + 1);
                $('.subtab_'+showNo).css('display','table-cell');

                // $('.save-'+showNo).attr('colspan',jumlahColspan + 1);
            } else {
                $('.open-close-tab').removeClass('bg-success-subtle').addClass('bg-warning-subtle');
                $(this).removeClass('bg-success-subtle').addClass('bg-warning-subtle');
                // $(this).removeClass('text-primary').addClass('text-danger');
                // $(this).html('<i class="mdi mdi-18px mdi-plus-box"></i>');
                // $('.kd-'+showNo).attr('colspan','1');
                $('.mainNilaiCell').attr('colspan',$('.mainNilaiCell').attr('colspan') - jumlahColspan + 1);
                $(this).attr('colspan','1');
                $('.subtab_'+showNo).css('display','none');
                // $('.save-'+showNo).attr('colspan','1');
            }
        });
    </script>
@endsection
