@extends('main')
@push('styles')
    <style>
        .menu {
            height: 200vh;
            overflow-y: auto;
        }

        .list {
            border-left: 3px solid #735858;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: hidden;
        }

        .selected {
            border: 2px solid blue;
            /* Gaya saat card dipilih */
        }

        .img-menu {
            width: 100%;
            height: auto;
            aspect-ratio: 1 / 1;
            object-fit: cover;
        }
    </style>
@endpush
@section('content')
    <div class="row ">
        <div class="menu col-6 col-md-8  bg-secondary">
            <div class="row list-menu mt-3">
                @foreach ($menu as $item)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card" id='listmenu_{{ $item->id }}' data-id='{{ $item->id }}'>
                            <img class="card-img-top img-menu" src="{{ asset('storage/' . $item->gambar) }}"
                                alt="Card image cap">
                            <div class="card-body">
                                <input type="checkbox" id='inputmenu_{{ $item->id }}' data-nama='{{ $item->nama }}'
                                    data-harga="{{ $item->harga_pokok }}" data-jumlah="0" class="row-checkbox d-none"
                                    value="{{ $item->id }}">
                                <p class="">{{ $item->nama }}</p>
                                <p class="">{{ 'Rp ' . number_format($item->harga_pokok, 0, ',', '.') }}</p>

                                <p class="card-text">{{ $item->deskripsi }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>



        </div>
        <div class="list col-6 col-md-4 ">
            <form action="{{ route('home.input') }}" method="POST" id="form">
                Menu yang Dipilih
                @csrf
                @method('POST')
                <div class="menu-selected">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Makanan</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody id="menu-items">
                                <!-- Selected items will be appended here dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <strong>Total Keseluruhan: </strong>
                    <span id="total-harga">-</span>
                </div>
                <button type="submit" id="pesan-sekarang" class="btn btn-primary">Pesan Sekarang</button>

            </form>

        </div>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Pilih Jumlah Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="orderCount">Jumlah:</label>
                    <input type="number" id="orderCount" class="form-control" value="1" min="1">
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirmOrder" class="btn btn-primary">Ok</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
    {{-- <button type="button" id="delete-menu-${id}" data-id="${id}" class="btn btn-danger delete-menu">Batal</button> --}}
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            console.log("Halaman telah dimuat.");
            var rows_selected = [];
            var harga_selected = [];
            var jumlah_selected = [];

            $('.card').on('click', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                if (!$(this).hasClass('selected')) {
                    $('#orderModal').data('menuId', id).modal('show');
                }
            });

            $('#orderModal').on('hide.bs.modal', function() {
                $('#orderCount').val(1);
            });
            // Nanti kasih ke ajax untuk ambil response nya
            $('#confirmOrder').on('click', function(e) {
                e.preventDefault();
                var id = $('#orderModal').data('menuId');
                var count = $('#orderCount').val();
                var harga = $('#inputmenu_' + id).data('harga') * count;
                var nama = $('#inputmenu_' + id).data('nama');
                $('#orderModal').modal('hide');
                $('#listmenu_' + id).addClass('selected');
                $('#inputmenu_' + id).prop('checked', true);
                $('#inputmenu_' + id).attr('data-jumlah', count);
                // Berarti nanti show menu per id
                // lalu responsenya itu adalah harga per barang dan total harga per countnya
                // Jadi dicontroller tidak menggail query saja tpi hitung per cout untuk harga barangnya
                // selain tampilin di view, juga menambah data di table cart untuk mengkonfirmasi ketika selesai
                // pilih menu lalu tekan pesan, maka langsung pindahkan ke tabel nota
                let newMenuRow = `<tr id="menu-pilih-${id}">
                    <td>${nama}</td>
                    <td>${count}</td>
                    <td><span class='harga-pilih' data-harga="${harga}">${formatPrice(harga)}</span></td>
                    <td>
                        <button type="button" id="delete-menu-${id}" data-id="${id}" class="btn btn-danger delete-menu"><i class="bi bi-x-circle"></i></button>
                        <input type="text" name="input_menu" class="row-checkbox d-none" value="${id}">
                    </td>
                  </tr>`;

                $('#menu-items').append(newMenuRow);


                rows_selected = [];
                harga_selected = [];
                jumlah_selected = [];
                $('.row-checkbox:checked').each(function() {
                    rows_selected.push($(this).val()); // Tambahkan nilai checkbox (ID) ke array
                    harga_selected.push($(this).data('harga'));
                    jumlah_selected.push($(this).data('jumlah'));
                });
                total();
            });


            $(document).on('click', '.delete-menu', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $('#listmenu_' + id).removeClass('selected');
                $('#inputmenu_' + id).prop('checked', false);
                $('#menu-pilih-' + id).remove();
                total();
            });


            $('#pesan-sekarang').click(function(e) {
                e.preventDefault();
                var datas = rows_selected;
                var data_harga = harga_selected;
                var data_jumlah = jumlah_selected;

                var combinedData = [];
                for (var i = 0; i < datas.length; i++) {
                    combinedData.push({
                        id_menu: datas[i],
                        harga: data_harga[i],
                        jumlah: data_jumlah[i]
                    });
                }

                $.ajax({
                    type: "POST",

                    url: "{{ route('home.input') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        menu_data: combinedData
                    },
                    success: function(response) {
                        var notaId = response.id_nota;

                        window.open("{{ route('home.printnota', ['id' => ':id']) }}".replace(
                            ':id', notaId), '_blank');

                        window.location.href = "{{ route('home.index') }}";
                    },
                    error: function(xhr, status, error) {
                        if (xhr.responseJSON.error) {
                            var errors = xhr.responseJSON.error;
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errors,
                            });

                        }
                    }
                });
            });

            function total() {
                let total = 0;
                $('.harga-pilih').each(function() {
                    let harga = parseInt($(this).data('harga'));
                    total += harga;
                });
                $('#total-harga').text(formatPrice(total)); // Format angka dengan titik
            }

            function formatPrice(price) {
                // Format the price to include "Rp" and thousand separator
                return 'Rp ' + price.toString().replace(/\B(?=(\d{3})+(?!\d))/g,
                    "."); // Format with dot as a thousand separator
            }

        });
    </script>
@endpush
