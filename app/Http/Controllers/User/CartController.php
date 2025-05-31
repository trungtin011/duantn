<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = [
            ['name' => 'Sản phẩm 1', 'price' => 150000, 'quantity' => 2, 'image' => 'product1.jpg'],
            ['name' => 'Sản phẩm 2', 'price' => 250000, 'quantity' => 1, 'image' => 'product2.jpg'],
        ];

       return view('user.cart', compact('cartItems'));

    }
}
