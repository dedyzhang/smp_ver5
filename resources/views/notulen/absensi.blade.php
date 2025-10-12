@extends('layouts.main')

@section('container')
    <div class="col-12 body-contain-customize">
        <h5>Notulen Rapat</h5>
        <p>Halaman ini diperuntukkan Guru yang ditunjuk, Admin, Kurikulum maupun kepala sekolah untuk membuat, melihat, mengupdate dan mencetak Notulen Hasil Rapat</p>
    </div>

    <div class="body-contain-customize mt-3 col-12 col-sm-12 col-md-8 col-lg-5 col-xl-5">
        <table class="table table-striped fs-12">
            <tr>
                <td width="35%">Hari</td>
                <td width="3%">:</td>
                <td width="62%">{{date('l',strtotime($notulen->tanggal_rapat))}}</td>
            </tr>
             <tr>
                <td width="35%">Tanggal</td>
                <td width="3%">:</td>
                <td width="62%">{{date('d M Y',strtotime($notulen->tanggal_rapat))}}</td>
            </tr>

        </table>
    </div>
    <div class="body-contain-customize mt-3 col-12">
        <p class="m-0 p-0"><b>List Daftar Hadir Guru</b></p>
        <p class="m-0 p-0 fs-10">Centang Guru yang Hadir</p>
        <div class="row mt-2 m-0 p-0 mb-2 mt-3">
            <div class="col-12 d-grid col-sm-12 d-grid col-md-auto d-md-auto col-lg-auto d-lg-auto col-xl-auto d-xl-auto">
                <button class="btn btn-sm btn-primary hadir-semua">Hadir Semua</button>
            </div>
        </div>
        <div class="row m-0 p-0">
            @foreach ($guru as $item)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 gy-3">
                    @php
                        $checked = in_array($item->uuid, $notulen_guru) ? 'checked' : '';
                    @endphp
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{$item->uuid}}" id="guru.{{$item->uuid}}" name="guru" {{ $checked }} />
                        <label class="form-check-label" for="guru.{{$item->uuid}}">{{$item->nama}} </label>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row mt-2 m-0 p-0 mb-2 mt-3">
            <div class="col-12 d-grid col-sm-12 d-grid col-md-auto d-md-auto col-lg-auto d-lg-auto col-xl-auto d-xl-auto">
                <button class="btn btn-sm btn-success simpan"><i class="fas fa-save"></i> Simpan Daftar Hadir</button>
            </div>
        </div>
    </div>
    <script>
        $('.hadir-semua').click(function() {
            $('input[name="guru"]').prop('checked', true);
        });

        $('.simpan').click(function() {
            var arrayGuru = [];
            $('input[name="guru"]:checked').each(function() {
                var id = $(this).val();
                arrayGuru.push(id);
            });
            loading();
            $.ajax({
                type: "POST",
                url: "{{ route('notulen.absensi.store') }}",
                data: {
                    'id_notulen': "{{ $notulen->uuid }}",
                    'array_guru': arrayGuru
                },
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                success: function(data) {
                    removeLoading();
                    cAlert('green','Sukses','Data Absensi Guru Berhasil Disimpan',false,"{{ route('notulen.index') }}");
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        });
    </script>
@endsection
