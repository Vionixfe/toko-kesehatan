    @extends('layouts.frontend')
    @section('title', $product->name)

    @section('content')
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{ $product->image ? Storage::url($product->image) : 'https://placehold.co/600x400?text=Produk' }}"
                                class="img-fluid rounded" alt="{{ $product->name }}">
                        </div>
                        <div class="col-md-6">
                            <h1 class="fw-bold">{{ $product->name }}</h1>
                            <p><span class="badge bg-primary">{{ $product->category->name }}</span></p>
                            <h2 class="my-3 text-danger fw-bold">Rp {{ number_format($product->price) }}</h2>

                            @guest
                                <a href="{{ route('login') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </a>
                            @endguest

                            @auth

                                @if (Auth::user()->role === 'customer')
                                    <form action="{{ route('cart.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <div class="row align-items-end">
                                            <div class="col-md-4">
                                                <label for="quantity" class="form-label">Kuantitas</label>
                                                <input type="number" name="quantity" id="quantity" class="form-control"
                                                    value="1" min="1" max="{{ $product->stock }}">
                                            </div>
                                            <div class="col-md-8">
                                                <button type="submit" class="btn btn-primary btn-lg w-100"><i
                                                        class="fas fa-shopping-cart"></i> Add to Cart</button>
                                            </div>
                                        </div>
                                    </form>
                                @else
                                    <div class="alert alert-warning mt-3">
                                        Anda login sebagai Admin. Admin tidak dapat melakukan transaksi.
                                    </div>
                                @endif
                            @endauth
                            <hr>
                            <h5 class="mt-4">Deskripsi Produk</h5>
                            <p>{!! nl2br(e($product->description)) !!}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mt-5">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Ulasan Pelanggan ({{ $reviewCount }})</h4>
                </div>
                <div class="card-body p-4">
                    @forelse($product->reviews as $review)
                        <div class="d-flex mb-4">
                           
                            <div class="ms-3 flex-grow-1">
                                <h5 class="fw-bold mb-1">{{ $review->user->nama }}</h5>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fa-star {{ $i <= $review->rating ? 'fas text-warning' : 'far text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-muted small">{{ $review->created_at->format('d F Y') }}</span>
                                </div>
                                <p>{{ $review->comment }}</p>
                            </div>
                        </div>
                        @if (!$loop->last)
                            <hr>
                        @endif
                    @empty
                        <p class="text-center text-muted">Belum ada ulasan untuk produk ini. Jadilah yang pertama memberi
                            ulasan!</p>
                    @endforelse
                </div>
            </div>
            <!-- Related Products -->
            @if ($relatedProducts->isNotEmpty())
                <div class="row mt-5">
                    <div class="col">
                        <h3 class="fw-bold">Produk Terkait</h3>
                        <hr>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                            @foreach ($relatedProducts as $related)
                                <x-product-card :product="$related" />
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endsection
