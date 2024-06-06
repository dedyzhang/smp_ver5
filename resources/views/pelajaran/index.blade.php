@extends('layouts.main') @section('container')
{{ Breadcrumbs::render('pelajaran') }}
<div
    class="body-contain-customize col-12 col-sm-12 col-md-auto col-lg-auto col-xl-auto d-grid d-sm-grid d-md-flex d-lg-flex d-xl-flex gap-2"
>
    <a href="{{ route('pelajaran.create') }}" class="btn btn-sm btn-warning"
        ><i class="fa-solid fa-plus"></i> Tambah Pelajaran</a
    >
    <a href="{{ route('pelajaran.sort') }}" class="btn btn-sm btn-primary"
        ><i class="fa-solid fa-arrow-down-1-9"></i> Atur Urutan</a
    >
    <button
        class="btn btn-sm btn-info text-light buka-modal-penjabaran"
    >
        <i class="fas fa-gear"></i> Atur Penjabaran
    </button>
</div>
<div
    class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3"
>
    <h5><b>Data Pelajaran</b></h5>
    @if (session('success'))
    <div
        class="alert alert-success alert-dismissible fade show d-flex align-content-between align-items-center mt-3"
        role="alert"
    >
        <i
            class="bi flex-shrink-0 me-2 fa-solid fa-check"
            aria-label="Success:"
        ></i>
        <div><strong>Sukses !</strong> {{ session("success") }}</div>
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>
    </div>
    @else @endif
</div>
<div
    class="body-contain-customize col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3"
>
    <table
        id="datatable-pelajaran"
        class="dataTables table table-striped table-bordered text-center"
        style="width: 100%"
    >
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Pelajaran</th>
                <th width="20%">Nama Singkat</th>
                <th width="5%">Urutan</th>
                <th width="10%">#</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pelajaran as $plj)
            <tr id="plj.{{ $plj->uuid }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $plj->pelajaran }}</td>
                <td>{{ $plj->pelajaran_singkat }}</td>
                <td>{{ $plj->urutan }}</td>
                <td>
                    <a
                        href="{{ route('pelajaran.edit', $plj->uuid) }}"
                        class="btn btn-sm btn-warning"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-title="Edit "
                        ><i class="fa-solid fa-pencil"></i
                    ></a>
                    <button class="btn btn-sm btn-danger hapus-pelajaran">
                        <i
                            class="fa-solid fa-trash-can"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            data-bs-title="Hapus"
                        ></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="modal fade in" id="modal-penjabaran">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Atur Penjabaran</h5>
                <button class="btn btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row m-0 p-0">
                    <div class="form-group col-12 m-0">
                        <label for="inggris">Penjabaran Inggris</label>
                        <select
                            name="inggris"
                            id="inggris"
                            class="form-control"
                        >
                            <option value="">Pilihlah Salah Satu</option>
                            @foreach ($pelajaran as $plj)
                            <option value="{{ $plj->uuid }}">
                                {{ $plj->pelajaran }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-12 m-0">
                        <label for="mandarin">Penjabaran Mandarin</label>
                        <select
                            name="mandarin"
                            id="mandarin"
                            class="form-control"
                        >
                            <option value="">Pilihlah Salah Satu</option>
                            @foreach ($pelajaran as $plj)
                            <option value="{{ $plj->uuid }}">
                                {{ $plj->pelajaran }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    class="btn btn-sm btn-warning text-warning-emphasis edit-penjabaran"
                >
                    <i class="fas fa-pencil"></i> Edit Penjabaran
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    var table = new DataTable("#datatable-pelajaran", {
        // scrollX : true,
        initComplete: function (settings, json) {
            $("#datatable-pelajaran").wrap(
                "<div style='overflow:auto; width:100%;position:relative;'></div>"
            );
        },
        columnDefs: [
            {
                className: "text-center",
                targets: [0, 2, 3, 4],
            },
            {
                className: "text-start",
                targets: [1],
            },
        ],
    });
    $("#datatable-pelajaran").on("click", ".hapus-pelajaran", function () {
        var id = $(this).closest("tr").attr("id").split(".").pop();
        var HapusPelajaran = () => {
            var url = "{{ route('pelajaran.destroy', ':id') }}";
            url = url.replace(":id", id);

            $.ajax({
                type: "DELETE",
                url: url,
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (data) {
                    if (data.success === true) {
                        cAlert(
                            "green",
                            "Berhasil",
                            "Berhasil menghapus Pelajaran",
                            true
                        );
                    }
                },
                error: function (data) {
                    var errors = data.responeJSON;
                    console.log(errors);
                },
            });
        };
        cConfirm(
            "Perhatian",
            "Yakin untuk menghapus pelajaran ini",
            HapusPelajaran
        );
    });
    $('.buka-modal-penjabaran').click(function() {
        loading();
        $.ajax({
            type:"get",
            url: "{{route('pelajaran.penjabaran')}}",
            success: function(data) {
                if(data.success) {
                    $('#inggris').val(data.english[0].uuid);
                    $('#mandarin').val(data.mandarin[0].uuid);
                    removeLoading();
                    $('#modal-penjabaran').modal("show");
                } else {
                    removeLoading();
                    $('#modal-penjabaran').modal("show");
                }
            }
        })

    })
    $(".edit-penjabaran").click(function() {
        var mandarin = $('#mandarin').val();
        var english = $('#inggris').val();
        if(mandarin == "" || inggris == "") {
            oAlert("blue","Perhatian","penjabaran English dan Mandarin tidak boleh kosong");
        } else {
            var penjabaranAdd = () => {
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{route('pelajaran.penjabaran')}}",
                    headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                    data: { english : english, mandarin: mandarin},
                    success: function(data) {
                        removeLoading();
                        cAlert("green","Sukses","Sukses Mengedit Penjabaran",true);
                        // console.log(data);
                    },error: function(data) {
                        console.log(data.responeJSON);
                    }
                });
            }
            cConfirm("Perhatian","Yakin untuk tambahkan penjabaran di pelajaran berikut",penjabaranAdd);
        }
    });
</script>
@endsection
