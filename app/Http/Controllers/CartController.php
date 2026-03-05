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
                'tax' => 0,
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
        $tax = $subtotal * 0.12;
        $total = $subtotal + $shipping + $tax;

        return view('customer.cart.index', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total'));
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

        if ($product->stock_qty < $request->quantity) {
            return redirect()->back()->with('error', 'Insufficient stock');
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

        if ($cartItem) {
            // Update quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Create new cart item
            CartItem::create([
                'cart_id' => $cart->cart_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->route('customer.cart.index')->with('success', 'Product added to cart');
    }

    /**
     * Update item quantity in cart.
     */
    public function updateQty(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,cart_item_id',
            'action' => 'required|in:increase,decrease'
        ]);

        $cartItem = CartItem::find($request->cart_item_id);

        if (!$cartItem) {
            return redirect()->back()->with('error', 'Item not found');
        }

        if ($request->action === 'increase') {
            $cartItem->quantity++;
        } elseif ($request->action === 'decrease' && $cartItem->quantity > 1) {
            $cartItem->quantity--;
        }

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
