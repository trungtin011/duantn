<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\HelpArticle;
use App\Models\HelpCategory;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    public function blog()
    {
        $post = Post::query();

        $categories = PostCategory::where('status', 'active')
            ->orderBy('title', 'ASC')
            ->get();

        $tags = PostTag::where('status', 'active')
            ->orderBy('title', 'ASC')
            ->get();

        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            $cat_ids = PostCategory::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            $post->whereIn('post_cat_id', $cat_ids);
        }
        if (!empty($_GET['tag'])) {
            $slug = explode(',', $_GET['tag']);
            $tag_ids = PostTag::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            $post->whereIn('post_tag_id', $tag_ids);
        }

        if (!empty($_GET['show'])) {
            $post = $post->where('status', 'active')->orderBy('id', 'DESC')->paginate($_GET['show']);
        } else {
            $post = $post->where('status', 'active')->orderBy('id', 'DESC')->paginate(9);
        }
        // $post=Post::where('status','active')->paginate(8);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('user.post.blog')->with('posts', $post)->with('recent_posts', $rcnt_post)->with('categories', $categories)->with('tags', $tags);
    }

    public function blogDetail($slug)
    {
        $post = Post::getPostBySlug($slug);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $userComment = null;
        if (Auth::check() && $post) {
            $userComment = Comment::where('post_id', $post->id)
                ->where('user_id', Auth::id())
                ->first();
        }
        return view('user.post.blog_detail')
            ->with('post', $post)
            ->with('recent_posts', $rcnt_post)
            ->with('userComment', $userComment);
    }

    public function blogSearch(Request $request)
    {
        // return $request->all();
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $categories = PostCategory::where('status', 'active')->orderBy('title', 'ASC')->get();
        $tags = PostTag::where('status', 'active')->orderBy('title', 'ASC')->get();
        $posts = Post::orwhere('title', 'like', '%' . $request->search . '%')
            ->orwhere('quote', 'like', '%' . $request->search . '%')
            ->orwhere('summary', 'like', '%' . $request->search . '%')
            ->orwhere('description', 'like', '%' . $request->search . '%')
            ->orwhere('slug', 'like', '%' . $request->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(8);
        return view('user.post.blog')->with('posts', $posts)->with('recent_posts', $rcnt_post)->with('categories', $categories)->with('tags', $tags);
    }

    public function blogFilter(Request $request)
    {
        $data = $request->all();
        // return $data;
        $catURL = "";
        if (!empty($data['category'])) {
            foreach ($data['category'] as $category) {
                if (empty($catURL)) {
                    $catURL .= '&category=' . $category;
                } else {
                    $catURL .= ',' . $category;
                }
            }
        }

        $tagURL = "";
        if (!empty($data['tag'])) {
            foreach ($data['tag'] as $tag) {
                if (empty($tagURL)) {
                    $tagURL .= '&tag=' . $tag;
                } else {
                    $tagURL .= ',' . $tag;
                }
            }
        }
        // return $tagURL;
        // return $catURL;
        return redirect()->route('blog', $catURL . $tagURL);
    }

    public function blogByCategory(Request $request)
    {
        $post = PostCategory::getBlogByCategory($request->slug);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $categories = PostCategory::where('status', 'active')->orderBy('title', 'ASC')->get();
        $tags = PostTag::where('status', 'active')->orderBy('title', 'ASC')->get();
        return view('user.post.blog')->with('posts', $post->post)->with('recent_posts', $rcnt_post)->with('categories', $categories)->with('tags', $tags);
    }

    public function blogByTag(Request $request)
    {
        // dd($request->slug);
        $post = Post::getBlogByTag($request->slug);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $categories = PostCategory::where('status', 'active')->orderBy('title', 'ASC')->get();
        $tags = PostTag::where('status', 'active')->orderBy('title', 'ASC')->get();
        return view('user.post.blog')->with('posts', $post)->with('recent_posts', $rcnt_post)->with('categories', $categories)->with('tags', $tags);
    }


    public function helpCenter()
    {
        $categories = HelpCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        return view('user.help.index', compact('categories'));
    }

    public function helpCategory($slug)
    {
        $category = HelpCategory::with([
            'children',
            'articles' => fn($q) => $q->where('status', 'active')
        ])->where('slug', $slug)->where('status', 'active')->firstOrFail();

        if ($category->children->count()) {
            return view('user.help.category', compact('category'));
        }

        if ($category->articles->count()) {
            return redirect()->route('help.detail', $category->articles->first()->slug);
        }

        abort(404, 'Không có nội dung.');
    }

    public function helpDetail($slug)
    {
        $article = HelpArticle::where('slug', $slug)->where('status', 'active')->firstOrFail();
        return view('user.help.detail', compact('article'));
    }
    public function ajaxHelpByCategory($slug)
    {
        $category = HelpCategory::where('slug', $slug)->with([
            'articles' => function ($q) {
                $q->where('status', 'active');
            }
        ])->firstOrFail();

        $html = view('user.help.partials.article_list', compact('category'))->render();

        return response()->json([
            'html' => $html
        ]);
    }


}
