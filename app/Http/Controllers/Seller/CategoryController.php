<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->paginate(10);

        $parentCategories = Category::whereNull('parent_id')->get();

        return view('seller.categories.index', compact('categories', 'parentCategories'));
    }
}
