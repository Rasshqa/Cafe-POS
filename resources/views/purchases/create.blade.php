@extends('layouts.master', ['title' => 'Buat Pembelian Baru'])

@section('content')
<div class="mb-4 animate-in">
    <a href="{{ route('purchases.index') }}" class="btn btn-ghost btn-sm mb-3"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
    <h5 class="fw-bold mb-1">Form Pembelian Stok</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">Tambah stok barang dari supplier</p>
</div>

<form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
    @csrf
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card animate-in" style="animation-delay:.1s;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Info Pembelian</h6>
                    <div class="mb-3">
                        <label class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">Pilih supplier</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pembelian <span class="text-danger">*</span></label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Opsional..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card animate-in" style="animation-delay:.2s;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Item Pembelian</h6>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addRow()"><i class="fa-solid fa-plus me-1"></i> Tambah Item</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="itemsTable">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th width="100">Qty</th>
                                    <th width="150">Harga Beli</th>
                                    <th width="130" class="text-end">Subtotal</th>
                                    <th width="40"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <!-- JS rendered -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Grand Total:</td>
                                    <td class="text-end fw-bold" style="color:#6366f1;" id="grandTotal">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 mt-3 animate-in" style="animation-delay:.3s;" id="saveBtn">
                <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Pembelian & Update Stok
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
const products = @json($products);
let rowIndex = 0;

function addRow() {
    let tbody = document.getElementById('itemsBody');
    let options = products.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
    tbody.insertAdjacentHTML('beforeend', `
        <tr id="row${rowIndex}">
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select form-select-sm" required>
                    <option value="">Pilih</option>${options}
                </select>
            </td>
            <td><input type="number" name="items[${rowIndex}][qty]" class="form-control form-control-sm" min="1" value="1" required onchange="calcRow(${rowIndex})" onkeyup="calcRow(${rowIndex})"></td>
            <td><input type="number" name="items[${rowIndex}][buy_price]" class="form-control form-control-sm" min="0" value="0" required onchange="calcRow(${rowIndex})" onkeyup="calcRow(${rowIndex})"></td>
            <td class="text-end fw-semibold" id="sub${rowIndex}" style="font-size:.85rem;">Rp 0</td>
            <td><button type="button" class="btn btn-sm p-0 text-danger" onclick="removeRow(${rowIndex})"><i class="fa-solid fa-xmark"></i></button></td>
        </tr>
    `);
    rowIndex++;
}

function removeRow(i) { document.getElementById('row'+i)?.remove(); calcGrand(); }

function calcRow(i) {
    let row = document.getElementById('row'+i);
    if (!row) return;
    let qty = parseInt(row.querySelector('[name$="[qty]"]').value) || 0;
    let price = parseFloat(row.querySelector('[name$="[buy_price]"]').value) || 0;
    document.getElementById('sub'+i).innerText = fmt(qty * price);
    calcGrand();
}

function calcGrand() {
    let total = 0;
    document.querySelectorAll('#itemsBody tr').forEach(row => {
        let qty = parseInt(row.querySelector('[name$="[qty]"]')?.value) || 0;
        let price = parseFloat(row.querySelector('[name$="[buy_price]"]')?.value) || 0;
        total += qty * price;
    });
    document.getElementById('grandTotal').innerText = fmt(total);
}

function fmt(n) { return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(n); }

addRow(); // Start with 1 row
</script>
@endpush
