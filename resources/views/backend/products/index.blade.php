    @extends('backend.template.main')

    @section('title', 'Manajemen Produk')

    @section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Manajemen Produk</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ route('products.create') }}" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                    <span class="text">Tambah Produk Baru</span>
                </a>
            </div>
            <div class="card-body">
                {{-- @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="products-table" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
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
           $(document).ready(function() {
                $('#products-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('products.data') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'image_display', name: 'image_display', orderable: false, searchable: false },
                        { data: 'name', name: 'name' },
                        { data: 'category_name', name: 'category.name' },
                        { data: 'price', name: 'price' },
                        { data: 'stock', name: 'stock' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
                });
            });

            function deleteProduct(e) {
                let uuid = e.getAttribute('data-uuid');
                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Produk ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: `/products/${uuid}`,
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function(data) {
                                Swal.fire("Berhasil!", data.message, "success");
                                $('#products-table').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire("Gagal!", "Terjadi kesalahan saat menghapus produk.", "error");
                            }
                        });
                    }
                })
            }
        </script>

         @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    html: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                });
            </script>
        @endif
    @endpush
    
   