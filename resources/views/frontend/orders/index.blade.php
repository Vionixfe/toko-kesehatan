    @extends('layouts.frontend')
    @section('title', 'Riwayat Pesanan Saya')

    @section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">Riwayat Pesanan Saya</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <a href="{{ route('home') }}" class="btn btn-secondary mb-3">
                    <i class="fa fa-arrow-left"></i> Kembali ke Home
                </a>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->invoice_number }}</strong></td>
                                <td>{{ $order->created_at->format('d F Y') }}</td>
                                <td>Rp {{ number_format($order->total_amount) }}</td>
                                <td><span class="badge bg-primary">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span></td>
                                <td>
                                    <a href="{{ route('my-orders.show', $order->id) }}" class="btn btn-sm btn-info">Lihat Detail</a>
                                    @if(in_array($order->status, ['completed', 'cancelled']))
                                    <form action="{{ route('my-orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('PERINGATAN: Aksi ini akan menghapus permanen pesanan Anda dan tidak dapat dibatalkan. Lanjutkan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Anda belum memiliki riwayat pesanan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>    <div class="mt-3">{{ $orders->links() }}</div>
            </div>
        </div>
    </div>
    @endsection
