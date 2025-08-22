<?php

// Test file để kiểm tra việc render Blade template với old() helper

// Simulate Laravel's old() helper
function old($key, $default = null) {
    // Simulate session data
    $sessionData = [
        'product_type' => 'variant', // Test với giá trị variant
        // 'product_type' => null, // Test với giá trị null
    ];
    
    return $sessionData[$key] ?? $default;
}

// Test các trường hợp
echo "Test 1: old('product_type') = " . var_export(old('product_type'), true) . "\n";
echo "Test 2: old('product_type', 'simple') = " . var_export(old('product_type', 'simple'), true) . "\n";
echo "Test 3: old('product_type') === 'variant' = " . var_export(old('product_type') === 'variant', true) . "\n";
echo "Test 4: old('product_type', 'simple') === 'simple' = " . var_export(old('product_type', 'simple') === 'simple', true) . "\n";

// Test với giá trị null
echo "\nTest với giá trị null:\n";
$sessionData = ['product_type' => null];
function old2($key, $default = null) {
    global $sessionData;
    return $sessionData[$key] ?? $default;
}

echo "Test 5: old2('product_type') = " . var_export(old2('product_type'), true) . "\n";
echo "Test 6: old2('product_type', 'simple') = " . var_export(old2('product_type', 'simple'), true) . "\n";
echo "Test 7: old2('product_type') === 'variant' = " . var_export(old2('product_type') === 'variant', true) . "\n";
echo "Test 8: old2('product_type', 'simple') === 'simple' = " . var_export(old2('product_type', 'simple') === 'simple', true) . "\n";

// Test HTML output
echo "\nHTML Output:\n";
echo '<input type="radio" name="product_type" value="simple" ' . (old('product_type', 'simple') === 'simple' ? 'checked' : '') . '> Sản phẩm đơn<br>';
echo '<input type="radio" name="product_type" value="variant" ' . (old('product_type') === 'variant' ? 'checked' : '') . '> Sản phẩm có biến thể<br>';
