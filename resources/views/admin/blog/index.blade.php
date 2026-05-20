@extends('layouts.admin')
@section('page_title','Blog Posts')
@section('content')
<div class="flex items-center justify-between mb-4">
<p class="text-slate-400 text-sm">{{ $posts->total() }} posts</p>
<a href="{{ route('admin.blog.create') }}" class="btn-primary text-sm py-2 px-4">+ New Post</a>
</div>
<div class="card overflow-hidden">
<table class="w-full"><thead class="border-b border-dark-border"><tr class="text-xs text-slate-400">
<th class="text-left px-5 py-3">Title</th><th class="text-left px-5 py-3">Author</th><th class="text-left px-5 py-3">Status</th><th class="text-left px-5 py-3">Views</th><th class="text-left px-5 py-3">Date</th><th class="text-left px-5 py-3">Actions</th>
</tr></thead>
<tbody>
@forelse($posts as $post)
<tr class="border-b border-dark-border table-row">
<td class="px-5 py-3"><p class="text-sm font-medium text-white">{{ Str::limit($post->title,50) }}</p><p class="text-xs text-slate-500">{{ $post->slug }}</p></td>
<td class="px-5 py-3 text-sm text-slate-400">{{ $post->author->display_name }}</td>
<td class="px-5 py-3">@if($post->status==='published')<span class="badge-active">Published</span>@elseif($post->status==='scheduled')<span class="badge-pending">Scheduled</span>@else<span class="text-xs text-slate-500">Draft</span>@endif</td>
<td class="px-5 py-3 text-sm text-slate-400">{{ number_format($post->views_count) }}</td>
<td class="px-5 py-3 text-xs text-slate-500">{{ $post->published_at?->format('M d, Y') ?? 'Draft' }}</td>
<td class="px-5 py-3"><div class="flex gap-2">
<a href="{{ route('admin.blog.edit',$post) }}" class="text-xs text-purple-400 hover:text-purple-300">Edit</a>
<form action="{{ route('admin.blog.destroy',$post) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-xs text-red-400 hover:text-red-300">Delete</button></form>
</div></td>
</tr>
@empty<tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">No blog posts yet</td></tr>@endforelse
</tbody></table>
<div class="px-5 py-4 border-t border-dark-border">{{ $posts->links() }}</div>
</div>
@endsection
