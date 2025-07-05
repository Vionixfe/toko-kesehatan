<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class UserOrderController extends Controller
{
    /**
     * Menampilkan halaman daftar semua pesanan milik user.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->paginate(10);
        return view('frontend.orders.index', compact('orders'));
    }

    /**
     * Menampilkan halaman detail satu pesanan.
     * Ini adalah halaman yang akan memperbaiki error Anda.
     */
    public function show(Order $order)
    {
        // Keamanan: Pastikan user hanya bisa melihat pesanannya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak');
        }
        return view('frontend.orders.show', compact('order'));
    }

    /**
     * Memproses upload bukti pembayaran dari customer.
     */
    public function uploadPaymentProof(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($order->payment_proof && Storage::disk('public')->exists($order->payment_proof)) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $order->payment_proof = $path;
        $order->status = 'verifying_payment'; // Status otomatis berubah
        $order->save();

        return redirect()->route('my-orders.show', $order->id)->with('success', 'Bukti pembayaran berhasil diunggah.');
    }

    public function cancel(Order $order)
    {
        // Keamanan: Pastikan user hanya bisa membatalkan pesanannya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        // Hanya izinkan pembatalan jika status masih menunggu pembayaran
        if ($order->status !== 'pending_payment') {
            return redirect()->back()->with('error', 'Pesanan ini tidak dapat dibatalkan lagi.');
        }

        // Gunakan Database Transaction untuk memastikan semua proses aman
        DB::beginTransaction();
        try {
            $order->status = 'cancelled';
            $order->save();
            foreach ($order->items as $item) {
                // Gunakan increment untuk menambah kembali stok
                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
            }
            DB::commit();

            return redirect()->route('my-orders.index')->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            // Jika ada error, batalkan semua proses
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membatalkan pesanan.');
        }
    }
    public function destroy(Order $order)
    {
        // Keamanan: Pastikan user hanya bisa menghapus pesanannya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        // Hanya izinkan jika status sudah selesai atau dibatalkan
        if (!in_array($order->status, ['completed', 'cancelled'])) {
            return redirect()->back()->with('error', 'Pesanan yang sedang berjalan tidak dapat dihapus.');
        }

        // Hapus bukti pembayaran dari storage jika ada
        if ($order->payment_proof && Storage::disk('public')->exists($order->payment_proof)) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        // Hapus pesanan dari database. Ini akan otomatis menghapus order_items juga
        // karena ada onDelete('cascade') di migrasi.
        $order->delete();

        return redirect()->route('my-orders.index')->with('success', 'Riwayat pesanan berhasil dihapus permanen.');
    }

    //  public function downloadInvoice(Order $order)
    //     {
    //         if ($order->user_id !== auth()->id()) {
    //             abort(403);
    //         }
    //         $pdf = Pdf::loadView('frontend.orders.invoice', compact('order'));
    //         return $pdf->download('invoice-' . $order->invoice_number . '.pdf');
    //     }
}
