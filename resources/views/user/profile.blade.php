@extends('layouts.app')
@section('title', $profile->display_name)
@section('content')
<div class="max-w-3xl mx-auto">
<!-- Cover -->
<div class="relative h-48 rounded-3xl overflow-hidden mb-16" style="background:linear-gradient(135deg,#7C3AED,#EC4899,#F59E0B);">
@if($profile->cover_image)<img src="{{ $profile->cover_url }}" class="w-full h-full object-cover">@endif
<!-- Avatar -->
<div class="absolute -bottom-12 left-6">
<img src="{{ $profile->avatar_url }}" class="w-24 h-24 rounded-2xl object-cover border-4 border-dark-bg">
</div>
</div>
<div class="flex items-start justify-between mb-6">
<div>
<div class="flex items-center gap-2">
<h1 class="text-2xl font-bold text-white">{{ $profile->display_name }}</h1>
@if($profile->is_verified)<svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>@endif
</div>
<p class="text-slate-400 text-sm">@{{ $profile->username }}</p>
@if($profile->bio)<p class="text-slate-300 text-sm mt-2 max-w-md">{{ $profile->bio }}</p>@endif
<div class="flex items-center gap-4 mt-3">
<span class="text-sm"><strong class="text-white">{{ number_format($profile->profile?->followers_count ?? 0) }}</strong> <span class="text-slate-400">Followers</span></span>
<span class="text-sm"><strong class="text-white">{{ number_format($profile->profile?->following_count ?? 0) }}</strong> <span class="text-slate-400">Following</span></span>
<span class="text-sm"><strong class="text-white">{{ number_format($profile->profile?->voice_posts_count ?? 0) }}</strong> <span class="text-slate-400">Posts</span></span>
</div>
</div>
@auth @if(auth()->id() !== $profile->id)
<div x-data="{ following: {{ $isFollowing?'true':'false' }} }">
<button @click="
  fetch(following ? '{{ route('profile.unfollow',$profile) }}' : '{{ route('profile.follow',$profile) }}',{method:following?'DELETE':'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})
  .then(()=>following=!following)"
class="btn-primary py-2.5 px-6" :class="following ? 'opacity-70' : ''">
<span x-text="following ? 'Following ✓' : 'Follow'"></span>
</button>
</div>
@else
<a href="{{ route('settings.index') }}" class="btn-secondary py-2.5 px-6">Edit Profile</a>
@endif @endauth
</div>
<!-- Voice Posts -->
<h3 class="text-lg font-semibold text-white mb-4">Voice Posts</h3>
<div class="grid gap-4">
@forelse($profile->voicePosts as $post)
<div class="card p-4 flex items-center gap-4 card-hover">
<div class="w-12 h-12 rounded-xl bg-dark-border flex-shrink-0 overflow-hidden">
@if($post->cover_image)<img src="{{ $post->cover_url }}" class="w-full h-full object-cover">@else<div class="w-full h-full gradient-bg flex items-center justify-center"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg></div>@endif
</div>
<div class="flex-1 min-w-0">
<a href="{{ route('voice.show',$post) }}" class="font-medium text-white hover:text-purple-400 truncate block">{{ $post->title }}</a>
<div class="flex gap-3 mt-1">
<span class="text-xs text-slate-500">{{ number_format($post->plays_count) }} plays</span>
<span class="text-xs text-slate-500">{{ number_format($post->likes_count) }} likes</span>
<span class="text-xs text-slate-500">{{ $post->published_at?->diffForHumans() }}</span>
</div>
</div>
<button onclick="playVoice('{{ $post->audio_url }}','{{ addslashes($post->title) }}','{{ addslashes($profile->display_name) }}','')" class="w-9 h-9 gradient-bg rounded-full flex items-center justify-center flex-shrink-0">
<svg class="w-3.5 h-3.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
</button>
</div>
@empty<div class="card p-8 text-center"><p class="text-slate-400">No voice posts yet.</p></div>@endforelse
</div>
</div>
@endsection
