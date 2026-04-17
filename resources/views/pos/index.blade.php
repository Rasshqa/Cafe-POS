@extends('layouts.master', ['title' => 'Kasir'])

@section('content')
@if(session('print_id'))
<script>window.open("{{ route('pos.receipt', session('print_id')) }}", "Receipt", "width=420,height=650");</script>
@endif

<div class="row g-4" style="min-height:calc(100vh - 120px);">
    <!-- LEFT: Products Grid -->
    <div class="col-lg-7 col-xl-7">
        <div class="d-flex align-items-center justify-content-between mb-3 animate-in">
            <h5 class="fw-bold mb-0">Daftar Menu</h5>
            <div class="input-group" style="max-width:220px;">
                <span class="input-group-text border-end-0 bg-white"><i class="fa-solid fa-search text-muted" style="font-size:.8rem;"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" placeholder="Cari menu..." id="searchProduct" onkeyup="filterProducts()" style="font-size:.85rem;">
            </div>
        </div>

        <div class="row g-3" id="productGrid" style="max-height:calc(100vh - 180px);overflow-y:auto;padding-right:4px;">
            @forelse($products as $product)
            <div class="col-lg-4 col-md-4 col-sm-6 product-item" data-name="{{ strtolower($product->name) }}">
                <div class="card h-100 product-card animate-in" style="cursor:pointer;animation-delay:{{ $loop->index * 0.03 }}s;"
                     onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }})">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top" style="height:110px;object-fit:cover;" alt="">
                    @else
                        <div class="d-flex align-items-center justify-content-center" style="height:110px;background:#f8fafc;">
                            <i class="fa-solid fa-mug-saucer fa-2x" style="color:#e2e8f0;"></i>
                        </div>
                    @endif
                    <div class="card-body p-3 text-center">
                        <div class="fw-semibold text-truncate mb-1" style="font-size:.85rem;" title="{{ $product->name }}">{{ $product->name }}</div>
                        <div class="fw-bold" style="color:#6366f1;font-size:.9rem;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        <div class="mt-2">
                            @if($product->stock > 0)
                                <span class="badge bg-success bg-opacity-10 text-success" style="font-size:.68rem;">Stok {{ $product->stock }}</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size:.68rem;">Habis</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-mug-hot fa-3x mb-3 opacity-25"></i>
                    <div>Belum ada produk tersedia</div>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- RIGHT: Cart Panel -->
    <div class="col-lg-5 col-xl-5">
        <div class="card h-100 d-flex flex-column animate-in" style="animation-delay:.15s;">
            <!-- Cart Header -->
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 px-4" style="border-bottom:1px solid #f1f5f9;">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-cart-shopping me-2" style="color:#f59e0b;"></i>Pesanan <span class="badge rounded-pill ms-1" style="background:#f1f5f9;color:#64748b;font-size:.7rem;" id="cartCount">0</span></h6>
                <button class="btn btn-sm btn-ghost text-danger px-2" onclick="clearCart()" title="Clear semua"><i class="fa-solid fa-trash-can"></i></button>
            </div>

            <!-- Cart Items -->
            <div class="flex-grow-1 overflow-auto" style="min-height:200px;max-height:320px;">
                <table class="table table-hover align-middle mb-0">
                    <tbody id="cartItems"></tbody>
                </table>
            </div>

            <!-- Payment Panel -->
            <div class="p-4" style="background:#f8fafc;border-top:1px solid #f1f5f9;">
                <form action="{{ route('pos.store') }}" method="POST" id="checkoutForm">
                    @csrf
                    <div id="hiddenInputs"></div>

                    <div class="d-flex justify-content-between mb-1" style="font-size:.85rem;color:#64748b;">
                        <span>Subtotal</span>
                        <span id="subtotalDisplay">Rp 0</span>
                        <input type="hidden" name="subtotal" id="subtotalInput" value="0">
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label" style="font-size:.72rem;">Diskon (Rp)</label>
                            <input type="number" class="form-control form-control-sm" id="discountInputRaw" name="discount" value="0" min="0" onkeyup="calcTotal()" onchange="calcTotal()">
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size:.72rem;">Pajak (%)</label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" id="taxPercentage" name="tax_percent" value="{{ $defaultTax ?? 0 }}" min="0" onkeyup="calcTotal()" onchange="calcTotal()">
                                <span class="input-group-text">%</span>
                            </div>
                            <input type="hidden" name="tax" id="taxNominalInput" value="0">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 pt-2" style="border-top:2px solid #e2e8f0;">
                        <span class="fw-bold" style="font-size:1.05rem;">Total</span>
                        <span class="fw-bold" style="font-size:1.3rem;color:#6366f1;" id="totalDisplay">Rp 0</span>
                        <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
                    </div>

                    <!-- Payment Method -->
                    <div class="btn-group w-100 mb-3" role="group">
                        <input type="radio" class="btn-check" name="payment_method" id="payCash" value="Cash" checked onclick="togglePay('Cash')">
                        <label class="btn btn-outline-secondary btn-sm" for="payCash"><i class="fa-solid fa-money-bill-wave me-1"></i> Cash</label>
                        <input type="radio" class="btn-check" name="payment_method" id="payQris" value="QRIS" onclick="togglePay('QRIS')">
                        <label class="btn btn-outline-secondary btn-sm" for="payQris"><i class="fa-solid fa-qrcode me-1"></i> QRIS</label>
                    </div>

                    <div id="cashInputBox" class="mb-3">
                        <label class="form-label">Uang Diterima</label>
                        <input type="number" name="pay_amount" id="payAmountInput" class="form-control form-control-lg text-end fw-bold" placeholder="0" min="0" required>
                    </div>

                    <button type="button" class="btn btn-primary w-100 py-3" style="font-size:1rem;" id="checkoutBtn" onclick="processCheckout()">
                        <i class="fa-solid fa-check-circle me-2"></i>Bayar & Cetak Struk
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .product-card { transition: transform .2s, box-shadow .2s; border: 1px solid #f1f5f9; }
    .product-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,.08) !important; border-color: #c7d2fe; }
    .product-card:active { transform: scale(.97); }
    .cart-add-flash { animation: cartFlash .4s ease-out; }
    @keyframes cartFlash { 0%{background:#eef2ff;} 100%{background:transparent;} }
</style>
@endpush
@endsection

@push('scripts')
<script>
let cart = [];
let isProcessing = false; // Anti double-click guard

function fmt(n) {
    return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(n);
}

function filterProducts() {
    const q = document.getElementById('searchProduct').value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(el => {
        el.style.display = el.dataset.name.includes(q) ? '' : 'none';
    });
}

function addToCart(id, name, price, stock) {
    if (stock <= 0) return showToast('Stok habis!','danger');
    let item = cart.find(i => i.id === id);
    if (item) {
        if (item.qty >= stock) return showToast('Melebihi stok!','warning');
        item.qty++;
    } else {
        cart.push({id, name, price, qty: 1, stock});
    }
    renderCart();
}

function updateQty(id, delta) {
    let idx = cart.findIndex(i => i.id === id);
    if (idx < 0) return;
    let item = cart[idx];
    let nq = item.qty + delta;
    if (nq <= 0) cart.splice(idx, 1);
    else if (nq > item.stock) return showToast('Stok tidak cukup!','warning');
    else item.qty = nq;
    renderCart();
}

function clearCart() {
    if (cart.length === 0) return;
    cart = [];
    renderCart();
}

function togglePay(method) {
    let box = document.getElementById('cashInputBox');
    if (method === 'QRIS') {
        box.style.display = 'none';
        document.getElementById('payAmountInput').value = document.getElementById('totalAmountInput').value;
    } else {
        box.style.display = 'block';
        document.getElementById('payAmountInput').value = '';
    }
}

function renderCart() {
    let body = document.getElementById('cartItems');
    let hidden = document.getElementById('hiddenInputs');
    let sub = 0;
    body.innerHTML = '';
    hidden.innerHTML = '';

    if (!cart.length) {
        body.innerHTML = `<tr><td class="text-center py-5 text-muted">
            <i class="fa-solid fa-cart-shopping fa-2x mb-2 opacity-25"></i><br>
            <span style="font-size:.85rem;">Belum ada pesanan</span></td></tr>`;
    }

    cart.forEach((item, i) => {
        let s = item.price * item.qty;
        sub += s;
        body.innerHTML += `
        <tr class="cart-add-flash">
            <td class="px-4 py-3" style="max-width:140px;">
                <div class="fw-semibold text-truncate" style="font-size:.85rem;" title="${item.name}">${item.name}</div>
                <small class="text-muted">${fmt(item.price)}</small>
            </td>
            <td width="110">
                <div class="input-group input-group-sm">
                    <button class="btn btn-outline-secondary px-2" type="button" onclick="updateQty(${item.id},-1)">−</button>
                    <input type="text" class="form-control text-center fw-bold bg-white px-0" value="${item.qty}" readonly style="max-width:36px;">
                    <button class="btn btn-outline-secondary px-2" type="button" onclick="updateQty(${item.id},1)">+</button>
                </div>
            </td>
            <td class="text-end fw-bold pe-3" style="font-size:.85rem;">${fmt(s)}</td>
            <td width="36"><button class="btn btn-sm p-0 text-danger" onclick="updateQty(${item.id},-${item.qty})"><i class="fa-solid fa-xmark"></i></button></td>
        </tr>`;
        hidden.innerHTML += `
            <input type="hidden" name="cart[${i}][id]" value="${item.id}">
            <input type="hidden" name="cart[${i}][qty]" value="${item.qty}">
            <input type="hidden" name="cart[${i}][price]" value="${item.price}">
            <input type="hidden" name="cart[${i}][name]" value="${item.name}">`;
    });

    document.getElementById('subtotalDisplay').innerText = fmt(sub);
    document.getElementById('subtotalInput').value = sub;
    document.getElementById('cartCount').innerText = cart.reduce((a,b)=>a+b.qty,0);
    calcTotal();
}

function calcTotal() {
    let sub = parseInt(document.getElementById('subtotalInput').value) || 0;
    let disc = parseInt(document.getElementById('discountInputRaw').value) || 0;
    let taxP = parseFloat(document.getElementById('taxPercentage').value) || 0;
    if (disc > sub) { disc = sub; document.getElementById('discountInputRaw').value = disc; }
    let after = sub - disc;
    let tax = (after * taxP) / 100;
    document.getElementById('taxNominalInput').value = Math.round(tax);
    let total = Math.round(after + tax);
    document.getElementById('totalDisplay').innerText = fmt(total);
    document.getElementById('totalAmountInput').value = total;
    if (document.querySelector('input[name="payment_method"]:checked').value === 'QRIS') {
        document.getElementById('payAmountInput').value = total;
    }
}

function processCheckout() {
    if (isProcessing) return;
    if (!cart.length) return showToast('Keranjang kosong!','warning');
    let total = parseInt(document.getElementById('totalAmountInput').value);
    let pay = parseInt(document.getElementById('payAmountInput').value);
    let method = document.querySelector('input[name="payment_method"]:checked').value;
    if (isNaN(pay)) return showToast('Isi nominal pembayaran!','warning');
    if (method === 'Cash' && pay < total) return showToast('Uang kurang! Kurang ' + fmt(total - pay),'danger');

    // Anti double-click
    isProcessing = true;
    let btn = document.getElementById('checkoutBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

    document.getElementById('checkoutForm').submit();
}

function showToast(msg, type='info') {
    let c = document.getElementById('toastContainer');
    let t = document.createElement('div');
    t.className = `toast align-items-center text-bg-${type} border-0 show animate-slide`;
    t.setAttribute('role','alert');
    t.innerHTML = `<div class="d-flex"><div class="toast-body">${msg}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    c.appendChild(t);
    bootstrap.Toast.getOrCreateInstance(t, {delay:3000}).show();
    t.addEventListener('hidden.bs.toast', () => t.remove());
}

// Keyboard shortcut: Enter to pay
document.addEventListener('keydown', e => {
    if (e.key === 'Enter' && e.ctrlKey) { e.preventDefault(); processCheckout(); }
});

renderCart();
</script>
@endpush
