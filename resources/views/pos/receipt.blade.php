<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px; color: #000; background: #fff;
            width: 300px; margin: 0 auto; padding: 16px;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        hr { border: none; border-top: 1px dashed #000; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 2px 0; font-size: 12px; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 2px; }
        .logo-img { max-height: 50px; max-width: 120px; margin-bottom: 8px; }
        @media print { .no-print { display: none; } body { width: 100%; padding: 0; } }
    </style>
</head>
<body onload="window.print()">

<div class="center">
    @if($storeLogo)
        <img src="{{ asset('storage/'.$storeLogo) }}" class="logo-img" alt="Logo"><br>
    @endif
    <div class="title">{{ $storeName }}</div>
    @if($storeAddress)<div>{{ $storeAddress }}</div>@endif
    @if($storePhone)<div>Telp: {{ $storePhone }}</div>@endif
</div>

<hr>

<table>
    <tr><td>No. TRX</td><td class="right">#{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</td></tr>
    <tr><td>Kasir</td><td class="right">{{ $transaction->user->name ?? 'Admin' }}</td></tr>
    <tr><td>Tanggal</td><td class="right">{{ $transaction->created_at->format('d/m/Y H:i') }}</td></tr>
</table>

<hr>

<table>
    @foreach($transaction->details as $item)
    <tr><td colspan="3" class="bold" style="padding-top:4px;">{{ $item->product->name ?? 'Dihapus' }}</td></tr>
    <tr>
        <td>{{ $item->qty }} x {{ number_format($item->price, 0, ',', '.') }}</td>
        <td></td>
        <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
    </tr>
    @endforeach
</table>

<hr>

<table>
    @php $subtotalBase = $transaction->details->sum('subtotal'); @endphp
    <tr><td>Subtotal</td><td class="right">Rp {{ number_format($subtotalBase, 0, ',', '.') }}</td></tr>
    @if($transaction->discount > 0)
    <tr><td>Diskon</td><td class="right">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td></tr>
    @endif
    @if($transaction->tax > 0)
    <tr><td>Pajak</td><td class="right">+ Rp {{ number_format($transaction->tax, 0, ',', '.') }}</td></tr>
    @endif
    <tr><td class="bold" style="font-size:14px;padding-top:4px;">TOTAL</td><td class="right bold" style="font-size:14px;padding-top:4px;">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td></tr>
</table>

<hr>

<table>
    <tr><td>Metode</td><td class="right">{{ strtoupper($transaction->payment_method) }}</td></tr>
    <tr><td>Dibayar</td><td class="right">Rp {{ number_format($transaction->pay_amount, 0, ',', '.') }}</td></tr>
    @if($transaction->payment_method == 'Cash')
    <tr><td>Kembali</td><td class="right">Rp {{ number_format($transaction->return_amount, 0, ',', '.') }}</td></tr>
    @endif
</table>

<div class="center" style="margin-top:16px;">
    <p>*** TERIMA KASIH ***</p>
    <p style="font-size:10px;margin-top:4px;">Barang yang sudah dibeli tidak dapat ditukar kembali.</p>
</div>

<div class="center no-print" style="margin-top:24px;">
    <button onclick="window.print()" style="padding:8px 20px;cursor:pointer;border-radius:6px;border:1px solid #ccc;background:#f8f8f8;margin-right:8px;">🖨️ Print</button>
    <button onclick="window.close()" style="padding:8px 20px;cursor:pointer;border-radius:6px;border:1px solid #ccc;background:#f8f8f8;">✕ Tutup</button>
</div>

</body>
</html>
