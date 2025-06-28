<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\ProductDimension;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
    public function model(array $row)
    {
        $product = Product::updateOrCreate(
            ['sku' => $row['sku']],
            [
                'shopID' => $row['shopid'],
                'name' => $row['name'],
                'slug' => $row['slug'] ?? Str::slug($row['name']),
                'description' => $row['description'],
                'price' => $row['price'],
                'purchase_price' => $row['purchase_price'],
                'sale_price' => $row['sale_price'] ?? 0,
                'sold_quantity' => $row['sold_quantity'] ?? 0,
                'stock_total' => $row['stock_total'] ?? 0,
                'brand' => $row['brand'],
                'category' => $row['category'],
                'sub_category' => $row['sub_category'],
                'status' => $row['status'],
                'meta_title' => $row['meta_title'],
                'meta_description' => $row['meta_description'],
                'meta_keywords' => $row['meta_keywords'],
                'is_featured' => filter_var($row['is_featured'], FILTER_VALIDATE_BOOLEAN),
            ]
        );

        // Variant
        if (!empty($row['variant_sku'])) {
            $variant = ProductVariant::updateOrCreate(
                ['sku' => $row['variant_sku']],
                [
                    'productID' => $product->id,
                    'color' => $row['variant_color'] ?? null,
                    'color_code' => $row['variant_color_code'] ?? null,
                    'size' => $row['variant_size'] ?? null,
                    'variant_name' => $row['variant_name'] ?? null,
                    'price' => $row['variant_price'] ?? $row['price'],
                    'purchase_price' => $row['purchase_price'],
                    'sale_price' => $row['variant_sale_price'] ?? 0,
                    'stock' => $row['variant_stock'] ?? 0,
                    'status' => $row['variant_status'] ?? 'active',
                ]
            );
        }

        // Dimensions
        if (!empty($row['dimension_length'])) {
            ProductDimension::updateOrCreate(
                [
                    'productID' => $product->id,
                    'variantID' => $variant->id ?? null,
                ],
                [
                    'length' => $row['dimension_length'],
                    'width' => $row['dimension_width'],
                    'height' => $row['dimension_height'],
                    'weight' => $row['dimension_weight'],
                ]
            );
        }

        // Image
        if (!empty($row['image_path'])) {
            ProductImage::updateOrCreate(
                [
                    'productID' => $product->id,
                    'image_path' => $row['image_path'],
                ],
                [
                    'variantID' => $variant->id ?? null,
                    'is_default' => filter_var($row['image_is_default'], FILTER_VALIDATE_BOOLEAN),
                    'display_order' => $row['image_display_order'] ?? 1,
                    'alt_text' => $row['image_alt_text'] ?? '',
                ]
            );
        }

        return $product;
    }

    public function rules(): array
    {
        return [
            'shopid' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'brand' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'sub_category' => 'required|string|max:255',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
