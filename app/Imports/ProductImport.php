<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'name'                => $row['name'],
            'price'               => $row['price'] ?? 0,
            'stock_qty'           => $row['stock_qty'] ?? 0,
            'low_stock_threshold' => $row['low_stock_threshold'] ?? 10,
            'category_id'         => $row['category_id'] ?? 1,
            'brand_id'            => $row['brand_id'] ?? 1,
            'image_url'           => $row['image_url'] ?? null,
            'description'         => $row['description'] ?? null,
            'compatibility'       => $row['compatibility'] ?? null,
            'is_archived'         => $row['is_archived'] ?? 0,
        ]);
    }
}
