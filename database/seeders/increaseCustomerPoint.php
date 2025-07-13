<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class increaseCustomerPoint extends Seeder
{

    public function run(): void
    {
        $customers = Customer::all();
        foreach ($customers as $customer) {
            $customer->total_points += 1000000;
            $customer->save();
        }
    }
}
