<?php

namespace App\Imports;

use App\Models\ProductImage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImageImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ProductImage([
            'product_id' => $row['product_id'],
            'image_url'  => $row['image_url'],
            'sort_order' => $row['sort_order'] ?? 0,
        ]);
    }
}
