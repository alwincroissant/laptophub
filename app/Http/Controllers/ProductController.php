<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $status = (string) $request->input('status', 'all');

        $metrics = [
            'total' => Product::count(),
            'active' => Product::where('is_archived', 0)->count(),
            'lowStock' => Product::where('is_archived', 0)
                ->whereColumn('stock_qty', '<=', 'low_stock_threshold')
                ->count(),
            'outOfStock' => Product::where('is_archived', 0)
                ->where('stock_qty', 0)
                ->count(),
        ];

        $productsQuery = Product::query()
            ->leftJoin('categories', 'products.category_id', '=', 'categories.category_id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.brand_id')
            ->select([
                'products.product_id',
                'products.name',
                'products.image_url',
                'products.price',
                'products.stock_qty',
                'products.low_stock_threshold',
                'products.is_archived',
                'products.updated_at',
                'products.deleted_at',
                'categories.name as category_name',
                'brands.name as brand_name',
            ]);

        if ($status === 'trashed') {
            $productsQuery->onlyTrashed();
        }

        if ($search !== '') {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('products.name', 'like', "%{$search}%")
                    ->orWhere('categories.name', 'like', "%{$search}%")
                    ->orWhere('brands.name', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $productsQuery->where('products.is_archived', 0);
        } elseif ($status === 'archived') {
            $productsQuery->where('products.is_archived', 1);
        } elseif ($status === 'low-stock') {
            $productsQuery->where('products.is_archived', 0)
                ->whereColumn('products.stock_qty', '<=', 'products.low_stock_threshold');
        } elseif ($status === 'out-of-stock') {
            $productsQuery->where('products.is_archived', 0)
                ->where('products.stock_qty', 0);
        }

        $products = $productsQuery
            ->orderByDesc('products.updated_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.product.index', [
            'products' => $products,
            'metrics' => $metrics,
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get(['category_id', 'name']);
        $brands = Brand::orderBy('name')->get(['brand_id', 'name']);

        return view('admin.product.create', [
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|integer|exists:categories,category_id',
            'brand_id' => 'required|integer|exists:brands,brand_id',
            'description' => 'nullable|string',
            'compatibility' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'is_archived' => 'nullable|boolean',
        ]);

        $validated['is_archived'] = $request->boolean('is_archived');

        if ($request->hasFile('image')) {
            $validated['image_url'] = Storage::url($request->file('image')->store('products', 'public'));
        }

        unset($validated['image']);

        Product::create($validated);

        return redirect()
            ->route('admin.product.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $productId)
    {
        $product = Product::withTrashed()
            ->leftJoin('categories', 'products.category_id', '=', 'categories.category_id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.brand_id')
            ->select([
                'products.product_id',
                'products.category_id',
                'products.brand_id',
                'products.name',
                'products.description',
                'products.compatibility',
                'products.image_url',
                'products.price',
                'products.stock_qty',
                'products.low_stock_threshold',
                'products.is_archived',
                'products.created_at',
                'products.updated_at',
                'products.deleted_at',
                'categories.name as category_name',
                'brands.name as brand_name',
            ])
            ->where('products.product_id', $productId)
            ->firstOrFail();

        return view('admin.product.show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get(['category_id', 'name']);
        $brands = Brand::orderBy('name')->get(['brand_id', 'name']);

        return view('admin.product.edit', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|integer|exists:categories,category_id',
            'brand_id' => 'required|integer|exists:brands,brand_id',
            'description' => 'nullable|string',
            'compatibility' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'is_archived' => 'nullable|boolean',
        ]);

        $validated['is_archived'] = $request->boolean('is_archived');

        if ($request->hasFile('image')) {
            if ($product->image_url && str_starts_with($product->image_url, '/storage/')) {
                Storage::disk('public')->delete(substr($product->image_url, strlen('/storage/')));
            }

            $validated['image_url'] = Storage::url($request->file('image')->store('products', 'public'));
        }

        unset($validated['image']);

        $product->update($validated);

        return redirect()
            ->route('admin.product.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.product.index')
            ->with('success', 'Product soft deleted successfully.');
    }

    public function restore(int $productId)
    {
        $product = Product::withTrashed()->findOrFail($productId);

        if ($product->trashed()) {
            $product->restore();
        }

        return redirect()
            ->route('admin.product.index', ['status' => 'trashed'])
            ->with('success', 'Product recovered successfully.');
    }

    public function forceDestroy(int $productId)
    {
        $product = Product::withTrashed()->findOrFail($productId);

        $product->forceDelete();

        return redirect()
            ->route('admin.product.index')
            ->with('success', 'Product deleted permanently.');
    }
}
