@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Area Kiri: Daftar Menu (Produk) -->
    <div class="col-md-7">
        <h4 class="mb-3">Pilih Menu</h4>
        <div class="row">
            @forelse($products as $product)
            <!-- Kita pastikan tingginya h-100 agar sejajar, lalu mb-4 untuk margin bawah -->
            <div class="col-md-4 mb-4"> 
                <div class="card shadow-sm h-100 position-relative pb-4">
                    <div class="card-body">
                        <h5 class="fw-bold text-primary">{{ $product->name }}</h5>
                        <p class="text-muted mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <small class="text-secondary d-block mb-3">Sisa Stok: {{ $product->stock }}</small>
                        <button class="btn btn-sm btn-outline-success w-100 position-absolute bottom-0 start-50 translate-middle-x mb-2" 
                                style="width: 90% !important;"
                                onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }})">
                            Tambah ke Cart
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-secondary">Belum ada produk yang tersedia atau stok habis.</div>
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- Area Kanan: Keranjang Ringkasan -->
    <div class="col-md-5">
        <div class="card shadow-sm border-warning">
            <div class="card-header bg-warning text-dark fw-bold">
                🛒 Keranjang Belanja
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover mb-0" id="cartTable">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Item</th>
                                <th width="110">Qty</th>
                                <th class="text-end">Subtotal</th>
                                <th width="40"></th>
                            </tr>
                        </thead>
                        <tbody id="cartItems">
                            <!-- Data baris akan diinject oleh Javascript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light p-3">
                <div class="d-flex justify-content-between mb-3">
                    <span class="fs-5 fw-bold">Total:</span>
                    <span class="fs-5 fw-bold text-danger" id="totalDisplay">Rp 0</span>
                </div>
                
                <form action="{{ route('pos.store') }}" method="POST" id="checkoutForm">
                    @csrf
                    <!-- Tempat menyimpan data array untuk di-submit ke PHP -->
                    <div id="hiddenInputs"></div>
                    
                    <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text fw-bold">Bayar (Rp)</span>
                        <input type="number" name="pay_amount" id="payAmountInput" class="form-control form-control-lg text-end fw-bold" required min="0" placeholder="0">
                    </div>
                    
                    <button type="button" class="btn btn-primary w-100 fw-bold py-3 fs-5" onclick="processCheckout()">💳 Proses Pembayaran</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Menyimpan state dari aplikasi (data keranjang)
    let cart = [];

    // Fungsi untuk mempercantik tulisan angka harga
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    // Fungsi menambah item ke keranjang
    function addToCart(id, name, price, stock) {
        // Cek apakah produk sudah ada di keranjang
        let existingItem = cart.find(item => item.id === id);
        
        if (existingItem) {
            if (existingItem.qty < stock) {
                existingItem.qty += 1;
            } else {
                alert('Oppss! Stok tidak mencukupi.');
            }
        } else {
            if (stock > 0) {
                cart.push({ id, name, price, qty: 1, stock });
            } else {
                alert('Stok habis!');
            }
        }
        
        // Selalu mutakhirkan tampilan UI
        renderCart();
    }

    // Fungsi merubah qty (plus/minus/hapus)
    function updateQty(id, change) {
        let itemIndex = cart.findIndex(item => item.id === id);
        if (itemIndex > -1) {
            let item = cart[itemIndex];
            let newQty = item.qty + change;
            
            if (newQty > 0 && newQty <= item.stock) {
                item.qty = newQty;
            } else if (newQty === 0) {
                cart.splice(itemIndex, 1); // Buang jika 0
            } else if (newQty > item.stock) {
                alert('Melebihi sisa stok yang ada!');
            }
        }
        renderCart();
    }

    // Fungsi utama merekonstruksi Tampilan HTML Cart
    function renderCart() {
        let cartItemsBody = document.getElementById('cartItems');
        let hiddenInputsContainer = document.getElementById('hiddenInputs');
        let total = 0;
        
        cartItemsBody.innerHTML = '';
        hiddenInputsContainer.innerHTML = '';

        if (cart.length === 0) {
            cartItemsBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">Keranjang masih kosong</td></tr>';
        }

        cart.forEach((item, index) => {
            let subtotal = item.price * item.qty;
            total += subtotal;

            // Generate baris tabel Tampilan Kasir
            cartItemsBody.innerHTML += `
                <tr>
                    <td class="align-middle fw-bold">${item.name}</td>
                    <td class="align-middle">
                        <div class="input-group input-group-sm">
                            <button class="btn btn-outline-secondary" onclick="updateQty(${item.id}, -1)">-</button>
                            <input type="text" class="form-control text-center px-0 bg-white" value="${item.qty}" readonly>
                            <button class="btn btn-outline-secondary" onclick="updateQty(${item.id}, 1)">+</button>
                        </div>
                    </td>
                    <td class="text-end align-middle">${formatRupiah(subtotal)}</td>
                    <td class="align-middle text-center">
                        <button class="btn btn-sm text-danger" onclick="updateQty(${item.id}, -${item.qty})">&times;</button>
                    </td>
                </tr>
            `;

            // Membuat input 'hidden' yang format namannya dibaca Array oleh Laravel ($request->cart[0][id])
            hiddenInputsContainer.innerHTML += `
                <input type="hidden" name="cart[${index}][id]" value="${item.id}">
                <input type="hidden" name="cart[${index}][qty]" value="${item.qty}">
                <input type="hidden" name="cart[${index}][price]" value="${item.price}">
            `;
        });

        // Update Total Harga
        document.getElementById('totalDisplay').innerText = formatRupiah(total);
        document.getElementById('totalAmountInput').value = total;
    }

    // Fungsi Validasi Submit Pembayaran
    function processCheckout() {
        if (cart.length === 0) {
            alert('Keranjang belanja masih kosong! Silakan tambahkan menu terlebih dahulu.');
            return;
        }

        let totalAmount = parseInt(document.getElementById('totalAmountInput').value);
        let payAmount = parseInt(document.getElementById('payAmountInput').value);

        if (!payAmount || isNaN(payAmount)) {
            alert('Silakan masukkan jumlah uang yang dibayarkan pelanggan!');
            return;
        }

        if (payAmount < totalAmount) {
            alert('Uang pembayaran tidak mencukupi! Kurang ' + formatRupiah(totalAmount - payAmount));
            return;
        }

        // Tembak Submit menuju file Controller Laravel Route -> "pos.store"
        document.getElementById('checkoutForm').submit();
    }

    // Eksekusi fungsi pertama kali agar default kosong ditampilkan
    renderCart();
</script>
@endpush
