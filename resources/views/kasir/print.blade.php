<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Struk Belanjaan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .struk {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }

        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .kode_struk,
        .pegawai {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-size: 16px;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tfoot tr td {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }

        .subtotal-label {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="struk">
        <div class="header">
            Struk Belanjaan
        </div>
        <div class="kode_struk">
            Kode Struk: <strong>{{ $nota->id }}</strong>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($aggregatedData as $data)
                    <tr>
                        <td>{{ $data['nama_barang'] }}</td>
                        <td>{{ $data['jumlah'] }}</td>
                        <td>
                            {{ 'Rp ' . number_format($data['harga'], 0, ',', '.') }}
                        </td>
                        <td> {{ 'Rp ' . number_format($data['subtotal'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="subtotal-label">TOTAL</td>
                    <td class="total"> {{ 'Rp ' . number_format($nota->total_harga, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
        <div class="footer">
            Terima kasih telah berbelanja di toko kami
        </div>
    </div>

    <script>
        // Fungsi untuk mencetak halaman saat halaman dimuat
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
