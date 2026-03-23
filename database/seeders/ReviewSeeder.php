<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $customers = User::whereHas('role', function($q) {
            $q->where('role_name', 'Customer');
        })->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        $reviewTemplates = [
            5 => ['Excellent product!', 'Highly recommended.', 'Exceeded expectations!', 'Perfect, just what I needed.', 'Great value for the money.'],
            4 => ['Very good, but shipping was a bit slow.', 'Solid product, runs great.', 'Good build quality overall.', 'Happy with the purchase.', 'Works well so far.'],
            3 => ['It is okay, nothing special.', 'Average performance.', 'Met expectations but could be better.', 'Decent, but a bit pricey.', 'Gets the job done, but has quirks.'],
            2 => ['Not what I expected.', 'Feels a bit cheap.', 'Having some issues with it.', 'Would not recommend unless on sale.', 'Disappointing overall.'],
            1 => ['Terrible product, broke in a week.', 'Do not buy this.', 'Waste of money.', 'Very poor quality.', 'Arrived damaged and buggy.'],
        ];

        // Ensure we assign reviews only for items actually ordered by customers
        // Since ChartDataSeeder runs before this, order items will exist
        $orderItems = OrderItem::with('order')->inRandomOrder()->take(50)->get();

        foreach ($orderItems as $item) {
            // Random chance to not leave a review
            if (rand(0, 100) > 70) {
                continue;
            }

            $rating = rand(1, 5);
            $titles = ['Great!', 'Good', 'Okay', 'Bad', 'Awful'];
            $title = $titles[5 - $rating]; // Example title logic

            $body = $reviewTemplates[$rating][array_rand($reviewTemplates[$rating])];

            Review::updateOrCreate(
                ['order_item_id' => $item->order_item_id],
                [
                    'product_id' => $item->product_id,
                    'user_id' => $item->order->user_id,
                    'rating' => $rating,
                    'title' => $title,
                    'body' => $body,
                    'is_visible' => 1,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]
            );
        }
    }
}
