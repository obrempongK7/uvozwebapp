@extends('layouts.app')
@section('title', $podcast->title)
@section('content')
<div class="max-w-3xl mx-auto">
<!-- Header -->
<div class="card p-6 mb-6">
<div class="flex items-start gap-5">
<div class="w-24 h-24 rounded-2xl bg-dark-border flex-shrink-0 overflow-hidden">
@if($podcast->cover_artwork)<img src="{{ $podcast->cover_url }}" class="w-full h-full object-cover">@else<div class="w-full h-full gradient-bg flex items-center justify-center"><svg class="w-10 h-10 text-white opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728"/></svg></div>@endif
</div>
<div class="flex-1">
<h1 class="text-xl font-bold text-white mb-1">{{ $podcast->title }}</h1>
<a href="{{ route('profile.show',$podcast->user->username) }}" class="text-sm text-purple-400 hover:text-purple-300">{{ $podcast->user->display_name }}</a>
<div class="flex items-center gap-4 mt-2">
<span class="text-sm text-slate-400">{{ number_format($podcast->subscribers_count) }} subscribers</span>
<span class="text-sm text-slate-400">{{ number_format($podcast->episodes_count) }} episodes</span>
@if($podcast->category)<span class="badge badge-purple text-xs">{{ $podcast->category }}</span>@endif
</div>
@if($podcast->description)<p class="text-sm text-slate-400 mt-3 leading-relaxed">{{ $podcast->description }}</p>@endif
</div>
@auth @if(auth()->id() !== $podcast->user_id)
<div x-data="{ subscribed: {{ $isSubscribed?'true':'false' }} }">
<button @click="fetch(subscribed ? '{{ route('podcast.unsubscribe',$podcast) }}' : '{{ route('podcast.subscribe',$podcast) }}',{method:subscribed?'DELETE':'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>subscribed=!subscribed)"
class="btn-primary py-2.5 px-5 text-sm" :class="subscribed?'opacity-70':''">
<span x-text="subscribed?'Subscribed ✓':'Subscribe'"></span>
</button>
</div>
@else<a href="{{ route('podcast.create') }}" class="btn-secondary text-sm py-2 px-4">+ Add Episode</a>@endif @endauth
</div>
<!-- Episodes -->
<h3 class="font-semibold text-white mb-4">Episodes ({{ $podcast->episodes_count }})</h3>
@forelse($episodes as $ep)
<div class="card p-4 mb-3 flex items-center gap-4 card-hover">
<div class="text-center flex-shrink-0 w-8"><p class="text-xs font-bold text-slate-500">{{ $ep->episode_number ?? '#' }}</p></div>
<div class="flex-1 min-w-0">
<a href="{{ route('podcast.episodes.show',[$podcast,$ep]) }}" class="font-medium text-white hover:text-purple-400 block truncate">{{ $ep->title }}</a>
<div class="flex items-center gap-3 mt-1">
<span class="text-xs text-slate-500">{{ $ep->formatted_duration }}</span>
<span class="text-xs text-slate-500">{{ number_format($ep->plays_count) }} plays</span>
<span class="text-xs text-slate-500">{{ $ep->published_at?->format('M d, Y') }}</span>
@if($ep->access !== 'free')<span class="badge badge-amber text-xs">{{ ucfirst($ep->access) }}</span>@endif
</div>
</div>
<button onclick="playVoice('{{ $ep->audio_url }}','{{ addslashes($ep->title) }}','{{ addslashes($podcast->title) }}','{{ $podcast->cover_url }}');fetch('{{ route('podcast.episodes.play',[$podcast,$ep]) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})"
class="w-9 h-9 gradient-bg rounded-full flex items-center justify-center flex-shrink-0">
<svg class="w-3.5 h-3.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
</button>
</div>
@empty<div class="card p-8 text-center"><p class="text-slate-400">No episodes yet.</p></div>@endforelse
{{ $episodes->links() }}
</div>
@endsection
