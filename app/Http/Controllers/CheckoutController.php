<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Show the checkout form.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->user_id)->first();

        if (!$cart) {
            return redirect()->route('customer.shop.index')->with('error', 'Your cart is empty');
        }

        $selectedCartItemIds = collect($request->input('selected_cart_item_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        $cartItemsQuery = CartItem::where('cart_id', $cart->cart_id)->with('product');

        if ($selectedCartItemIds->isNotEmpty()) {
            $cartItemsQuery->whereIn('cart_item_id', $selectedCartItemIds);
        }

        $cartItems = $cartItemsQuery->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Select at least one item to checkout.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $shipping = $subtotal > 0 ? 200 : 0;
        $tax = $subtotal * 0.12;
        $total = $subtotal + $shipping + $tax;

        $selectedCartItemIds = $cartItems->pluck('cart_item_id')->values();

        return view('customer.checkout.index', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total', 'selectedCartItemIds'));
    }

    /**
     * Process the checkout and create order.
     */
    public function process(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'region' => 'required|string|max:255',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'payment_method' => 'required|integer',
            'selected_cart_item_ids' => 'nullable|array',
            'selected_cart_item_ids.*' => 'integer|exists:cart_items,cart_item_id',
            'terms' => 'required',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->user_id)->first();

        if (!$cart) {
            return redirect()->route('customer.shop.index')->with('error', 'Your cart is empty');
        }

        $selectedCartItemIds = collect($request->input('selected_cart_item_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        $cartItemsQuery = CartItem::where('cart_id', $cart->cart_id)->with('product');

        if ($selectedCartItemIds->isNotEmpty()) {
            $cartItemsQuery->whereIn('cart_item_id', $selectedCartItemIds);
        }

        $cartItems = $cartItemsQuery->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Select at least one item to checkout.');
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $shipping = $subtotal > 0 ? 200 : 0;
        $tax = $subtotal * 0.12;
        $total = $subtotal + $shipping + $tax;

        // Build shipping address
        $shippingAddress = "{$request->street_address}, {$request->city}, {$request->region} {$request->postal_code}";

        try {
            // Create order
            $order = Order::create([
                'user_id' => $user->user_id,
                'payment_method_id' => $request->payment_method,
                'status_id' => 1, // Pending status
                'shipping_address' => $shippingAddress,
                'placed_at' => now(),
                'updated_at' => now()
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->product->price
                ]);
            }

            // Remove only checked-out items from cart
            CartItem::whereIn('cart_item_id', $cartItems->pluck('cart_item_id'))->delete();

            // Delete cart if no items remain
            if (!CartItem::where('cart_id', $cart->cart_id)->exists()) {
                $cart->delete();
            }

            return redirect()->route('customer.orders.show', $order->order_id)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to process order. Please try again.');
        }
    }
}
