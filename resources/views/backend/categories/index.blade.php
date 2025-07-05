@extends('backend.template.main')

@section('title', 'Category')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Manajemen Kategori</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Kategori Baru
                </a>
            </div>
            <div class="card-body">
               
                <div class="table-responsive">
                    <table class="table table-bordered" id="categoryDataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Dibuat Pada</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
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
            $('#categoryDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('categories.data') }}",
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });

        window.deleteCategory = function(e) {
            let uuid = e.getAttribute('data-uuid');

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data kategori ini akan dihapus secara permanen dan tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: `/categories/${uuid}`,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            Swal.fire({
                                title: "Berhasil Dihapus!",
                                text: data.message,
                                icon: "success",
                                timer: 2500,
                                showConfirmButton: false
                            }).then(() => {
                                $('#categoryDataTable').DataTable().ajax.reload(null, false);
                            });
                        },
                        error: function(xhr) {
                            let message = "Terjadi kesalahan saat menghapus data kategori.";
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                title: "Gagal!",
                                text: message,
                                icon: "error"
                            });
                            console.log(xhr);
                        }
                    });
                }
            });
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
