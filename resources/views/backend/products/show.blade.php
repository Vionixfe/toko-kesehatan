@extends('backend.template.main')
@section('title', 'Detail Produk: ' . $product->name)

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Produk</h1>
        <a href="{{ route('products.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ $product->name }}</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Kolom untuk Gambar Produk -->
                <div class="col-md-4 text-center">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 400px; border: 1px solid #ddd; padding: 5px;">
                    @else
                        <div class="img-fluid rounded d-flex align-items-center justify-content-center bg-light" style="height: 400px; border: 1px solid #ddd;">
                            <span class="text-muted">Tidak ada gambar</span>
                        </div>
                    @endif
                </div>

                <!-- Kolom untuk Detail Produk -->
                <div class="col-md-8">
                    <h3>{{ $product->name }}</h3>
                    <hr>
                    <dl class="row">
                        <dt class="col-sm-3">Kategori</dt>
                        <dd class="col-sm-9">
                            <span class="badge bg-info text-white">{{ $product->category->name ?? 'N/A' }}</span>
                        </dd>

                        <dt class="col-sm-3">Harga</dt>
                        <dd class="col-sm-9">
                            <h5 class="font-weight-bold text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</h5>
                        </dd>

                        <dt class="col-sm-3">Stok Saat Ini</dt>
                        <dd class="col-sm-9">
                            @if($product->stock > 0)
                                <span class="badge bg-success">{{ $product->stock }} Unit</span>
                            @else
                                <span class="badge bg-danger">Stok Habis</span>
                            @endif
                        </dd>

                        <dt class="col-sm-3">Deskripsi Produk</dt>
                        <dd class="col-sm-9">
                            <p>{!! nl2br(e($product->description)) !!}</p>
                        </dd>

                        <dt class="col-sm-3">Tanggal Dibuat</dt>
                        <dd class="col-sm-9">{{ $product->created_at->format('d F Y, H:i') }}</dd>

                        <dt class="col-sm-3">Terakhir Diperbarui</dt>
                        <dd class="col-sm-9">{{ $product->updated_at->format('d F Y, H:i') }}</dd>
                    </dl>

                    <hr>
                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-start">
                        <a href="{{ route('products.edit', $product->uuid) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit"></i> Edit Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Anda perlu memastikan fungsi deleteProduct sudah ada di layout utama atau di push dari halaman index --}}
{{-- Untuk keamanan, lebih baik push script yang relevan di halaman ini saja --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
