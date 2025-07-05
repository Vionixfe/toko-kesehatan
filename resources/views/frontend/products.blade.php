    @extends('layouts.frontend')
    @section('title', 'Semua Produk')

    @section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col"><h2 class="fw-bold">Produk Tersedia</h2></div>
        </div>
        <!-- Filter Form -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('shop.index') }}" method="GET">
                            <div class="input-group">
                                <select name="category" class="form-select" style="max-width: 200px;" onchange="this.form.submit()">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="search" class="form-control" placeholder="Cari nama produk..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product Grid -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @forelse ($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-12"><div class="alert alert-warning text-center">Produk tidak ditemukan.</div></div>
            @endforelse
        </div>
        <!-- Paginasi -->
        <div class="row mt-5"><div class="col">{{ $products->links() }}</div></div>
    </div>
    @endsection
    