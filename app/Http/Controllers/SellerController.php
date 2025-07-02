<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class SellerController extends Controller
{
    public function profile($id)
    {
        $seller = User::with(['products.images'])->findOrFail($id);
        return view('user.seller.profile', compact('seller'));
    }
}

