    @extends('backend.template.main')
    @section('title', 'Order Management')

    @section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Order Management</h1>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="orders-table" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Total Payment</th>
                                <th>Payment Status</th>
                                <th>Date</th>
                                <th>Shipping Address</th>
                                <th>Shipping Status</th>
                                <th>Action</th>
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
                $('#orders-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('orders.data') }}",
                    columns: [
                        { data: 'invoice_number', name: 'invoice_number' },
                        { data: 'customer_name', name: 'user.name' },
                        { data: 'total_amount', name: 'total_amount' },
                        { data: 'payment_status_badge', name: 'payment_status_badge', orderable: false, searchable: false },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'shipping_address', name: 'shipping_address', orderable: false },
                        { data: 'shipping_status_badge', name: 'status' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    order: [[ 4, 'desc' ]]
                });
            });
        </script>
    @endpush
    