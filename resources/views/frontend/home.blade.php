    @extends('layouts.frontend')
    @section('title', 'Selamat Datang di Sehat Medika')

    @section('content')
    <!-- Hero Section -->
    <div class="container-fluid bg-light py-5 text-center">
        <h1 class="display-4 fw-bold">Solusi Alat Kesehatan Terpercaya</h1>
        <p class="fs-5 text-muted col-md-8 mx-auto">Kami menyediakan berbagai macam alat kesehatan berkualitas tinggi untuk kebutuhan medis profesional maupun pribadi.</p>
        <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg">Jelajahi Produk Kami</a>
    </div>

    <!-- Featured Products Section -->
    <div class="container py-5">
        <div class="section-title text-center mb-5">
            <h2>Produk Unggulan</h2>
            <p>Temukan produk-produk terbaik dan terlaris dari kami.</p>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @forelse ($featuredProducts as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-12"><p class="text-center text-muted">Belum ada produk unggulan.</p></div>
            @endforelse
        </div>
    </div>
    @endsection
    