<?php

namespace App\Http\Controllers\Backend;

    use App\Http\Controllers\Controller;
    use App\Models\Review;
    use Illuminate\Http\Request;
    use Yajra\DataTables\Facades\DataTables;

    class ReviewController extends Controller
    {
        public function index()
        {
            return view('backend.reviews.index');
        }

        public function getReviews(Request $request)
        {
            if ($request->ajax()) {
                // Mengambil semua ulasan dengan data user dan produknya (Eager Loading)
                $data = Review::with(['user', 'product'])->latest()->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('user_name', function($row){
                        return $row->user ? $row->user->nama : 'User Dihapus';
                    })
                    ->addColumn('product_name', function($row){
                        return $row->product ? $row->product->name : 'Produk Dihapus';
                    })
                    ->addColumn('rating_stars', function($row){
                        $stars = '';
                        for ($i = 1; $i <= 5; $i++) {
                            $starClass = $i <= $row->rating ? 'fas fa-star text-warning' : 'far fa-star text-muted';
                            $stars .= '<i class="'.$starClass.'"></i> ';
                        }
                        return $stars;
                    })
                    ->addColumn('action', function($row){
                        $deleteUrl = route('reviews.destroy', $row->id);
                        $btn = '<form action="'.$deleteUrl.'" method="POST" onsubmit="return confirm(\'Anda yakin ingin menghapus ulasan ini?\')">';
                        $btn .= csrf_field();
                        $btn .= method_field('DELETE');
                        $btn .= '<button type="submit" class="btn btn-sm btn-danger" title="Hapus Ulasan"><i class="fas fa-trash"></i> Hapus</button>';
                        $btn .= '</form>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'rating_stars'])
                    ->make(true);
            }
            abort(403);
        }

        public function destroy(Review $review)
        {
            $review->delete();
            return redirect()->route('reviews.index')->with('success', 'Ulasan berhasil dihapus.');
        }
    }
