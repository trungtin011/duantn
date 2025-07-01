<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostCategory;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public function index()
    {
        $postCategories = PostCategory::orderBy('id', 'DESC')->paginate(10);
        return view('admin.postcategory.index', compact('postCategories'));
    }

    public function create()
    {
        return view('admin.postcategory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'status' => 'required|in:active,inactive'
        ]);

        $data = $request->all();
        $slug = Str::slug($request->title);
        $count = PostCategory::where('slug', $slug)->count();
        if ($count > 0) {
            $slug .= '-' . time();
        }
        $data['slug'] = $slug;

        $status = PostCategory::create($data);
        return redirect()->route('post-categories.index')->with($status ? 'success' : 'error', $status ? 'Thêm thành công' : 'Thêm thất bại');
    }

    public function edit($id)
    {
        $postCategory = PostCategory::findOrFail($id);
        return view('admin.postcategory.edit', compact('postCategory'));
    }

    public function update(Request $request, $id)
    {
        $postCategory = PostCategory::findOrFail($id);

        $request->validate([
            'title' => 'required|string',
            'status' => 'required|in:active,inactive'
        ]);

        $postCategory->update($request->all());
        return redirect()->route('post-categories.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        $postCategory = PostCategory::findOrFail($id);
        $postCategory->delete();
        return redirect()->route('post-categories.index')->with('success', 'Xóa thành công');
    }
}
