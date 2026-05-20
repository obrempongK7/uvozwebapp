@extends('layouts.admin')
@section('page_title','New Blog Post')
@section('content')
<div class="max-w-3xl mx-auto card p-6">
<form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
@csrf
<div><label class="block text-xs text-slate-400 mb-1.5">Title *</label><input type="text" name="title" class="input-field" required></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Excerpt</label><textarea name="excerpt" rows="2" class="input-field resize-none"></textarea></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Content *</label><textarea name="content" rows="15" class="input-field resize-none" required></textarea></div>
<div class="grid sm:grid-cols-2 gap-4">
<div><label class="block text-xs text-slate-400 mb-1.5">Category</label><input type="text" name="category" class="input-field" placeholder="Tips, News, Updates..."></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Status</label><select name="status" class="input-field"><option value="draft">Draft</option><option value="published">Publish Now</option></select></div>
</div>
<div><label class="block text-xs text-slate-400 mb-1.5">Featured Image</label><input type="file" name="featured_image" accept="image/*" class="input-field"></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Meta Description</label><textarea name="meta_description" rows="2" class="input-field resize-none"></textarea></div>
<div class="flex gap-4"><button type="submit" class="btn-primary">Save Post</button><a href="{{ route('admin.blog.index') }}" class="btn-secondary">Cancel</a></div>
</form>
</div>
@endsection
