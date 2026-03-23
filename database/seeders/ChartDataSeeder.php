<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\RestockTransaction;
use Carbon\Carbon;

class ChartDataSeeder extends Seeder
{
    public function run()
    {
        $orderStatuses = OrderStatus::all();
        if ($orderStatuses->isEmpty()) {
            $this->command->info("Order statuses not found.");
            return;
        }

        $paymentMethods = PaymentMethod::all();
        if ($paymentMethods->isEmpty()) {
            $paymentMethods = collect([PaymentMethod::create(['method_name' => 'Cash on Delivery'])]);
        }
        $customerUsers = User::whereHas('role', function($q) {
            $q->where('role_name', 'Customer');
        })->get();

        $staffUser = User::whereHas('role', function($q) {
            $q->whereIn('role_name', ['Admin', 'InventoryManager']);
        })->first();

        if ($customerUsers->isEmpty()) {
            // fallback to users
            $customerUsers = clone User::all();
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

            $customerUser = $customerUsers->random();

            $randomStatus = $orderStatuses->random();

            $randomPaymentMethod = $paymentMethods->random();

            // Adjust created_at explicitly so it aligns
            $order = new Order([
                'user_id' => $customerUser->user_id ?? $customerUser->id,
                'status_id' => $randomStatus->status_id ?? $randomStatus->id,
                'payment_method_id' => $randomPaymentMethod->payment_method_id ?? $randomPaymentMethod->id,
                'shipping_address' => '123 Fake Street, Seeder City',
                'placed_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
            $order->timestamps = false;
            $order->save();

            // Determine who changed the status realistically
            $isCustomerAction = in_array(strtolower($randomStatus->status_name), ['pending', 'cancelled']);
            $staffId = $staffUser->user_id ?? ($staffUser->id ?? ($customerUser->user_id ?? $customerUser->id));
            $changedBy = $isCustomerAction ? ($customerUser->user_id ?? $customerUser->id) : $staffId;

            // Seed recent activity (OrderStatusLog)
            \App\Models\OrderStatusLog::create([
                'order_id' => $order->order_id ?? $order->id,
                'status_id' => $randomStatus->status_id ?? $randomStatus->id,
                'changed_by' => $changedBy,
                'changed_at' => $randomDate,
                'note' => 'Status seeded via ChartDataSeeder',
            ]);

            $itemCount = rand(1, 4);
            $orderProducts = $products->random(min($itemCount, $products->count()));

            foreach ($orderProducts as $product) {
                $qty = rand(1, 2);
                $orderItem = new OrderItem([
                    'order_id' => $order->order_id ?? $order->id,
                    'product_id' => $product->product_id ?? $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                ]);
                $orderItem->timestamps = false;
                $orderItem->save();

                // Deduction restock transaction
                RestockTransaction::create([
                    'product_id' => $product->product_id ?? $product->id,
                    'supplier_id' => null,
                    'managed_by' => null,
                    'quantity_added' => -$qty,
                    'unit_cost' => $product->price * 0.7, // Assume cost was ~70% of price
                    'transaction_type' => 'remove',
                    'notes' => 'Sale seeded (Order #' . ($order->order_id ?? $order->id) . ')',
                    'restocked_at' => $randomDate,
                ]);
            }
        }
        
        $this->command->info("Successfully injected beautiful test data!");
    }
}
