@extends('layouts.app')
@section('title','Edit Podcast')
@section('content')
<div class="max-w-2xl mx-auto">
<h1 class="text-2xl font-bold text-white mb-6">Edit Podcast</h1>
<div class="card p-6">
<form action="{{ route('podcast.update', $podcast) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
@csrf @method('PUT')
<div><label class="block text-sm font-medium text-slate-300 mb-2">Title *</label><input type="text" name="title" value="{{ $podcast->title }}" class="input-field" required></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Description</label><textarea name="description" rows="3" class="input-field resize-none">{{ $podcast->description }}</textarea></div>
<div class="grid sm:grid-cols-2 gap-4">
<div><label class="block text-sm font-medium text-slate-300 mb-2">Category</label><input type="text" name="category" value="{{ $podcast->category }}" class="input-field"></div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">Status</label><select name="status" class="input-field"><option value="active" {{ $podcast->status==='active'?'selected':'' }}>Active</option><option value="inactive" {{ $podcast->status==='inactive'?'selected':'' }}>Inactive</option><option value="draft" {{ $podcast->status==='draft'?'selected':'' }}>Draft</option></select></div>
</div>
<div><label class="block text-sm font-medium text-slate-300 mb-2">New Cover Artwork</label><input type="file" name="cover_artwork" accept="image/*" class="input-field"></div>
@if($podcast->cover_artwork)<div><p class="text-xs text-slate-400 mb-1">Current Cover</p><img src="{{ $podcast->cover_url }}" class="h-20 rounded-xl object-cover"></div>@endif
<div class="flex gap-4 pt-2">
<button type="submit" class="btn-primary">Save Changes</button>
<a href="{{ route('podcast.show', $podcast) }}" class="btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>
@endsection
