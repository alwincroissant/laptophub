<?php

namespace App\Http\Controllers;

use App\DataTables\ProductsDataTable;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductsDataTable $dataTable, Request $request)
    {
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

        return $dataTable->render('admin.product.index', [
            'metrics' => $metrics,
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
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['is_archived'] = $request->boolean('is_archived');

        if ($request->hasFile('image')) {
            $validated['image_url'] = Storage::url($request->file('image')->store('products', 'public'));
        }

        unset($validated['image']);
        unset($validated['gallery_images']);

        $product = Product::create($validated);

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $file) {
                \App\Models\ProductImage::create([
                    'product_id' => $product->product_id,
                    'image_url' => Storage::url($file->store('products/gallery', 'public')),
                    'sort_order' => $index,
                ]);
            }
        }

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
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'delete_main_image' => 'nullable|boolean',
            'delete_gallery_images' => 'nullable|array',
            'delete_gallery_images.*' => 'integer|exists:product_images,image_id'
        ]);

        $validated['is_archived'] = $request->boolean('is_archived');

        if ($request->hasFile('image')) {
            if ($product->image_url && str_starts_with($product->image_url, '/storage/')) {
                Storage::disk('public')->delete(substr($product->image_url, strlen('/storage/')));
            }

            $validated['image_url'] = Storage::url($request->file('image')->store('products', 'public'));
        }

        unset($validated['image']);
        unset($validated['gallery_images']);
        unset($validated['delete_main_image']);
        unset($validated['delete_gallery_images']);

        if ($request->boolean('delete_main_image')) {
            if ($product->image_url && str_starts_with($product->image_url, '/storage/')) {
                Storage::disk('public')->delete(substr($product->image_url, strlen('/storage/')));
            }
            if (!array_key_exists('image_url', $validated)) {
                $validated['image_url'] = null;
            }
        }

        if ($request->has('delete_gallery_images')) {
            $galleryIdsToDelete = $request->input('delete_gallery_images');
            $imagesToDelete = \App\Models\ProductImage::whereIn('image_id', $galleryIdsToDelete)->get();
            foreach ($imagesToDelete as $imgToDelete) {
                if ($imgToDelete->image_url && str_starts_with($imgToDelete->image_url, '/storage/')) {
                    Storage::disk('public')->delete(substr($imgToDelete->image_url, strlen('/storage/')));
                }
                $imgToDelete->delete();
            }
        }

        $product->update($validated);

        if ($request->hasFile('gallery_images')) {
            $lastSortOrder = $product->images()->max('sort_order') ?? -1;
            foreach ($request->file('gallery_images') as $file) {
                $lastSortOrder++;
                \App\Models\ProductImage::create([
                    'product_id' => $product->product_id,
                    'image_url' => Storage::url($file->store('products/gallery', 'public')),
                    'sort_order' => $lastSortOrder,
                ]);
            }
        }

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

    public function import(Request $request)
    {
        $request->validate([
            'item_upload' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(
            new ProductImport,
            $request->file('item_upload')
        );

        return redirect()->back()->with('success', 'Excel file imported successfully.');
    }
}
