<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])->get();
        
        $totalRevenue = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();

        // Chart Data (Group by date)
        $chartData = Transaction::select(
                DB::raw('DATE(created_at) as date'), 
                DB::raw('SUM(total_amount) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $chartLabels = $chartData->pluck('date');
        $chartValues = $chartData->pluck('total');

        // Top Selling Products
        $topProducts = TransactionDetail::select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_qty', 'DESC')
            ->take(5)
            ->get();

        return view('reports.index', compact(
            'totalRevenue', 'totalTransactions', 'startDate', 'endDate',
            'chartLabels', 'chartValues', 'topProducts'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $transactions = Transaction::with('user')->whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at')->get();

        $filename = "Laporan_Penjualan_" . $startDate->format('d-M-Y') . "_sampai_" . $endDate->format('d-M-Y') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID Trx', 'Tanggal', 'Kasir', 'Total (Rp)', 'Diskon (Rp)', 'Pajak (Rp)', 'Dibayar (Rp)', 'Kembali (Rp)', 'Metode'];

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            // Menambahkan BOM untuk UTF-8 di Excel
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns, ';'); // Gunakan semicolon untuk standar Excel lokalisasi ID

            foreach ($transactions as $t) {
                fputcsv($file, [
                    '#' . str_pad($t->id, 5, '0', STR_PAD_LEFT),
                    $t->created_at->format('d/m/Y H:i:s'),
                    $t->user->name ?? 'Admin',
                    $t->total_amount,
                    $t->discount,
                    $t->tax,
                    $t->pay_amount,
                    $t->return_amount,
                    $t->payment_method
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
