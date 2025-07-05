<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    @forelse ($products as $product)
        <x-product-card :product="$product" />
    @empty
        <div class="col-12"><div class="alert alert-warning text-center">Produk tidak ditemukan.</div></div>
    @endforelse
</div>
<div class="row mt-5">
    <div class="col">{{ $products->links() }}</div>
</div>