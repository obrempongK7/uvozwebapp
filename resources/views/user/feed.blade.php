@extends('layouts.app')
@section('title','Feed')
@section('content')
<div class="max-w-2xl mx-auto">
<!-- Feed Tabs -->
<div class="flex gap-1 mb-6 p-1 rounded-2xl bg-dark-surface border border-dark-border overflow-x-auto">
@foreach([['for-you','For You',route('feed.index',['type'=>'for-you'])],['following','Following',route('feed.following')],['trending','Trending',route('feed.trending')],['nearby','Nearby',route('feed.index',['type'=>'nearby'])]] as $tab)
<a href="{{ $tab[2] }}" class="flex-1 text-center py-2.5 px-4 rounded-xl text-sm font-medium transition-all whitespace-nowrap {{ $type===$tab[0] ? 'gradient-bg text-white shadow-lg' : 'text-slate-400 hover:text-white' }}">{{ $tab[1] }}</a>
@endforeach
</div>

<!-- Active Campaigns Banner -->
@if($campaigns->count())
<div class="card p-4 mb-4" style="border-color:rgba(245,158,11,.3);background:rgba(245,158,11,.05);">
<p class="text-xs font-semibold text-amber-400 mb-2">💰 Earn Extra Points – Complete These Tasks</p>
<div class="flex gap-3 overflow-x-auto pb-1">
@foreach($campaigns as $campaign)
<div class="flex-shrink-0 w-48 card p-3">
<p class="text-xs font-semibold text-white mb-1 truncate">{{ $campaign->title }}</p>
<p class="text-xs text-amber-400 font-bold">+{{ number_format($campaign->points_per_action) }} pts</p>
<a href="{{ route('campaign.show',$campaign) }}" class="mt-2 block text-center text-xs text-purple-400 hover:text-purple-300">Complete →</a>
</div>
@endforeach
</div>
</div>
@endif

<!-- Posts -->
<div class="space-y-4">
@forelse($posts as $post)
<div class="card p-5 card-hover">
<div class="flex items-center gap-3 mb-4">
<a href="{{ route('profile.show',$post->user->username) }}"><img src="{{ $post->user->avatar_url }}" class="w-10 h-10 rounded-full object-cover"></a>
<div class="flex-1">
<a href="{{ route('profile.show',$post->user->username) }}" class="text-sm font-semibold text-white hover:text-purple-400">{{ $post->user->display_name }}</a>
@if($post->user->is_verified)<svg class="inline w-3.5 h-3.5 text-purple-400 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>@endif
<p class="text-xs text-slate-500">{{ $post->published_at?->diffForHumans() }}</p>
</div>
@auth @if(auth()->id() !== $post->user_id)
<button onclick="this.closest('form').submit()" class="text-xs px-3 py-1 rounded-xl border border-dark-border text-slate-400 hover:border-purple-500 hover:text-purple-400 transition-all">Follow</button>
@endif @endauth
</div>
<a href="{{ route('voice.show',$post) }}" class="block font-semibold text-white hover:text-purple-400 mb-3">{{ $post->title }}</a>
@if($post->description)<p class="text-sm text-slate-400 mb-3 line-clamp-2">{{ $post->description }}</p>@endif
<!-- Mini Player -->
<div class="rounded-2xl p-4 mb-3" style="background:#0B1020;">
<div class="flex items-center gap-3">
<button onclick="playVoice('{{ $post->audio_url }}','{{ addslashes($post->title) }}','{{ addslashes($post->user->display_name) }}','{{ $post->cover_url }}');fetch('{{ route('voice.play',$post) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}});"
    class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center flex-shrink-0">
<svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
</button>
<div class="flex-1">
<div class="flex gap-px h-6 items-center">
@for($i=0;$i<24;$i++)<div class="w-1 rounded-full" style="height:{{ rand(4,24) }}px;background:linear-gradient(180deg,rgba(124,58,237,.6),rgba(236,72,153,.3));"></div>@endfor
</div>
</div>
<span class="text-xs text-slate-500">{{ $post->formatted_duration }}</span>
</div>
</div>
<!-- Actions -->
<div class="flex items-center gap-4" x-data="{ liked: false }">
<button @click="fetch('{{ route('voice.like',$post) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(r=>r.json()).then(d=>liked=d.liked)"
    class="flex items-center gap-1.5 text-sm transition-colors" :class="liked?'text-pink-400':'text-slate-400 hover:text-pink-400'">
<svg class="w-4 h-4" :fill="liked?'currentColor':'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
{{ number_format($post->likes_count) }}
</button>
<a href="{{ route('voice.show',$post) }}" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-white">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
{{ number_format($post->comments_count) }}
</a>
<span class="flex items-center gap-1.5 text-sm text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>{{ number_format($post->plays_count) }}</span>
</div>
</div>
@empty
<div class="card p-12 text-center">
<svg class="w-16 h-16 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2"/></svg>
<h3 class="text-lg font-semibold text-white mb-2">Your feed is empty</h3>
<p class="text-slate-400 text-sm mb-4">Follow creators or switch to "For You"</p>
<a href="{{ route('explore') }}" class="btn-primary">Discover Creators</a>
</div>
@endforelse
</div>
{{ $posts->links() }}
</div>
@endsection
