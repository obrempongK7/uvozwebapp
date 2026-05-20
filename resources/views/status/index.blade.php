@extends('layouts.app')
@section('title','Status Hub')
@section('content')
<div class="max-w-2xl mx-auto">
<div class="flex items-center justify-between mb-4">
<h1 class="text-2xl font-bold text-white">Status Hub</h1>
</div>
<!-- Create Status -->
<div class="card p-4 mb-6" x-data="{ type: 'text', open: false }">
<button @click="open=!open" class="flex items-center gap-3 w-full text-left">
<img src="{{ auth()->user()->avatar_url }}" class="w-10 h-10 rounded-full object-cover">
<span class="text-slate-400 flex-1">Share a status update...</span>
<span class="btn-primary py-2 px-4 text-sm">Post</span>
</button>
<div x-show="open" x-transition class="mt-4 border-t border-dark-border pt-4">
<form id="statusForm" class="space-y-4">
<div class="flex gap-2">
@foreach(['text'=>'📝 Text','image'=>'📸 Image','video'=>'🎥 Video','link'=>'🔗 Link'] as $v=>$l)
<button type="button" @click="type='{{ $v }}'" class="px-3 py-1.5 rounded-xl text-xs font-medium transition-all" :class="type==='{{ $v }}'?'gradient-bg text-white':'btn-secondary'">{{ $l }}</button>
@endforeach
</div>
<textarea name="content" placeholder="What's on your mind?" class="input-field resize-none" rows="3"></textarea>
<div x-show="type==='image'||type==='video'"><input type="file" name="media" :accept="type==='image'?'image/*':'video/*'" class="input-field"></div>
<div x-show="type==='link'"><input type="url" name="link_url" placeholder="https://..." class="input-field"></div>
<div class="flex gap-3">
<select name="visibility" class="input-field py-2 text-sm"><option value="public">🌍 Public</option><option value="followers">👥 Followers</option></select>
<button type="button" onclick="submitStatus()" class="btn-primary py-2 px-5 text-sm">Post Status</button>
</div>
</form>
</div>
</div>
<!-- Status Feed -->
<div class="space-y-4">
@forelse($posts as $post)
<div class="card p-4" x-data="{ liked: false }">
<div class="flex items-center gap-3 mb-3">
<a href="{{ route('profile.show', $post->user->username) }}"><img src="{{ $post->user->avatar_url }}" class="w-10 h-10 rounded-full object-cover"></a>
<div class="flex-1">
<a href="{{ route('profile.show', $post->user->username) }}" class="text-sm font-semibold text-white hover:text-purple-400">{{ $post->user->display_name }}</a>
<p class="text-xs text-slate-500">{{ $post->created_at->diffForHumans() }}</p>
</div>
@if($post->expires_at)<span class="text-xs text-amber-400">⏱ {{ $post->expires_at->diffForHumans() }}</span>@endif
</div>
@if($post->content)<p class="text-sm text-slate-300 mb-3 leading-relaxed">{{ $post->content }}</p>@endif
@if($post->media_file && $post->type==='image')<img src="{{ asset('storage/'.$post->media_file) }}" class="w-full rounded-2xl mb-3 max-h-80 object-cover">@endif
@if($post->link_url)<a href="{{ $post->link_url }}" target="_blank" class="block p-3 rounded-xl bg-dark-border hover:bg-dark-hover transition-colors mb-3"><p class="text-xs text-purple-400 truncate">{{ $post->link_url }}</p>@if($post->link_title)<p class="text-sm text-white mt-1">{{ $post->link_title }}</p>@endif</a>@endif
@if($post->contact_buttons && count($post->contact_buttons))
<div class="flex flex-wrap gap-2 mb-3">
@foreach($post->contact_buttons as $btn)
<button onclick="fetch('{{ route('status.contact-click', $post) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})" class="btn-secondary text-xs py-1.5 px-3">{{ ucfirst($btn['type'] ?? 'contact') }}</button>
@endforeach
</div>
@endif
<div class="flex items-center gap-4 pt-2 border-t border-dark-border">
<button @click="fetch('{{ route('status.like', $post) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(r=>r.json()).then(d=>liked=d.liked)"
class="flex items-center gap-1.5 text-sm transition-colors" :class="liked?'text-pink-400':'text-slate-400 hover:text-pink-400'">
<svg class="w-4 h-4" :fill="liked?'currentColor':'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
{{ number_format($post->likes_count) }}
</button>
<span class="text-xs text-slate-500">{{ number_format($post->views_count) }} views</span>
@if($post->is_monetized)<span class="badge badge-amber text-xs">💰 Monetized</span>@endif
</div>
</div>
@empty
<div class="card p-12 text-center"><p class="text-slate-400">No status posts yet. Share something!</p></div>
@endforelse
</div>
{{ $posts->links() }}
</div>
@endsection
@section('scripts')
<script>
function submitStatus() {
    const form = document.getElementById('statusForm');
    const fd = new FormData(form);
    fd.append('_token', '{{ csrf_token() }}');
    fetch('{{ route('status.store') }}', {method: 'POST', body: fd})
    .then(r => r.json()).then(() => location.reload());
}
</script>
@endsection
