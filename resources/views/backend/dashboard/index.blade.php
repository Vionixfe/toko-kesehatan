    @extends('backend.template.main')
    @section('title', 'Admin Dashboard')

    @section('content')
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            </div>

            <!-- Content Row: Kartu Metrik -->
            <div class="row">
                <!-- Total Pendapatan -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-2"
                                        style="font-size: 1.4rem; font-weight: bold;">
                                        Total Pendapatan
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800" style="font-size: 2rem;">
                                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Pesanan -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-2"
                                        style="font-size: 1.4rem; font-weight: bold;">
                                        Total Pesanan
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800" style="font-size: 2rem;">
                                        {{ $totalOrders }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Customer -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-2"
                                        style="font-size: 1.4rem; font-weight: bold;">
                                        Total Customer
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800" style="font-size: 2rem;">
                                        {{ $totalCustomers }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pesanan Pending -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-2"
                                        style="font-size: 1.4rem; font-weight: bold;">
                                        Pesanan Perlu Diproses
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800" style="font-size: 2rem;">
                                        {{ $pendingOrders }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- Content Row: Grafik dan Pesanan Terbaru -->
            <div class="row">
                <!-- Grafik Statistik Pesanan -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Statistik Pesanan Bulanan (Tahun
                                {{ date('Y') }})</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="monthlyOrdersChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pesanan Terbaru -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Pesanan Terbaru</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    @forelse($recentOrders as $order)
                                        <tr>
                                            <td><strong>#{{ $order->invoice_number }}</strong><br><small>{{ $order->user->name ?? 'Guest' }}</small>
                                            </td>
                                            <td class="text-end">Rp
                                                {{ number_format($order->total_amount) }}<br><small><span
                                                        class="badge bg-primary">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span></small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>Belum ada pesanan.</td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Ekspor Laporan Penjualan</h6>
                    </div>
                    <div class="card-body">
                        <p>Pilih rentang tanggal untuk mengunduh laporan penjualan dalam format PDF.</p>

                        {{-- Form ini akan mengirim data ke route 'reports.export' --}}
                        <form action="{{ route('reports.export') }}" method="POST">
                            @csrf
                            <div class="row align-items-end">
                                {{-- Input Tanggal Mulai --}}
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="start_date">Dari Tanggal:</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control"
                                            required>
                                    </div>
                                </div>

                                {{-- Input Tanggal Selesai --}}
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="end_date">Sampai Tanggal:</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                                    </div>
                                </div>

                                {{-- Tombol Unduh Laporan --}}
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-download fa-sm"></i> Unduh
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('js')
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Bar Chart untuk Pesanan Bulanan
                var ctx = document.getElementById("monthlyOrdersChart").getContext('2d');
                var myBarChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartData['labels']) !!},
                        datasets: [{
                            label: "Jumlah Pesanan",
                            backgroundColor: "#4e73df",
                            hoverBackgroundColor: "#2e59d9",
                            borderColor: "#4e73df",
                            data: {!! json_encode($chartData['data']) !!},
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1 // Hanya tampilkan angka bulat di sumbu Y
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
