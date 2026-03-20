<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::query()
            ->with([
                'user:user_id,full_name,email',
                'product:product_id,name',
                'orderItem:order_item_id,order_id',
                'orderItem.order:order_id,placed_at',
            ])
            ->orderByDesc('created_at')
            ->get();

        $metrics = [
            'total' => Review::count(),
            'shown' => Review::where('is_visible', true)->count(),
            'hidden' => Review::where('is_visible', false)->count(),
            'avg_rating' => (float) (Review::avg('rating') ?? 0),
        ];

        return view('admin.review.index', [
            'reviews' => $reviews,
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

    public function edit(Review $review): View
    {
        $review->load([
            'user:user_id,full_name,email',
            'product:product_id,name',
        ]);

        return view('admin.review.edit', ['review' => $review]);
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['nullable', 'string', 'max:150'],
            'body' => ['nullable', 'string', 'max:1500'],
            'is_visible' => ['nullable'],
        ]);

        $review->rating = (int) $data['rating'];
        $review->title = trim((string) ($data['title'] ?? '')) ?: null;
        $review->body = trim((string) ($data['body'] ?? '')) ?: null;
        $review->is_visible = $request->has('is_visible');
        $review->save();

        return redirect()
            ->route('admin.review.index')
            ->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()
            ->route('admin.review.index')
            ->with('success', 'Review deleted successfully.');
    }
}
