<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\RestockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestockTransactionController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderBy('name')->get(['product_id', 'name', 'stock_qty']);
        $suppliers = Supplier::orderBy('name')->get(['supplier_id', 'name']);

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

        return view('admin.restock.index', compact('products', 'suppliers', 'restocks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_type' => 'required|in:add,subtract',
            'product_id' => 'required|exists:products,product_id',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'quantity_added' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $finalQuantity = $validated['transaction_type'] === 'subtract' 
            ? -abs($validated['quantity_added']) 
            : abs($validated['quantity_added']);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        DB::transaction(function () use ($validated, $finalQuantity, $user) {
            RestockTransaction::create([
                'product_id' => $validated['product_id'],
                'supplier_id' => $validated['supplier_id'],
                'managed_by' => $user->user_id,
                'quantity_added' => $finalQuantity,
                'unit_cost' => $validated['unit_cost'],
                'notes' => $validated['notes'] ?? null,
                'restocked_at' => now(),
            ]);

            Product::where('product_id', $validated['product_id'])
                ->increment('stock_qty', $finalQuantity);
        });

        $msg = $validated['transaction_type'] === 'subtract'
            ? 'Stock correction applied. Inventory deducted by ' . abs($validated['quantity_added']) . '.'
            : 'Restock recorded and inventory updated by ' . abs($validated['quantity_added']) . '.';

        return redirect()->route('admin.restock.index')->with('success', $msg);
    }
}
