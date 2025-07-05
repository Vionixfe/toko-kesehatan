@extends('backend.template.main')
@section('title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Penjualan</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan Berdasarkan Tanggal</h6>
        </div>
        <div class="card-body">
            <p>Pilih rentang tanggal untuk menghasilkan laporan penjualan dalam format PDF. Laporan hanya akan mencakup pesanan dengan status 'Processing', 'Shipped', atau 'Completed'.</p>

            {{-- Form untuk memilih tanggal --}}
            <form action="{{ route('reports.export') }}" method="POST" target="_blank">
                @csrf
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="start_date">Tanggal Mulai:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="end_date">Tanggal Selesai:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-file-pdf fa-sm"></i> Ekspor PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
```
---

### **Langkah 3: Buat Template untuk PDF Laporan**

Ini adalah "cetakan" untuk file PDF yang akan dihasilkan.

**Aksi:** Di dalam folder `resources/views/backend/reports`, buat file baru bernama `sales_report_pdf.blade.php`.

```html
{{-- FILE 2: Template PDF Laporan Penjualan --}}
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
        .summary-table { width: 40%; float: right; margin-top: 20px; }
        .summary-table td { padding: 5px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #777; }
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
                <th style="width: 15%;">Tanggal</th>
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
                <td>{{ $order->user->name ?? 'N/A' }}</td>
                <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td class="text-center">{{ ucwords(str_replace('_', ' ', $order->status)) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data penjualan pada periode ini.</td>
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
```
---

### **Langkah 4: Isi Logika di `ReportController`**

Controller ini akan mengambil data dari form, memprosesnya, dan membuat PDF.

**Aksi:** Buka file `app/Http/Controllers/Backend/ReportController.php` dan isi dengan kode lengkap ini.

```php
<?php

// FILE 3: Controller untuk Laporan
// LOKASI: app/Http/Controllers/Backend/ReportController.php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF; // Facade untuk laravel-dompdf

class ReportController extends Controller
{
    /**
     * Menampilkan halaman utama untuk filter laporan.
     */
    public function index()
    {
        return view('backend.reports.index');
    }

    /**
     * Memproses permintaan ekspor dan menghasilkan PDF.
     */
    public function export(Request $request)
    {
        // 1. Validasi input tanggal
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // 2. Konversi tanggal ke objek Carbon
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        // 3. Ambil data pesanan dari database sesuai rentang tanggal
        // Hanya ambil pesanan yang sudah lunas
        $orders = Order::with('user')
            ->whereIn('status', ['processing', 'shipped', 'completed'])
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->orderBy('paid_at', 'asc')
            ->get();

        // 4. Hitung ringkasan data
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total_amount');

        // 5. Siapkan data untuk dikirim ke view PDF
        $data = [
            'orders' => $orders,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
        ];

        // 6. Buat PDF dari view
        $pdf = PDF::loadView('reports.sales_report_pdf', $data);

        // 7. Atur nama file dan kirim sebagai unduhan ke browser
        $fileName = 'laporan-penjualan-' . $startDate->format('d-m-Y') . '-' . $endDate->format('d-m-Y') . '.pdf';
        return $pdf->stream($fileName);
    }
}
```
---

### **Langkah 5: Daftarkan Route Baru**

Langkah terakhir adalah memberitahu Laravel tentang halaman dan fungsi baru kita.

**Aksi:** Buka file `routes/web.php` dan tambahkan grup route ini di dalam grup `admin`.

```php
// FILE 4: Daftarkan Route Baru
// LOKASI: routes/web.php

// ... (kode route lainnya)

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... (route admin Anda yang lain)

    // GRUP ROUTE UNTUK LAPORAN
    Route::prefix('reports')->name('reports.')->group(function () {
        // Route untuk menampilkan halaman filter
        Route::get('/', [App\Http\Controllers\Backend\ReportController::class, 'index'])->name('index');
        // Route untuk memproses ekspor PDF
        Route::post('/export', [App\Http\Controllers\Backend\ReportController::class, 'export'])->name('export');
    });

});
