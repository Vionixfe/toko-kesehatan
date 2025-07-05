<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $order->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3 { margin: 0; padding: 0; font-size: 18px; }
        .header p { margin: 0; padding: 0; font-size: 14px; }
        .info-table, .items-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 3px; }
        .items-table { margin-top: 20px; }
        .items-table th, .items-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .items-table th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .signature { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>Toko Alat Kesehatan</h3>
            <p>Laporan Belanja Anda</p>
        </div>

        <table class="info-table">
            <tr>
                <td width="15%">User ID</td>
                <td width="35%">: {{ $order->user->id }}</td>
                <td width="15%">Tanggal</td>
                <td width="35%">: {{ $order->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>: {{ $order->user->nama }}</td>
                <td>ID Pesanan</td>
                <td>: #{{ $order->invoice_number }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: {{ $order->shipping_address }}</td>
                <td>Nama Bank</td>
                <td>: -</td>
            </tr>
            <tr>
                <td>No. HP</td>
                <td>: {{ $order->user->phone_number ?? '-' }}</td>
                <td>Cara Bayar</td>
                <td>: {{ $order->payment_method == 'bank_transfer' ? 'Prepaid' : 'Postpaid' }}</td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th>Nama Produk dengan IDnya</th>
                    <th width="10%">Jumlah</th>
                    <th width="20%">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->product->name }} (ID: {{ $item->product_id }})</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->price * $item->quantity) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total Belanja (termasuk pajak):</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($order->total_amount) }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="signature">
            <p>TANDA TANGAN TOKO</p>
            <br><br><br>
            <p>(___________________)</p>
        </div>
    </div>
</body>
</html>
