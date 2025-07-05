<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Belanja #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #000;
            font-size: 12px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h3,
        .header p {
            margin: 0;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .details-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .items-table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            font-weight: bold;
        }

        .signature-section {
            margin-top: 50px;
            text-align: right;
        }

        .signature-box {
            margin-top: 60px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h3>Toko Alat Kesehatan</h3>
            <p>Laporan Belanja Anda</p>
        </div>

        <table class="details-table">
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 80px;">User ID</td>
                            <td>: {{ $order->user->id }}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>: {{ $order->user->nama }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>: {{ $order->user->alamat ?? 'N/A' }}</td>
                        </tr>
                         <tr>
                <td>Tanggal Bayar</td>
                <td>:
                    {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}
                </td>

            </tr>
                    </table>
                </td>
           
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th>Nama Produk dengan IDnya</th>
                    <th style="width: 10%;">Jumlah</th>
                    <th style="width: 25%;">Harga</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $item)
                    <tr>
                        {{-- '$loop->iteration' akan membuat nomor urut otomatis (1, 2, 3, ...) --}}
                        <td class="text-center">{{ $loop->iteration }}.</td>

                        {{-- '$item->product->name' mengambil nama produk dari relasi 'product' di model OrderItem. --}}
                        {{-- '??' adalah operator untuk memberikan nilai default jika produk sudah dihapus. --}}
                        <td>{{ $item->product->name ?? 'Produk Tidak Ditemukan' }} (No. Invoice:
                            {{ $order->invoice_number }})</td>

                        {{-- '$item->quantity' mengambil nilai dari kolom 'quantity' di tabel 'order_items'. --}}
                        <td class="text-center">{{ $item->quantity }}</td>

                       {{-- Kolom "Harga" ini sekarang menampilkan subtotal per item --}}
                    <td class="text-right">Rp. {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>

                    </tr>
                @empty
                    {{-- Bagian ini akan ditampilkan jika pesanan tidak memiliki item sama sekali --}}
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada item dalam pesanan ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" class="text-right">Total Belanja (termasuk pajak):</td>
                    <td class="text-right">Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="signature-section">
            TANDATANGAN TOKO
            <div class="signature-box">(.........................)</div>
        </div>
    </div>
</body>

</html>
