<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $statusId = (int) $request->input('status_id', 0);

        $statuses = OrderStatus::orderBy('status_id')->get(['status_id', 'status_name']);

        $metrics = [
            'total' => Order::count(),
            'pending' => Order::where('status_id', $this->statusIdByName($statuses, 'Pending'))->count(),
            'processing' => Order::where('status_id', $this->statusIdByName($statuses, 'Processing'))->count(),
            'shipped' => Order::where('status_id', $this->statusIdByName($statuses, 'Shipped'))->count(),
            'delivered' => Order::where('status_id', $this->statusIdByName($statuses, 'Delivered'))->count(),
            'cancelled' => Order::where('status_id', $this->statusIdByName($statuses, 'Cancelled'))->count(),
        ];

        $ordersQuery = Order::query()
            ->leftJoin('users', 'orders.user_id', '=', 'users.user_id')
            ->leftJoin('order_statuses', 'orders.status_id', '=', 'order_statuses.status_id')
            ->leftJoin('payment_methods', 'orders.payment_method_id', '=', 'payment_methods.payment_method_id')
            ->leftJoin('order_items', 'orders.order_id', '=', 'order_items.order_id')
            ->select([
                'orders.order_id',
                'orders.status_id',
                'orders.placed_at',
                'orders.updated_at',
                'users.full_name as customer_name',
                'users.email as customer_email',
                'order_statuses.status_name',
                'payment_methods.method_name as payment_method',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as item_count'),
                DB::raw('COALESCE(SUM(order_items.quantity * order_items.unit_price), 0) as subtotal'),
            ])
            ->groupBy([
                'orders.order_id',
                'orders.status_id',
                'orders.placed_at',
                'orders.updated_at',
                'users.full_name',
                'users.email',
                'order_statuses.status_name',
                'payment_methods.method_name',
            ]);

        if ($statusId > 0) {
            $ordersQuery->where('orders.status_id', $statusId);
        }

        if ($search !== '') {
            $ordersQuery->where(function ($query) use ($search) {
                $query->where('users.full_name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('orders.order_id', 'like', "%{$search}%");
            });
        }

        $orders = $ordersQuery
            ->orderByDesc('orders.placed_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.order.index', [
            'orders' => $orders,
            'statuses' => $statuses,
            'statusId' => $statusId,
            'search' => $search,
            'metrics' => $metrics,
        ]);
    }

    public function show(int $orderId)
    {
        $order = Order::with(['user', 'status', 'paymentMethod', 'items.product'])
            ->findOrFail($orderId);

        $statuses = OrderStatus::orderBy('status_id')->get(['status_id', 'status_name']);

        $statusLogs = OrderStatusLog::where('order_id', $order->order_id)
            ->with(['status', 'changedBy'])
            ->orderByDesc('changed_at')
            ->get();

        $subtotal = 0.0;
        foreach ($order->items as $item) {
            $subtotal += (float) $item->unit_price * (int) $item->quantity;
        }

        $shipping = $subtotal > 0 ? 200 : 0;
        $total = $subtotal + $shipping;

        return view('admin.order.show', [
            'order' => $order,
            'statuses' => $statuses,
            'statusLogs' => $statusLogs,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
        ]);
    }

    public function updateStatus(Request $request, int $orderId)
    {
        $data = $request->validate([
            'status_id' => ['required', 'integer', 'exists:order_statuses,status_id'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $order = Order::findOrFail($orderId);

        if ((int) $order->status_id === (int) $data['status_id']) {
            return redirect()
                ->back()
                ->with('success', 'Order status is already set to the selected value.');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();

        DB::transaction(function () use ($order, $data, $user) {
            $order->status_id = (int) $data['status_id'];
            $order->updated_at = now();
            $order->save();

            OrderStatusLog::create([
                'order_id' => $order->order_id,
                'status_id' => (int) $data['status_id'],
                'changed_by' => $user->user_id,
                'changed_at' => now(),
                'note' => $data['note'] ?? null,
            ]);
        });

        return redirect()
            ->route('admin.order.show', $order->order_id)
            ->with('success', 'Order status updated successfully.');
    }

    private function statusIdByName($statuses, string $name): int
    {
        $match = $statuses->first(function ($status) use ($name) {
            return strcasecmp((string) $status->status_name, $name) === 0;
        });

        return $match ? (int) $match->status_id : 0;
    }
}
