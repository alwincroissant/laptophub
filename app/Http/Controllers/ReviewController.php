<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, int $productId): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $product = Product::query()
            ->where('product_id', $productId)
            ->where('is_archived', false)
            ->firstOrFail();

        $data = $request->validate([
            'order_item_id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['nullable', 'string', 'max:150'],
            'body' => ['nullable', 'string', 'max:1500'],
        ]);

        $eligibleOrderItem = OrderItem::query()
            ->where('order_item_id', (int) $data['order_item_id'])
            ->where('product_id', $product->product_id)
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->user_id)
                    ->whereHas('status', function ($statusQuery) {
                        $statusQuery->whereRaw('LOWER(status_name) = ?', ['delivered']);
                    });
            })
            ->whereDoesntHave('review')
            ->first();

        if (! $eligibleOrderItem) {
            return redirect()
                ->route('customer.shop.show', $product->product_id)
                ->with('error', 'You can only review products from delivered orders that are not yet reviewed.');
        }

        Review::create([
            'product_id' => $product->product_id,
            'user_id' => $user->user_id,
            'order_item_id' => $eligibleOrderItem->order_item_id,
            'rating' => (int) $data['rating'],
            'title' => trim((string) ($data['title'] ?? '')) ?: null,
            'body' => trim((string) ($data['body'] ?? '')) ?: null,
            'is_visible' => true,
            'created_at' => now(),
        ]);

        return redirect()
            ->to(route('customer.shop.show', $product->product_id) . '#reviews')
            ->with('success', 'Thanks for your review.');
    }

    public function update(Request $request, int $productId, Review $review): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ((int) $review->user_id !== (int) $user->user_id) {
            abort(403);
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['nullable', 'string', 'max:150'],
            'body' => ['nullable', 'string', 'max:1500'],
        ]);

        $review->rating = (int) $data['rating'];
        $review->title = trim((string) ($data['title'] ?? '')) ?: null;
        $review->body = trim((string) ($data['body'] ?? '')) ?: null;
        $review->save();

        return redirect()
            ->to(route('customer.shop.show', $productId) . '#reviews')
            ->with('success', 'Your review has been updated.');
    }

    public function destroy(Request $request, int $productId, Review $review): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ((int) $review->user_id !== (int) $user->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $review->delete();

        return redirect()
            ->to(route('customer.shop.show', $productId) . '#reviews')
            ->with('success', 'Your review has been successfully removed.');
    }
}
