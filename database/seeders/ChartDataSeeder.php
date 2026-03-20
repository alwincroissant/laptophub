<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use Carbon\Carbon;

class ChartDataSeeder extends Seeder
{
    public function run()
    {
        $deliveredStatus = OrderStatus::where('status_name', 'Delivered')->first();
        if (!$deliveredStatus) {
            $this->command->info("Delivered status not found.");
            return;
        }

        $paymentMethod = PaymentMethod::first();
        if (!$paymentMethod) {
            $paymentMethod = PaymentMethod::create(['method_name' => 'Cash']);
        }
        
        $customerUser = User::whereHas('role', function($q) {
            $q->where('role_name', 'Customer');
        })->first();

        if (!$customerUser) {
            // fallback to first user
            $customerUser = User::first();
        }

        $products = Product::all();
        if ($products->isEmpty()) {
            $this->command->info("No products found. Run ProductSeeder first.");
            return;
        }

        $this->command->info("Growing data points: 250 orders over a 12-month period for realistic graphs...");

        for ($i = 0; $i < 250; $i++) {
            $daysAgo = rand(0, 365);
            $randomDate = Carbon::now()->subDays($daysAgo)->setTime(rand(8, 20), rand(0, 59), rand(0, 59));

            // Adjust created_at explicitly so it aligns
            $order = new Order([
                'user_id' => $customerUser->user_id ?? $customerUser->id,
                'status_id' => $deliveredStatus->status_id ?? $deliveredStatus->id,
                'payment_method_id' => $paymentMethod->payment_method_id ?? $paymentMethod->id,
                'shipping_address' => '123 Fake Street, Seeder City',
                'placed_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
            $order->timestamps = false;
            $order->save();

            $itemCount = rand(1, 4);
            $orderProducts = $products->random(min($itemCount, $products->count()));

            foreach ($orderProducts as $product) {
                $qty = rand(1, 3);
                $orderItem = new OrderItem([
                    'order_id' => $order->order_id ?? $order->id,
                    'product_id' => $product->product_id ?? $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                ]);
                $orderItem->timestamps = false;
                $orderItem->save();
            }
        }
        
        $this->command->info("Successfully injected beautiful test data!");
    }
}
