<?php
namespace App\Http\Controllers;
use App\Models\BlogPost;

class BlogController extends Controller {
    public function index() { $posts = BlogPost::where('status','published')->orderByDesc('published_at')->paginate(12); return view('blog.index', compact('posts')); }
    public function show(string $slug) { $post = BlogPost::where('slug',$slug)->where('status','published')->firstOrFail(); $post->increment('views_count'); return view('blog.show', compact('post')); }
}
