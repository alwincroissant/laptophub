<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of products with filters.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter by brand
        if ($request->filled('brand')) {
            $brands = is_array($request->brand) ? $request->brand : [$request->brand];
            $query->whereIn('brand_id', $brands);
        }

        // Filter by category
        if ($request->filled('category')) {
            $categories = is_array($request->category) ? $request->category : [$request->category];
            $query->whereIn('category_id', $categories);
        }

        // Filter by price range
        if ($request->filled('price')) {
            $priceRanges = is_array($request->price) ? $request->price : [$request->price];
            foreach ($priceRanges as $range) {
                switch ($range) {
                    case 'budget':
                        $query->orWhereBetween('price', [0, 30000]);
                        break;
                    case 'mid':
                        $query->orWhereBetween('price', [30000, 70000]);
                        break;
                    case 'premium':
                        $query->orWhereBetween('price', [70000, 150000]);
                        break;
                    case 'luxury':
                        $query->orWhere('price', '>', 150000);
                        break;
                }
            }
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'price-asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Only include active, non-archived products
        $query->where('is_archived', false);

        $products = $query->paginate(12);

        // Get brands for filter
        $brands = Brand::all();

        return view('customer.shop.index', compact('products', 'brands'));
    }

    /**
     * Show the form for searching products.
     */
    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->filled('q')) {
            $searchTerm = $request->get('q');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $query->where('is_archived', false);
        $products = $query->paginate(12);
        $brands = Brand::all();

        return view('customer.shop.index', compact('products', 'brands'));
    }
}
