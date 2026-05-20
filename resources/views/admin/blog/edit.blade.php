@extends('layouts.admin')
@section('page_title','Edit Post')
@section('content')
<div class="max-w-3xl mx-auto card p-6">
<form action="{{ route('admin.blog.update',$post) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
@csrf @method('PUT')
<div><label class="block text-xs text-slate-400 mb-1.5">Title *</label><input type="text" name="title" value="{{ $post->title }}" class="input-field" required></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Excerpt</label><textarea name="excerpt" rows="2" class="input-field resize-none">{{ $post->excerpt }}</textarea></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Content *</label><textarea name="content" rows="15" class="input-field resize-none" required>{{ $post->content }}</textarea></div>
<div class="grid sm:grid-cols-2 gap-4">
<div><label class="block text-xs text-slate-400 mb-1.5">Category</label><input type="text" name="category" value="{{ $post->category }}" class="input-field"></div>
<div><label class="block text-xs text-slate-400 mb-1.5">Status</label><select name="status" class="input-field">@foreach(['draft'=>'Draft','published'=>'Published'] as $v=>$l)<option value="{{ $v }}" {{ $post->status===$v?'selected':'' }}>{{ $l }}</option>@endforeach</select></div>
</div>
<div><label class="block text-xs text-slate-400 mb-1.5">New Featured Image</label><input type="file" name="featured_image" accept="image/*" class="input-field"></div>
@if($post->featured_image)<div><p class="text-xs text-slate-400 mb-1">Current</p><img src="{{ $post->featured_image_url }}" class="h-24 rounded-xl object-cover"></div>@endif
<div class="flex gap-4"><button type="submit" class="btn-primary">Update Post</button><a href="{{ route('admin.blog.index') }}" class="btn-secondary">Cancel</a></div>
</form>
</div>
@endsection
