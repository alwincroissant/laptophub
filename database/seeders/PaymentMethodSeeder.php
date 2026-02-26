<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Cash on Delivery', 'Online Payment'] as $methodName) {
            DB::table('payment_methods')->updateOrInsert(
                ['method_name' => $methodName],
                ['method_name' => $methodName]
            );
        }
    }
}
