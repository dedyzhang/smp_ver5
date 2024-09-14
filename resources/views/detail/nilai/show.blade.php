@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12">
        <h5>Informasi Penilaian</h5>
        <p>Halaman ini merupakan halaman informasi penilaian yang diberikan oleh guru</p>
    </div>
    <div class="mt-3 p-0">
    @foreach ($materiArray as $materiItem)
        <div class="aspect-tab">
            <input id="item-{{$materiItem['uuid']}}" type="checkbox" class="aspect-input" name="aspect">
            <label for="item-{{$materiItem['uuid']}}" class="aspect-label"></label>
            <div class="aspect-content">
                <div class="aspect-info">
                    <div class="chart-pie">
                        <span class="chart-pie-count text-primary-emphasis fs-25"><i class="fa-solid fa-chalkboard-user"></i></span>
                    </div>
                    <div class="aspect-name">
                       <p class="mt-3">{{$materiItem['materi']}}</p>
                    </div>
                </div>
            </div>
            <div class="aspect-tab-content">
                <div class="sentiment-wrapper fs-12">
                    <p class="mb-1"><b>A.Nilai Formatif</b></p>
                    <p>Nilai formatif adalah penilaian yang dilakukan selama proses pembelajaran berlangsung untuk memantau kemajuan belajar siswa</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="85%">Tujuan Pembelajaran</th>
                                <th width="10%">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                                $rata2 = 0;
                                $jumlah = 0;
                            @endphp
                            @foreach ($tupeArray as $tupe)
                                @php
                                    $rata2 += $formatif_array[$tupe['uuid']]['nilai'];
                                    $jumlah++;
                                @endphp
                                @if ($tupe['id_materi'] === $materiItem['uuid'])
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$tupe['tupe']}}</td>
                                        <td class="@if ($formatif_array[$tupe['uuid']]['nilai'] < $ngajar->kkm) bg-danger-subtle @else bg-success-subtle @endif">{{$formatif_array[$tupe['uuid']]['nilai']}}</td>
                                    </tr>
                                @endif
                                @php
                                    $no++;
                                @endphp
                            @endforeach
                            @php
                                $rata2 = round($rata2/$jumlah,2);
                            @endphp
                                <tr>
                                    <td colspan="2"><b>Rata-Rata Nilai Formatif untuk Materi ini</b></td>
                                    <td class="@if ($rata2 < $ngajar->kkm) bg-danger-subtle @else bg-success-subtle @endif">{{$rata2}}</td>
                                </tr>
                        </tbody>
                    </table>

                    <p class="mt-5 mb-1"><b>B.Nilai Sumatif</b></p>
                    <p>Nilai sumatif adalah nilai yang didapatkan dari hasil penilaian sumatif, yaitu proses evaluasi untuk mengetahui tingkat pencapaian siswa pada akhir periode pembelajaran</p>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td width="90%">Nilai Sumatif dalam Materi ini</td>
                                <td class="@if ($sumatif_array[$materiItem['uuid']]['nilai'] < $ngajar->kkm) bg-danger-subtle @else bg-success-subtle @endif">{{$sumatif_array[$materiItem['uuid']]['nilai']}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
    </div>
@endsection
