<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * Process the core POS checkout logic including stock reduction.
     */
    public function processCheckout(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Kalkulasi uang kembali khusus Cash
            $return_amount = $data['payment_method'] === 'QRIS' ? 0 : ($data['pay_amount'] - $data['total_amount']);

            // 1. Simpan header transaksi
            $transaction = Transaction::create([
                'total_amount' => $data['total_amount'],
                'discount' => $data['discount'],
                'tax' => $data['tax'],
                'pay_amount' => $data['pay_amount'],
                'return_amount' => $return_amount,
                'payment_method' => $data['payment_method'],
            ]);

            // 2. Loop keranjang belanja
            foreach ($data['cart'] as $item) {
                // Simpan detail (history item dibeli)
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['qty'] * $item['price'],
                ]);

                // 3. Kurangi stok berbasis Pessimistic Locking demi Data Integrity
                $product = Product::lockForUpdate()->find($item['id']);
                if ($product && $product->stock >= $item['qty']) {
                    $product->stock -= $item['qty'];
                    $product->save();
                } else {
                    // Batalkan seluruh transaksi jika ada anomali (stok kurang saat diklik bersamaan oleh kasir lain)
                    throw new \Exception("Stok produk {$item['name']} tidak mencukupi saat proses pembayaran.");
                }
            }

            return $transaction;
        });
    }
}
