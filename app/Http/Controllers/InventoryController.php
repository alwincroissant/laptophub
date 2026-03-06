<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $status = (string) $request->input('status', 'all');

        $metrics = [
            'total' => Product::count(),
            'inStock' => Product::where('stock_qty', '>', 0)->where('is_archived', false)->count(),
            'lowStock' => Product::where('is_archived', false)
                ->whereColumn('stock_qty', '<=', 'low_stock_threshold')
                ->where('stock_qty', '>', 0)
                ->count(),
            'outOfStock' => Product::where('is_archived', false)->where('stock_qty', 0)->count(),
        ];

        $query = Product::query()
            ->leftJoin('categories', 'products.category_id', '=', 'categories.category_id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.brand_id')
            ->select([
                'products.product_id',
                'products.name',
                'products.image_url',
                'products.stock_qty',
                'products.low_stock_threshold',
                'products.price',
                'products.is_archived',
                'products.updated_at',
                'categories.name as category_name',
                'brands.name as brand_name',
            ]);

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('products.name', 'like', "%{$search}%")
                    ->orWhere('categories.name', 'like', "%{$search}%")
                    ->orWhere('brands.name', 'like', "%{$search}%");
            });
        }

        if ($status === 'low-stock') {
            $query->where('products.is_archived', false)
                ->whereColumn('products.stock_qty', '<=', 'products.low_stock_threshold')
                ->where('products.stock_qty', '>', 0);
        } elseif ($status === 'out-of-stock') {
            $query->where('products.is_archived', false)
                ->where('products.stock_qty', 0);
        } elseif ($status === 'archived') {
            $query->where('products.is_archived', true);
        } elseif ($status === 'active') {
            $query->where('products.is_archived', false);
        }

        $items = $query
            ->orderByDesc('products.updated_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.inventory.index', [
            'items' => $items,
            'metrics' => $metrics,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get(['category_id', 'name']);
        $brands = Brand::orderBy('name')->get(['brand_id', 'name']);

        return view('admin.inventory.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|integer|exists:categories,category_id',
            'brand_id' => 'required|integer|exists:brands,brand_id',
            'price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'compatibility' => 'nullable|string',
            'image_url' => 'nullable|url|max:255',
            'is_archived' => 'nullable|boolean',
        ]);

        $validated['is_archived'] = $request->boolean('is_archived');

        Product::create($validated);

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory item created successfully.');
    }

    public function edit(Product $inventory)
    {
        $categories = Category::orderBy('name')->get(['category_id', 'name']);
        $brands = Brand::orderBy('name')->get(['brand_id', 'name']);

        return view('admin.inventory.edit', [
            'item' => $inventory,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function update(Request $request, Product $inventory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|integer|exists:categories,category_id',
            'brand_id' => 'required|integer|exists:brands,brand_id',
            'price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'compatibility' => 'nullable|string',
            'image_url' => 'nullable|url|max:255',
            'is_archived' => 'nullable|boolean',
        ]);

        $validated['is_archived'] = $request->boolean('is_archived');

        $inventory->update($validated);

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory item updated successfully.');
    }

    public function destroy(Product $inventory)
    {
        $inventory->delete();

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory item deleted.');
    }
}
