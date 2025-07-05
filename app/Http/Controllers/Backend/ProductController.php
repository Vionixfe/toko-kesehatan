<?php

    namespace App\Http\Controllers\Backend;

    use App\Http\Controllers\Controller;
    use App\Models\Product;
    use App\Models\Category;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;
    use Yajra\DataTables\Facades\DataTables;

    class ProductController extends Controller
    {
        public function index()
        {
            return view('backend.products.index');
        }

        public function getProducts(Request $request)
        {
            if ($request->ajax()) {
                $data = Product::with('category')->orderBy('category_id')->latest()->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('category_name', function($row){
                        return $row->category ? $row->category->name : '<span class="text-danger">N/A</span>';
                    })
                    ->editColumn('price', function($row){
                        return 'Rp ' . number_format($row->price, 0, ',', '.');
                    })
                    ->addColumn('image_display', function($row){
                        if ($row->image) {
                            return '<img src="'. Storage::url($row->image) .'" alt="Product Image" class="img-thumbnail" width="80">';
                        }
                        return 'Tidak ada gambar';
                    })
                    ->addColumn('action', function($row){
                        $showUrl = route('products.show', $row->uuid);
                        $editUrl = route('products.edit', $row->uuid);
                        $btn = '<div class="d-flex justify-content-center">';
                        $btn .= '<a href="' . $showUrl . '" class="btn btn-sm btn-info mx-1" title="Lihat Produk"><i class="fas fa-eye"></i></a>';
                        $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-warning mx-1" title="Edit Produk"><i class="fas fa-edit"></i></a>';
                        $btn .= '<button type="button" class="btn btn-sm btn-danger mx-1" title="Hapus Produk" onclick="deleteProduct(this)" data-uuid="'.$row->uuid.'"><i class="fas fa-trash"></i></button>';
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'image_display', 'category_name'])
                    ->make(true);
            }
            abort(403, 'Unauthorized action.');
        }

        public function create()
        {
            $categories = Category::orderBy('name')->get();
            return view('backend.products.create', compact('categories'));
        }

        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|integer|min:0',
                'stock' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->except('image');

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $data['image'] = $path;
            }

            Product::create($data);

            return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
        }

        public function edit($uuid)
        {
            $product = Product::where('uuid', $uuid)->firstOrFail();
            $categories = Category::orderBy('name')->get();
            return view('backend.products.edit', compact('product', 'categories'));
        }

        public function update(Request $request, Product $product)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|integer|min:0',
                'stock' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->except('image');

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $path = $request->file('image')->store('products', 'public');
                $data['image'] = $path;
            }

            $product->update($data);

            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
        }

        public function destroy($uuid)
        {
            try {
                $product = Product::where('uuid', $uuid)->firstOrFail();
                // Hapus gambar dari storage
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->delete();
                return response()->json(['message' => 'Produk berhasil dihapus!'], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Gagal menghapus produk.'], 500);
            }
        }

        public function show($uuid)
        {
            $product = Product::with('category')->where('uuid', $uuid)->firstOrFail();

            return view('backend.products.show', compact('product'));
        }
    }
    