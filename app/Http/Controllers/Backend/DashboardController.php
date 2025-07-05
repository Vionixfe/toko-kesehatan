<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Menghitung Metrik Utama (ini sudah benar)
        $totalRevenue = Order::whereIn('status', ['shipped', 'completed'])->sum('total_amount');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $pendingOrders = Order::whereIn('status', ['verifying_payment', 'processing'])->count();


         $currentYear = Carbon::now()->year;
        $ordersThisYear = Order::whereYear('created_at', $currentYear)->get();

        // 3. Siapkan array untuk 12 bulan, semuanya diisi dengan 0
        $monthlyData = array_fill(1, 12, 0);

        // 4. Loop melalui pesanan yang ditemukan dan hitung jumlahnya per bulan
        foreach ($ordersThisYear as $order) {
            // Dapatkan nomor bulan (1 untuk Januari, 2 untuk Februari, dst.)
            $month = $order->created_at->month;
            // Tambahkan 1 ke bulan yang sesuai
            $monthlyData[$month]++;
        }

        // 5. Siapkan data final untuk dikirim ke Chart.js
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'data' => array_values($monthlyData), // Kirim hanya nilainya, misal: [0, 0, 0, 0, 0, 0, 2, 0, ...]
        ];

        // 6. Mengambil data pesanan terbaru (ini sudah benar)
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Mengirim semua data ke view
        return view('backend.dashboard.index', compact(
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'pendingOrders',
            'chartData',
            'recentOrders'
        ));
    }
}
