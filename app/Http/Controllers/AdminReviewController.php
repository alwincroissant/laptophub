<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $visibility = (string) $request->input('visibility', 'all');
        $rating = (int) $request->input('rating', 0);

        $reviewsQuery = Review::query()
            ->with([
                'user:user_id,full_name,email',
                'product:product_id,name',
                'orderItem:order_item_id,order_id',
                'orderItem.order:order_id,placed_at',
            ]);

        if ($visibility === 'shown') {
            $reviewsQuery->where('is_visible', true);
        } elseif ($visibility === 'hidden') {
            $reviewsQuery->where('is_visible', false);
        }

        if ($rating >= 1 && $rating <= 5) {
            $reviewsQuery->where('rating', $rating);
        }

        if ($search !== '') {
            $reviewsQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('full_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('orderItem', function ($orderItemQuery) use ($search) {
                        $orderItemQuery->where('order_id', 'like', "%{$search}%");
                    });
            });
        }

        $reviews = $reviewsQuery
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $metrics = [
            'total' => Review::count(),
            'shown' => Review::where('is_visible', true)->count(),
            'hidden' => Review::where('is_visible', false)->count(),
            'avg_rating' => (float) (Review::avg('rating') ?? 0),
        ];

        return view('admin.review.index', [
            'reviews' => $reviews,
            'search' => $search,
            'visibility' => $visibility,
            'rating' => $rating,
            'metrics' => $metrics,
        ]);
    }

    public function toggleVisibility(Review $review): RedirectResponse
    {
        $review->is_visible = ! (bool) $review->is_visible;
        $review->save();

        return redirect()
            ->route('admin.review.index')
            ->with('success', 'Review visibility updated successfully.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()
            ->route('admin.review.index')
            ->with('success', 'Review deleted successfully.');
    }
}
