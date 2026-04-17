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

    public function store(Request $request, \App\Services\TransactionService $transactionService)
    {
        $request->validate([
            'cart' => 'required|array',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'pay_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:Cash,QRIS',
        ]);

        if ($request->pay_amount < $request->total_amount && $request->payment_method == 'Cash') {
            return back()->with('error', 'Uang tidak cukup!');
        }

        try {
            // Logic transaksi dipindahkan ke TransactionService (Struktur lebih clean & scalable)
            $transaction = $transactionService->processCheckout($request->all());
            
            return redirect()->route('pos.index')
                             ->with('success', 'Transaksi #'.$transaction->id.' berhasil disimpan!')
                             ->with('print_id', $transaction->id);

        } catch (\Exception $e) {
            return back()->with('error', 'Transaksi gagal: ' . $e->getMessage());
        }
    }

    public function history()
    {
        $transactions = Transaction::latest()->get();
        return view('pos.history', compact('transactions'));
    }

    public function receipt($id)
    {
        $transaction = Transaction::with('details.product')->findOrFail($id);
        return view('pos.receipt', compact('transaction'));
    }
}
