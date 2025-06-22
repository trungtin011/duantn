<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::getAllPost();
        // return $posts;
        return view('admin.post.index')->with('posts', $posts);
    }

    public function create()
    {
        $categories = PostCategory::where('status', 'active')->get();
        $tags = PostTag::where('status', 'active')->get();
        $users = User::all();
        return view('admin.post.create', compact('categories', 'tags', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'summary' => 'required|string',
            'description' => 'nullable|string',
            'quote' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'tags' => 'nullable',
            'post_cat_id' => 'required|exists:post_categories,id',
            'post_tag_id' => 'nullable|exists:post_tags,id',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        $slug = Str::slug($request->title);
        $count = Post::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
        }
        $data['slug'] = $slug;


        $tags = $request->input('tags');
        if (is_array($tags)) {
            $data['tags'] = implode(',', $tags);
        } else {
            $data['tags'] = '';
        }
        $data['added_by'] = auth()->user()->id;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/posts'), $filename);
            $data['photo'] = 'uploads/posts/' . $filename;
        }

        $post = Post::create($data);

        if ($post) {
            return redirect()->route('post.index')->with('success', 'Bài viết đã được tạo thành công.');
        } else {
            return back()->with('error', 'Tạo bài viết thất bại, vui lòng thử lại.');
        }
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = PostCategory::all();
        $tags = PostTag::all();
        $users = User::all(); // lấy danh sách tác giả

        return view('admin.post.edit', compact('post', 'categories', 'tags', 'users'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'quote' => 'nullable|string',
            'description' => 'nullable|string',
            'post_cat_id' => 'required|exists:post_categories,id',
            'tags' => 'nullable|array',
            'added_by' => 'required|exists:users,id',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $post = Post::findOrFail($id);
        $data = $request->all();

        // Xử lý slug
        $slug = \Str::slug($request->title);
        if (Post::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug .= '-' . time();
        }
        $data['slug'] = $slug;

        // Xử lý tags
        $data['tags'] = $request->tags ? implode(',', $request->tags) : '';

        // Xử lý ảnh
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('uploads/posts', $name, 'public');
            $data['photo'] = 'storage/' . $path;
        }

        $post->update($data);

        return redirect()->route('post.index')->with('success', 'Cập nhật bài viết thành công!');
    }


    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('post.index')->with('success', 'Post deleted successfully!');
    }
}
use App\Models\Review;

for ($i = 0; $i < 5; $i++) {
    Review::create([
        'userID' => 1,
        'productID' => 4,
        'shopID' => 1,        // ID shop có thật
        'rating' => 5,
        'comment' => 'Sản phẩm rất tốt!',
        'created_at' => now()->subDays(rand(0, 4)), // trong 5 ngày gần đây
    ]);
}
