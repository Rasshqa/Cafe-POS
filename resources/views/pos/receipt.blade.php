<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px;
            width: 300px;
            margin: 0 auto;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .fw-bold { font-weight: bold; }
        hr { border-top: 1px dashed #000; border-bottom: none; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 3px 0; }
        .title { font-size: 18px; margin-bottom: 5px; font-weight: bold; }
        .subtitle { font-size: 12px; margin-bottom: 15px; }
        
        @media print {
            .no-print { display: none; }
            body { width: 100%; padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="text-center">
        <div class="title">CAFE POS PRO</div>
        <div class="subtitle">
            Jl. Teknologi Baru No 99<br>
            Telp: 0812-3456-7890
        </div>
    </div>
    
    <hr>
    
    <table>
        <tr>
            <td class="text-left">No. TRX</td>
            <td class="text-right">#{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td class="text-left">Kasir</td>
            <td class="text-right">Admin</td>
        </tr>
        <tr>
            <td class="text-left">Tanggal</td>
            <td class="text-right">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <hr>

    <table>
        @foreach($transaction->details as $item)
        <tr>
            <td colspan="3" class="fw-bold pb-1">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
        </tr>
        <tr>
            <td class="text-left">{{ $item->qty }} x {{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="text-right">=</td>
            <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <hr>

    <table>
        @php
            $subtotalBase = $transaction->details->sum('subtotal');
        @endphp
        <tr>
            <td class="text-left">Subtotal</td>
            <td class="text-right">Rp {{ number_format($subtotalBase, 0, ',', '.') }}</td>
        </tr>
        @if($transaction->discount > 0)
        <tr>
            <td class="text-left">Diskon (-)</td>
            <td class="text-right">Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($transaction->tax > 0)
        <tr>
            <td class="text-left">Pajak (+)</td>
            <td class="text-right">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td class="text-left fw-bold" style="font-size:16px; padding-top: 5px;">Total</td>
            <td class="text-right fw-bold" style="font-size:16px; padding-top: 5px;">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <hr>

    <table>
        <tr>
            <td class="text-left">Metode</td>
            <td class="text-right">{{ strtoupper($transaction->payment_method) }}</td>
        </tr>
        <tr>
            <td class="text-left">Tunai / Dibayar</td>
            <td class="text-right">Rp {{ number_format($transaction->pay_amount, 0, ',', '.') }}</td>
        </tr>
        @if($transaction->payment_method == 'Cash')
        <tr>
            <td class="text-left">Kembali</td>
            <td class="text-right">Rp {{ number_format($transaction->return_amount, 0, ',', '.') }}</td>
        </tr>
        @endif
    </table>

    <div class="text-center" style="margin-top: 20px;">
        <p>*** TERIMA KASIH ***</p>
        <p style="font-size: 10px;">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>

    <!-- Tombol Tutup Otomatis (hanya terlihat jika dibuka pop-up window) -->
    <div class="text-center no-print" style="margin-top: 30px;">
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; border-radius: 5px; border: 1px solid #ccc; background: #eee;">Tutup Jendela Ini</button>
    </div>

</body>
</html>
