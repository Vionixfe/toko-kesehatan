    @extends('layouts.frontend')
    @section('title', 'Detail Pesanan #' . $order->invoice_number)

    @section('content')
        <div class="container py-5">
            <h2 class="fw-bold mb-4">Detail Pesanan <span class="text-primary">#{{ $order->invoice_number }}</span></h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    {{-- Detail Item --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Detail Item</h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('my-orders.index') }}" class="btn btn-secondary mb-3">
                                &larr; Kembali
                            </a>
                            <div class="mb-3"></div> {{-- Spacer between button and table --}}
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Produk</th>
                                        <th scope="col">Harga Satuan</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name ?? 'Produk Dihapus' }}<br><small
                                                    class="text-muted">{{ $item->product && $item->product->sku ? 'Kode Produk: ' . $item->product->sku : '' }}</small>
                                            </td>
                                            <td>Rp {{ number_format($item->price) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>Rp {{ number_format($item->quantity * $item->price) }}</td>
                                            <td class="text-end">

                                                @if ($order->status == 'completed')
                                                    @if ($item->product && !$item->product->reviews()->where('user_id', Auth::id())->exists())
                                                        <a href="{{ route('reviews.create', $item->product_id) }}"
                                                            class="btn btn-sm btn-outline-primary">Tulis Ulasan</a>
                                                    @else
                                                        <button class="btn btn-sm btn-success" disabled>Sudah
                                                            Diulas</button>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th scope="row" colspan="3" class="text-end">Total:</th>
                                        <th scope="row" class="text-end">Rp {{ number_format($order->total_amount) }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Status & Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            <p>Status: <strong
                                    class="text-primary">{{ str_replace('_', ' ', ucfirst($order->status)) }}</strong></p>
                            <hr>
                            @if ($order->status == 'pending_payment')
                                <h6 class="fw-bold">Lakukan Pembayaran</h6>
                                <p>Silakan transfer sejumlah <strong>Rp {{ number_format($order->total_amount) }}</strong>
                                    ke rekening berikut:</p>
                                <p class="fw-bold">Bank ABC: 1234567890 a.n. Toko Sehat Medika</p>
                                <hr>
                                <h6 class="fw-bold">Unggah Bukti Pembayaran</h6>
                                <form action="{{ route('my-orders.upload_proof', $order->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <input class="form-control @error('payment_proof') is-invalid @enderror"
                                            type="file" name="payment_proof" required>
                                        @error('payment_proof')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Konfirmasi Pembayaran</button>
                                </form>
                                <hr>

                                <form action="{{ route('my-orders.cancel', $order->id) }}" method="POST"
                                    onsubmit="return confirm('Anda yakin ingin membatalkan pesanan ini? Stok akan dikembalikan.')">
                                    @csrf
                                    {{-- JANGAN TAMBAHKAN @method('DELETE') DI SINI --}}
                                    <button type="submit" class="btn btn-outline-danger w-100">Batalkan Pesanan</button>
                                </form>
                            @elseif($order->payment_proof)
                                <h6 class="fw-bold">Bukti Pembayaran Anda</h6>
                                <p>Terima kasih. Mohon tunggu verifikasi dari admin.</p>
                                <a href="{{ Storage::url($order->payment_proof) }}" target="_blank">
                                    <img src="{{ Storage::url($order->payment_proof) }}" class="img-fluid img-thumbnail">
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                 
            </div>
        </div>
    @endsection
