<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;
use App\Models\User;
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

        $settings = \App\Models\Setting::pluck('value', 'key');
        $shippingFeeSetting = isset($settings['shipping_fee']) ? (float) $settings['shipping_fee'] : 0;
        $taxRateSetting = isset($settings['tax_rate']) ? (float) $settings['tax_rate'] : 0;

        $shipping = $subtotal > 0 ? $shippingFeeSetting : 0;
        $taxAmount = $subtotal > 0 ? ($subtotal * ($taxRateSetting / 100)) : 0;
        $total = $subtotal + $shipping + $taxAmount;

        $selectedCartItemIds = $cartItems->pluck('cart_item_id')->values();
        $addresses = UserAddress::where('user_id', $user->user_id)
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get();

        $selectedAddressId = (int) $request->input('selected_address_id', 0);
        $selectedAddress = $addresses->firstWhere('address_id', $selectedAddressId)
            ?? $addresses->firstWhere('is_default', true)
            ?? $addresses->first();

        $selectedAddressId = $selectedAddress ? (int) $selectedAddress->address_id : null;

        return view('customer.checkout.index', compact('cartItems', 'subtotal', 'shipping', 'taxAmount', 'taxRateSetting', 'total', 'selectedCartItemIds', 'addresses', 'selectedAddressId'));
    }

    /**
     * Process the checkout and create order.
     */
    public function process(Request $request)
    {
        $request->validate([
            'address_id' => 'required|integer|exists:user_addresses,address_id',
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

        $address = UserAddress::where('address_id', $request->address_id)
            ->where('user_id', $user->user_id)
            ->first();

        if (!$address) {
            return redirect()->back()->with('error', 'Invalid shipping address selected.');
        }

        $shippingAddress = $address->formattedAddress();

        try {
            $order = DB::transaction(function () use ($user, $request, $shippingAddress, $selectedCartItemIds) {
                $cart = Cart::where('user_id', $user->user_id)
                    ->lockForUpdate()
                    ->first();

                if (!$cart) {
                    throw new \RuntimeException('Your cart is empty');
                }

                $cartItemsQuery = CartItem::where('cart_id', $cart->cart_id)
                    ->with('product')
                    ->lockForUpdate();

                if ($selectedCartItemIds->isNotEmpty()) {
                    $cartItemsQuery->whereIn('cart_item_id', $selectedCartItemIds);
                }

                $cartItems = $cartItemsQuery->get();

                if ($cartItems->isEmpty()) {
                    throw new \RuntimeException('Select at least one item to checkout.');
                }

                // Calculate totals from locked cart items to keep checkout atomic.
                $subtotal = $cartItems->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                });

                $settings = \App\Models\Setting::pluck('value', 'key');
                $shippingFeeSetting = isset($settings['shipping_fee']) ? (float) $settings['shipping_fee'] : 0;
                $taxRateSetting = isset($settings['tax_rate']) ? (float) $settings['tax_rate'] : 0;

                $shipping = $subtotal > 0 ? $shippingFeeSetting : 0;
                $taxAmount = $subtotal > 0 ? ($subtotal * ($taxRateSetting / 100)) : 0;
                $total = $subtotal + $shipping + $taxAmount;

                $order = Order::create([
                    'user_id' => $user->user_id,
                    'payment_method_id' => $request->payment_method,
                    'status_id' => 1,
                    'shipping_address' => $shippingAddress,
                    'tax_rate' => $taxRateSetting,
                    'tax_amount' => $taxAmount,
                    'shipping_fee' => $shipping,
                    'placed_at' => now(),
                    'updated_at' => now()
                ]);

                foreach ($cartItems as $cartItem) {
                    OrderItem::create([
                        'order_id' => $order->order_id,
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'unit_price' => $cartItem->product->price,
                    ]);

                    // Log the sale deduction in the inventory tracker
                    \App\Models\RestockTransaction::create([
                        'product_id' => $cartItem->product_id,
                        'supplier_id' => null,
                        'transaction_type' => 'remove',
                        'managed_by' => $user->user_id,
                        'quantity_added' => -$cartItem->quantity,
                        'unit_cost' => 0, // Ignored logic for 'remove' transactions on the backend
                        'notes' => 'Sale: Order #' . $order->order_id,
                        'restocked_at' => now(),
                    ]);

                    // Decrease stock
                    $cartItem->product->decrement('stock_qty', $cartItem->quantity);
                }

                CartItem::whereIn('cart_item_id', $cartItems->pluck('cart_item_id'))->delete();

                if (!CartItem::where('cart_id', $cart->cart_id)->exists()) {
                    $cart->delete();
                }

                return $order;
            }, 3);

            Mail::to($user->email, $user->full_name)->send(new OrderPlaced($order));

            return redirect()->route('customer.orders.show', $order->order_id)
                ->with('success', 'Order placed successfully!');
        } catch (\Throwable $e) {
            $message = $e->getMessage() ?: 'Failed to process order. Please try again.';

            return redirect()->back()->with('error', $message);
        }
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'label' => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'region' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'street_address' => 'required|string|max:255',
            'set_default' => 'nullable',
            'selected_cart_item_ids' => 'nullable|array',
            'selected_cart_item_ids.*' => 'integer|exists:cart_items,cart_item_id',
            'payment_method' => 'nullable|integer',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $setAsDefault = (bool) $request->boolean('set_default');

        $address = DB::transaction(function () use ($request, $user, $setAsDefault) {
            if ($setAsDefault || !UserAddress::where('user_id', $user->user_id)->exists()) {
                UserAddress::where('user_id', $user->user_id)->update(['is_default' => false]);
                $setAsDefault = true;
            }

            return UserAddress::create([
                'user_id' => $user->user_id,
                'label' => $request->label,
                'recipient_name' => $request->recipient_name,
                'phone' => $request->phone,
                'region' => $request->region,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'street_address' => $request->street_address,
                'is_default' => $setAsDefault,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->route('customer.checkout.index', $this->checkoutQueryParams(
            $request,
            ['selected_address_id' => $address->address_id]
        ))->with('success', 'Shipping address added.');
    }

    public function setDefaultAddress(Request $request, UserAddress $address)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($address->user_id !== $user->user_id) {
            abort(403, 'Unauthorized');
        }

        DB::transaction(function () use ($user, $address) {
            UserAddress::where('user_id', $user->user_id)->update(['is_default' => false]);
            $address->is_default = true;
            $address->save();
        });

        return redirect()->route('customer.checkout.index', $this->checkoutQueryParams(
            $request,
            ['selected_address_id' => $address->address_id]
        ))->with('success', 'Default address updated.');
    }

    public function destroyAddress(Request $request, UserAddress $address)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($address->user_id !== $user->user_id) {
            abort(403, 'Unauthorized');
        }

        $wasDefault = (bool) $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $nextAddress = UserAddress::where('user_id', $user->user_id)
                ->orderByDesc('updated_at')
                ->first();

            if ($nextAddress) {
                $nextAddress->is_default = true;
                $nextAddress->save();
            }
        }

        return redirect()->route('customer.checkout.index', $this->checkoutQueryParams($request))
            ->with('success', 'Address removed.');
    }

    private function checkoutQueryParams(Request $request, array $extra = []): array
    {
        $selectedCartItemIds = collect($request->input('selected_cart_item_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $query = [];

        if (!empty($selectedCartItemIds)) {
            $query['selected_cart_item_ids'] = $selectedCartItemIds;
        }

        if ($request->filled('payment_method')) {
            $query['payment_method'] = (int) $request->input('payment_method');
        }

        return array_merge($query, $extra);
    }
}
