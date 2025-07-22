<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShopCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ShopCategoryController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->shop;
        $categories = $shop->shopCategories()->with('products')->get();

        return view('seller.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('seller.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        auth()->user()->shop->shopCategories()->create([
            'name' => $request->name,
        ]);

        return redirect()->route('seller.categories.index')->with('success', 'Tạo danh mục thành công');
    }

    public function edit(ShopCategory $category)
    {
        $this->authorizeCategory($category);

        $products = auth()->user()->shop->products;
        $selectedProducts = $category->products->pluck('id')->toArray();

        return view('seller.categories.edit', compact('category', 'products', 'selectedProducts'));
    }

    public function update(Request $request, ShopCategory $category)
    {
        $this->authorizeCategory($category);

        $request->validate([
            'name' => 'required|string|max:255',
            'product_ids' => 'array',
        ]);

        $category->update(['name' => $request->name]);
        $category->products()->sync($request->product_ids ?? []);

        return redirect()->route('seller.categories.index')->with('success', 'Cập nhật danh mục thành công');
    }

    public function destroy(ShopCategory $category)
    {
        $this->authorizeCategory($category);
        $category->delete();

        return redirect()->route('seller.categories.index')->with('success', 'Xoá danh mục thành công');
    }

    private function authorizeCategory(ShopCategory $category)
    {
        if ($category->shop_id !== auth()->user()->shop->id) {
            abort(403, 'Bạn không có quyền truy cập danh mục này');
        }
    }
}
