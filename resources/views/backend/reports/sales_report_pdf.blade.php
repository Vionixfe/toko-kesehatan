{{-- LOKASI: resources/views/backend/reports/sales_report_pdf.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; font-size: 11px; }
        .header { text-align: center; margin-bottom: 25px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th, .items-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .items-table th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary-table { width: 40%; float: right; margin-top: 20px; border-collapse: collapse; }
        .summary-table td { padding: 8px; border: 1px solid #ccc; }
        .footer { position: fixed; bottom: -20px; width: 100%; text-align: center; font-size: 9px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Penjualan</h2>
        <p><strong>Toko Alat Kesehatan Sehat Medika</strong></p>
        <p>Periode: {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}</p>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 15%;">Invoice</th>
                <th style="width: 15%;">Tanggal Lunas</th>
                <th>Customer</th>
                <th style="width: 20%;">Total Penjualan</th>
                <th style="width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $order->invoice_number }}</td>
                <td>{{ $order->paid_at->format('d/m/Y') }}</td>
                <td>{{ $order->user->nama ?? 'N/A' }}</td>
                <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td class="text-center">{{ ucwords(str_replace('_', ' ', $order->status)) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada data penjualan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary-table">
        <tr>
            <td><strong>Jumlah Pesanan</strong></td>
            <td class="text-right"><strong>{{ $totalOrders }}</strong></td>
        </tr>
        <tr>
            <td><strong>Total Pendapatan</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh sistem pada tanggal {{ now()->format('d F Y, H:i') }} WIB.
    </div>
</body>
</html>
