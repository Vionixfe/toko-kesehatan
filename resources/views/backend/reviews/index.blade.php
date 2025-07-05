@extends('backend.template.main')
@section('title', 'Manajemen Ulasan Produk')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Ulasan Produk</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="reviews-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Customer</th>
                            <th>Produk</th>
                            <th>Rating</th>
                            <th>Komentar</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

    <script>
        $(function () {
            $('#reviews-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('reviews.data') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user_name', name: 'user.name' },
                    { data: 'product_name', name: 'product.name' },
                    { data: 'rating_stars', name: 'rating', orderable: true, searchable: false },
                    { data: 'comment', name: 'comment' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                // Mengurutkan berdasarkan tanggal terbaru secara default
                order: [[ 5, 'desc' ]] 
            });
        });
    </script>
@endpush
