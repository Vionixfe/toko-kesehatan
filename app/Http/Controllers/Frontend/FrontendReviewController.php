<?php

    namespace App\Http\Controllers\Frontend;

    use App\Http\Controllers\Controller;
    use App\Models\Product;
    use App\Models\Review;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class FrontendReviewController extends Controller
    {
        public function create(Product $product)
        {
            // Cek apakah user sudah pernah membeli produk ini (opsional tapi bagus)
            // Cek apakah user sudah pernah mereview produk ini
            $existingReview = Review::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
            if ($existingReview) {
                return redirect()->route('products.show', $product->slug)->with('error', 'Anda sudah memberi ulasan untuk produk ini.');
            }

            return view('frontend.reviews.create', compact('product'));
        }

        public function store(Request $request)
        {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            // Cek lagi untuk mencegah submit ganda
            $existingReview = Review::where('user_id', Auth::id())->where('product_id', $request->product_id)->exists();
            if ($existingReview) {
                return redirect()->back()->with('error', 'Anda sudah memberi ulasan untuk produk ini.');
            }

            Review::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            $product = Product::findOrFail($request->product_id);
            return redirect()->route('products.show', $product->slug)->with('success', 'Terima kasih atas ulasan Anda!');
        }
    }
