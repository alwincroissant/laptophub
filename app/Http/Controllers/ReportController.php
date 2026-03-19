<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * FR1.9.1 Sales Report
     */
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Capture only DELIVERED orders which represent finalized sales natively
        $query = Order::with(['items', 'user'])
            ->whereHas('status', function($q){
                $q->where('status_name', 'Delivered');
            });

        if ($startDate) {
            $query->whereDate('placed_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('placed_at', '<=', $endDate);
        }

        $orders = $query->orderBy('placed_at', 'desc')->get();

        $totalRevenue = 0;
        foreach($orders as $order) {
            foreach($order->items as $item) {
                $totalRevenue += ($item->unit_price * $item->quantity);
            }
        }

        return view('admin.reports.sales', compact('orders', 'totalRevenue', 'startDate', 'endDate'));
    }

    /**
     * FR1.9.2 Inventory Report
     */
    public function inventory()
    {
        // Snapshot of all products physically mapped directly to their category.
        $products = Product::with('category')->orderBy('stock_qty', 'asc')->get();
        
        $totalStockValue = 0;
        foreach($products as $product) {
            $totalStockValue += ($product->price * $product->stock_qty);
        }

        return view('admin.reports.inventory', compact('products', 'totalStockValue'));
    }

    /**
     * FR1.9.4 Order Summary Report
     */
    public function orders(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Compute strict grouped aggregation natively using DB facade without Javascript dependencies
        $summaryQuery = DB::table('orders')
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.status_id');
            
        if ($startDate) {
            $summaryQuery->whereDate('orders.placed_at', '>=', $startDate);
        }
        if ($endDate) {
            $summaryQuery->whereDate('orders.placed_at', '<=', $endDate);
        }

        $summary = $summaryQuery->select('order_statuses.status_name', DB::raw('count(*) as total_orders'))
            ->groupBy('order_statuses.status_name')
            ->get();
            
        // Fetch exhaustive order array mapped to UI payload
        $allOrdersQuery = Order::with(['user', 'status', 'items']);
        
        if ($startDate) {
            $allOrdersQuery->whereDate('placed_at', '>=', $startDate);
        }
        if ($endDate) {
            $allOrdersQuery->whereDate('placed_at', '<=', $endDate);
        }

        $allOrders = $allOrdersQuery->orderBy('placed_at', 'desc')->paginate(50)->appends($request->all());
            
        return view('admin.reports.orders', compact('summary', 'allOrders', 'startDate', 'endDate'));
    }

    /**
     * FR1.9.5 Top Products Report
     */
    public function topProducts(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $categoryId = $request->input('category_id');

        $query = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.status_id')
            ->join('products', 'order_items.product_id', '=', 'products.product_id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.category_id')
            ->whereIn('order_statuses.status_name', ['Shipped', 'Delivered']);

        if ($startDate) {
            $query->whereDate('orders.placed_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('orders.placed_at', '<=', $endDate);
        }
        if ($categoryId) {
            $query->where('products.category_id', $categoryId);
        }

        $topProducts = $query->select(
                'products.name as product_name',
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_revenue')
            )
            ->groupBy('products.product_id', 'products.name', 'categories.name')
            ->orderByDesc('total_sold')
            ->limit(20)
            ->get();

        $categories = \App\Models\Category::orderBy('name', 'asc')->get();

        return view('admin.reports.top_products', compact('topProducts', 'startDate', 'endDate', 'categoryId', 'categories'));
    }
}
