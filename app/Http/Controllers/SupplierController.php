<?php

namespace App\Http\Controllers;

use App\DataTables\SuppliersDataTable;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index(SuppliersDataTable $dataTable)
    {
        $status = (string) request('status', 'all');
        return $dataTable->render('admin.supplier.index', compact('status'));
    }

    public function create()
    {
        $products = Product::query()
            ->where('is_archived', false)
            ->orderBy('name')
            ->get(['product_id', 'name']);

        return view('admin.supplier.create', ['products' => $products]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:suppliers,name'],
            'contact_name' => ['nullable', 'string', 'max:100'],
            'contact_email' => ['nullable', 'email', 'max:150'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['integer', 'exists:products,product_id'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $productIds = collect($request->input('product_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        DB::transaction(function () use ($validated, $productIds) {
            $supplier = Supplier::create($validated);
            $supplier->products()->sync($productIds);
        });

        return redirect()->route('admin.supplier.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['products' => function ($query) {
            $query->where('is_archived', false)
                  ->whereNull('deleted_at')
                  ->orderBy('name');
        }]);

        return view('admin.supplier.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        $products = Product::query()
            ->where('is_archived', false)
            ->orderBy('name')
            ->get(['product_id', 'name']);

        $selectedProductIds = $supplier->products()->pluck('products.product_id')->all();

        return view('admin.supplier.edit', [
            'supplier' => $supplier,
            'products' => $products,
            'selectedProductIds' => $selectedProductIds,
        ]);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('suppliers', 'name')->ignore($supplier->supplier_id, 'supplier_id'),
            ],
            'contact_name' => ['nullable', 'string', 'max:100'],
            'contact_email' => ['nullable', 'email', 'max:150'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['integer', 'exists:products,product_id'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $productIds = collect($request->input('product_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        DB::transaction(function () use ($supplier, $validated, $productIds) {
            $supplier->update($validated);
            $supplier->products()->sync($productIds);
        });

        return redirect()->route('admin.supplier.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('admin.supplier.index')->with('success', 'Supplier soft deleted successfully.');
    }

    public function restore(int $supplierId)
    {
        $supplier = Supplier::withTrashed()->findOrFail($supplierId);

        if ($supplier->trashed()) {
            $supplier->restore();
        }

        return redirect()->route('admin.supplier.index', ['status' => 'trashed'])->with('success', 'Supplier restored successfully.');
    }

    public function forceDestroy(int $supplierId)
    {
        $supplier = Supplier::withTrashed()->findOrFail($supplierId);
        $supplier->forceDelete();

        return redirect()->route('admin.supplier.index')->with('success', 'Supplier permanently deleted.');
    }
}
