<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Laptops', 'description' => 'Portable computers for work, school, and gaming.'],
            ['name' => 'Memory (RAM)', 'description' => 'Laptop memory modules for performance upgrades.'],
            ['name' => 'Storage (SSD/HDD)', 'description' => 'Internal storage drives and replacements.'],
            ['name' => 'Laptop Batteries', 'description' => 'Replacement batteries for supported laptop models.'],
            ['name' => 'Chargers & Adapters', 'description' => 'Power adapters and charging accessories.'],
            ['name' => 'Accessories', 'description' => 'Miscellaneous laptop accessories and peripherals.'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['name' => $category['name']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_active' => 1,
                ]
            );
        }
    }
}
