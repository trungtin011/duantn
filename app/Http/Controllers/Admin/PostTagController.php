<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostTag;
use Illuminate\Support\Str;

class PostTagController extends Controller
{
    public function index()
    {
        $postTags = PostTag::orderBy('id','desc')->paginate(10);
        return view('admin.posttag.index', compact('postTags'));
    }

    public function create()
    {
        return view('admin.posttag.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'status' => 'required|in:active,inactive'
        ]);

        $slug = Str::slug($request->title);
        if (PostTag::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        PostTag::create([
            'title' => $request->title,
            'slug' => $slug,
            'status' => $request->status
        ]);

        return redirect()->route('post-tags.index')->with('success', 'Tag created successfully');
    }

    public function edit(PostTag $postTag)
    {
        return view('admin.posttag.edit', compact('postTag'));
    }

    public function update(Request $request, PostTag $postTag)
    {
        $request->validate([
            'title' => 'required|string',
            'status' => 'required|in:active,inactive'
        ]);

        $postTag->update($request->only('title', 'status'));

        return redirect()->route('post-tags.index')->with('success', 'Tag Cập nhật successfully');
    }

    public function destroy(PostTag $postTag)
    {
        $postTag->delete();

        return redirect()->route('post-tags.index')->with('success', 'Tag deleted successfully');
    }
}
