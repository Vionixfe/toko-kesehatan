@extends('backend.template.main')
@section('title', 'Detail Pesanan #' . $order->invoice_number)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Pesanan <span class="text-primary">#{{ $order->invoice_number }}</span></h1>
        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali</a>
    </div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Detail Pesanan & Customer</h6></div>
                <div class="card-body">
                    <h5>Item Pesanan</h5>
                    <div class="table-responsive mb-4">
                        <table class="table">
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td><img src="{{ $item->product && $item->product->image ? Storage::url($item->product->image) : 'https://placehold.co/60x60' }}" width="60" class="img-thumbnail"></td>
                                    <td><strong>{{ $item->product->name ?? 'Produk Dihapus' }}</strong><br><small class="text-muted">{{ $item->quantity }} x Rp {{ number_format($item->price) }}</small></td>
                                    <td class="text-end"><strong>Rp {{ number_format($item->quantity * $item->price) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot><tr><th colspan="2" class="text-end">Total:</th><th class="text-end">Rp {{ number_format($order->total_amount) }}</th></tr></tfoot>
                        </table>
                    </div>
                    <hr>
                    <h5>Informasi Pengiriman</h5>
                    <dl class="row">
                        <dt class="col-sm-3">Customer</dt><dd class="col-sm-9">{{ $order->user->name }}</dd>
                        <dt class="col-sm-3">Alamat</dt><dd class="col-sm-9">{{ $order->shipping_address }}</dd>
                        @if($order->shipping_receipt_number)<dt class="col-sm-3">Nomor Resi</dt><dd class="col-sm-9"><strong class="text-primary">{{ $order->shipping_receipt_number }}</strong></dd>@endif
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Aksi Pesanan</h6></div>
                <div class="card-body">
                    <p>Status Saat Ini:
                        @php
                            $statusClasses = ['pending_payment' => 'bg-warning text-dark', 'verifying_payment' => 'bg-info text-white', 'processing' => 'bg-primary text-white', 'shipped' => 'bg-success text-white', 'completed' => 'bg-dark', 'cancelled' => 'bg-danger text-white'];
                        @endphp
                        <span class="badge {{ $statusClasses[$order->status] ?? '' }}">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span>
                    </p><hr>

                    @if($order->status == 'verifying_payment')
                        <h5>Verifikasi Pembayaran</h5>
                        @if($order->payment_proof)
                            <a href="{{ Storage::url($order->payment_proof) }}" target="_blank"><img src="{{ Storage::url($order->payment_proof) }}" class="img-fluid img-thumbnail mb-3"></a>
                        @else
                            <p class="text-muted">Customer belum mengunggah bukti pembayaran.</p>
                        @endif

                        {{-- Pastikan form ini ada dan method-nya adalah POST --}}
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin memverifikasi pembayaran ini?');">
                            @csrf
                            <input type="hidden" name="action" value="verify_payment">
                            <button type="submit" class="btn btn-primary w-100">Verifikasi & Kirim Faktur</button>
                        </form>
                    @endif

                    @if($order->status == 'processing')
                        <h5>Manajemen Pengiriman</h5>
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="ship_order">
                            <div class="mb-3"><label for="shipping_receipt_number" class="form-label">Nomor Resi</label><input type="text" name="shipping_receipt_number" id="shipping_receipt_number" class="form-control" required></div>
                            <button type="submit" class="btn btn-primary w-100">Tandai Dikirim</button>
                        </form>
                    @endif

                    @if(!in_array($order->status, ['completed', 'cancelled']))
                    <hr><h5>Aksi Lainnya</h5><div class="d-grid gap-2">
                        @if($order->status === 'shipped')
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="complete_order">
                            <button type="submit" class="btn btn-secondary w-100">Tandai Selesai</button>
                        </form>
                        @endif
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            <input type="hidden" name="action" value="cancel_order">
                            <button type="submit" class="btn btn-danger w-100 mt-2">Batalkan Pesanan</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
