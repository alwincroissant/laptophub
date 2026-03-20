<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Charts\YearlySalesChart;
use App\Charts\SalesBarChart;
use App\Charts\ProductPieChart;

class ReportController extends Controller
{
    private $bgcolor;

    public function __construct()
    {
        $this->bgcolor = collect([
            '#1a3a5c', '#c0392b', '#d29624', '#2c3e50', 
            '#e67e22', '#27ae60', '#95a5a6', '#7f8c8d'
        ]);
    }

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
    /**
     * MP7 Analytics Charts
     */
    public function charts(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // 1. Yearly Sales (Specific Year grouped by month)
        $selectedYear = $request->input('year', date('Y'));
        
        
        $yearlyOrders = Order::with('items')
            ->whereHas('status', function ($q) {
                $q->where('status_name', 'Delivered');
            })
            ->whereYear('placed_at', $selectedYear)
            ->get();

        $yearlySalesRaw = array_fill(1, 12, 0); // Initialize array for 12 months (Jan-Dec)
        foreach ($yearlyOrders as $order) {
            $month = (int) \Carbon\Carbon::parse($order->placed_at)->format('n');
            $monthlyRevenue = $order->items->sum(function ($item) {
                return $item->unit_price * $item->quantity;
            });
            $yearlySalesRaw[$month] += $monthlyRevenue;
        }
        
        $yearlySalesLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $yearlySalesData = array_values($yearlySalesRaw);

        // 2 & 3. Sales Bar Chart and Product Pie Chart with Date Range
        $rangeOrders = Order::with('items.product')
            ->whereHas('status', function ($q) {
                $q->where('status_name', 'Delivered');
            })
            ->whereDate('placed_at', '>=', $startDate)
            ->whereDate('placed_at', '<=', $endDate)
            ->get();

        $dateRangeRaw = [];
        $productSalesRaw = [];
        $totalRangeRevenue = 0;

        foreach ($rangeOrders as $order) {
            $dateStr = \Carbon\Carbon::parse($order->placed_at)->format('M d, Y');
            if (!isset($dateRangeRaw[$dateStr])) {
                $dateRangeRaw[$dateStr] = 0;
            }
            
            $dailyRevenue = 0;
            foreach ($order->items as $item) {
                $revenue = $item->unit_price * $item->quantity;
                $dailyRevenue += $revenue;
                
                $productName = $item->product ? $item->product->name : 'Deleted Product';
                if (!isset($productSalesRaw[$productName])) {
                    $productSalesRaw[$productName] = 0;
                }
                $productSalesRaw[$productName] += $revenue;
            }
            
            $dateRangeRaw[$dateStr] += $dailyRevenue;
            $totalRangeRevenue += $dailyRevenue;
        }
        
        // Ensure dates are sorted chronologically if parsing M d, Y... wait, Y-m-d is better for sorting
        // Let's re-sort based on carbon
        uksort($dateRangeRaw, function($a, $b) {
            return strtotime($a) - strtotime($b);
        });
        
        $barChartLabels = array_keys($dateRangeRaw);
        $barChartData = array_values($dateRangeRaw);

        // Format Pie Chart Data (Top 6 + Other)
        arsort($productSalesRaw);
        $topProducts = array_slice($productSalesRaw, 0, 6, true);
        $otherRevenue = array_sum(array_slice($productSalesRaw, 6));
        
        if ($otherRevenue > 0) {
            $topProducts['Other Products'] = $otherRevenue;
        }

        $pieChartLabels = array_keys($topProducts);
        $pieChartData = array_values($topProducts);

        // Helper for percentage
        $pieChartPercentages = [];
        foreach($pieChartData as $val) {
            $pieChartPercentages[] = $totalRangeRevenue > 0 ? round(($val / $totalRangeRevenue) * 100, 1) : 0;
        }

        $yearlyChart = new YearlySalesChart;
        $yearlyChart->labels($yearlySalesLabels);
        $dataset = $yearlyChart->dataset('Revenue per Year', 'line', $yearlySalesData);
        $dataset->color('#1a3a5c');
        $dataset->backgroundColor('rgba(26, 58, 92, 0.1)');
        $yearlyChart->options([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'legend' => ['display' => false],
            'tooltips' => ['enabled' => true],
        ]);

        $barChart = new SalesBarChart;
        $barChart->labels($barChartLabels);
        $dataset = $barChart->dataset('Revenue (₱)', 'bar', $barChartData);
        $dataset->backgroundColor('#c0392b');
        $barChart->options([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'legend' => ['display' => false],
            'tooltips' => ['enabled' => true],
            'scales' => [
                'yAxes' => [['display' => true, 'ticks' => ['beginAtZero' => true]]],
                'xAxes' => [['display' => true]]
            ],
        ]);

        $pieChart = new ProductPieChart;
        $pieLabelsWithPerc = [];
        foreach($pieChartLabels as $idx => $label) {
            $pieLabelsWithPerc[] = $label . ' (' . $pieChartPercentages[$idx] . '%)';
        }
        $pieChart->labels($pieLabelsWithPerc);
        $dataset = $pieChart->dataset('Product Sales', 'pie', $pieChartData);
        $dataset->backgroundColor($this->bgcolor);
        $pieChart->options([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'legend' => ['display' => true, 'position' => 'right'],
            'tooltips' => ['enabled' => true],
        ]);

        return view('admin.reports.charts', compact(
            'startDate', 'endDate', 'selectedYear',
            'yearlyChart', 'barChart', 'pieChart', 'totalRangeRevenue'
        ));
    }
}
