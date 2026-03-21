<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\RestockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestockTransactionController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::orderBy('name')->get(['supplier_id', 'name']);
        
        $selectedSupplierId = $request->input('supplier_id');
        $products = [];
        
        if ($selectedSupplierId) {
            $products = SupplierProduct::where('supplier_id', $selectedSupplierId)
                ->with('product')
                ->orderBy('product_id')
                ->get()
                ->map(fn($sp) => $sp->product)
                ->sortBy('name')
                ->values();
        } else {
            $products = Product::orderBy('name')->get(['product_id', 'name', 'stock_qty']);
        }

        $baseQuery = RestockTransaction::query();

        if ($request->filled('start_date')) {
            $baseQuery->whereDate('restocked_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $baseQuery->whereDate('restocked_at', '<=', $request->end_date);
        }
        if ($request->filled('product_id')) {
            $baseQuery->where('restock_transactions.product_id', $request->product_id);
        }
        if ($request->filled('supplier_id')) {
            $baseQuery->where('restock_transactions.supplier_id', $request->supplier_id);
        }

        $restocks = (clone $baseQuery)->with(['product', 'supplier', 'manager'])
            ->orderByDesc('restocked_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.restock.index', compact('products', 'suppliers', 'restocks', 'selectedSupplierId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_type' => 'required|in:add,adjust,remove',
            'product_id' => 'required|exists:products,product_id',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $quantity = abs((int) $validated['quantity']);
        $transactionType = $validated['transaction_type'];
        $totalCost = $quantity * (float) $validated['unit_cost'];

        /** @var \App\Models\User $user */
        $user = auth()->user();

        DB::transaction(function () use ($validated, $quantity, $transactionType, $totalCost, $user) {
            $quantityChange = $quantity;
            
            if ($transactionType === 'remove') {
                $quantityChange = -$quantity;
            } elseif ($transactionType === 'adjust') {
                $quantityChange = $quantity;
            }
            
            RestockTransaction::create([
                'product_id' => $validated['product_id'],
                'supplier_id' => $validated['supplier_id'],
                'transaction_type' => $transactionType,
                'managed_by' => $user->user_id,
                'quantity_added' => $quantityChange,
                'unit_cost' => $validated['unit_cost'],
                'notes' => $validated['notes'] ?? null,
                'restocked_at' => now(),
            ]);

            Product::where('product_id', $validated['product_id'])
                ->increment('stock_qty', $quantityChange);
        });

        $messages = [
            'add' => "Restock added. Inventory increased by {$quantity}. Cost: ₱" . number_format($totalCost, 2) . " deducted from revenue.",
            'adjust' => "Inventory adjusted by {$quantity}. No revenue impact.",
            'remove' => "Stock removed. Inventory decreased by {$quantity}. Cost: ₱" . number_format($totalCost, 2) . " refunded to revenue.",
        ];

        return redirect()->route('admin.restock.index')->with('success', $messages[$transactionType]);
    }
    
    public function getSupplierProducts(Request $request)
    {
        $supplierId = $request->input('supplier_id');
        
        if (!$supplierId) {
            return response()->json(['products' => []]);
        }
        
        $products = SupplierProduct::where('supplier_id', $supplierId)
            ->with('product')
            ->orderBy('product_id')
            ->get()
            ->map(fn($sp) => [
                'product_id' => $sp->product->product_id,
                'name' => $sp->product->name,
                'stock_qty' => $sp->product->stock_qty,
            ]);
        
        return response()->json(['products' => $products]);
    }
}
