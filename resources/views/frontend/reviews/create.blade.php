    @extends('layouts.frontend')
    @section('title', 'Beri Ulasan untuk ' . $product->name)
    @push('styles')
    <style>.rating { display: inline-block; }.rating input { display: none; }.rating label { float: right; cursor: pointer; color: #ccc; transition: color 0.2s; }.rating label:before { content: '\f005'; font-family: 'Font Awesome 5 Free'; font-weight: 900; font-size: 2rem; }.rating input:checked ~ label, .rating label:hover, .rating label:hover ~ label { color: #ffc107; }</style>
    @endpush
    @section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header"><h4>Beri Ulasan Anda</h4></div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <img src="{{ $product->image ? Storage::url($product->image) : 'https://placehold.co/100x100' }}" class="img-fluid rounded me-3" width="80">
                            <h5>{{ $product->name }}</h5>
                        </div>
                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rating Anda</label>
                                <div class="rating">
                                    <input type="radio" id="star5" name="rating" value="5" required/><label for="star5" title="5 stars"></label>
                                    <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars"></label>
                                    <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars"></label>
                                    <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars"></label>
                                    <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star"></label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label fw-bold">Komentar Anda (Opsional)</label>
                                <textarea name="comment" id="comment" class="form-control" rows="5" placeholder="Bagikan pengalaman Anda tentang produk ini..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
    