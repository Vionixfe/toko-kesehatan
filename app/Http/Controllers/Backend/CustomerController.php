<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index()
    {
        return view('backend.customers.index');
    }

    public function getCustomers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'customer')->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function($row){
                    return '<span class="badge bg-primary">Customer</span>';
                })
                ->editColumn('created_at', function($row){
                    return $row->created_at->format('d F Y');
                })
                ->addColumn('action', function($row){
                    $showUrl = route('customers.show', $row->id);
                    $toggleRoleUrl = route('customers.toggleRole', $row->id);

                    $btn  = '<a href="' . $showUrl . '" class="btn btn-sm btn-success me-1" title="Lihat Detail"><i class="fas fa-eye"></i></a>';
                    $btn .= '<form action="'.$toggleRoleUrl.'" method="POST" class="d-inline" style="display:inline;" onsubmit="return confirm(\'Anda yakin ingin menjadikan user ini sebagai Admin?\')">';
                    $btn .= csrf_field();
                    $btn .= '<button type="submit" class="btn btn-sm btn-warning me-1" title="Jadikan Admin"><i class="fas fa-user-shield"></i></button>';
                    $btn .= '</form>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger mx-1" title="Hapus Produk" onclick="deleteCustomer(this)" data-id="'.$row->id.'"><i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'role'])
                ->make(true);
        }
        abort(403);
    }

    public function show($id)
    {
        $user = User::where('id', $id)->where('role', 'customer')->firstOrFail();
        return view('backend.customers.show', compact('user'));
    }

    public function toggleRole(User $user)
    {
        // Toggle nilai role (customer <-> admin)
        $user->role = $user->role === 'admin' ? 'customer' : 'admin';
        $user->save();

        $message = $user->role === 'admin' ? 'User berhasil dijadikan Admin.' : 'Peran admin untuk user telah dicabut.';

        return redirect()->back()->with('success', $message);
    }


    public function destroy($id)
    {
        try {
            $user = User::where('id', $id)->where('role', 'customer')->firstOrFail();
            $user->delete();
            return response()->json(['message' => 'Customer berhasil dihapus!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus customer.'], 500);
        }
    }
}