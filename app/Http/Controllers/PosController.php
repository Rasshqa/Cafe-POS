<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Setting;
use Illuminate\Http\Request;

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
        $products = Product::with('category')->where('stock', '>', 0)->get();
        $store = \App\Helpers\StoreHelper::current();
        $defaultTax = $store ? $store->default_tax : 0;
        return view('pos.index', compact('products', 'defaultTax'));
    }

    public function store(Request $request, \App\Services\TransactionService $transactionService)
    {
        $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.qty' => 'required|integer|min:1',
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
            $transaction = $transactionService->processCheckout($request->all());
            
            return redirect()->route('pos.index')
                             ->with('success', 'Transaksi #'.$transaction->id.' berhasil!')
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
        
        $store = \App\Helpers\StoreHelper::current();
        
        $storeName = $store->name ?? 'Kasir Pro';
        $storeAddress = $store->address ?? '';
        $storePhone = $store->phone ?? '';
        $storeLogo = $store->logo ?? '';
        
        return view('pos.receipt', compact('transaction', 'storeName', 'storeAddress', 'storePhone', 'storeLogo'));
    }
}
