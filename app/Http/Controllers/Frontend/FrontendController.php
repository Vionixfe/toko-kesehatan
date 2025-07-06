<?php

    namespace App\Http\Controllers\Frontend;

    use App\Models\Category;
    use App\Models\Product;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;

    class FrontendController extends Controller
    {
        public function home()
        {
            $featuredProducts = Product::with('category')->latest()->take(4)->get();
            return view('frontend.home', compact('featuredProducts'));
        }

        public function shop(Request $request)
        {
            $categories = Category::orderBy('name')->get();
            $productsQuery = Product::with('category');

            if ($request->filled('category')) {
                $productsQuery->where('category_id', $request->category);
            }

            if ($request->filled('search')) {
                $productsQuery->where('name', 'like', '%' . $request->search . '%');
            }



            $products = $productsQuery->latest()->paginate(16)->withQueryString();
            return view('frontend.products', compact('products', 'categories'));
        }


        public function shows(Product $product)
        {
             $product->load(['category', 'reviews.user']);

        // Menghitung rata-rata rating dan jumlah ulasan
        $averageRating = $product->reviews->avg('rating');
        $reviewCount = $product->reviews->count();

            $relatedProducts = Product::where('category_id', $product->category_id)
                                      ->where('id', '!=', $product->id)
                                      ->take(4)
                                      ->get();

            return view('frontend.shows', compact('product', 'relatedProducts', 'averageRating', 'reviewCount'));
        }
    }
