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
}
