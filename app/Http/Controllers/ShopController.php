<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Display a listing of products with filters.
     */
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['brand', 'category'])
            ->withCount([
                'reviews as visible_reviews_count' => function ($reviewQuery) {
                    $reviewQuery->where('is_visible', true);
                },
            ])
            ->withAvg([
                'reviews as visible_reviews_avg' => function ($reviewQuery) {
                    $reviewQuery->where('is_visible', true);
                },
            ], 'rating');

        $search = trim((string) $request->input('search', ''));
        $selectedBrands = array_map('intval', (array) $request->input('brand', []));
        $selectedCategories = array_map('intval', (array) $request->input('category', []));
        $selectedPrices = (array) $request->input('price', []);

        // Only include active, non-archived products
        $query->where('is_archived', false);

        if ($search !== '') {
            $query->where(function ($productQuery) use ($search) {
                $productQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('brand', function ($brandQuery) use ($search) {
                        $brandQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by brand
        if (! empty($selectedBrands)) {
            $query->whereIn('brand_id', $selectedBrands);
        }

        // Filter by category
        if (! empty($selectedCategories)) {
            $query->whereIn('category_id', $selectedCategories);
        }

        // Filter by price range
        if (! empty($selectedPrices)) {
            $query->where(function ($priceQuery) use ($selectedPrices) {
                foreach ($selectedPrices as $range) {
                    switch ($range) {
                        case 'budget':
                            $priceQuery->orWhereBetween('price', [0, 30000]);
                            break;
                        case 'mid':
                            $priceQuery->orWhereBetween('price', [30000, 70000]);
                            break;
                        case 'premium':
                            $priceQuery->orWhereBetween('price', [70000, 150000]);
                            break;
                        case 'luxury':
                            $priceQuery->orWhere('price', '>', 150000);
                            break;
                    }
                }
            });
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

        $products = $query->paginate(12)->withQueryString();

        // Get brands/categories for filter
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('customer.shop.index', compact(
            'products',
            'brands',
            'categories',
            'search',
            'selectedBrands',
            'selectedCategories',
            'selectedPrices',
            'sort'
        ));
    }

    /**
     * Show the form for searching products.
     */
    public function search(Request $request)
    {
        $query = Product::query()
            ->with(['brand', 'category'])
            ->withCount([
                'reviews as visible_reviews_count' => function ($reviewQuery) {
                    $reviewQuery->where('is_visible', true);
                },
            ])
            ->withAvg([
                'reviews as visible_reviews_avg' => function ($reviewQuery) {
                    $reviewQuery->where('is_visible', true);
                },
            ], 'rating');

        if ($request->filled('q')) {
            $searchTerm = $request->get('q');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $query->where('is_archived', false);
        $products = $query->paginate(12);
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('customer.shop.index', [
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories,
            'search' => (string) $request->get('q', ''),
            'selectedBrands' => [],
            'selectedCategories' => [],
            'selectedPrices' => [],
            'sort' => 'newest',
        ]);
    }

    /**
     * Display detailed product information.
     */
    public function show(Request $request, int $productId)
    {
        $product = Product::query()
            ->with(['brand', 'category'])
            ->withCount([
                'reviews as visible_reviews_count' => function ($reviewQuery) {
                    $reviewQuery->where('is_visible', true);
                },
            ])
            ->withAvg([
                'reviews as visible_reviews_avg' => function ($reviewQuery) {
                    $reviewQuery->where('is_visible', true);
                },
            ], 'rating')
            ->where('product_id', $productId)
            ->where('is_archived', false)
            ->firstOrFail();

        $reviews = Review::query()
            ->where('product_id', $product->product_id)
            ->where('is_visible', true)
            ->with('user:user_id,full_name')
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();

        $eligibleReviewItems = collect();
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            $eligibleReviewItems = OrderItem::query()
                ->where('product_id', $product->product_id)
                ->whereHas('order', function ($orderQuery) use ($user) {
                    $orderQuery->where('user_id', $user->user_id)
                        ->whereHas('status', function ($statusQuery) {
                            $statusQuery->whereRaw('LOWER(status_name) = ?', ['delivered']);
                        });
                })
                ->whereDoesntHave('review')
                ->with('order:order_id,placed_at')
                ->orderByDesc('order_id')
                ->get(['order_item_id', 'order_id']);
        }

        $selectedOrderItemId = (int) $request->query('order_item_id', 0);
        if ($selectedOrderItemId > 0 && $eligibleReviewItems->where('order_item_id', $selectedOrderItemId)->isEmpty()) {
            $selectedOrderItemId = 0;
        }

        return view('customer.shop.show', [
            'product' => $product,
            'reviews' => $reviews,
            'eligibleReviewItems' => $eligibleReviewItems,
            'selectedOrderItemId' => $selectedOrderItemId,
        ]);
    }
}
