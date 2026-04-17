<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('supplier')->latest()->get();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.buy_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $totalAmount = 0;

                // Hitung total
                foreach ($request->items as $item) {
                    $totalAmount += $item['qty'] * $item['buy_price'];
                }

                $purchase = Purchase::create([
                    'supplier_id' => $request->supplier_id,
                    'total_amount' => $totalAmount,
                    'purchase_date' => $request->purchase_date,
                    'notes' => $request->notes,
                ]);

                foreach ($request->items as $item) {
                    PurchaseDetail::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                        'buy_price' => $item['buy_price'],
                        'subtotal' => $item['qty'] * $item['buy_price'],
                    ]);

                    // Tambah stok produk
                    $product = Product::find($item['product_id']);
                    $product->stock += $item['qty'];
                    $product->save();
                }
            });

            return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil dicatat! Stok produk telah diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('supplier', 'details.product');
        return view('purchases.show', compact('purchase'));
    }
}
