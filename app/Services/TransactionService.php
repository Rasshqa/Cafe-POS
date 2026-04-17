<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * Process checkout with server-side total recalculation.
     * NEVER trust frontend-submitted totals.
     */
    public function processCheckout(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            // 1. Recalculate subtotal dari database (TIDAK percaya frontend)
            $serverSubtotal = 0;
            $cartItems = [];

            foreach ($data['cart'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['id']);
                
                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok \"{$product->name}\" tidak cukup. Sisa: {$product->stock}");
                }

                $serverSubtotal += $product->price * $item['qty'];
                $cartItems[] = [
                    'product' => $product,
                    'qty' => (int)$item['qty'],
                    'price' => $product->price, // Gunakan harga dari DB, bukan frontend
                ];
            }

            // 2. Recalculate discount & tax
            $discount = min(max(0, (float)$data['discount']), $serverSubtotal);
            $afterDiscount = $serverSubtotal - $discount;
            
            $taxPercent = isset($data['tax_percent']) ? max(0, (float)$data['tax_percent']) : 0;
            // Fallback: jika tax_percent tidak dikirim, hitung dari nominal tax yang dikirim
            if ($taxPercent == 0 && isset($data['tax']) && $afterDiscount > 0) {
                $taxPercent = ($data['tax'] / $afterDiscount) * 100;
            }
            $tax = round($afterDiscount * $taxPercent / 100);
            
            $totalAmount = $afterDiscount + $tax;

            // 3. Validasi pembayaran
            $payAmount = (float)$data['pay_amount'];
            $paymentMethod = $data['payment_method'];
            
            if ($paymentMethod === 'QRIS') {
                $payAmount = $totalAmount; // QRIS selalu uang pas
            }

            if ($payAmount < $totalAmount) {
                throw new \Exception("Pembayaran kurang. Total: {$totalAmount}, Dibayar: {$payAmount}");
            }

            $returnAmount = $paymentMethod === 'QRIS' ? 0 : ($payAmount - $totalAmount);

            // 4. Simpan transaksi
            $transaction = Transaction::create([
                'total_amount' => $totalAmount,
                'discount' => $discount,
                'tax' => $tax,
                'pay_amount' => $payAmount,
                'return_amount' => $returnAmount,
                'payment_method' => $paymentMethod,
            ]);

            // 5. Simpan detail dan kurangi stok
            foreach ($cartItems as $cartItem) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $cartItem['product']->id,
                    'qty' => $cartItem['qty'],
                    'price' => $cartItem['price'],
                    'subtotal' => $cartItem['price'] * $cartItem['qty'],
                ]);

                $cartItem['product']->stock -= $cartItem['qty'];
                $cartItem['product']->save();
            }

            return $transaction;
        });
    }
}
