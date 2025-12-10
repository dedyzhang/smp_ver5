@extends('layouts.main')

@section('container')
    @if (\Request::route()->getName() === 'walikelas.koku')
        {{Breadcrumbs::render('kokurikuler-wali')}}
    @else
        {{Breadcrumbs::render('kokurikuler-show',$kelas)}}
    @endif
    <div class="body-contain-customize col-md-12 col-lg-12 col-sm-12 mt-3">
        <h5><b>Data Kokurikuler Siswa</b></h5>
         <p>Halaman ini berguna untuk mengakses, mengedit, dan menyelesaikan Data Kokurikuler siswa Dalam 1 semester Tahun Pelajaran.</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="table-wrapper">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <td width="5%">No</td>
                        <td width="20%">Nama</td>
                        <td width="10%">NIS</td>
                        <td width="65%" style="min-width: 200px">Deskripsi Kokurikuler</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $item)
                        @php
                            $find = $koku->first(function($elem) use($item) {
                                if($elem->id_siswa == $item->uuid) {
                                    return $elem;
                                }
                            });
                        @endphp
                        <tr id="siswa.{{ $item->uuid }}">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->nama}}</td>
                            <td>{{$item->nis}}</td>
                            <td class="editable" contenteditable="true">
                                {{ $find && $find->deskripsi ? $find->deskripsi : "" }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        var textAwal;
        $('table').on('focus','.editable',function(){
            textAwal = $(this).text();
        });
        $('table').on('blur','.editable',function() {
            $('.editable').prop('contenteditable',false);
            var siswa = $(this).closest('tr').attr('id').split('.').pop();
            var deskripsi = $(this).text();
            var url = "{{ route('koku.update',':id') }}";
            url = url.replace(':id',siswa);
            var ini = this;

            if(textAwal != deskripsi) {
                $(this).css({
                    'background':'url({{asset('img/loading.gif')}}) right no-repeat',
                    'background-size': 'contain',
                    'background-origin': 'content-box'
                });
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {'deskripsi': deskripsi},
                    headers: {'X-CSRF-TOKEN' : '{{ csrf_token() }}'},
                    success: function(data) {
                        $('.editable').prop('contenteditable',true);
                        if(data.success == "true") {
                            $(ini).css({
                                'background':'none',
                            });
                        }
                    },error: function(data) {
                        console.log(data.responseJSON.message);
                        $('.editable').prop('contenteditable',true);
                    }
                })
            } else {
                $('.editable').prop('contenteditable',true);
            }
        })
    </script>
@endsection