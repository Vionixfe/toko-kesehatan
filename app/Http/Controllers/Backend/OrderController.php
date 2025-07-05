<?php


namespace App\Http\Controllers\Backend;


use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\InvoiceMailable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index()
    {
        return view('backend.orders.index');
    }

    public function getOrders(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::with('user')->latest()->get();

            return DataTables::of($data)
                ->addColumn('customer_name', fn($row) => $row->user ? $row->user->nama : 'Guest')
                ->editColumn('total_amount', fn($row) => 'Rp ' . number_format($row->total_amount))
                ->editColumn('created_at', fn($row) => $row->created_at->format('d/m/Y, H:i'))

                ->addColumn('payment_status_badge', function ($row) {
                    if (in_array($row->status, ['pending_payment', 'cancelled'])) {
                        return '<span class="badge bg-warning text-dark">Pending</span>';
                    }
                    return '<span class="badge bg-success">Success</span>';
                })

                ->addColumn('shipping_status_badge', function ($row) {
                    $statusMap = [
                        'pending_payment' => ['class' => 'bg-secondary', 'text' => 'Awaiting Payment'],
                        'verifying_payment' => ['class' => 'bg-info', 'text' => 'Verifying'],
                        'processing' => ['class' => 'bg-primary', 'text' => 'Processing'],
                        'shipped' => ['class' => 'bg-success', 'text' => 'Delivered'],
                        'completed' => ['class' => 'bg-dark', 'text' => 'Completed'],
                        'cancelled' => ['class' => 'bg-danger', 'text' => 'Cancelled'],
                    ];
                    $statusInfo = $statusMap[$row->status] ?? ['class' => 'bg-light text-dark', 'text' => 'Unknown'];
                    return '<span class="badge ' . $statusInfo['class'] . '">' . $statusInfo['text'] . '</span>';
                })

                ->addColumn('action', fn($row) => '<a href="' . route('orders.show', $row->id) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Detail</a>')
                ->rawColumns(['action', 'payment_status_badge', 'shipping_status_badge'])
                ->make(true);
        }
        abort(403);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('backend.orders.show', compact('order'));
    }

   public function updateStatus(Request $request, Order $order)
    {
        $action = $request->input('action');
        $message = 'Status pesanan berhasil diperbarui.';

        switch ($action) {
            case 'verify_payment':
                if ($order->status == 'verifying_payment') {
                    $order->status = 'processing';
                    $order->paid_at = now();
                    $message = 'Pembayaran diverifikasi dan notifikasi telah dikirim.';

                    try {
                        Mail::to($order->user->email)->send(new InvoiceMailable($order));
                        $admins = User::where('role', 'admin')->get();
                        foreach ($admins as $admin) {
                            Mail::to($admin->email)->send(new InvoiceMailable($order, true));
                        }
                    } catch (\Exception $e) {
                        \Log::error('Email sending failed for order ' . $order->id . ': ' . $e->getMessage());
                        $message .= ' (Namun, pengiriman email notifikasi gagal. Cek log.)';
                    }
                } else {
                    return redirect()->back()->with('error', 'Aksi ini tidak dapat dilakukan untuk status pesanan saat ini.');
                }
                break;

            case 'ship_order':
                // Pastikan status pesanan adalah 'processing' sebelum dikirim
                if ($order->status == 'processing') {
                    $request->validate(['shipping_receipt_number' => 'required|string|max:255']);
                    $order->status = 'shipped';
                    $order->shipping_receipt_number = $request->shipping_receipt_number;
                    $message = 'Pesanan ditandai sebagai dikirim dengan nomor resi ' . $request->shipping_receipt_number;
                } else {
                     return redirect()->back()->with('error', 'Pesanan tidak dapat dikirim karena statusnya bukan "Processing".');
                }
                break;

            case 'complete_order':
                 // Pastikan status pesanan adalah 'shipped' sebelum diselesaikan
                if ($order->status == 'shipped') {
                    $order->status = 'completed';
                    $message = 'Pesanan telah diselesaikan.';
                } else {
                    return redirect()->back()->with('error', 'Pesanan tidak dapat diselesaikan karena belum dikirim.');
                }
                break;

            case 'cancel_order':
                $order->status = 'cancelled';
                $message = 'Pesanan telah dibatalkan.';
                break;

            default:
                // Jika tidak ada case yang cocok, kembalikan pesan ini
                return redirect()->back()->with('error', 'Aksi tidak valid atau tidak dikenali.');
        }

        $order->save();
        return redirect()->route('orders.show', $order->id)->with('success', `message`);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $order = Order::create([
            'user_id' => $request->user()->id,
            'product_name' => $validatedData['product_name'],
            'quantity' => $validatedData['quantity'],
            'total_amount' => $validatedData['total_amount'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Pesanan berhasil dibuat, menunggu pembayaran.',
            'order_id' => $order->id
        ], 201);
    }


}
