@extends('layouts.app')
@section('title','Voice Hub')
@section('content')
<div class="flex items-center justify-between mb-6">
<div><h1 class="text-2xl font-bold text-white">Voice Hub</h1><p class="text-slate-400 text-sm mt-1">Discover and listen to voice posts</p></div>
<a href="{{ route('voice.create') }}" class="btn-primary">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
New Voice Post
</a>
</div>
<div class="grid gap-4">
@forelse($posts as $post)
<div class="card p-5 card-hover">
<div class="flex items-start gap-4">
<div class="w-12 h-12 rounded-xl bg-dark-border flex-shrink-0 overflow-hidden">
@if($post->cover_image)<img src="{{ $post->cover_url }}" class="w-full h-full object-cover">
@else<div class="w-full h-full gradient-bg flex items-center justify-center"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg></div>@endif
</div>
<div class="flex-1 min-w-0">
<div class="flex items-start justify-between gap-2">
<div>
<a href="{{ route('voice.show', $post) }}" class="font-semibold text-white hover:text-purple-400 transition-colors">{{ $post->title }}</a>
<div class="flex items-center gap-2 mt-1">
<a href="{{ route('profile.show', $post->user->username) }}" class="flex items-center gap-1.5">
<img src="{{ $post->user->avatar_url }}" class="w-5 h-5 rounded-full object-cover">
<span class="text-xs text-slate-400 hover:text-white">{{ $post->user->display_name }}</span>
</a>
<span class="text-slate-600">·</span>
<span class="text-xs text-slate-500">{{ $post->published_at?->diffForHumans() }}</span>
@if($post->formatted_duration !== '0:00')<span class="text-slate-600">·</span><span class="text-xs text-slate-500">{{ $post->formatted_duration }}</span>@endif
</div>
</div>
<button onclick="playVoice('{{ $post->audio_url }}','{{ addslashes($post->title) }}','{{ addslashes($post->user->display_name) }}','{{ $post->cover_url }}')"
    class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center flex-shrink-0">
<svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
</button>
</div>
@if($post->description)<p class="text-sm text-slate-400 mt-2 line-clamp-2">{{ $post->description }}</p>@endif
<div class="flex items-center gap-4 mt-3">
<span class="flex items-center gap-1 text-xs text-slate-500"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>{{ number_format($post->plays_count) }}</span>
<span class="flex items-center gap-1 text-xs text-slate-500"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>{{ number_format($post->likes_count) }}</span>
<span class="flex items-center gap-1 text-xs text-slate-500"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>{{ number_format($post->comments_count) }}</span>
</div>
</div>
</div>
</div>
@empty
<div class="card p-12 text-center">
<svg class="w-16 h-16 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
<h3 class="text-lg font-semibold text-white mb-2">No voice posts yet</h3>
<p class="text-slate-400 text-sm mb-6">Be the first to share your voice!</p>
<a href="{{ route('voice.create') }}" class="btn-primary">Create Voice Post</a>
</div>
@endforelse
</div>
{{ $posts->links() }}
@endsection
