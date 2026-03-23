<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Supplier;

class SupplierProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::with('brand')->get();

        foreach ($products as $product) {
            if ($product->brand) {
                $supplier = Supplier::where('name', $product->brand->name . ' Authorized Supplier')->first();

                if ($supplier) {
                    DB::table('supplier_products')->updateOrInsert(
                        [
                            'supplier_id' => $supplier->supplier_id,
                            'product_id' => $product->product_id,
                        ],
                        []
                    );
                }
            }
        }
    }
}
