<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->paginate(10);

        $parentCategories = Category::whereNull('parent_id')->get();

        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image_path' => 'nullable|image|max:5120',
            'status' => 'nullable|in:active,inactive,deleted',
        ]);

        // Nếu slug chưa được nhập, tự động tạo từ name
        $validatedData = $request->all();
        $validatedData['slug'] = $request->slug ?? Str::slug($request->name);
        $validatedData['parent_id'] = $request->parent_id ?? NULL;

        if ($request->hasFile('image_path')) {
            $validatedData['image_path'] = $request->file('image_path')->store('categories', 'public');
        }

        try {
            Category::create($validatedData);
            return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được thêm!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi thêm danh mục.'])->withInput();
        }
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $parentCategories = Category::whereNull('parent_id')->where('id', '!=', $id)->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $id,
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image_path' => 'nullable|image|max:5120',
            'status' => 'nullable|in:active,inactive,deleted',
        ]);

        $validatedData = $request->all();
        $validatedData['parent_id'] = $request->parent_id ?? NULL;

        if ($request->hasFile('image_path')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            $validatedData['image_path'] = $request->file('image_path')->store('categories', 'public');
        }

        $category->update($validatedData);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        // Cập nhật danh mục con, đặt `parent_id = NULL`
        Category::where('parent_id', $category->id)->update(['parent_id' => NULL]);

        // Xóa danh mục cha
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa, các danh mục con đã trở thành danh mục độc lập!');
    }

    public function removeSubCategory($id)
    {
        $subCategory = Category::findOrFail($id);

        // Đặt parent_id = NULL để biến danh mục con thành danh mục độc lập
        $subCategory->update(['parent_id' => NULL]);

        return redirect()->back()->with('success', 'Danh mục con đã được gỡ bỏ và trở thành danh mục độc lập!');
    }
}
