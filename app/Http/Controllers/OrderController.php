<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\OrderStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $query = Order::where('user_id', $user->user_id)
            ->with(['items', 'status', 'paymentMethod'])
            ->orderBy('placed_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->whereHas('status', function ($q) use ($request) {
                $q->where('status_name', 'like', ucfirst($request->status));
            });
        }

        $orders = $query->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Display a specific order.
     */
    public function show(Order $order)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Verify order belongs to authenticated user
        if ($order->user_id !== $user->user_id) {
            abort(403, 'Unauthorized');
        }

        $order->load(['items.product', 'status', 'paymentMethod']);

        $subtotal = (float) $order->items()
            ->selectRaw('COALESCE(SUM(unit_price * quantity), 0) as subtotal')
            ->value('subtotal');

        $shipping = 200;
        $total = $subtotal + $shipping;

        return view('customer.orders.show', compact('order', 'subtotal', 'shipping', 'total'));
    }

    public function cancel(Order $order)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($order->user_id !== $user->user_id) {
            abort(403, 'Unauthorized');
        }

        $order->loadMissing('status');

        $currentStatus = strtolower((string) ($order->status->status_name ?? ''));
        if (!in_array($currentStatus, ['pending', 'processing'], true)) {
            return redirect()
                ->route('customer.orders.show', $order->order_id)
                ->with('error', 'This order can no longer be cancelled.');
        }

        $cancelledStatus = OrderStatus::whereRaw('LOWER(status_name) = ?', ['cancelled'])->first();
        if (!$cancelledStatus) {
            return redirect()
                ->route('customer.orders.show', $order->order_id)
                ->with('error', 'Cancel status is not configured. Please contact support.');
        }

        DB::transaction(function () use ($order, $cancelledStatus, $user) {
            $order->status_id = $cancelledStatus->status_id;
            $order->updated_at = now();
            $order->save();

            OrderStatusLog::create([
                'order_id' => $order->order_id,
                'status_id' => $cancelledStatus->status_id,
                'changed_by' => $user->user_id,
                'changed_at' => now(),
                'note' => 'Cancelled by customer',
            ]);
        });

        return redirect()
            ->route('customer.orders.show', $order->order_id)
            ->with('success', 'Order cancelled successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
