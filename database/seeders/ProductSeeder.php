<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brandIds = DB::table('brands')->pluck('brand_id', 'name');
        $categoryIds = DB::table('categories')->pluck('category_id', 'name');

        $products = [
            [
                'name' => 'Dell Inspiron 15 3530',
                'category' => 'Laptops',
                'brand' => 'Dell',
                'description' => '15.6-inch productivity laptop with Intel Core i5 and fast SSD storage.',
                'compatibility' => null,
                'image_url' => '/storage/products/Dell/Dell Inspiron 16 Plus.jpg',
                'price' => 42999.00,
                'stock_qty' => 12,
                'low_stock_threshold' => 3,
                'is_archived' => 0,
            ],
            [
                'name' => 'HP Pavilion 14',
                'category' => 'Laptops',
                'brand' => 'HP',
                'description' => 'Lightweight 14-inch laptop for students and office work.',
                'compatibility' => null,
                'image_url' => '/storage/products/HP/HP OmniBook X Flip 16.jpg',
                'price' => 38999.00,
                'stock_qty' => 9,
                'low_stock_threshold' => 3,
                'is_archived' => 0,
            ],
            [
                'name' => 'Lenovo IdeaPad Slim 3',
                'category' => 'Laptops',
                'brand' => 'Lenovo',
                'description' => 'Everyday laptop with balanced performance and long battery life.',
                'compatibility' => null,
                'image_url' => '/storage/products/Lenovo/ThinkPad T14s Gen 6.jpg',
                'price' => 35999.00,
                'stock_qty' => 15,
                'low_stock_threshold' => 4,
                'is_archived' => 0,
            ],
            [
                'name' => 'ASUS TUF Gaming F15',
                'category' => 'Laptops',
                'brand' => 'ASUS',
                'description' => 'Gaming laptop with high-refresh display and dedicated graphics.',
                'compatibility' => null,
                'image_url' => '/storage/products/Acer/Swift Edge 16 AI.jpg',
                'price' => 68999.00,
                'stock_qty' => 6,
                'low_stock_threshold' => 2,
                'is_archived' => 0,
            ],
            [
                'name' => 'Acer Aspire 5 A515',
                'category' => 'Laptops',
                'brand' => 'Acer',
                'description' => 'Reliable mainstream laptop for school and business tasks.',
                'compatibility' => null,
                'image_url' => '/storage/products/MSI/MSI Raider 16 Max HX.jpg',
                'price' => 33999.00,
                'stock_qty' => 11,
                'low_stock_threshold' => 3,
                'is_archived' => 0,
            ],
            [
                'name' => 'MSI Modern 14',
                'category' => 'Laptops',
                'brand' => 'MSI',
                'description' => 'Portable ultrabook-style laptop with modern design and SSD.',
                'compatibility' => null,
                'image_url' => '/storage/products/MSI/MSI Prestige 14 AI Plus.jpg',
                'price' => 46999.00,
                'stock_qty' => 8,
                'low_stock_threshold' => 2,
                'is_archived' => 0,
            ],
            [
                'name' => 'Crucial DDR4 8GB 3200MHz SO-DIMM',
                'category' => 'Memory (RAM)',
                'brand' => 'Dell',
                'description' => '8GB laptop RAM upgrade module, DDR4 SO-DIMM form factor.',
                'compatibility' => 'Compatible with most DDR4 SO-DIMM laptop slots.',
                'image_url' => '/storage/products/Dell/Dell 16GB DDR5-5600 SODIMM.jpg',
                'price' => 1450.00,
                'stock_qty' => 35,
                'low_stock_threshold' => 8,
                'is_archived' => 0,
            ],
            [
                'name' => 'Kingston DDR4 16GB 3200MHz SO-DIMM',
                'category' => 'Memory (RAM)',
                'brand' => 'HP',
                'description' => '16GB RAM module for multitasking and faster responsiveness.',
                'compatibility' => 'For laptops supporting DDR4 3200MHz SO-DIMM.',
                'image_url' => '/storage/products/Dell/Dell 32GB DDR5-5600 SODIMM Kit (2x16).jpg',
                'price' => 2890.00,
                'stock_qty' => 22,
                'low_stock_threshold' => 6,
                'is_archived' => 0,
            ],
            [
                'name' => 'WD Blue SN570 500GB NVMe SSD',
                'category' => 'Storage (SSD/HDD)',
                'brand' => 'Lenovo',
                'description' => 'NVMe SSD for fast boot and app loading performance.',
                'compatibility' => 'M.2 2280 NVMe slot required.',
                'image_url' => '/storage/products/Lenovo/Lenovo 16GB DDR5-5600 SODIMM.jpg',
                'price' => 2499.00,
                'stock_qty' => 28,
                'low_stock_threshold' => 6,
                'is_archived' => 0,
            ],
            [
                'name' => 'Seagate 1TB 2.5 HDD',
                'category' => 'Storage (SSD/HDD)',
                'brand' => 'ASUS',
                'description' => 'Affordable 1TB 2.5-inch hard drive for laptop upgrades.',
                'compatibility' => '2.5-inch SATA bay required.',
                'image_url' => '/storage/products/Lenovo/Lenovo 32GB DDR5-5600 SODIMM.png',
                'price' => 1899.00,
                'stock_qty' => 19,
                'low_stock_threshold' => 5,
                'is_archived' => 0,
            ],
            [
                'name' => 'ASUS 65W Replacement Laptop Battery',
                'category' => 'Laptop Batteries',
                'brand' => 'ASUS',
                'description' => 'Replacement battery pack for selected ASUS laptop models.',
                'compatibility' => 'ASUS X and VivoBook series (selected variants).',
                'image_url' => '/storage/products/ASUS/ASUS Laptop Battery (Zenbook Vivobook series).jpg',
                'price' => 3299.00,
                'stock_qty' => 7,
                'low_stock_threshold' => 2,
                'is_archived' => 0,
            ],
            [
                'name' => 'Dell 56Wh Replacement Battery',
                'category' => 'Laptop Batteries',
                'brand' => 'Dell',
                'description' => 'OEM-style battery replacement for supported Dell laptops.',
                'compatibility' => 'Dell Inspiron and Vostro series (selected models).',
                'image_url' => '/storage/products/Lenovo/Lenovo ThinkPad Battery (61 72Wh class).jpg',
                'price' => 3599.00,
                'stock_qty' => 5,
                'low_stock_threshold' => 2,
                'is_archived' => 0,
            ],
            [
                'name' => 'HP 65W Smart AC Adapter',
                'category' => 'Chargers & Adapters',
                'brand' => 'HP',
                'description' => 'Official-style HP charger for everyday laptop charging.',
                'compatibility' => 'HP laptops with compatible barrel connector.',
                'image_url' => '/storage/products/HP/HP USB‑C 100W Power Adapter.jpg',
                'price' => 1699.00,
                'stock_qty' => 24,
                'low_stock_threshold' => 6,
                'is_archived' => 0,
            ],
            [
                'name' => 'Lenovo USB-C 65W Power Adapter',
                'category' => 'Chargers & Adapters',
                'brand' => 'Lenovo',
                'description' => 'USB-C power adapter for modern Lenovo and compatible laptops.',
                'compatibility' => 'USB-C PD laptops up to 65W input.',
                'image_url' => '/storage/products/Lenovo/Lenovo 140W USB‑C GaN Charger.png',
                'price' => 1999.00,
                'stock_qty' => 18,
                'low_stock_threshold' => 4,
                'is_archived' => 0,
            ],
            [
                'name' => 'Acer Dual-Fan Cooling Pad',
                'category' => 'Cooling Pads',
                'brand' => 'Acer',
                'description' => 'Quiet dual-fan cooling pad with adjustable stand height.',
                'compatibility' => 'Supports up to 15.6-inch laptops.',
                'image_url' => '/storage/products/ASUS/ASUS Laptop Battery (Zenbook Vivobook series).jpg',
                'price' => 1199.00,
                'stock_qty' => 30,
                'low_stock_threshold' => 8,
                'is_archived' => 0,
            ],
            [
                'name' => 'MSI RGB Cooling Pad',
                'category' => 'Cooling Pads',
                'brand' => 'MSI',
                'description' => 'High-airflow RGB cooling pad for gaming laptops.',
                'compatibility' => 'Supports up to 17-inch laptops.',
                'image_url' => '/storage/products/MSI/MSI Laptop Battery (Prestige Stealth class).png',
                'price' => 1799.00,
                'stock_qty' => 14,
                'low_stock_threshold' => 4,
                'is_archived' => 0,
            ],
            [
                'name' => 'Dell Latitude E7470 (Legacy)',
                'category' => 'Laptops',
                'brand' => 'Dell',
                'description' => 'Legacy corporate model kept for archived catalog testing.',
                'compatibility' => null,
                'image_url' => '/storage/products/Dell/Alienware 18 Area-51.jpg',
                'price' => 15999.00,
                'stock_qty' => 0,
                'low_stock_threshold' => 1,
                'is_archived' => 1,
            ],
        ];

        foreach ($products as $product) {
            $categoryId = $categoryIds[$product['category']] ?? null;
            $brandId = $brandIds[$product['brand']] ?? null;

            if (!$categoryId || !$brandId) {
                continue;
            }

            DB::table('products')->updateOrInsert(
                ['name' => $product['name']],
                [
                    'category_id' => $categoryId,
                    'brand_id' => $brandId,
                    'description' => $product['description'],
                    'compatibility' => $product['compatibility'],
                    'image_url' => $product['image_url'],
                    'price' => $product['price'],
                    'stock_qty' => $product['stock_qty'],
                    'low_stock_threshold' => $product['low_stock_threshold'],
                    'is_archived' => $product['is_archived'],
                ]
            );
        }
    }
}
