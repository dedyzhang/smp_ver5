@extends('layouts.main')

@section('container')
<div class="body-contain-customize col-12">
        <h5>Buku Batas</h5>
        <p>Halaman Ini diperuntukkan melihat buku batas yang sudah diisi oleh guru mapel</p>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <div class="row m-0 p-0 d-flex align-items-end">
            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                <label for="kelas">Kelas</label>
                <select class="form-control" data-toggle="select" name="kelas" id="kelas">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($kelas as $item)
                        <option value="{{$item->uuid}}">{{$item->tingkat.$item->kelas}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                <label for="minggu-ke">Minggu Ke</label>
                <select class="form-control" data-toggle="select" name="minggu-ke" id="minggu-ke">
                    <option value="">Pilih Salah Satu</option>
                    @foreach ($tanggal as $item => $value)
                        @php
                            $getVal = "";
                            $count = count($value);
                            $i = 0;
                            $tanggalVal = "";
                        @endphp
                        @foreach ($value as $elm)
                            @php
                                if($i === 0) {
                                    $tanggalVal .= date('d M Y', strtotime($elm->tanggal))." - ";
                                } else if($i == $count - 1) {
                                    $tanggalVal .= date('d M Y', strtotime($elm->tanggal));
                                }
                                $getVal .= $elm->uuid.",";
                                $i++;
                            @endphp
                        @endforeach
                        @php
                            $getVal = substr($getVal,0,-1);
                        @endphp
                        <option value="{{$getVal}}">{{$item}} ({{$tanggalVal}})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <button class="btn btn-sm btn-warning text-warning-emphasis mt-sm-3 mt-3 mt-md-3 mt-lg-0 mt-xl-0 lihat-buku-batas">
                    <i class="fas fa-eye"></i> Lihat Buku Batas
                </button>
            </div>
        </div>
    </div>
    <div class="body-contain-customize col-12 mt-3">
        <p class="m-0"><b>Halaman Buku Batas</b></p>
        <div class="m-0 p-0 mt-3 bukuBatas"></div>
    </div>
    <script>
        $('.lihat-buku-batas').click(function() {
            var kelas = $('#kelas').val();
            var tanggal = $('#minggu-ke').val();
            var html = "";
            loading();
            $.ajax({
                type: "GET",
                url : "{{route('agenda.cekBatas')}}",
                data: {kelas: kelas, tanggal: tanggal},
                success: function(data) {
                    removeLoading();
                    var jadwal = data.jadwalArray;
                    var list = "";
                    Object.entries(jadwal).forEach(function(elem) {
                        var jadwalmapel = "";
                        var no = 1;
                        elem[1].forEach(function(item) {
                            if(item.jenis == "spesial") {
                                jadwalmapel += `
                                    <tr class="table-warning">
                                        <td align="center">${no}</td>
                                        <td align="center">${moment(item.waktu.waktu_mulai,'HH:mm').format('HH:mm')} - ${moment(item.waktu.waktu_akhir,'HH:mm').format('HH:mm')}</td>
                                        <td colspan="6" align="center"><b>${item.spesial}</b></td>
                                    </tr>
                                `;
                            } else if(item.jenis == "mapel") {
                                var agendaGuru = data.agenda.filter(element => element.id_jadwal == item.uuid);
                                jadwalmapel += `
                                    <tr>
                                        <td align="center" valign="middle">${no}</td>
                                        <td align="center" valign="middle">${moment(item.waktu.waktu_mulai,'HH:mm').format('HH:mm')} - ${moment(item.waktu.waktu_akhir,'HH:mm').format('HH:mm')}</td>
                                        <td valign="middle" class="plj">${item.pelajaran.pelajaran}</td>
                                        <td valign="middle" class="nama">${item.guru.nama}</td>
                                        <td valign="middle" class="pembahasan" id="bahas.${item.pelajaran.uuid}">
                                            ${agendaGuru[0] !== undefined ? agendaGuru[0].pembahasan : ""}
                                        </td>
                                        <td align="center" valign="middle" class="metode" id="metode.${item.pelajaran.uuid}">
                                            ${agendaGuru[0] !== undefined ? agendaGuru[0].metode : ""}
                                        </td>
                                        <td align="center" valign="middle" class="proses" id="proses.${item.pelajaran.uuid}">
                                            ${agendaGuru[0] !== undefined ? (agendaGuru[0].proses == "belum" ? "B" : "S") : ""}
                                        </td>
                                        <td align="center" valign="middle" class="jumlahAbsen" id="jumlahAbsen.${item.pelajaran.uuid}">
                                            ${agendaGuru[0] !== undefined ? (agendaGuru[0].absensi ? agendaGuru[0].absensi.length : "") : ""}
                                        </td>
                                    </tr>
                                `;
                            }
                            no = +no + 1;
                        });
                        list += `
                            <p class="fs-12 m-0 p-0 mt-3"><b>${elem[0]}</b></p>
                            <div class="table-responsive">
                                <table class="table table-bordered fs-11">
                                    <thead>

                                            <td width="5%" align="center"><b>No</b></td>
                                            <td width="10%" align="center"><b>Waktu</b></td>
                                            <td width="20%" align="center"><b>Pelajaran</b></td>
                                            <td width="15%" align="center"><b>Guru Yang Mengajar</b></td>
                                            <td width="15%" align="center"><b>Pokok Pembahasan</b></td>
                                            <td width="15%" align="center"><b>Metode</b></td>
                                            <td width="5%" align="center"><b>S/B</b></td>
                                            <td width="5%" align="center"><b>Absen</b></td>

                                    </thead>
                                    <tbody>
                                        ${jadwalmapel}
                                    </tbody>
                                </table>
                            </div>
                        `;
                    });
                    $('.bukuBatas').html(list);
                    var span = 1;
                    var prevTD = "";
                    var prevTDVal = "";
                    $(".plj").each(function(){
                        var $this = $(this);
                        var next = $this.closest('tr').prev().children('td').length;
                        if(next == 3) {
                            $this.addClass('ada-break');
                        }
                    });
                    $(".plj").each(function() { //for each first td in every tr
                        var $this = $(this);
                        if ($this.text() == prevTDVal) { // check value of previous td text
                            span++;
                            if (prevTD != "") {
                                if($this.hasClass('ada-break')) {
                                    prevTD=""; // store current td
                                    prevTDVal="";
                                    span=1;
                                } else {
                                    prevTD.attr("rowspan", span); // add attribute to previous td
                                    $this.remove(); // remove current td
                                }
                            }
                        } else {
                            prevTD=$this; // store current td
                            prevTDVal=$this.text();
                            span=1;
                        }
                    });
                    $(".nama").each(function(){
                        var $this = $(this);
                        var next = $this.closest('tr').prev().children('td').length;
                        if(next == 3) {
                            $this.addClass('ada-break');
                        }
                    });
                    $(".nama").each(function() { //for each first td in every tr
                        var $this = $(this);
                        if ($this.text() == prevTDVal) { // check value of previous td text
                            span++;
                            if (prevTD != "") {
                                if($this.hasClass('ada-break')) {
                                    prevTD=""; // store current td
                                    prevTDVal="";
                                    span=1;
                                } else {
                                    prevTD.attr("rowspan", span); // add attribute to previous td
                                    $this.remove(); // remove current td
                                }
                            }
                        } else {
                            prevTD=$this; // store current td
                            prevTDVal=$this.text();
                            span=1;
                        }
                    });
                    $(".pembahasan").each(function(){
                        var $this = $(this);
                        var next = $this.closest('tr').prev().children('td').length;
                        if(next == 3) {
                            $this.addClass('ada-break');
                        }
                    });
                    $(".pembahasan").each(function() { //for each first td in every tr
                        var $this = $(this);
                        if ($this.attr('id').split('.').pop() == prevTDVal) { // check value of previous td text
                            span++;
                            if (prevTD != "") {
                                if($this.hasClass('ada-break')) {
                                    prevTD=""; // store current td
                                    prevTDVal="";
                                    span=1;
                                } else {
                                    prevTD.attr("rowspan", span); // add attribute to previous td
                                    $this.remove(); // remove current td
                                }
                            }
                        } else {
                            prevTD=$this; // store current td
                            prevTDVal=$this.attr('id').split('.').pop();
                            span=1;
                        }
                    });
                    $(".metode").each(function(){
                        var $this = $(this);
                        var next = $this.closest('tr').prev().children('td').length;
                        if(next == 3) {
                            $this.addClass('ada-break');
                        }
                    });
                    $(".metode").each(function() { //for each first td in every tr
                        var $this = $(this);
                        if ($this.attr('id').split('.').pop() == prevTDVal) { // check value of previous td text
                            span++;
                            if (prevTD != "") {
                                if($this.hasClass('ada-break')) {
                                    prevTD=""; // store current td
                                    prevTDVal="";
                                    span=1;
                                } else {
                                    prevTD.attr("rowspan", span); // add attribute to previous td
                                    $this.remove(); // remove current td
                                }
                            }
                        } else {
                            prevTD=$this; // store current td
                            prevTDVal=$this.attr('id').split('.').pop();
                            span=1;
                        }
                    });
                    $(".proses").each(function(){
                        var $this = $(this);
                        var next = $this.closest('tr').prev().children('td').length;
                        if(next == 3) {
                            $this.addClass('ada-break');
                        }
                    });
                    $(".proses").each(function() { //for each first td in every tr
                        var $this = $(this);
                        if ($this.attr('id').split('.').pop() == prevTDVal) { // check value of previous td text
                            span++;
                            if (prevTD != "") {
                                if($this.hasClass('ada-break')) {
                                    prevTD=""; // store current td
                                    prevTDVal="";
                                    span=1;
                                } else {
                                    prevTD.attr("rowspan", span); // add attribute to previous td
                                    $this.remove(); // remove current td
                                }
                            }
                        } else {
                            prevTD=$this; // store current td
                            prevTDVal=$this.attr('id').split('.').pop();
                            span=1;
                        }
                    });
                    $(".jumlahAbsen").each(function(){
                        var $this = $(this);
                        var next = $this.closest('tr').prev().children('td').length;
                        if(next == 3) {
                            $this.addClass('ada-break');
                        }
                    });
                    $(".jumlahAbsen").each(function() { //for each first td in every tr
                        var $this = $(this);
                        if ($this.attr('id').split('.').pop() == prevTDVal) { // check value of previous td text
                            span++;
                            if (prevTD != "") {
                                if($this.hasClass('ada-break')) {
                                    prevTD=""; // store current td
                                    prevTDVal="";
                                    span=1;
                                } else {
                                    prevTD.attr("rowspan", span); // add attribute to previous td
                                    $this.remove(); // remove current td
                                }
                            }
                        } else {
                            prevTD=$this; // store current td
                            prevTDVal=$this.attr('id').split('.').pop();
                            span=1;
                        }
                    });
                },
                error: function(data) {
                    console.log(data.responseJSON.message);
                }
            })
        });
    </script>
</div>
@endsection

