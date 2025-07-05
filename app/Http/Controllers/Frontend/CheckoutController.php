<?php

namespace App\Http\Controllers\Frontend;

    use App\Models\Cart;
    use App\Models\Order;
    use App\Models\OrderItem;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use App\Http\Controllers\Controller;

    class CheckoutController extends Controller
    {
        public function process(Request $request)
        {
            // Validasi sekarang memeriksa array 'cart_items'
            $request->validate([
                'address' => 'required|string|max:1000',
                'house_marker' => 'nullable|string|max:255',
                'payment_method' => 'required|string',
                'cart_items' => 'required|array',
                'cart_items.*' => 'exists:carts,id', 
            ]);

            $user = Auth::user();
            // Ambil HANYA item yang dicentang dan milik user yang login
            $selectedItems = Cart::with('product')
                                ->where('user_id', $user->id)
                                ->whereIn('id', $request->cart_items)
                                ->get();

            if ($selectedItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Anda belum memilih item untuk di-checkout.');
            }

            $subtotal = $selectedItems->sum(fn($item) => $item->quantity * $item->product->price);
            $tax = $subtotal * 0.11;
            $totalAmount = $subtotal + $tax;

            DB::beginTransaction();
            try {
                $order = Order::create([
                    'user_id' => $user->id,
                    'invoice_number' => 'INV-' . time() . '-' . $user->id,
                    'total_amount' => $totalAmount,
                    'shipping_address' => $request->address . ' (' . $request->house_marker . ')',
                    'payment_method' => $request->payment_method,
                    'status' => 'pending_payment',
                ]);

                foreach ($selectedItems as $cartItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->product->price,
                    ]);
                    $cartItem->product->decrement('stock', $cartItem->quantity);
                    $cartItem->delete(); // Hapus item dari keranjang setelah dipesan
                }

                DB::commit();
                return redirect()->route('my-orders.show', $order->id)->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('cart.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }
    }
