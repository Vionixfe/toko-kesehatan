<?php
    
    namespace App\Http\Controllers\Frontend;
    
    use App\Models\Cart;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use App\Http\Controllers\Controller;
    
    class CartController extends Controller
    {
        // Menampilkan halaman keranjang belanja
        public function index(Request $request)
    {
        // Mulai query dasar untuk mengambil item keranjang milik user yang login
        $cartQuery = Cart::with('product')->where('user_id', Auth::id());

        // PERBAIKAN: Tambahkan logika pencarian
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            // Gunakan whereHas untuk memfilter keranjang berdasarkan kondisi di tabel produk
            $cartQuery->whereHas('product', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Ambil hasilnya
        $cartItems = $cartQuery->get();
        
        // Hitung subtotal hanya dari item yang ditampilkan
        $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

        // Kirim data ke view
        return view('frontend.cart.index', compact('cartItems', 'subtotal'));
    }

        // Menambahkan produk ke keranjang
        public function store(Request $request)
        {
            $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'required|integer|min:1']);
            $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $request->product_id)->first();

            if ($cartItem) {
                $cartItem->quantity += $request->quantity;
                $cartItem->save();
            } else {
                Cart::create(['user_id' => Auth::id(), 'product_id' => $request->product_id, 'quantity' => $request->quantity]);
            }
            return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan!');
        }

        // Memperbarui kuantitas berdasarkan UUID
         public function update(Request $request, $uuid)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem = Cart::where('uuid', $uuid)->where('user_id', Auth::id())->firstOrFail();
        
        $cartItem->update(['quantity' => $request->quantity]);

        // Jika ini adalah permintaan AJAX
        if ($request->ajax()) {
            // Kirim kembali data yang dibutuhkan untuk update tampilan
            return response()->json([
                'message' => 'Kuantitas berhasil diperbarui.',
                'new_total_item' => $cartItem->quantity * $cartItem->product->price,
            ]);
        }

        // Jika bukan AJAX (sebagai fallback), lakukan redirect seperti biasa
        return redirect()->route('cart.index');
    }

        // Menghapus item berdasarkan UUID
        public function destroy($uuid)
        {
            $cartItem = Cart::where('uuid', $uuid)->where('user_id', Auth::id())->firstOrFail();
            $cartItem->delete();
            return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus.');
        }
    }
    