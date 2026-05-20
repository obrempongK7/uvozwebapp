@extends('layouts.app')
@section('title','Search')
@section('content')
<div class="max-w-3xl mx-auto">
<div class="mb-6">
<form class="relative">
<svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
<input type="text" name="q" value="{{ $query }}" placeholder="Search people, voices, podcasts..." class="input-field pl-12 py-4 text-lg" autofocus>
</form>
</div>
@if($query)
@if(!empty($results['users']) && $results['users']->count())
<div class="mb-6">
<h3 class="font-semibold text-slate-300 mb-3 text-sm uppercase tracking-wider">People</h3>
<div class="grid sm:grid-cols-2 gap-3">
@foreach($results['users'] as $u)
<a href="{{ route('profile.show',$u->username) }}" class="card p-4 flex items-center gap-3 card-hover">
<img src="{{ $u->avatar_url }}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
<div class="min-w-0">
<div class="flex items-center gap-1"><p class="font-semibold text-white truncate">{{ $u->display_name }}</p>@if($u->is_verified)<svg class="w-3.5 h-3.5 text-purple-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>@endif</div>
<p class="text-xs text-slate-400">@{{ $u->username }} · {{ $u->country }}</p>
</div>
</a>
@endforeach
</div>
</div>
@endif
@if(!empty($results['posts']) && $results['posts']->count())
<div class="mb-6">
<h3 class="font-semibold text-slate-300 mb-3 text-sm uppercase tracking-wider">Voice Posts</h3>
<div class="space-y-3">
@foreach($results['posts'] as $post)
<div class="card p-4 flex items-center gap-3 card-hover">
<div class="w-12 h-12 rounded-xl gradient-bg flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg></div>
<div class="flex-1 min-w-0">
<a href="{{ route('voice.show',$post) }}" class="font-medium text-white hover:text-purple-400 truncate block">{{ $post->title }}</a>
<p class="text-xs text-slate-400">by {{ $post->user->display_name }} · {{ number_format($post->plays_count) }} plays</p>
</div>
<button onclick="playVoice('{{ $post->audio_url }}','{{ addslashes($post->title) }}','{{ addslashes($post->user->display_name) }}','')" class="w-9 h-9 gradient-bg rounded-full flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></button>
</div>
@endforeach
</div>
</div>
@endif
@if(empty($results) || (empty($results['users']->count()) && empty($results['posts']->count())))
<div class="card p-12 text-center"><svg class="w-12 h-12 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg><p class="text-slate-400">No results for "{{ $query }}"</p></div>
@endif
@else
<div class="card p-12 text-center"><svg class="w-12 h-12 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg><p class="text-slate-400">Search for people, voice posts, podcasts and more.</p></div>
@endif
</div>
@endsection
