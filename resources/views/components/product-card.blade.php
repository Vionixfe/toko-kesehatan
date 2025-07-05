    <div class="col">
        <div class="card h-100 product-card">
            <a href="{{ route('products.shows', $product->slug) }}">
                <img src="{{ $product->image ? Storage::url($product->image) : 'https://placehold.co/600x400?text=Produk' }}"
                    class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
            </a>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><a href="{{ route('products.shows', $product->slug) }}"
                        class="text-dark text-decoration-none">{{ $product->name }}</a></h5>
                <p class="card-text text-muted small">{{ $product->category->name }}</p>
                <h6 class="card-subtitle mb-2 fw-bold">Rp {{ number_format($product->price) }}</h6>
                <div class="mt-auto">
                    <div class="d-flex gap-2">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </a>
                        @endguest

                        @auth
                            @if (Auth::user()->role === 'customer')
                                <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                     <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary w-100 add-to-cart-btn">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-secondary w-100" disabled>
                                    Admin Tidak Bisa Belanja
                                </button>
                            @endif
                        @endauth
                        <a href="{{ route('products.shows', $product->slug) }}" class="btn btn-secondary">
                            <i class="fas fa-eye"></i> View
                        </a>


                    </div>
                </div>
            </div>
        </div>
    </div>
