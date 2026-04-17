@extends('layouts.master', ['title' => 'Mesin Kasir (POS)'])

@section('content')
<!-- Jika ada print_id yang di return -->
@if(session('print_id'))
    <script>
        window.open("{{ route('pos.receipt', session('print_id')) }}", "ReceiptWindow", "width=400,height=600");
    </script>
@endif

<div class="row h-100">
    <!-- Area Kiri: Daftar Menu (Produk) -->
    <div class="col-xl-7 col-lg-7">
        <!-- Search & Filter bar bisa ditambahkan di sini kedepannya -->
        <h5 class="mb-3 fw-bold text-secondary">Daftar Menu</h5>
        
        <div class="row g-3" style="max-height: 80vh; overflow-y: auto;">
            @forelse($products as $product)
            <div class="col-md-4 col-sm-6">
                <div class="card h-100 border-0 shadow-sm cursor-pointer product-card" 
                     onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }})">
                    <!-- Tampilkan Gambar -->
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top" style="height: 120px; object-fit: cover;" alt="{{ $product->name }}">
                    @else
                        <div class="bg-light d-flex justify-content-center align-items-center w-100" style="height: 120px;">
                            <i class="fa-solid fa-utensils fa-3x text-secondary opacity-25"></i>
                        </div>
                    @endif

                    <div class="card-body p-3 text-center">
                        <h6 class="fw-bold mb-1 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                        <span class="text-primary fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <div class="mt-2">
                            @if($product->stock > 0)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">Stok: {{ $product->stock }}</span>
                            @else
                                <span class="badge bg-danger">Habis</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-secondary text-center py-5">
                    <i class="fa-solid fa-basket-shopping fa-3x mb-3 text-muted"></i><br>
                    Data produk belum tersedia.
                </div>
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- Area Kanan: Keranjang Belanja & Pembayaran -->
    <div class="col-xl-5 col-lg-5">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-cart-shopping text-warning me-2"></i> Pesanan</h5>
                <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="clearCart()">Clear</button>
            </div>
            
            <!-- List Cart -->
            <div class="card-body p-0">
                <div class="table-responsive" style="min-height: 250px; max-height: 250px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <tbody id="cartItems">
                            <!-- Di-render oleh Javascript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Panel Kalkulasi -->
            <div class="card-footer bg-light p-4 border-top-0">
                <form action="{{ route('pos.store') }}" method="POST" id="checkoutForm">
                    @csrf
                    <div id="hiddenInputs"></div>

                    <!-- Ringkasan Angka -->
                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Subtotal</span>
                        <span id="subtotalDisplay">Rp 0</span>
                        <input type="hidden" name="subtotal" id="subtotalInput" value="0">
                    </div>

                    <!-- Input Diskon & Pajak (Interaktif) -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Diskon (Rp)</label>
                            <input type="number" class="form-control form-control-sm" id="discountInputRaw" name="discount" value="0" min="0" onkeyup="calculateGrandTotal()" onchange="calculateGrandTotal()">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Pajak (%)</label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" id="taxPercentage" value="0" min="0" onkeyup="calculateGrandTotal()" onchange="calculateGrandTotal()">
                                <span class="input-group-text">%</span>
                            </div>
                            <input type="hidden" name="tax" id="taxNominalInput" value="0">
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">Total Tagihan</h4>
                        <h3 class="fw-bold text-primary mb-0" id="totalDisplay">Rp 0</h3>
                        <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
                    </div>
                    
                    <!-- Metode Pembayaran -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-2">Metode Pembayaran</h6>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="payment_method" id="payCash" value="Cash" checked onclick="togglePaymentMethod('Cash')">
                            <label class="btn btn-outline-primary" for="payCash"><i class="fa-solid fa-money-bill-wave me-1"></i> Tunai (Cash)</label>

                            <input type="radio" class="btn-check" name="payment_method" id="payQris" value="QRIS" onclick="togglePaymentMethod('QRIS')">
                            <label class="btn btn-outline-primary" for="payQris"><i class="fa-solid fa-qrcode me-1"></i> QRIS / Digital</label>
                        </div>
                    </div>

                    <!-- Input Jumlah Uang Cash -->
                    <div class="mb-4" id="cashInputBox">
                        <label class="fw-bold mb-2">Uang Diterima (Rp)</label>
                        <input type="number" name="pay_amount" id="payAmountInput" class="form-control form-control-lg text-end fw-bold" placeholder="0" min="0" required>
                    </div>

                    <button type="button" class="btn btn-success w-100 fw-bold py-3 fs-5 rounded-pill shadow-sm" onclick="processCheckout()">
                        <i class="fa-solid fa-check-circle me-2"></i> Bayar & Cetak Struk
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Efect Hover untuk Card Menu */
    .product-card { transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;}
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;}
    .product-card:active { transform: scale(0.98); }
</style>
@endsection

@push('scripts')
<script>
    let cart = [];

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    function addToCart(id, name, price, stock) {
        if(stock <= 0) {
            alert('Produk ini kehabisan stok!');
            return;
        }

        let existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            if (existingItem.qty < stock) {
                existingItem.qty += 1;
            } else {
                alert('Melebihi sisa stok yang ada di database!');
            }
        } else {
            cart.push({ id, name, price, qty: 1, stock });
        }
        renderCart();
    }

    function updateQty(id, change) {
        let itemIndex = cart.findIndex(item => item.id === id);
        if (itemIndex > -1) {
            let item = cart[itemIndex];
            let newQty = item.qty + change;
            
            if (newQty > 0 && newQty <= item.stock) {
                item.qty = newQty;
            } else if (newQty === 0) {
                cart.splice(itemIndex, 1);
            } else if (newQty > item.stock) {
                alert('Stok tidak cukup!');
            }
        }
        renderCart();
    }

    function clearCart() {
        if(confirm('Hapus semua isi keranjang?')) {
            cart = [];
            renderCart();
        }
    }

    function togglePaymentMethod(method) {
        if(method === 'QRIS') {
            document.getElementById('cashInputBox').style.display = 'none';
            // Set otomatis payAmount = total jika QRIS selected
            document.getElementById('payAmountInput').value = document.getElementById('totalAmountInput').value;
        } else {
            document.getElementById('cashInputBox').style.display = 'block';
            document.getElementById('payAmountInput').value = ''; // Kosongkan lagi agar diisi kasir
        }
    }

    function renderCart() {
        let cartItemsBody = document.getElementById('cartItems');
        let hiddenInputsContainer = document.getElementById('hiddenInputs');
        let subtotal = 0;
        
        cartItemsBody.innerHTML = '';
        hiddenInputsContainer.innerHTML = '';

        if (cart.length === 0) {
            cartItemsBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-5"><i class="fa-solid fa-cart-arrow-down fa-2x mb-2 opacity-50"></i><br>Pesanan kosong</td></tr>';
        }

        cart.forEach((item, index) => {
            let itemSubtotal = item.price * item.qty;
            subtotal += itemSubtotal;

            cartItemsBody.innerHTML += `
                <tr>
                    <td class="fw-bold text-dark px-3 py-3" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${item.name}">${item.name}<br>
                        <small class="text-muted fw-normal">${formatRupiah(item.price)}</small>
                    </td>
                    <td width="100">
                        <div class="input-group input-group-sm">
                            <button class="btn btn-outline-secondary px-2" onclick="updateQty(${item.id}, -1)">-</button>
                            <input type="text" class="form-control text-center px-0 fw-bold bg-white" value="${item.qty}" readonly>
                            <button class="btn btn-outline-secondary px-2" onclick="updateQty(${item.id}, 1)">+</button>
                        </div>
                    </td>
                    <td class="text-end fw-bold text-dark">${formatRupiah(itemSubtotal)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-link text-danger p-0" onclick="updateQty(${item.id}, -${item.qty})"><i class="fa-solid fa-circle-xmark fa-lg"></i></button>
                    </td>
                </tr>
            `;

            hiddenInputsContainer.innerHTML += `
                <input type="hidden" name="cart[${index}][id]" value="${item.id}">
                <input type="hidden" name="cart[${index}][qty]" value="${item.qty}">
                <input type="hidden" name="cart[${index}][price]" value="${item.price}">
            `;
        });

        // Set Subtotal Raw
        document.getElementById('subtotalDisplay').innerText = formatRupiah(subtotal);
        document.getElementById('subtotalInput').value = subtotal;

        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let subtotal = parseInt(document.getElementById('subtotalInput').value) || 0;
        
        let discount = parseInt(document.getElementById('discountInputRaw').value) || 0;
        let taxPercent = parseFloat(document.getElementById('taxPercentage').value) || 0;

        // Validasi Diskon tidak boleh melebihi subtotal
        if (discount > subtotal) {
            discount = subtotal;
            document.getElementById('discountInputRaw').value = discount;
        }

        let afterDiscount = subtotal - discount;
        
        // Kalkulasi Pajak Nominal dari (Subtotal - Diskon)
        let nominalTax = (afterDiscount * taxPercent) / 100;
        document.getElementById('taxNominalInput').value = nominalTax;

        let grandTotal = afterDiscount + nominalTax;
        
        document.getElementById('totalDisplay').innerText = formatRupiah(grandTotal);
        document.getElementById('totalAmountInput').value = grandTotal;

        // Auto update input bayar jika sedang dalam mode QRIS (uang pas otomatis)
        let method = document.querySelector('input[name="payment_method"]:checked').value;
        if(method === 'QRIS') {
            document.getElementById('payAmountInput').value = grandTotal;
        }
    }

    function processCheckout() {
        if (cart.length === 0) {
            alert('Keranjang masih kosong!');
            return;
        }

        let totalAmount = parseInt(document.getElementById('totalAmountInput').value);
        let payAmount = parseInt(document.getElementById('payAmountInput').value);
        let method = document.querySelector('input[name="payment_method"]:checked').value;

        if (isNaN(payAmount)) {
            alert('Harap isi nominal uang pelanggan!');
            document.getElementById('payAmountInput').focus();
            return;
        }

        if (method === 'Cash' && payAmount < totalAmount) {
            alert('Uang pelanggan tidak cukup! Kurang ' + formatRupiah(totalAmount - payAmount));
            return;
        }

        if(confirm('Apakah Anda yakin pesanan sudah sesuai?')) {
            document.getElementById('checkoutForm').submit();
        }
    }

    renderCart(); // Initial
</script>
@endpush
