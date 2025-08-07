<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ShopCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ShopCategoryController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        $categories = $shop->shopCategories()->with('products')->paginate(10);
        $products = $shop->products;
        $selectedProducts = []; // danh mục mới chưa có sản phẩm chọn

        return view('seller.categories.index', compact('categories', 'products', 'selectedProducts'));
    }

    public function create()
    {
        $shop = Auth::user()->shop;
        $products = $shop->products;
        $selectedProducts = []; // danh mục mới chưa có sản phẩm

        return view('seller.categories.create', compact('products', 'selectedProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_ids' => 'array',
        ]);

        $shop = Auth::user()->shop;

        $category = $shop->shopCategories()->create([
            'name' => $request->name,
        ]);

        $category->products()->sync($request->product_ids ?? []);

        return redirect()->route('seller.categories.index')->with('success', 'Tạo danh mục thành công');
    }


    public function edit(ShopCategory $category)
    {
        if ($category->shop_id !== Auth::user()->shop->id) {
            abort(403, 'Bạn không có quyền truy cập danh mục này');
        }

        $products = Auth::user()->shop->products;
        $selectedProducts = $category->products->pluck('id')->toArray();

        return view('seller.categories.edit', compact('category', 'products', 'selectedProducts'));
    }

    public function update(Request $request, ShopCategory $category)
    {
        if ($category->shop_id !== Auth::user()->shop->id) {
            abort(403, 'Bạn không có quyền truy cập danh mục này');
        }

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
        if ($category->shop_id !== Auth::user()->shop->id) {
            abort(403, 'Bạn không có quyền truy cập danh mục này');
        }

        $category->delete();

        return redirect()->route('seller.categories.index')->with('success', 'Xoá danh mục thành công');
    }
}
