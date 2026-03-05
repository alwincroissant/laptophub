<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

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

        $subtotal = $order->items->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });

        $shipping = 200;
        $tax = $subtotal * 0.12;
        $total = $subtotal + $shipping + $tax;

        return view('customer.orders.show', compact('order', 'subtotal', 'shipping', 'tax', 'total'));
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
