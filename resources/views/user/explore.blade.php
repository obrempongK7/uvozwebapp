@extends('layouts.app')
@section('title','Explore')
@section('content')
<div class="space-y-8">
<!-- Trending hashtags -->
@if($trendingHashtags->count())
<section>
<h2 class="text-lg font-bold text-white mb-3">🔥 Trending</h2>
<div class="flex flex-wrap gap-2">
@foreach($trendingHashtags as $tag)
<a href="{{ route('search', ['q' => '#'.$tag->name]) }}" class="badge badge-purple py-1.5 px-3 text-sm">#{{ $tag->name }} <span class="ml-1 opacity-60">{{ number_format($tag->posts_count) }}</span></a>
@endforeach
</div>
</section>
@endif
<!-- Live Rooms -->
@if($liveRooms->count())
<section>
<div class="flex items-center justify-between mb-3"><h2 class="text-lg font-bold text-white">🔴 Live Now</h2><a href="{{ route('live.index') }}" class="text-sm text-purple-400 hover:text-purple-300">View all →</a></div>
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
@foreach($liveRooms as $room)
<a href="{{ route('live.show', $room) }}" class="card p-4 card-hover">
<div class="flex items-center gap-2 mb-2"><span class="flex items-center gap-1.5 text-xs text-red-400 font-bold"><span class="w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>LIVE</span></div>
<h3 class="font-semibold text-white text-sm mb-1 truncate">{{ $room->title }}</h3>
<p class="text-xs text-slate-400">{{ $room->host->display_name }} · {{ number_format($room->listeners_count) }} listeners</p>
</a>
@endforeach
</div>
</section>
@endif
<!-- Voice Posts -->
<section>
<div class="flex items-center justify-between mb-3"><h2 class="text-lg font-bold text-white">🎙️ Top Voice Posts</h2><a href="{{ route('voice.index') }}" class="text-sm text-purple-400 hover:text-purple-300">View all →</a></div>
<div class="grid sm:grid-cols-2 gap-4">
@foreach($voicePosts as $post)
<div class="card p-4 flex items-center gap-3 card-hover">
<div class="w-12 h-12 rounded-xl gradient-bg flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg></div>
<div class="flex-1 min-w-0"><a href="{{ route('voice.show', $post) }}" class="text-sm font-semibold text-white hover:text-purple-400 truncate block">{{ $post->title }}</a><p class="text-xs text-slate-400">{{ $post->user->display_name }} · {{ number_format($post->plays_count) }} plays</p></div>
<button onclick="playVoice('{{ $post->audio_url }}','{{ addslashes($post->title) }}','{{ addslashes($post->user->display_name) }}',' ')" class="w-9 h-9 gradient-bg rounded-full flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></button>
</div>
@endforeach
</div>
</section>
<!-- Top Users -->
<section>
<div class="flex items-center justify-between mb-3"><h2 class="text-lg font-bold text-white">⭐ Top Creators</h2></div>
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
@foreach($topUsers as $creator)
<a href="{{ route('profile.show', $creator->username) }}" class="card p-4 text-center card-hover">
<img src="{{ $creator->avatar_url }}" class="w-14 h-14 rounded-full object-cover mx-auto mb-2">
<p class="text-sm font-semibold text-white truncate">{{ $creator->display_name }}</p>
<p class="text-xs text-slate-400">{{ number_format($creator->profile?->followers_count ?? 0) }} followers</p>
</a>
@endforeach
</div>
</section>
<!-- Podcasts -->
@if($podcasts->count())
<section>
<div class="flex items-center justify-between mb-3"><h2 class="text-lg font-bold text-white">🎧 Popular Podcasts</h2><a href="{{ route('podcast.index') }}" class="text-sm text-purple-400 hover:text-purple-300">View all →</a></div>
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
@foreach($podcasts as $podcast)
<a href="{{ route('podcast.show', $podcast) }}" class="card p-4 card-hover">
<div class="w-full h-24 rounded-xl bg-dark-border overflow-hidden mb-3">
@if($podcast->cover_artwork)<img src="{{ $podcast->cover_url }}" class="w-full h-full object-cover">@else<div class="w-full h-full gradient-bg opacity-60"></div>@endif
</div>
<p class="text-sm font-semibold text-white truncate">{{ $podcast->title }}</p>
<p class="text-xs text-slate-400">{{ number_format($podcast->subscribers_count) }} subscribers</p>
</a>
@endforeach
</div>
</section>
@endif
</div>
@endsection
