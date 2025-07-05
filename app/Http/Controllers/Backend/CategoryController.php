<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Menampilkan halaman daftar kategori.
     */
    public function index()
    {
        // Method ini sekarang hanya bertugas menampilkan view
        return view('backend.categories.index');
    }

    /**
     * Menyediakan data untuk DataTables melalui request AJAX.
     */
    public function getCategories(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d F Y, H:i') . ' WIB';
                })
                ->addColumn('action', function($row){
                    $editUrl = route('categories.edit', $row->id);

                    $btn = '<div class="btn-group" role="group">';
                    // Tombol Edit: hanya icon, warna warning
                    $btn .= '<div class="d-flex justify-content-center align-items-center">';
                    $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-warning mx-1" title="Edit Kategori">';
                    $btn .= '<i class="fas fa-edit"></i></a>';

                    // Tombol Hapus: hanya icon
                    $btn .= '<button type="button" class="btn btn-sm btn-danger mx-1" title="Hapus Kategori" onclick="deleteCategory(this)" data-uuid="'.$row->uuid.'">';
                    $btn .= '<i class="fas fa-trash"></i></button>';
                    $btn .= '</div></div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        abort(403, 'Unauthorized action.');
    }

    /**
     * Menampilkan form untuk membuat kategori baru.
     */
    public function create()
    {
        return view('backend.categories.create');
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit kategori.
     */
    public function edit(Category $category)
    {
        return view('backend.categories.edit', compact('category'));
    }

    /**
     * Memperbarui kategori di database.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        // Update meskipun tidak ada perubahan pada data
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori dari database.
     */
     public function destroy($uuid) // Parameter sekarang adalah $uuid
    {
        try {
            // Cari kategori berdasarkan kolom 'uuid'
            $category = Category::where('uuid', $uuid)->firstOrFail();
            $category->delete();

            // Mengembalikan respons JSON untuk AJAX SweetAlert
            return response()->json(['message' => 'Kategori berhasil dihapus!'], 200);

        } catch (\Exception $e) {
            // Tangani error jika kategori tidak ditemukan atau error lainnya
            return response()->json(['message' => 'Gagal menghapus data. Kategori tidak ditemukan.'], 404);
        }
    }
}
