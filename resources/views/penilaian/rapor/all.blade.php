@extends('layouts.main') @section('container')
    @if (\Request::route()->getName() === 'penilaian.admin.rapor.showAll')
        {{ Breadcrumbs::render('penilaian-admin-rapor-showAll', $kelas) }}
    @else
        {{ Breadcrumbs::render('walikelas-nilai-olahan') }}
    @endif
    <div class="body-contain-customize col-12">
        <h5>
            <b>Rapor Kelas {{ $kelas->tingkat . $kelas->kelas }}</b>
        </h5>
    </div>
    <div class="body-contain-customize col-12 d-grid col-sm-12 d-sm-grid col-md-auto d-md-flex col-lg-auto d-lg-flex">
        <a href="{{ route('penilaian.admin.rapor.semua',$kelas->uuid) }}" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Print Rapor Semua Siswa</a>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="table-responsive">
            <table class="table table-bordered fs-11 nilai-rapor">
                <thead>
                    <tr class="text-center align-middle">
                        <td rowspan="3" width="3%">No</td>
                        <td rowspan="3" style="min-width: 170px" width="20%">
                            Nama
                        </td>
                        @if (\Request::route()->getName() === 'penilaian.admin.rapor.showAll')
                        <td rowspan="3" style="min-width:80px">
                           Print Rapor
                        </td>
                        @endif
                        <td colspan="{{ $ngajar->count() }}" class="main-cell">
                            Nilai
                        </td>
                        <td rowspan="3" width="5%">Jumlah</td>
                        <td rowspan="3" width="10%">Rata-Rata</td>
                    </tr>
                    <tr class="text-center">
                        @foreach ($ngajar as $item)
                            <td width="3%" colspan="1" style="min-width: 50px" class="parent-cell tutup"
                                id="parent.{{ $item->uuid }}" data-bs-toggle="tooltip"
                                data-bs-title="{{ $item->guru->nama }}" data-bs-placement="top">
                                {{ $item->pelajaran_singkat }}
                            </td>
                        @endforeach
                    </tr>
                    <tr class="text-center" style="border-bottom-width: 4px">
                        @foreach ($ngajar as $item)
                            <td width="3%" class="second-parent-cell parent{{ $item->uuid }}" colspan="1">
                                {{ $item->kkm }}
                            </td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $siswa)
                        @php $jumlah = 0; @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $siswa->nama }}</td>
                            @if (\Request::route()->getName() === 'penilaian.admin.rapor.showAll')
                            <td class="text-center">
                                <a href="{{route('penilaian.admin.rapor.individu',$siswa->uuid)}}"><i class="fas fa-print"></i></a>
                            </td>
                            @endif
                            @foreach ($ngajar as $item)
                                @if (isset($rapor_array[$item->uuid . '.' . $siswa->uuid]))
                                    <td
                                        class="text-center child-cell no-hide child{{ $item->uuid }} @if ($rapor_array[$item->uuid . '.' . $siswa->uuid]['nilai'] < $item->kkm) text-danger bg-danger-subtle @endif">
                                        {{ $rapor_array[$item->uuid . '.' . $siswa->uuid]['nilai'] }}
                                    </td>
                                    @php $jumlah += $rapor_array[$item->uuid . '.' . $siswa->uuid]['nilai']; @endphp
                                    <td class="child-cell hide child{{ $item->uuid }}" data-bs-toggle="tooltip"
                                        data-bs-title="{{ $rapor_array[$item->uuid . '.' . $siswa->uuid]['positif'] }}"
                                        data-bs-placement="top">
                                        <i class="fas fa-square-plus text-success"></i>
                                    </td>
                                    <td class="child-cell hide child{{ $item->uuid }}" data-bs-toggle="tooltip"
                                        data-bs-title="{{ $rapor_array[$item->uuid . '.' . $siswa->uuid]['negatif'] }}"
                                        data-bs-placement="top">
                                        <i class="fas fa-square-minus text-danger"></i>
                                    </td>
                                @else
                                    <td class="text-center child-cell no-hide child{{ $item->uuid }}">
                                        0
                                    </td>
                                    <td class="child-cell hide child{{ $item->uuid }}">
                                        <i class="fas fa-square-plus text-success"></i>
                                    </td>
                                    <td class="child-cell hide child{{ $item->uuid }}">
                                        <i class="fas fa-square-minus text-danger"></i>
                                    </td>
                                    @php $jumlah += 0; @endphp
                                @endif
                            @endforeach
                            <td class="text-center">{{ $jumlah }}</td>
                            <td class="text-center">
                                {{ round($jumlah / $ngajar->count(), 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $(".parent-cell").click(function() {
            var id = $(this).attr("id").split(".").pop();
            var defaultColspan = "{{ $ngajar->count() }}";
            $(".main-cell").attr("colspan", defaultColspan);
            $(".parent-cell").attr("colspan", 1);
            $(".second-parent-cell").attr("colspan", 1);
            $(".hide").css("display", "none");

            if ($(this).hasClass("tutup")) {
                $(".parent-cell").removeClass("buka").addClass("tutup");
                $(this).addClass("buka").removeClass("tutup");
                var mainCell = parseInt($(".main-cell").attr("colspan"));
                $(this).attr("colspan", 3);
                $(".main-cell").attr("colspan", mainCell + 2);
                $(".parent" + id).attr("colspan", 3);
                var kelas = ".hide.child" + id;
                $(kelas).css("display", "table-cell");
            } else {
                $(".parent-cell").removeClass("buka").addClass("tutup");
                $(this).addClass("tutup").removeClass("buka");
                $(this).attr("colspan", 1);
                $(".main-cell").attr("colspan", defaultColspan);
                $(".parent" + id).attr("colspan", 1);
                var kelas = ".hide.child" + id;
                $(kelas).css("display", "none");
            }
        });
    </script>
@endsection
