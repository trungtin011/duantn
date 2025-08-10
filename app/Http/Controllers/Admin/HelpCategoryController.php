<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\HelpArticle;
use Illuminate\Support\Facades\Storage;

class HelpCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HelpCategory::with('parent')->orderBy('sort_order');

        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('status') && in_array($request->get('status'), ['active', 'inactive'], true)) {
            $query->where('status', $request->get('status'));
        }

        $categories = $query->paginate(15)->appends($request->query());

        if ($request->ajax()) {
            return view('admin.help_category.partials.table', compact('categories'));
        }

        return view('admin.help_category.index', compact('categories'));
    }

    public function ajaxList(Request $request)
    {
        // Reuse index logic for filtering/pagination and return partial
        return $this->index($request);
    }

    public function create()
    {
        $parents = HelpCategory::whereNull('parent_id')->get();
        return view('admin.help_category.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'parent_id' => $request->parent_id,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status ?? 'active',
        ];

        // Nếu có ảnh icon
        if ($request->hasFile('icon_file')) {
            $file = $request->file('icon_file');
            $filename = time() . '-' . $file->getClientOriginalName();
            $path = $file->storeAs('help-category-icons', $filename, 'public');
            $data['icon'] = $path;
        }

        HelpCategory::create($data);

        return redirect()->route('help-category.index')->with('success', 'Thêm danh mục thành công');
    }


    public function edit($id)
    {
        $category = HelpCategory::findOrFail($id);
        $parents = HelpCategory::whereNull('parent_id')->where('id', '!=', $id)->get();
        return view('admin.help_category.edit', compact('category', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $category = HelpCategory::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'parent_id' => $request->parent_id,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status ?? 'active',
        ];

        if ($request->hasFile('icon_file')) {
            // Delete old icon if exists
            if (!empty($category->icon) && Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }

            $file = $request->file('icon_file');
            $filename = time() . '-' . $file->getClientOriginalName();
            $path = $file->storeAs('help-category-icons', $filename, 'public');
            $data['icon'] = $path;
        }

        $category->update($data);

        return redirect()->route('help-category.index')->with('success', 'Cập nhật danh mục thành công');
    }

    public function destroy($id)
    {
        HelpCategory::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Xóa danh mục thành công');
    }
    public function ajaxDetail($slug)
    {
        $article = HelpArticle::where('slug', $slug)->where('status', 'active')->firstOrFail();

        return response()->view('user.help.ajax_detail', compact('article'));
    }


}
