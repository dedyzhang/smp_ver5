@extends('layouts.main') @section('container')
    @if (\Request::route()->getName() === 'penilaian.admin.pas.showAll')
        {{ Breadcrumbs::render('penilaian-admin-pas-showAll', $kelas) }}
    @else
        {{ Breadcrumbs::render('walikelas-nilai-pas') }}
    @endif
    <div class="body-contain-customize col-12">
        <h5>
            <b>PAS Kelas {{ $kelas->tingkat . $kelas->kelas }}</b>
        </h5>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="table-responsive">
            <table class="table table-bordered fs-11">
                <thead>
                    <tr class="text-center align-middle">
                        <td rowspan="3" width="3%">No</td>
                        <td rowspan="3" style="min-width: 170px" width="20%">Nama</td>
                        <td colspan="{{ $ngajar->count() }}">Nilai</td>
                        <td rowspan="3" width="5%">Jumlah</td>
                        <td rowspan="3" width="10%">Rata-Rata</td>
                    </tr>
                    <tr class="text-center">
                        @foreach ($ngajar as $item)
                            <td width="3%" style="min-width: 50px" data-bs-toggle="tooltip"
                                data-bs-title="{{ $item->guru->nama }}" data-bs-placement="top">
                                {{ $item->pelajaran_singkat }}
                            </td>
                        @endforeach
                    </tr>
                    <tr class="text-center">
                        @foreach ($ngajar as $item)
                            <td width="3%">{{ $item->kkm }}</td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $siswa)
                        @php
                            $jumlah = 0;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $siswa->nama }}</td>
                            @foreach ($ngajar as $item)
                                @if (isset($pas_array[$item->uuid . '.' . $siswa->uuid]))
                                    <td
                                        class="text-center @if ($pas_array[$item->uuid . '.' . $siswa->uuid] < $item->kkm) text-danger bg-danger-subtle @endif">
                                        {{ $pas_array[$item->uuid . '.' . $siswa->uuid] }}</td>
                                    @php
                                        $jumlah += $pas_array[$item->uuid . '.' . $siswa->uuid];
                                    @endphp
                                @else
                                    <td class="text-center">0</td>
                                    @php
                                        $jumlah += 0;
                                    @endphp
                                @endif
                            @endforeach
                            <td class="text-center">{{ $jumlah }}</td>
                            <td class="text-center">{{ round($jumlah / $ngajar->count(), 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
