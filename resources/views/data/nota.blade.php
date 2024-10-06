@extends('main')
@push('styles')
@endpush
@section('content')
    <div class="container mt-5">
        <table id="table" class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Id Nota</th>
                    <th scope="col">Total Pembelian</th>
                    <th width="15%" scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>


    </div>
@endsection
@push('js')
    <script>
        var table = $('#table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searchHighlight: true,
            ajax: "{{ route('data.nota') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'total_harga',
                    name: 'total_harga'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                },
            ]
        });

        // $("body").on("click", "#edit", function(e) {
        //     e.preventDefault();
        //     var id = $(this).data('id');
        //     $("#form").attr('action', "{{ route('menu.update', ':id') }}".replace(
        //         ':id', id));
        //     $("input[name=_method]").val('PUT');
        //     $('#judulform').text('Edit Data');
        //     $('#btn').text('Update');
        //     $.ajax({
        //         url: "{{ route('menu.show', ':id') }} ".replace(':id', id),
        //         type: 'GET',
        //         dataType: "json",
        //         processData: false,
        //         contentType: false,
        //         success: function(response) {
        //             $("#modalform").modal('show')
        //             $('#nama').val(response.nama);
        //             $('#harga_pokok').val(response.harga_pokok);
        //             $('#deskripsi').val(response.deskripsi);
        //             // $('#jurusan').val(response.jurusan).prop(selected);
        //         }
        //     });

        // })

        $("body").on("click", "#delete", function(e) {
            var id = $(this).data('id');
            $.ajax({
                type: "DELETE",
                url: "{{ route('nota.delete', ':id') }}".replace(':id', id),
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
