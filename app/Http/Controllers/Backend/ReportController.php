<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Memproses permintaan ekspor dan menghasilkan PDF untuk diunduh.
     */
    public function export(Request $request)
    {
        // 1. Validasi input tanggal
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // 2. Proses tanggal input
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        // 3. Ambil data pesanan yang sudah lunas dari database
        $orders = Order::with('user')
            ->whereIn('status', ['processing', 'shipped', 'completed'])
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->orderBy('paid_at', 'asc')
            ->get();

        // 4. Hitung ringkasan data
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total_amount');

        // 5. Siapkan semua data untuk dikirim ke template PDF
        $data = [
            'orders' => $orders,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
        ];

        // 6. Buat PDF dari view
        $pdf = Pdf::loadView('backend.reports.sales_report_pdf', $data);

        // 7. Buat nama file yang dinamis
        $fileName = 'laporan-penjualan-' . $startDate->format('d-m-Y') . '-sampai-' . $endDate->format('d-m-Y') . '.pdf';

        // 8. Langsung unduh file PDF ke komputer admin
        return $pdf->download($fileName);
    }
}
