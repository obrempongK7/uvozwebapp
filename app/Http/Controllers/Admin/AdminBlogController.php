<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBlogController extends Controller {
    public function index() { $posts = BlogPost::with('author')->orderByDesc('created_at')->paginate(20); return view('admin.blog.index', compact('posts')); }
    public function create() { return view('admin.blog.create'); }
    public function store(Request $request) {
        $request->validate(['title'=>['required','string','max:200'],'content'=>['required','string']]);
        $data = $request->except('_token','featured_image');
        $data['slug'] = Str::slug($request->title);
        $data['author_id'] = \Auth::id();
        if ($request->hasFile('featured_image')) $data['featured_image'] = $request->file('featured_image')->store('blog','public');
        if ($request->status==='published') $data['published_at'] = now();
        BlogPost::create($data);
        return redirect()->route('admin.blog.index')->with('success','Post created.');
    }
    public function edit(BlogPost $post) { return view('admin.blog.edit', compact('post')); }
    public function update(Request $request, BlogPost $post) {
        $data = $request->except('_token','_method','featured_image');
        if ($request->hasFile('featured_image')) $data['featured_image'] = $request->file('featured_image')->store('blog','public');
        if ($request->status==='published'&&!$post->published_at) $data['published_at'] = now();
        $post->update($data);
        return redirect()->route('admin.blog.index')->with('success','Post updated.');
    }
    public function destroy(BlogPost $post) { $post->delete(); return back()->with('success','Post deleted.'); }
}
