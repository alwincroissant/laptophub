<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\OrderStatusLog;
use App\Models\Product;
use App\Models\RestockTransaction;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $grossRevenue = (float) OrderItem::query()
            ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total')
            ->value('total');

        $restockExpense = (float) RestockTransaction::query()
            ->where('transaction_type', 'add')
            ->selectRaw('COALESCE(SUM(quantity_added * unit_cost), 0) as total')
            ->value('total');

        $restockRefund = (float) RestockTransaction::query()
            ->where('transaction_type', 'remove')
            ->selectRaw('COALESCE(SUM(ABS(quantity_added) * unit_cost), 0) as total')
            ->value('total');

        $restockImpact = $restockRefund - $restockExpense;
        $totalRevenue = $grossRevenue + $restockImpact;

        $activeUsers = User::where('is_active', true)->count();
        $activeProducts = Product::where('is_archived', false)->count();
        $lowStockCount = Product::where('is_archived', false)
            ->whereColumn('stock_qty', '<=', 'low_stock_threshold')
            ->count();

        $recentOrders = Order::with([
            'user:user_id,first_name,last_name',
            'status:status_id,status_name',
            'paymentMethod:payment_method_id,method_name',
            'items:order_item_id,order_id,quantity,unit_price',
        ])
            ->orderByDesc('placed_at')
            ->limit(8)
            ->get();

        $statusBreakdown = OrderStatus::query()
            ->leftJoin('orders', 'order_statuses.status_id', '=', 'orders.status_id')
            ->groupBy('order_statuses.status_id', 'order_statuses.status_name')
            ->orderBy('order_statuses.status_id')
            ->get([
                'order_statuses.status_id',
                'order_statuses.status_name',
                DB::raw('COUNT(orders.order_id) as total'),
            ]);

        $lowStockProducts = Product::query()
            ->leftJoin('categories', 'products.category_id', '=', 'categories.category_id')
            ->select([
                'products.product_id',
                'products.name',
                'products.stock_qty',
                'products.low_stock_threshold',
                'categories.name as category_name',
            ])
            ->where('products.is_archived', false)
            ->whereColumn('products.stock_qty', '<=', 'products.low_stock_threshold')
            ->orderBy('products.stock_qty')
            ->limit(6)
            ->get();

        $recentActivities = OrderStatusLog::with([
            'order:order_id',
            'status:status_id,status_name',
            'changedBy:user_id,first_name,last_name',
        ])
            ->orderByDesc('changed_at')
            ->limit(8)
            ->get();

        $topReviewedProducts = DB::table('reviews')
            ->join('products', 'reviews.product_id', '=', 'products.product_id')
            ->select([
                'products.product_id',
                'products.name as product_name',
                DB::raw('ROUND(AVG(reviews.rating), 1) as avg_rating'),
                DB::raw('COUNT(reviews.review_id) as total_reviews'),
            ])
            ->where('reviews.is_visible', true)
            ->groupBy('products.product_id', 'products.name')
            ->orderByDesc('avg_rating')
            ->orderByDesc('total_reviews')
            ->limit(6)
            ->get();

        $recentUsers = User::with('role:role_id,role_name')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $activeSuppliers = Supplier::query()
            ->where('is_active', true)
            ->withCount('products')
            ->orderBy('name')
            ->limit(6)
            ->get(['supplier_id', 'name', 'contact_name', 'is_active']);

        $recentStockChanges = RestockTransaction::query()
            ->with([
                'product:product_id,name',
                'supplier:supplier_id,name',
                'manager:user_id,first_name,last_name',
            ])
            ->orderByDesc('restocked_at')
            ->limit(8)
            ->get();

        return view('admin.dashboard', [
            'totalOrders' => $totalOrders,
            'grossRevenue' => $grossRevenue,
            'totalRevenue' => $totalRevenue,
            'restockImpact' => $restockImpact,
            'activeUsers' => $activeUsers,
            'activeProducts' => $activeProducts,
            'lowStockCount' => $lowStockCount,
            'recentOrders' => $recentOrders,
            'statusBreakdown' => $statusBreakdown,
            'lowStockProducts' => $lowStockProducts,
            'recentActivities' => $recentActivities,
            'topReviewedProducts' => $topReviewedProducts,
            'recentUsers' => $recentUsers,
            'activeSuppliers' => $activeSuppliers,
            'recentStockChanges' => $recentStockChanges,
        ]);
    }
}
