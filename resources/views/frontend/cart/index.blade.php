@extends('layouts.frontend')
@section('title', 'Keranjang Belanja & Checkout')


@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">Keranjang Belanja</h2>

        {{-- Tombol kembali ke halaman home dan Fitur pencarian produk samping2 --}}
        <div class="mb-4 d-flex">
            <a href="{{ route('home') }}" class="btn btn-info me-2">Kembali</a>
            <form action="{{ route('cart.index') }}" method="GET" class="d-flex flex-grow-1">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari produk di keranjang..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($cartItems->isEmpty())
            <div class="alert alert-info text-center">
                Keranjang belanja Anda masih kosong. <a href="{{ route('products.index') }}">Mulai Belanja Sekarang!</a>
            </div>
        @else
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="form-check-input" id="select-all" checked>
                                            </th>
                                            <th colspan="2">Produk</th>
                                            <th class="text-center">Kuantitas</th>
                                            <th class="text-end">Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cartItems as $item)
                                            <tr id="cart-row-{{ $item->uuid }}">
                                                <td>

                                                    <input type="checkbox" class="form-check-input cart-item-checkbox"
                                                        data-item-id="{{ $item->id }}"
                                                        data-price="{{ $item->product->price }}"
                                                        data-quantity="{{ $item->quantity }}" checked>
                                                </td>
                                                <td style="width: 80px;">
                                                    <img src="{{ $item->product->image ? Storage::url($item->product->image) : 'https://placehold.co/100x100' }}"
                                                        class="img-fluid rounded-3" alt="{{ $item->product->name }}">
                                                </td>
                                                <td>
                                                    <strong>{{ $item->product->name }}</strong>
                                                    <p class="small text-muted mb-0">Rp
                                                        {{ number_format($item->product->price) }}</p>
                                                </td>
                                                <td>
                                                    {{-- Form untuk update kuantitas --}}
                                                    {{-- PERUBAHAN: Tombol sekarang menggunakan class khusus untuk event listener JS --}}
                                                    <div class="input-group justify-content-center" style="width: 120px;">
                                                        <button class="btn btn-outline-secondary quantity-btn"
                                                            type="button" data-action="decrease"
                                                            data-uuid="{{ $item->uuid }}">-</button>
                                                        <input type="text"
                                                            class="form-control text-center quantity-input"
                                                            value="{{ $item->quantity }}" readonly
                                                            data-uuid="{{ $item->uuid }}">
                                                        <button class="btn btn-outline-secondary quantity-btn"
                                                            type="button" data-action="increase"
                                                            data-uuid="{{ $item->uuid }}">+</button>
                                                    </div>
                                                </td>
                                                {{-- PERUBAHAN: Menambahkan ID unik untuk total per item --}}
                                                <td class="text-end fw-bold" id="item-total-{{ $item->uuid }}">Rp
                                                    {{ number_format($item->product->price * $item->quantity) }}</td>
                                                <td class="text-center">
                                                    <form action="{{ route('cart.destroy', $item->uuid) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger p-0"
                                                            title="Hapus item"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    {{-- Form Checkout --}}
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Ringkasan Pesanan</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <p>Subtotal</p>
                                    <p id="subtotal-amount">Rp 0</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p>Pajak (11%)</p>
                                    <p id="tax-amount">Rp 0</p>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold fs-5">
                                    <p>Total</p>
                                    <p id="total-amount">Rp 0</p>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mt-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Detail Pengiriman</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat Lengkap</label>
                                    <textarea name="address" id="address" class="form-control" rows="3" required>{{ old('address', Auth::user()->address) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="house_marker" class="form-label">Patokan (Opsional)</label>
                                    <input type="text" name="house_marker" id="house_marker" class="form-control"
                                        placeholder="Contoh: Pagar biru">
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mt-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="transfer"
                                        value="bank_transfer" checked>
                                    <label class="form-check-label" for="transfer">Transfer Bank</label>
                                </div>
                            </div>
                        </div>

                        {{-- Input tersembunyi untuk menyimpan ID item yang dipilih --}}
                        <div id="selected-items-container"></div>


                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Buat Pesanan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.cart-item-checkbox');
            const selectAllCheckbox = document.getElementById('select-all');
            const subtotalEl = document.getElementById('subtotal-amount');
            const taxEl = document.getElementById('tax-amount');
            const totalEl = document.getElementById('total-amount');
            const selectedItemsContainer = document.getElementById('selected-items-container');
            const taxRate = 0.11;

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            }

            function calculateAndRender() {
                let subtotal = 0;
                let selectedIds = [];

                // Hapus input lama sebelum menambahkan yang baru
                selectedItemsContainer.innerHTML = '';

                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        // Kalkulasi total
                        const price = parseFloat(checkbox.getAttribute('data-price'));
                        const quantity = parseInt(checkbox.getAttribute('data-quantity'));
                        subtotal += price * quantity;

                        // Kumpulkan ID item yang dipilih
                        const itemId = checkbox.getAttribute('data-item-id');
                        selectedIds.push(itemId);

                        // Buat input tersembunyi untuk setiap item yang dipilih
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'cart_items[]';
                        hiddenInput.value = itemId;
                        selectedItemsContainer.appendChild(hiddenInput);
                    }
                });

                const tax = subtotal * taxRate;
                const total = subtotal + tax;

                subtotalEl.textContent = formatRupiah(subtotal);
                taxEl.textContent = formatRupiah(tax);
                totalEl.textContent = formatRupiah(total);
            }

            // Event listener untuk tombol +/-
            $('.quantity-btn').on('click', function() {
                const uuid = $(this).data('uuid');
                const action = $(this).data('action');
                const quantityInput = $(`.quantity-input[data-uuid="${uuid}"]`);
                let currentQuantity = parseInt(quantityInput.val());

                if (action === 'increase') {
                    currentQuantity++;
                } else if (action === 'decrease' && currentQuantity > 1) {
                    currentQuantity--;
                } else {
                    return; // Jangan lakukan apa-apa jika kuantitas sudah 1 dan tombol - ditekan
                }

                // Update kuantitas di server via AJAX
                $.ajax({
                    url: `/cart/${uuid}`, // URL ke route cart.update
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        quantity: currentQuantity
                    },
                    success: function(response) {
                        // Update tampilan jika berhasil
                        quantityInput.val(currentQuantity);
                        $(`#item-total-${uuid}`).text(formatRupiah(response.new_total_item));
                        // Update data-quantity di checkbox agar kalkulasi total benar
                        $(`.cart-item-checkbox[data-item-id="${$('#cart-row-'+uuid+' .cart-item-checkbox').data('item-id')}"]`)
                            .data('quantity', currentQuantity);
                        calculateTotal();
                    },
                    error: function() {
                        alert('Gagal memperbarui kuantitas.');
                    }
                });
            });

            if (selectAllCheckbox) {
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', calculateAndRender);
                });

                selectAllCheckbox.addEventListener('change', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    calculateAndRender();
                });

                // Lakukan kalkulasi pertama kali saat halaman dimuat
                calculateAndRender();
            }



        });
    </script>
@endpush
