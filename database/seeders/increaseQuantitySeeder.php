<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class increaseQuantitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        foreach ($products as $product) {
            $product->stock_total = 500;
            $product->save();
            $product->variants->each(function ($variant) {
                $variant->stock = 500;
                $variant->save();
            });
        }
    }
}
