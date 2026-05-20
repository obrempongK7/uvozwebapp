@extends('layouts.admin')
@section('page_title','Status Posts')
@section('content')
<div class="card overflow-hidden">
<table class="w-full"><thead class="border-b border-dark-border"><tr class="text-xs text-slate-400"><th class="text-left px-5 py-3">Post</th><th class="text-left px-5 py-3">User</th><th class="text-left px-5 py-3">Type</th><th class="text-left px-5 py-3">Views</th><th class="text-left px-5 py-3">Date</th><th class="text-left px-5 py-3">Actions</th></tr></thead>
<tbody>
@forelse($posts as $post)
<tr class="border-b border-dark-border table-row">
<td class="px-5 py-3"><p class="text-sm text-white truncate max-w-48">{{ Str::limit($post->content ?? 'Media post', 60) }}</p></td>
<td class="px-5 py-3"><div class="flex items-center gap-2"><img src="{{ $post->user->avatar_url }}" class="w-7 h-7 rounded-full object-cover"><span class="text-sm text-white">{{ $post->user->display_name }}</span></div></td>
<td class="px-5 py-3"><span class="badge badge-purple text-xs">{{ ucfirst($post->type) }}</span></td>
<td class="px-5 py-3 text-sm text-slate-400">{{ number_format($post->views_count) }}</td>
<td class="px-5 py-3 text-xs text-slate-500">{{ $post->created_at->format('M d, Y') }}</td>
<td class="px-5 py-3"><form action="{{ route('admin.content.status.delete',$post) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-xs text-red-400 hover:text-red-300">Delete</button></form></td>
</tr>
@empty<tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">No status posts</td></tr>@endforelse
</tbody></table>
<div class="px-5 py-4 border-t border-dark-border">{{ $posts->links() }}</div>
</div>
@endsection
