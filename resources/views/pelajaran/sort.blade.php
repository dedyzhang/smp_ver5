@extends('layouts.main')

@section('container')
    <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <h5><b>Atur Urutan</b></h5>
    </div>
    <div class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
        <div class="sort-pelajaran row m-0 mt-3">
            <i class="ps-0">Tarik dan geser ke atas atau kebawah untuk mengganti urutan soal</i>
            <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8 p-0">
                <ul id="sortable-pelajaran" style="padding-left: 0 !important;cursor:pointer;">
                    @foreach ($pelajaran as $plj)
                        <li class="d-block p-3 item-sort bg-warning-subtle" style="border-bottom:1px solid #b7b7b7" data-id="{{$plj->uuid}}"><i class="fa-solid fa-arrows-up-down-left-right handle fs-20 me-4"></i> {{$plj->pelajaran}}</li>
                    @endforeach
                </ul>
                <button class="btn btn-sm btn-primary edit-urutan"><i class="fa-solid fa-plus"></i> Edit Urutan</button>
            </div>
        </div>
    </div>
    <script>
        var el = document.getElementById('sortable-pelajaran');
        Sortable.create(el,{
            animation: 150,
        });
        $('.edit-urutan').click(function() {
            loading();
            var url = "{{route('pelajaran.sorting')}}";
            var urutan_array = [];
            var i = 1;
            $('.item-sort').each(function() {
                urutan_array.push({
                    "urutan": i,
                    "uuid" : $(this).data('id')
                });
                i++;
            });
            $.ajax({
                type: "post",
                url: url,
                headers: {
                    "X-CSRF-TOKEN": "{{csrf_token()}}"
                },
                data: {
                    "urutan" : urutan_array
                },
                success: function(data) {
                    cAlert("green",'Berhasil',"Data Berhasil diurutkan kembali",false,"{{route('pelajaran.index')}}");
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    console.log(errors.message);
                }
            })
        });
    </script>
@endsection
