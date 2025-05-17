@extends('layouts.main')

@section('container')
    @php
        $kelulusan_setting = $settingKelulusan->first(function($elemen) {
            if($elemen->jenis == "tanggal_kelulusan") {
                return $elemen;
            }
        });

        if($kelulusan_setting) {
            $array_tanggal_kelulusan = unserialize($kelulusan_setting->nilai);
            if($array_tanggal_kelulusan['kelulusan'] != null) {
                $tanggal_kelulusan = date('M d, Y H:i:s', strtotime($array_tanggal_kelulusan['kelulusan']));
            }
        } else {
            $array_tanggal_kelulusan = array();
        }
    @endphp
    <div class="col-12 body-contain-customize">
        <h5><b>Kelulusan</b></h5>
        <p>Halaman ini diperuntukkan siswa dan orangtua untuk melihat hasil kelulusan di tahun pelajaran berlangsung</p>
    </div>
    <div class="col-12 body-contain-customize mt-3">
        @if ($array_tanggal_kelulusan['tampil'] == "true" && $array_tanggal_kelulusan['kelulusan'] != null)
            @if (time() < strtotime($array_tanggal_kelulusan['kelulusan']))
                <div class="object">
                    <h4 class="text-center">Hitung Mundur Pengumuman Kelulusan</h4>
                    <ul>
                        <li><span id="days"></span>days</li>
                        <li><span id="hours"></span>Hours</li>
                        <li><span id="minutes"></span>Minutes</li>
                        <li><span id="seconds"></span>Seconds</li>
                    </ul>
                </div>
            @else
                <p>Kepala Sekolah {{$nama_sekolah}} menerangkan bahwa</p>
                <ul class="list-unstyled">
                    <li class="mt-2"><span><i class="fa-solid fa-pencil me-1"></i> Nama Siswa : </span>{{$siswa->nama}}</li>
                    <li class="mt-2"><span><i class="fa-solid fa-pencil me-1"></i> NIS / NISN : </span>{{$siswa->nis}} / {{$siswa->nisn}}</li>
                    <li class="mt-2"><span><i class="fa-solid fa-pencil me-1"></i> Tempat / Tanggal Lahir : </span>{{$siswa->tempat_lahir_ijazah}} / {{date('d F Y', strtotime($siswa->tanggal_lahir_ijazah))}}</li>
                    <li class="mt-2"><span><i class="fa-solid fa-pencil me-1"></i> Nama Orang tua : </span>{{$siswa->ortu_ijazah}}</li>
                    <li class="mt-2"><span><i class="fa-solid fa-pencil me-1"></i> Tahun Ajaran : </span>{{$semester->tp}}</li>
                </ul>

                <p class="mt-3">Peserta didik tersebut telah dinyatakan <b class="fs-20">{{$kelulusan && $kelulusan->kelulusan != null ? ( $kelulusan->kelulusan == "true" ? strtoupper("Lulus") : strtoupper("Tidak Lulus") ) : ""}}</b> dari sekolah {{$nama_sekolah}} pada tahun ajaran {{$semester->tp}} dengan nilai sebagai berikut : </p>
                <div class="row mt-3 m-0 p-0">
                    <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10 m-0 p-0">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="60%" class="text-center">Mata Pelajaran</th>
                                    <th width="10%" class="text-center">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                    $pelajaranKelulusan = $kelulusan->nilai ? unserialize($kelulusan->nilai) : [];
                                    $total = 0;
                                    $count = 0;
                                @endphp
                                @if ($pelajaranKelulusan)
                                    @foreach ($pelajaranKelulusan as $item => $value)
                                        <tr>
                                            <td class="text-center">{{$no++}}</td>
                                            <td>{{$pelajaranArray[$item]}}</td>
                                            <td class="text-center">{{$value}}</td>
                                        </tr>
                                        @php
                                            $total += $value;
                                            $count++;
                                        @endphp
                                    @endforeach
                                @endif
                                <tr>
                                    <td colspan="2"><b>Rata-Rata</b></td>
                                    <td class="text-center"><b>{{$total > 0 && $count > 0 ? round($total / $count,2) : 0}}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @else
            <div class="alert alert-danger" role="alert">
                <strong>Perhatian!</strong> Pengumuman kelulusan belum ditentukan. Cek Secara berkala untuk mendapatkan informasi lebih lanjut.
            </div>
        @endif
    </div>
    <script>
        $(document).ready(function() {
            const second = 1000,
            minute = second * 60,
            hour = minute * 60,
            day = hour * 24;
            var dist;
            let countDown = new Date("{{$tanggal_kelulusan}}").getTime(),
            x = setInterval(function () {
                let now = new Date().getTime(),
                distance = countDown - now;

                (document.getElementById("days").innerText = Math.floor(distance / day)),
                (document.getElementById("hours").innerText = Math.floor(
                    (distance % day) / hour
                )),
                (document.getElementById("minutes").innerText = Math.floor(
                    (distance % hour) / minute
                )),
                (document.getElementById("seconds").innerText = Math.floor(
                    (distance % minute) / second
                ));

                {{-- if (distance < 0) {
                clearInterval(x);
                $(".firstCol .object").removeClass("active");
                $(".firstCol .secondObject").addClass("active");
                $(".chatbox").attr("src", "image/chatboxReady.png");
                } else {
                $(".firstCol .object").addClass("active");
                $(".firstCol .secondObject").removeClass("active");
                } --}}
                dist = distance;
            }, second);
        });
    </script>
@endsection
