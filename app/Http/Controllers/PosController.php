<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function dashboard()
    {
        $todayTransactions = Transaction::whereDate('created_at', today())->get();
        $totalSales = $todayTransactions->sum('total_amount');
        return view('dashboard', compact('todayTransactions', 'totalSales'));
    }

    public function index()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('pos.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'pay_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);

        if ($request->pay_amount < $request->total_amount) {
            return back()->with('error', 'Uang tidak cukup!');
        }

        DB::transaction(function () use ($request) {
            $return_amount = $request->pay_amount - $request->total_amount;

            $transaction = Transaction::create([
                'total_amount' => $request->total_amount,
                'pay_amount' => $request->pay_amount,
                'return_amount' => $return_amount,
            ]);

            foreach ($request->cart as $item) {
                // Konversi dari JSON/Array format Frontend anti
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['qty'] * $item['price'],
                ]);

                // Kurangi stok
                $product = Product::find($item['id']);
                $product->stock -= $item['qty'];
                $product->save();
            }
        });

        return redirect()->route('pos.index')->with('success', 'Transaksi berhasil disimpan!');
    }

    public function history()
    {
        $transactions = Transaction::latest()->get();
        return view('pos.history', compact('transactions'));
    }
}
