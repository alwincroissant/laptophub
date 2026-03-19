<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->user_id)->first();

        if (!$cart) {
            return view('customer.cart.index', [
                'cartItems' => collect(),
                'subtotal' => 0,
                'shipping' => 0,
                'total' => 0
            ]);
        }

        $cartItems = CartItem::where('cart_id', $cart->cart_id)
            ->with('product')
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $shipping = $subtotal > 0 ? 200 : 0;
        $total = $subtotal + $shipping;

        return view('customer.cart.index', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1'
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $product = Product::find($request->product_id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }

        // Get or create cart for user
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->user_id],
            ['user_id' => $user->user_id]
        );

        // Check if item already exists in cart
        $cartItem = CartItem::where('cart_id', $cart->cart_id)
            ->where('product_id', $request->product_id)
            ->first();

        $existingQty = $cartItem ? (int) $cartItem->quantity : 0;
        $requestedQty = (int) $request->quantity;
        $newQty = $existingQty + $requestedQty;

        if ($newQty > (int) $product->stock_qty) {
            return redirect()->back()->with(
                'error',
                "Only {$product->stock_qty} unit(s) of {$product->name} available. You already have {$existingQty} in cart."
            );
        }

        if ($cartItem) {
            // Update quantity
            $cartItem->quantity = $newQty;
            $cartItem->save();
        } else {
            // Create new cart item
            CartItem::create([
                'cart_id' => $cart->cart_id,
                'product_id' => $request->product_id,
                'quantity' => $requestedQty
            ]);
        }

        $quantityAdded = $requestedQty;
        $itemWord = $quantityAdded === 1 ? 'unit' : 'units';
        $message = "Added {$quantityAdded} {$itemWord} of {$product->name} to cart.";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Update item quantity in cart.
     */
    public function updateQty(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,cart_item_id',
            'action' => 'nullable|in:increase,decrease,set',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $cartItem = CartItem::with('product')->find($request->cart_item_id);

        if (!$cartItem) {
            return redirect()->back()->with('error', 'Item not found');
        }

        if (!$cartItem->product) {
            return redirect()->back()->with('error', 'Product not found');
        }

        $newQuantity = (int) $request->input('quantity', $cartItem->quantity);

        if ($request->action === 'increase') {
            $newQuantity++;
        } elseif ($request->action === 'decrease' && $newQuantity > 1) {
            $newQuantity--;
        }

        if ($newQuantity > (int) $cartItem->product->stock_qty) {
            return redirect()->back()->with(
                'error',
                "Cannot set quantity. Only {$cartItem->product->stock_qty} unit(s) of {$cartItem->product->name} available."
            );
        }

        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        return redirect()->route('customer.cart.index')->with('success', 'Cart updated');
    }

    /**
     * Remove an item from cart.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,cart_item_id'
        ]);

        $cartItem = CartItem::find($request->cart_item_id);
        $cartItem->delete();

        return redirect()->route('customer.cart.index')->with('success', 'Item removed from cart');
    }
}
