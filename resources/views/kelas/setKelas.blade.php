@extends('layouts.main')

@section('container')
    {{Breadcrumbs::render('kelas-set')}}
    <div class="body-contain-customize col-auto col-sm-auto col-md-auto col-lg-auto col-xl-auto">
        <a class="btn btn-sm btn-warning" href="{{route('kelas.historiRombel')}}"><i class="fa-solid fa-recycle"></i> Histori Pengajuan Rombel</a>
    </div>
    <div class="body-contain-customize mt-3 col-md-12 col-lg-12 col-sm-12 set-kelas-siswa">
        <p class="body-title">Pengaturan Anggota Kelas</p>
        {{-- <hr /> --}}
        <div class="row m-0 mt-3 mb-3">
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-0">
                <div class="form-group">
                    <label for="search">Cari Siswa</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Masukkan Nama Siswa" />
                </div>
            </div>
        </div>
        <div class="row m-0">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <ul class="kotak-set drag d-flex flex-wrap">
                    @foreach ($siswa as $siswa)
                        <li draggable="true" id="siswa.{{$siswa->uuid}}" data-searchterm="{{Str::lower($siswa->nama)}}" class="item flex-wrap justify-content-center text-center m-1" data-value="{{$siswa->nis}}">
                        <img src="{{$siswa->jk == 'l' ? asset('img/student-boy.png') : asset('img/student-girl.png')}}" alt="" width="70px" height="70px">
                        <p class="fs-12 mt-2 mb-0"><b>{{$siswa->nis}}</b></p>
                        <p class="fs-12">{{Str::limit($siswa->nama,30)}}</p>
                        </li>
                    @endforeach

                </ul>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <ul class="kotak-set drop d-flex flex-wrap">

                </ul>
            </div>
        </div>
        <div class="row m-0">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12  d-flex justify-content-end">
                <button class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modal-set-kelas">Set Kelas</button>
                <button class="btn btn-sm btn-warning hapus-rombel">Hapus Siswa</button>
            </div>
        </div>
    </div>
    <div class="modal fade in" id="modal-set-kelas">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Atur ke kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kelas">Masukkan Siswa ke dalam rombel</label>
                        <select class="form-control" id="kelas">
                            <option value="">Pilih Salah Satu</option>
                            @foreach ($kelas as $kelas)
                                <option value="{{$kelas->uuid}}">{{$kelas->tingkat.$kelas->kelas}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light save-rombel"><i class="fa-solid fa-save"></i> Save Rombel</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(".drag li").on('dragstart',function(evt){
            evt.originalEvent.dataTransfer.setData("text",evt.target.id);
        });
        $(".drop").on('drop',function(evt){
            evt.preventDefault();
            var data = evt.originalEvent.dataTransfer.getData("text");
            $('.drop').append(document.getElementById(data));
        });
        $('.drop').on('dragover',function(evt){
            evt.preventDefault();
        });
        $('.drop').on('click','.item',function(){
            if($(this).hasClass('select')){
                $(this).removeClass('select');
            } else {
                $(this).addClass('select');
            }
        });
        $('.hapus-rombel').click(function(){
            $('.kotak-set.drop').children('li').each(function(){
                if($(this).hasClass('select')) {
                    $(this).remove();
                    $(this).removeClass('select');
                    $('.kotak-set.drag').append($(this));
                }
            });
            $('.kotak-set.drag').children('li').sort(function(a, b) {
                var one = $(a).data('value');
                var two = $(b).data('value');
                if (one < two) {
                    return -1;
                } else {
                    return 1;
                }
            }).appendTo('.kotak-set.drag');
        });
        $('#search').on('keyup',function(){
            var searchTerm = $(this).val().toLowerCase();
            if(searchTerm != "") {
                $('.kotak-set.drag li').hide().filter('[data-searchterm*="' + searchTerm + '"]').show();
            } else {
                $('.kotak-set.drag li').show();
            }
        });
        $('.save-rombel').click(function() {
            var idSiswa = [];
            var idKelas = $('#kelas').val();
            var token = '{{csrf_token()}}';

            var url = "{{route('kelas.saveRombel', ':id')}}";
            url = url.replace(':id', idKelas);

            $('.kotak-set.drop').children('li').each(function() {
                var uuid = $(this).attr('id').split('.').pop();
                idSiswa.push(uuid);
            });
            if(idSiswa == "" || idKelas == "") {
                oAlert("blue","Perhatian","Siswa yang dimasukkan kedalam rombel tidak boleh kosong. data Kelas juga tidak boleh kosong");
            } else {
                loading();
                $.ajax({
                    type: "POST",
                    url: url,
                    headers: {'X-CSRF-TOKEN': token},
                    data: {
                        idSiswa : idSiswa,
                    },
                    success: function(data) {
                        if(data.success === true) {
                            setTimeout(() => {
                                removeLoading();
                                cAlert("green","Sukses","Sukses menyimpan anggota Rombel",true);
                            }, 500);
                        }

                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors.message);
                    }
                });
            }
        });
    </script>
@endsection
