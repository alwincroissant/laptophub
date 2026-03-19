<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RestockTransaction;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseReportController extends Controller
{
    public function expenses(Request $request)
    {
        $products = Product::orderBy('name')->get(['product_id', 'name']);
        $suppliers = Supplier::orderBy('name')->get(['supplier_id', 'name']);

        $baseQuery = RestockTransaction::query();

        if ($request->filled('start_date')) {
            $baseQuery->whereDate('restocked_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $baseQuery->whereDate('restocked_at', '<=', $request->end_date);
        }
        if ($request->filled('product_id')) {
            $baseQuery->where('restock_transactions.product_id', $request->product_id);
        }
        if ($request->filled('supplier_id')) {
            $baseQuery->where('restock_transactions.supplier_id', $request->supplier_id);
        }

        $totalExpenses = (clone $baseQuery)
            ->select(DB::raw('SUM(quantity_added * unit_cost) as total'))
            ->value('total') ?? 0;

        $expensesPerProduct = (clone $baseQuery)
            ->join('products', 'restock_transactions.product_id', '=', 'products.product_id')
            ->select('products.name as product_name', DB::raw('SUM(restock_transactions.quantity_added * restock_transactions.unit_cost) as total_cost'))
            ->groupBy('products.product_id', 'products.name')
            ->orderByDesc('total_cost')
            ->limit(10)
            ->get();

        return view('admin.reports.expenses', compact('products', 'suppliers', 'totalExpenses', 'expensesPerProduct'));
    }
}
