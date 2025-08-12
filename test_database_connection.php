<?php
// Test database connection và dữ liệu sản phẩm
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Product;

try {
    echo "<h1>Test Database Connection</h1>";
    
    // Test kết nối database
    echo "<h2>1. Database Connection Test</h2>";
    $pdo = DB::connection()->getPdo();
    echo "✅ Database connected successfully<br>";
    echo "Database: " . DB::connection()->getDatabaseName() . "<br><br>";
    
    // Test bảng products
    echo "<h2>2. Products Table Test</h2>";
    $productsCount = DB::table('products')->count();
    echo "Total products: {$productsCount}<br>";
    
    if ($productsCount > 0) {
        $sampleProduct = DB::table('products')->first();
        echo "Sample product:<br>";
        echo "- ID: {$sampleProduct->id}<br>";
        echo "- Name: {$sampleProduct->name}<br>";
        echo "- Price: {$sampleProduct->price}<br>";
        echo "- Sale Price: {$sampleProduct->sale_price}<br>";
        echo "- Status: {$sampleProduct->status}<br><br>";
    }
    
    // Test bảng product_variants
    echo "<h2>3. Product Variants Table Test</h2>";
    $variantsCount = DB::table('product_variants')->count();
    echo "Total variants: {$variantsCount}<br>";
    
    if ($variantsCount > 0) {
        $sampleVariant = DB::table('product_variants')->first();
        echo "Sample variant:<br>";
        echo "- ID: {$sampleVariant->id}<br>";
        echo "- Product ID: {$sampleVariant->productID}<br>";
        echo "- Price: {$sampleVariant->price}<br>";
        echo "- Sale Price: {$sampleVariant->sale_price}<br><br>";
    }
    
    // Test bảng product_images
    echo "<h2>4. Product Images Table Test</h2>";
    $imagesCount = DB::table('product_images')->count();
    echo "Total images: {$imagesCount}<br>";
    
    if ($imagesCount > 0) {
        $sampleImage = DB::table('product_images')->first();
        echo "Sample image:<br>";
        echo "- ID: {$sampleImage->id}<br>";
        echo "- Product ID: {$sampleImage->productID}<br>";
        echo "- Image Path: {$sampleImage->image_path}<br>";
        echo "- Is Default: {$sampleImage->is_default}<br><br>";
    }
    
    // Test query sản phẩm với variants
    echo "<h2>5. Product with Variants Query Test</h2>";
    $productsWithVariants = DB::table('products')
        ->leftJoin('product_variants', 'products.id', '=', 'product_variants.productID')
        ->select(
            'products.id',
            'products.name',
            'products.price as product_price',
            'products.sale_price as product_sale_price',
            'product_variants.price as variant_price',
            'product_variants.sale_price as variant_sale_price'
        )
        ->where('products.status', 'active')
        ->limit(5)
        ->get();
    
    echo "Products with variants (first 5):<br>";
    foreach ($productsWithVariants as $product) {
        echo "- ID: {$product->id}, Name: {$product->name}<br>";
        echo "  Product Price: {$product->product_price}, Sale: {$product->product_sale_price}<br>";
        echo "  Variant Price: {$product->variant_price}, Sale: {$product->variant_sale_price}<br><br>";
    }
    
    // Test Eloquent model
    echo "<h2>6. Eloquent Model Test</h2>";
    try {
        $product = Product::with(['variants', 'images'])->first();
        if ($product) {
            echo "Eloquent Product:<br>";
            echo "- ID: {$product->id}<br>";
            echo "- Name: {$product->name}<br>";
            echo "- Price: {$product->price}<br>";
            echo "- Sale Price: {$product->sale_price}<br>";
            echo "- Variants count: " . $product->variants->count() . "<br>";
            echo "- Images count: " . $product->images->count() . "<br>";
            
            if ($product->variants->isNotEmpty()) {
                echo "- First variant price: " . $product->variants->first()->price . "<br>";
                echo "- First variant sale price: " . $product->variants->first()->sale_price . "<br>";
            }
        }
    } catch (Exception $e) {
        echo "❌ Eloquent error: " . $e->getMessage() . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}
?>
