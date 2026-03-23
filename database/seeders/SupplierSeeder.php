<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $brands = Brand::all();

        foreach ($brands as $brand) {
            Supplier::updateOrCreate(
                ['name' => $brand->name . ' Authorized Supplier'],
                [
                    'contact_name' => $brand->name . ' Representative',
                    'contact_email' => 'contact@' . strtolower($brand->name) . 'supplier.com',
                    'contact_phone' => '1-800-' . strtoupper(substr($brand->name, 0, 4)) . '-SUPP',
                    'address' => '123 ' . $brand->name . ' Boulevard, Tech City',
                    'is_active' => 1,
                ]
            );
        }
    }
}
