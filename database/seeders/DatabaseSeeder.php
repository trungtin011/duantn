<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Bảng users
        DB::table('users')->insert([
            [
                'id' => 1,
                'username' => 'admin',
                'fullname' => 'Admin User',
                'phone' => '0901234567',
                'email' => 'Admin@gmail.com',
                'password' => Hash::make('123123123'),
                'status' => 'active',
                'gender' => 'male',
                'role' => 'admin',
                'avatar' => null,
                'is_verified' => 1,
                'birthday' => '1990-01-01',
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'username' => 'seller1',
                'fullname' => 'Seller One',
                'phone' => '0901234568',
                'email' => 'Seller@gmail.com',
                'password' => Hash::make('123123123'),
                'status' => 'active',
                'gender' => 'female',
                'role' => 'seller',
                'avatar' => null,
                'is_verified' => 1,
                'birthday' => '1995-05-05',
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'username' => 'Khoa Không Khoẻ',
                'fullname' => 'Y Khoa Êban',
                'phone' => null,
                'email' => 'Customer@gmail.com',
                'password' => Hash::make('123123123'),
                'status' => 'active',
                'gender' => 'male',
                'role' => 'customer',
                'avatar' => 'avatars/Vq5Uh3ncvujRPMQXWJi0F9UrM5lgMTbOysnqSCbw.jpg',
                'is_verified' => 0,
                'birthday' => null,
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng user_addresses
        DB::table('user_addresses')->insert([
            [
                'userID' => 3,
                'receiver_name' => 'Customer One',
                'receiver_phone' => '0901234569',
                'address' => '13 Lý Thái Tổ',
                'province' => 'Thành phố Hồ Chí Minh',
                'district' => 'Quận 1',
                'ward' => 'Tự Đức',
                'zip_code' => null,
                'address_type' => 'home',
                'note' => null,
                'is_default' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng customers
        DB::table('customers')->insert([
            [
                'userID' => 3,
                'ranking' => 'bronze',
                'preferred_payment_method' => 'cod',
                'total_orders' => 1,
                'total_spent' => 500000,
                'total_points' => 50,
                'last_order_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng business_licenses
        DB::table('business_licenses')->insert([
            [
                'business_license_number' => 'BL001',
                'tax_number' => 'TAX001',
                'business_ID' => 'BID001',
                'business_name' => 'Seller One Business',
                'business_license_date' => '2023-01-01',
                'expiry_date' => '2028-01-01',
                'status' => 'approved',
                'rejection_reason' => null,
                'verified_by' => 1,
                'verified_at' => Carbon::now(),
                'license_file_path' => '/licenses/bl001.pdf',
                'is_active' => 1,
                'business_type' => 'company',
                'invoice_email' => 'billing@seller1.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng sellers
        DB::table('sellers')->insert([
            [
                'userID' => 2,
                'status' => 'active',
                'identity_card' => 123456789,
                'identity_card_date' => '2020-01-01',
                'identity_card_place' => 'Hanoi',
                'bank_account' => null,
                'bank_name' => 'Vietcombank',
                'bank_account_name' => 'Seller One',
                'business_license_id' => 1,
                'identity_card_type' => 'cccd',
                'identity_card_image' => null,
                'identity_card_holding_image' => null,
                'privacy_policy_agreed' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng seller_registrations
        DB::table('seller_registrations')->insert([
            [
                'userID' => 2,
                'shop_data' => json_encode(['name' => 'Seller One Shop', 'email' => 'shop@seller1.com']),
                'shipping_options' => json_encode(['express' => true, 'cod_enabled' => true]),
                'business_data' => json_encode(['business_name' => 'Seller One Business']),
                'identity_data' => json_encode(['identity_card' => 123456789]),
                'status' => 'completed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng shops
        DB::table('shops')->insert([
            [
                'id' => 1,
                'ownerID' => 2,
                'shop_name' => 'Seller One Shop',
                'shop_phone' => '0901234568',
                'shop_email' => 'shop@seller1.com',
                'shop_description' => 'Quality products at affordable prices',
                'shop_rating' => 4.5,
                'total_ratings' => 10,
                'total_products' => 2,
                'total_sales' => 1000000,
                'total_followers' => 100,
                'opening_hours' => json_encode(['mon-fri' => '9:00-17:00']),
                'social_media_links' => json_encode(['facebook' => 'fb.com/seller1']),
                'shop_logo' => '/logos/seller1.png',
                'shop_banner' => '/banners/seller1.png',
                'shop_status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng shop_addresses
        DB::table('shop_addresses')->insert([
            [
                'shopID' => 1,
                'shop_address' => '456 Shop Street',
                'shop_province' => 'Hà Nội',
                'shop_district' => 'Hoàn Kiếm',
                'shop_ward' => 'Hàng Bạc',
                'note' => null,
                'is_default' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng shop_followers
        DB::table('shop_followers')->insert([
            [
                'shopID' => 1,
                'followerID' => 3,
                'notifications_enabled' => 1,
                'followed_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng shop_shipping_options
        DB::table('shop_shipping_options')->insert([
            [
                'shopID' => 1,
                'shipping_type' => 'express',
                'cod_enabled' => 1,
                'is_active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'shopID' => 1,
                'shipping_type' => 'economy',
                'cod_enabled' => 0,
                'is_active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng brand
        DB::table('brand')->insert([
            [
                'id' => 1,
                'name' => 'Brand A',
                'slug' => 'brand-a',
                'description' => 'Premium brand',
                'image_path' => '/brands/brand-a.png',
                'meta_title' => null,
                'meta_description' => null,
                'meta_keywords' => null,
                'status' => 'active',
                'parent_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng categories
        DB::table('categories')->insert([
            [
                'id' => 1,
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => null,
                'image_path' => null,
                'meta_title' => null,
                'meta_description' => null,
                'meta_keywords' => null,
                'status' => 'active',
                'parent_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng employees
        DB::table('employees')->insert([
            [
                'shopID' => 1,
                'userID' => 2,
                'position' => 'Manager',
                'salary' => 10000000,
                'hire_date' => '2023-01-01',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng products
        DB::table('products')->insert([
            [
                'id' => 1,
                'shopID' => 1,
                'name' => 'Smartphone X',
                'slug' => 'smartphone-x',
                'description' => 'High-end smartphone',
                'price' => 10000000,
                'purchase_price' => 8000000,
                'sale_price' => 9000000,
                'sold_quantity' => 10,
                'stock_total' => 50,
                'sku' => 'SPX001',
                'brand' => 'Brand A',
                'category' => 'Electronics',
                'status' => 'active',
                'meta_title' => null,
                'meta_description' => null,
                'meta_keywords' => null,
                'is_featured' => 1,
                'is_variant' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng product_variants
        DB::table('product_variants')->insert([
            [
                'id' => 1,
                'productID' => 1,
                'variant_name' => 'Black 128GB',
                'price' => 9000000,
                'purchase_price' => 7200000,
                'sale_price' => 8500000,
                'stock' => 30,
                'sku' => 'SPX001-BLACK',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng product_images
        DB::table('product_images')->insert([
            [
                'productID' => 1,
                'image_path' => '/products/smartphone-x-black.png',
                'variantID' => 1,
                'is_default' => 1,
                'display_order' => 1,
                'alt_text' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng product_dimensions
        DB::table('product_dimensions')->insert([
            [
                'productID' => 1,
                'variantID' => 1,
                'length' => null,
                'width' => null,
                'height' => null,
                'weight' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng cart
        DB::table('cart')->insert([
            [
                'userID' => 3,
                'productID' => 1,
                'variantID' => 1,
                'quantity' => 1,
                'price' => 8500000,
                'total_price' => 8500000,
                'session_id' => 'session123',
                'buying_flag' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng orders
        DB::table('orders')->insert([
            [
                'id' => 1,
                'userID' => 3,
                'order_code' => 'ORD001',
                'total_price' => 8500000,
                'coupon_id' => null,
                'coupon_discount' => 0,
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'order_status' => 'processing',
                'order_note' => null,
                'cancel_reason' => null,
                'paid_at' => null,
                'cancelled_at' => null,
                'delivered_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng order_addresses
        DB::table('order_addresses')->insert([
            [
                'order_id' => 1,
                'receiver_name' => 'Customer One',
                'receiver_phone' => '0901234569',
                'receiver_email' => null,
                'address' => '13 Lý Thái Tổ',
                'province' => 'Hà Nội',
                'district' => 'Hoàn Kiếm',
                'ward' => 'Hàng Bạc',
                'zip_code' => null,
                'note' => null,
                'address_type' => 'home',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng shop_order
        DB::table('shop_order')->insert([
            [
                'shopID' => 1,
                'orderID' => 1,
                'shipping_provider' => null,
                'shipping_fee' => null,
                'tracking_code' => null,
                'expected_delivery_date' => null,
                'actual_delivery_date' => null,
                'status' => 'pending',
                'note' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng items_order
        DB::table('items_order')->insert([
            [
                'orderID' => 1,
                'shop_orderID' => 1,
                'productID' => 1,
                'variantID' => 1,
                'product_name' => 'Smartphone X',
                'brand' => 'Brand A',
                'category' => 'Electronics',
                'attribute_value' => 'Black',
                'attribute_name' => 'Color',
                'product_image' => '/products/smartphone-x-black.png',
                'quantity' => 1,
                'unit_price' => 8500000,
                'total_price' => 8500000,
                'discount_amount' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng order_status_history
        DB::table('order_status_history')->insert([
            [
                'order_id' => 1,
                'status' => 'processing',
                'description' => 'Order is being processed',
                'shipping_provider' => null,
                'note' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng coupon
        DB::table('coupon')->insert([
            [
                'code' => 'DISC10',
                'name' => '10% Discount',
                'description' => null,
                'discount_value' => 10,
                'discount_type' => 'percentage',
                'max_discount_amount' => 100000,
                'min_order_amount' => 500000,
                'quantity' => 100,
                'max_uses_per_user' => 1,
                'max_uses_total' => 100,
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(30),
                'rank_limit' => 'all',
                'is_active' => 1,
                'is_public' => 1,
                'created_by' => 1,
                'shop_id' => 1,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng coupon_user
        DB::table('coupon_user')->insert([
            [
                'coupon_id' => 1,
                'user_id' => 3,
                'status' => 'available',
                'used_at' => null,
                'order_id' => null,
                'discount_amount' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng review
        DB::table('review')->insert([
            [
                'userID' => 3,
                'productID' => 1,
                'shopID' => 1,
                'rating' => 5,
                'comment' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng review_images
        DB::table('review_images')->insert([
            [
                'reviewID' => 1,
                'image_path' => '/reviews/review1.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng wishlist
        DB::table('wishlist')->insert([
            [
                'userID' => 3,
                'productID' => 1,
                'shopID' => 1,
                'note' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng view_history
        DB::table('view_history')->insert([
            [
                'userID' => 3,
                'productID' => 1,
                'shopID' => 1,
                'view_count' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng notifications
        DB::table('notifications')->insert([
            [
                'shop_id' => 1,
                'sender_id' => 1,
                'receiver_user_id' => 3,
                'receiver_shop_id' => null,
                'title' => 'Order Confirmation',
                'content' => 'Your order ORD001 has been confirmed.',
                'type' => 'order',
                'reference_id' => 1,
                'receiver_type' => 'user',
                'priority' => 'normal',
                'status' => 'pending',
                'is_read' => false,
                'read_at' => null,
                'expired_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng report
        DB::table('report')->insert([
            [
                'reporter_id' => 3,
                'product_id' => 1,
                'shop_id' => 1,
                'order_id' => null,
                'user_id' => null,
                'report_type' => 'product_violation',
                'report_content' => 'Product description inaccurate',
                'evidence' => json_encode(['image' => '/evidence/report1.png']),
                'priority' => 'medium',
                'status' => 'pending',
                'resolution' => null,
                'resolution_note' => null,
                'assigned_to' => null,
                'resolved_by' => null,
                'resolved_at' => null,
                'due_date' => null,
                'is_anonymous' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
