<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpArticle;
use App\Models\HelpCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HelpArticleController extends Controller
{
    public function index()
    {
        $articles = HelpArticle::with('category')->latest()->get();
        return view('admin.help_article.index', compact('articles'));
    }

    public function create()
    {
        $categories = HelpCategory::orderBy('title')->get();
        return view('admin.help_article.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'content' => 'required',
        ]);

        HelpArticle::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'content' => $request->content,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('help-article.index')->with('success', 'Thêm bài viết thành công');
    }

    public function edit($id)
    {
        $article = HelpArticle::findOrFail($id);
        $categories = HelpCategory::orderBy('title')->get();
        return view('admin.help_article.edit', compact('article', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $article = HelpArticle::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'content' => 'required',
        ]);

        $article->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'content' => $request->content,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('help-article.index')->with('success', 'Cập nhật bài viết thành công');
    }

    public function destroy($id)
    {
        HelpArticle::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Xóa bài viết thành công');
    }
}
