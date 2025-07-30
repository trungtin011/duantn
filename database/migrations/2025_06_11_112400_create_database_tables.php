<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Bảng users
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username', 50)->unique();
            $table->string('fullname', 100);
            $table->string('phone', 255)->nullable()->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->enum('role', ['admin', 'customer', 'seller', 'employee']);
            $table->text('avatar')->nullable();
            $table->boolean('is_verified')->default(0);
            $table->date('birthday')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->index(['email', 'phone', 'username', 'status', 'role']);
        });

        // Bảng user_addresses
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('userID');
            $table->string('receiver_name', 100);
            $table->string('receiver_phone', 11);
            $table->string('address', 255);
            $table->string('province', 100);
            $table->string('district', 100);
            $table->string('ward', 100);
            $table->string('zip_code', 10)->nullable();
            $table->enum('address_type', ['home', 'office', 'other'])->default('home');
            $table->text('note')->nullable();
            $table->boolean('is_default')->default(0);
            $table->timestamps();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
        });

        // Bảng customers
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('userID');
            $table->enum('ranking', ['gold', 'silver', 'bronze', 'diamond'])->default('bronze');
            $table->enum('preferred_payment_method', ['cod', 'momo', 'zalopay'])->nullable();
            $table->integer('total_orders')->default(0);
            $table->decimal('total_spent', 12, 0)->default(0);
            $table->integer('total_points')->default(0);
            $table->timestamp('last_order_at')->nullable();
            $table->timestamps();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
        });

        // Bảng business_licenses
        Schema::create('business_licenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('business_license_number', 255)->unique();
            $table->string('tax_number', 255)->unique();
            $table->string('business_ID', 255)->unique();
            $table->string('business_name', 255);
            $table->date('business_license_date');
            $table->date('expiry_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('license_file_path');
            $table->boolean('is_active')->default(0);
            $table->enum('business_type', ['individual', 'household', 'company'])->default('individual');
            $table->string('invoice_email', 100)->nullable();
            $table->timestamps();
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
        });

        // Bảng sellers
        Schema::create('sellers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('userID');
            $table->enum('status', ['active', 'suspended', 'banned'])->default('active');
            $table->bigInteger('identity_card')->unique();
            $table->date('identity_card_date');
            $table->string('identity_card_place', 255);
            $table->string('bank_account', 255)->nullable()->unique();
            $table->string('bank_name', 255);
            $table->string('bank_account_name', 255);
            $table->unsignedBigInteger('business_license_id');
            $table->enum('identity_card_type', ['cccd', 'cmnd', 'passport'])->default('cccd');
            $table->text('identity_card_image')->nullable();
            $table->text('identity_card_holding_image')->nullable();
            $table->boolean('privacy_policy_agreed')->default(0)->nullable();
            $table->timestamps();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('business_license_id')->references('id')->on('business_licenses')->onDelete('cascade');
        });

        // Bảng identity_verifications
        Schema::create('identity_verifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('userID');
            $table->string('full_name', 100);
            $table->string('identity_number', 20)->unique();
            $table->date('birth_date');
            $table->string('nationality', 100)->default('Vietnam');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('hometown', 255);
            $table->string('residence', 255);
            $table->enum('identity_type', ['cccd', 'cmnd'])->default('cccd');
            $table->date('identity_card_date');
            $table->string('identity_card_place', 255);
            $table->text('identity_card_image');
            $table->text('identity_card_holding_image');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['status', 'identity_number']);
        });

        // Bảng seller_registrations
        Schema::create('seller_registrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('userID');
            $table->json('shop_data');
            $table->json('shipping_options');
            $table->json('business_data');
            $table->json('identity_data');
            $table->enum('status', ['pending', 'completed', 'abandoned'])->default('pending');
            $table->timestamps();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
        });

        // Bảng shops
        Schema::create('shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ownerID');
            $table->string('shop_name', 100);
            $table->string('shop_phone', 11);
            $table->string('shop_email', 100);
            $table->text('shop_description');
            $table->decimal('shop_rating', 3, 2)->default(0.00);
            $table->integer('total_ratings')->default(0);
            $table->integer('total_products')->default(0);
            $table->decimal('total_sales', 12, 2)->default(0.00);
            $table->integer('total_followers')->default(0);
            $table->json('opening_hours')->nullable();
            $table->json('social_media_links')->nullable();
            $table->text('shop_logo');
            $table->text('shop_banner');
            $table->enum('shop_status', ['active', 'inactive', 'banned'])->default('active');
            $table->timestamps();
            $table->foreign('ownerID')->references('id')->on('users')->onDelete('cascade');
            $table->index(['shop_status', 'shop_rating']);
        });

        // Bảng shop_addresses
        Schema::create('shop_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shopID');
            $table->string('shop_address', 255);
            $table->string('shop_province', 100);
            $table->string('shop_district', 100);
            $table->string('shop_ward', 100);
            $table->text('note')->nullable();
            $table->boolean('is_default')->default(0);
            $table->timestamps();
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
        });

        // Bảng shop_followers
        Schema::create('shop_followers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shopID');
            $table->unsignedBigInteger('followerID');
            $table->boolean('notifications_enabled')->default(1);
            $table->timestamp('followed_at')->useCurrent();
            $table->timestamps();
            $table->unique(['shopID', 'followerID'], 'shop_followers_unique');
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('followerID')->references('id')->on('users')->onDelete('cascade');
        });

        // Bảng shop_shipping_options
        Schema::create('shop_shipping_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shopID');
            $table->enum('shipping_type', ['express', 'fast', 'economy', 'self_pickup', 'bulky']);
            $table->boolean('cod_enabled')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->unique(['shopID', 'shipping_type'], 'shop_shipping_unique');
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
        });

        // Bảng brand
        Schema::create('brand', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->text('description');
            $table->text('image_path');
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted']);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('brand')->onDelete('cascade');
            $table->index(['name', 'slug']);
        });

        // Bảng categories
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->text('description')->nullable();
            $table->text('image_path')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted']);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            $table->index(['name', 'slug']);
        });

        // Bảng employees
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shopID');
            $table->unsignedBigInteger('userID');
            $table->string('position', 50);
            $table->decimal('salary', 12, 2);
            $table->date('hire_date');
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->timestamps();
            $table->unique(['shopID', 'userID'], 'employees_unique');
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
        });

        // Bảng products
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shopID')->nullable();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->longText('description');
            $table->decimal('price', 12, 0);
            $table->decimal('purchase_price', 12, 0);   
            $table->decimal('sale_price', 12, 0); 
            $table->integer('sold_quantity');
            $table->integer('stock_total');
            $table->string('sku', 100)->nullable()->unique();
            $table->string('brand', 100);
            $table->string('category', 100);
            $table->enum('status', ['active', 'out_of_stock', 'deleted']);
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_variant')->default(0);
            $table->timestamps();
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
            $table->index(['name', 'category', 'brand', 'status']);
        });

        Schema::create('attributes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->timestamps();
        });

        Schema::create('attribute_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('attribute_id');
            $table->string('value', 100);
            $table->timestamps();
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
        });

        Schema::create('product_attribute', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('attribute_id');
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->unique(['product_id', 'attribute_id'], 'product_attribute_unique');
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('productID');
            $table->string('variant_name', 100);
            $table->decimal('price', 12, 0);
            $table->decimal('purchase_price', 12, 0);
            $table->decimal('sale_price', 12, 0);
            $table->integer('stock');
            $table->string('sku', 100)->nullable()->unique();
            $table->enum('status', ['active', 'out_of_stock', 'deleted', 'draft']);
            $table->timestamps();

            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::create('product_variant_attribute_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_variant_id');
            $table->unsignedBigInteger('attribute_value_id');
            $table->timestamps();
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->onDelete('cascade');
            $table->unique(['product_variant_id', 'attribute_value_id'], 'variant_attr_value_unique');
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('productID');
            $table->text('image_path');
            $table->unsignedBigInteger('variantID')->nullable();
            $table->boolean('is_default')->default(0);
            $table->integer('display_order')->default(0);
            $table->string('alt_text', 100)->nullable();
            $table->timestamps();
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variantID')->references('id')->on('product_variants')->onDelete('cascade');
        });

        Schema::create('product_dimensions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('productID');
            $table->unsignedBigInteger('variantID')->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->timestamps();
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variantID')->references('id')->on('product_variants')->onDelete('cascade');
        });

        Schema::create('cart', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('userID');
            $table->unsignedBigInteger('productID');
            $table->unsignedBigInteger('variantID')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 12, 0);
            $table->decimal('total_price', 12, 0);
            $table->string('session_id', 100);
            $table->boolean('buying_flag', false);
            $table->timestamps();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variantID')->references('id')->on('product_variants')->onDelete('cascade');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('userID')->nullable();
            $table->string('order_code', 100)->unique();
            $table->decimal('total_price', 12, 2);
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->decimal('coupon_discount', 12, 2)->default(0.00);
            $table->string('payment_method', 100);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded']);
            $table->enum('order_status', [
                'pending',
                'partially_confirmed',
                'confirmed',
                'partially_ready_to_pick',
                'ready_to_pick',
                'partially_picked',
                'picked',
                'partially_shipping',
                'shipping',
                'partially_delivered',
                'delivered',
                'cancelled',
                'shipping_failed',
                'returned',
                'completed'
            ])->default('pending');
            $table->text('order_note')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->foreign('userID')->references('id')->on('users')->onDelete('set null');
            $table->index(['order_code', 'payment_status', 'order_status', 'created_at']);
        });

        Schema::create('order_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->string('receiver_name', 100);
            $table->string('receiver_phone', 11);
            $table->string('receiver_email', 100)->nullable();
            $table->string('address', 255);
            $table->string('province', 100);
            $table->string('district', 100);
            $table->string('ward', 100);
            $table->string('zip_code', 10)->nullable();
            $table->text('note')->nullable();
            $table->enum('address_type', ['home', 'office', 'other'])->default('home');
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });

        Schema::create('shop_order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shopID');
            $table->unsignedBigInteger('orderID');
            $table->string('code', 255)->nullable();
            $table->string('shipping_provider', 255)->nullable();
            $table->string('shipping_fee', 255)->nullable();
            $table->string('tracking_code', 255)->nullable();
            $table->dateTime('expected_delivery_date')->nullable();
            $table->dateTime('actual_delivery_date')->nullable();
            $table->enum('status', [
                'pending',
                'confirmed',
                'ready_to_pick',
                'picked',
                'shipping',
                'delivered',
                'cancelled',
                'shipping_failed',
                'returned',
                'completed'
            ])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('orderID')->references('id')->on('orders')->onDelete('cascade');
        });
        Schema::create('history_order_shop', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_order_id');
            $table->enum('status', [
                'pending',
                'confirmed',
                'ready_to_pick',
                'picked',
                'shipping',
                'delivered',
                'cancelled',
                'shipping_failed',
                'returned',
                'completed'
            ])->default('pending');
            $table->string('description')->nullable();
            $table->string('note')->nullable();
            $table->foreign('shop_order_id')->references('id')->on('shop_order')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('items_order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('orderID');
            $table->unsignedBigInteger('shop_orderID');
            $table->unsignedBigInteger('productID');
            $table->unsignedBigInteger('variantID');
            $table->string('product_name', 255)->nullable();
            $table->string('brand', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->string('variant_name', 255)->nullable();
            $table->text('product_image')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
            $table->decimal('discount_amount', 12, 2)->default(0.00)->nullable();
            $table->timestamps();
            $table->foreign('orderID')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('shop_orderID')->references('id')->on('shop_order')->onDelete('cascade');
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variantID')->references('id')->on('product_variants')->onDelete('cascade');
            $table->index(['orderID', 'shop_orderID', 'productID', 'variantID', 'created_at'], 'items_order_idx');
        });

        // Bảng order_status_history
        Schema::create('order_status_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->enum('order_status', [
                'pending',
                'partially_confirmed',
                'confirmed',
                'partially_ready_to_pick',
                'ready_to_pick',
                'partially_picked',
                'picked',
                'partially_shipping',
                'shipping',
                'partially_delivered',
                'delivered',
                'cancelled',
                'shipping_failed',
                'returned',
                'completed'
            ])->default('pending');
            $table->string('description')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });

        // Bảng coupon
        Schema::create('coupon', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 100)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->decimal('discount_value', 12, 2);
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('max_discount_amount', 12, 2)->nullable();
            $table->decimal('min_order_amount', 12, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('max_uses_per_user')->nullable();
            $table->integer('max_uses_total')->nullable();
            $table->integer('used_count')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('rank_limit', ['gold', 'silver', 'bronze', 'diamond', 'all'])->default('all');
            $table->boolean('is_active')->default(1);
            $table->boolean('is_public')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired', 'deleted'])->default('active');
            $table->string('image', 255)->nullable();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->index(['code', 'status', 'start_date', 'end_date']);
        });

        Schema::create('coupon_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('coupon_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['available', 'used', 'expired'])->default('available');
            $table->timestamp('used_at')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->decimal('discount_amount', 12, 2)->nullable();
            $table->timestamps();
            $table->unique(['coupon_id', 'user_id'], 'coupon_user_unique');
            $table->foreign('coupon_id')->references('id')->on('coupon')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->index('status');
        });

        // Bảng review
        // Schema::create('reviews', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->unsignedBigInteger('userID');
        //     $table->unsignedBigInteger('productID');
        //     $table->unsignedBigInteger('shopID');
        //     $table->integer('rating');
        //     $table->text('comment')->nullable();
        //     $table->timestamps();
        //     $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
        //     $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
        //     $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
        // });

        // Schema::create('product_reviews', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->foreignId('user_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('product_id')->constrained()->onDelete('cascade');
        //     $table->tinyInteger('rating')->comment('1-5 sao');
        //     $table->text('comment')->nullable();
        //     $table->string('image_path')->nullable();
        //     $table->string('video_path')->nullable();
        //     $table->timestamps();

        //     $table->unique(['user_id', 'product_id']);
        // });
        // // Bảng review_images
        // Schema::create('review_images', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->unsignedBigInteger('review_id');
        //     $table->text('image_path');
        //     $table->timestamps();

        //     // Sửa lại tên bảng được tham chiếu đúng
        //     $table->foreign('review_id')->references('id')->on('product_reviews')->onDelete('cascade');
        // });


        // Bảng wishlist
        Schema::create('wishlist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('userID');
            $table->unsignedBigInteger('productID');
            $table->unsignedBigInteger('shopID');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->unique(['userID', 'productID'], 'wishlist_unique');
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
        });

        // Bảng view_history
        Schema::create('view_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('userID');
            $table->unsignedBigInteger('productID');
            $table->unsignedBigInteger('shopID');
            $table->integer('view_count')->default(1);
            $table->timestamps();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
        });

        // Bảng notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->string('title', 100);
            $table->text('content');
            $table->string('type', 100);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->enum('receiver_type', ['user', 'shop', 'all', 'admin', 'employee']);
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            $table->enum('status', ['pending', 'active', 'inactive', 'failed'])->default('pending');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['type', 'status', 'priority', 'receiver_type', 'created_at'], 'notif_type_status_idx');
        });

        Schema::create('notification_receiver', function (Blueprint $table) {
            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('receiver_id');
            $table->enum('receiver_type', ['user', 'shop', 'all', 'admin', 'employee']);
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade');
            $table->primary(['notification_id', 'receiver_id']);
        });

        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shopID');
            $table->unsignedBigInteger('productID');
            $table->unsignedBigInteger('variantID')->nullable();
            $table->integer('quantity');
            $table->enum('type', ['import', 'export', 'adjustment']);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variantID')->references('id')->on('product_variants')->onDelete('cascade');
        });

        Schema::create('report', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reporter_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('report_type', ['product_violation', 'shop_violation', 'order_issue', 'user_violation', 'fake_product', 'copyright', 'other']);
            $table->text('report_content');
            $table->json('evidence')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'under_review', 'processing', 'resolved', 'rejected'])->default('pending');
            $table->enum('resolution', ['accepted', 'rejected', 'warning_issued', 'suspended', 'banned'])->nullable();
            $table->text('resolution_note')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('resolved_by')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->boolean('is_anonymous')->default(0);
            $table->timestamps();
            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['report_type', 'status', 'priority', 'created_at']);
        });

        Schema::create('point_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('userID');
            $table->integer('points');
            $table->enum('type', ['checkin', 'order', 'bonus', 'redeem']);
            $table->string('description', 255)->nullable();
            $table->unsignedBigInteger('orderID')->nullable();
            $table->timestamps();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('orderID')->references('id')->on('orders')->onDelete('set null');
        });

        // Bảng cache
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner', 255);
            $table->integer('expiration');
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid', 255)->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name', 255);
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token', 255);
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity');
            $table->index(['user_id', 'last_activity']);
        });

        // Cập nhật dữ liệu cho bảng sellers
        DB::table('sellers')->where('bank_account', '')->update(['bank_account' => null]);
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('point_transactions');
        Schema::dropIfExists('report');
        Schema::dropIfExists('stock_transactions');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('view_history');
        Schema::dropIfExists('wishlist');
        Schema::dropIfExists('review_images');
        Schema::dropIfExists('review');
        Schema::dropIfExists('coupon_user');
        Schema::dropIfExists('coupon');
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('items_order');
        Schema::dropIfExists('order_addresses');
        Schema::dropIfExists('shop_order');
        Schema::dropIfExists('history_order_shop');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart');
        Schema::dropIfExists('product_dimensions');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_variant_attribute_values');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_attribute');
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('products');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brand');
        Schema::dropIfExists('shop_shipping_options');
        Schema::dropIfExists('shop_followers');
        Schema::dropIfExists('shop_addresses');
        Schema::dropIfExists('shops');
        Schema::dropIfExists('seller_registrations');
        Schema::dropIfExists('identity_verifications');
        Schema::dropIfExists('sellers');
        Schema::dropIfExists('business_licenses');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('users');
    }
};
