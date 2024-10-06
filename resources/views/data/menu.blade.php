@extends('main')
@push('styles')
@endpush
@section('content')
    <div class="container mt-5">
        <!-- Button trigger modal -->
        <button type="button" id="tambahdata" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalform">
            Tambah Data
        </button>
        <table id="table" class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Deskripsi</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Gambar</th>
                    <th width="15%" scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="modalform" tabindex="-1" aria-labelledby="judulform" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="judulform"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form" method="post" enctype="multipart/form-data">
                            @method('post')
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Menu</label>
                                <input type="text" class="form-control" id="nama" name="nama">
                                <div class="invalid-feedback" id="namaError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="harga_pokok" class="form-label">Harga Pokok Menu</label>
                                <input type="number" class="form-control" id="harga_pokok" name="harga_pokok">
                                <div class="invalid-feedback" id="harga_pokokError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi Menu</label><br>
                                <textarea name="deskripsi" id="deskripsi" cols="50" rows="10"></textarea>
                                <div class="invalid-feedback" id="deskripsiError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Upload Gambar</label>
                                <img class="img-preview img-fluid" style="display: none;">
                                <input class="form-control form-control-sm " id="image" name="image" type="file">
                                Aspect Ratio = 1:1
                                <div class="invalid-feedback" id="imageError"></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id='btn' class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        var table = $('#table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searchHighlight: true,
            ajax: "{{ route('data.menu') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'deskripsi',
                    name: 'deskripsi'
                },
                {
                    data: 'harga_pokok',
                    name: 'harga_pokok'
                },
                {
                    data: 'cover',
                    name: 'cover'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                },
            ]
        });
        $("#tambahdata").click(function(e) {
            e.preventDefault();
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#form')[0].reset();
            $('#judulform').text('Tambah Data');
            $('#btn').text('Tambah Data');
            $("#form").attr('action', '{{ route('menu.store') }}');
        });

        $('#modalform').on('hide.bs.modal', function() {
            $('#form')[0].reset();
            $('.invalid-feedback').text('').hide();
            $('.form-control').removeClass('is-invalid');
            $('#btn').text('Save');
        });

        $("body").on("click", "#edit", function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $("#form").attr('action', "{{ route('menu.update', ':id') }}".replace(
                ':id', id));
            $("input[name=_method]").val('PUT');
            $('#judulform').text('Edit Data');
            $('#btn').text('Update');
            $.ajax({
                url: "{{ route('menu.show', ':id') }} ".replace(':id', id),
                type: 'GET',
                dataType: "json",
                processData: false,
                contentType: false,
                success: function(response) {
                    $("#modalform").modal('show')
                    $('#nama').val(response.nama);
                    $('#harga_pokok').val(response.harga_pokok);
                    $('#deskripsi').val(response.deskripsi);
                    $('#image').val(response.gambar);
                    // $('#jurusan').val(response.jurusan).prop(selected);
                }
            });

        })

        $("body").on("click", "#delete", function(e) {
            var id = $(this).data('id');
            $.ajax({
                type: "DELETE",
                url: "{{ route('menu.delete', ':id') }}".replace(':id', id),
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    $("#table").DataTable().draw()
                }
            });
        })


        //form on submit, sama saja
        $('#btn').on('click', function(e) {
            e.preventDefault();
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            var formData = new FormData($('#form')[0]);
            $("input[name=_method]").val('POST');
            $.ajax({
                type: 'POST',
                url: $("#form").attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    $('#modalform').modal('hide');
                    $("#table").DataTable().draw()
                },
                error: function(xhr, status, error) {
                    var err = xhr.responseJSON.errors;
                    $('.invalid-feedback').text('').hide();
                    $.each(err, function(key, value) {
                        $('#' + key + 'Error').text(value).show();
                        $('#' + key).addClass('is-invalid');
                    });
                }
            });
        });
    </script>
@endpush
