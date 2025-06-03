<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            BusinessLicensesTableSeeder::class,
            SellersTableSeeder::class,
            ShopsTableSeeder::class,
            CategoriesTableSeeder::class,
            BrandsTableSeeder::class,
            ProductsTableSeeder::class,
            ProductVariantsTableSeeder::class,
            UserAddressesTableSeeder::class,
            CartTableSeeder::class,
            OrdersTableSeeder::class,
            OrderItemsTableSeeder::class,
            OrderAddressesTableSeeder::class,
        ]);
    }
}