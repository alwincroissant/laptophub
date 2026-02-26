<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'] as $statusName) {
            DB::table('order_statuses')->updateOrInsert(
                ['status_name' => $statusName],
                ['status_name' => $statusName]
            );
        }
    }
}
