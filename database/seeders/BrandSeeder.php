<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Dell', 'HP', 'Lenovo', 'ASUS', 'Acer', 'MSI'] as $brandName) {
            DB::table('brands')->updateOrInsert(
                ['name' => $brandName],
                [
                    'name' => $brandName,
                    'is_active' => 1,
                ]
            );
        }
    }
}
