    @extends('backend.template.main')

    @section('title', 'Manajemen Customer')

    @section('content')
        <div class="container-fluid">
            <h1 class="h3 mb-4 text-gray-800">Manajemen Customer</h1>
            <div class="card shadow mb-4">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="customers-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Bergabung Pada</th>
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
            $(function() {
                        $('#customers-table').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('customers.data') }}",
                            columns: [{
                                    data: 'DT_RowIndex',
                                    name: 'DT_RowIndex',
                                    orderable: false,
                                    searchable: false
                                },
                                {
                                    data: 'nama',
                                    name: 'nama'
                                },
                                {
                                    data: 'email',
                                    name: 'email'
                                },
                                {
                                    data: 'role',
                                    name: 'role',
                                    orderable: false,
                                    searchable: false
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
                                }
                            ]
                        });

                        window.deleteCustomer = function(e) {
                            let id = e.getAttribute('data-id');
                            Swal.fire({
                                title: 'Anda Yakin?',
                                text: "Customer ini akan dihapus secara permanen!",
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
                                        url: `/customers/${id}`,
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        success: function(data) {
                                            Swal.fire("Berhasil!", data.message, "success");
                                            $('#customers-table').DataTable().ajax.reload();
                                        },
                                        error: function(xhr) {
                                            Swal.fire("Gagal!",
                                                "Terjadi kesalahan saat menghapus customer.", "error");
                                        }
                                    });
                                }
                            })
                        }
                    });
        </script>
    @endpush
