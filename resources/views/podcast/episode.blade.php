@extends('layouts.app')
@section('title', $episode->title)
@section('content')
<div class="max-w-2xl mx-auto">
<a href="{{ route('podcast.show', $podcast) }}" class="flex items-center gap-2 text-sm text-slate-400 hover:text-white mb-6">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back to {{ $podcast->title }}
</a>
<div class="card p-6">
<div class="flex items-start gap-4 mb-5">
<img src="{{ $podcast->cover_url }}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
<div>
<h1 class="text-xl font-bold text-white">{{ $episode->title }}</h1>
<p class="text-sm text-purple-400 mt-1">{{ $podcast->title }}</p>
<div class="flex items-center gap-3 mt-2">
<span class="text-xs text-slate-500">{{ $episode->formatted_duration }}</span>
<span class="text-xs text-slate-500">{{ number_format($episode->plays_count) }} plays</span>
@if($episode->episode_number)<span class="text-xs text-slate-500">Episode {{ $episode->episode_number }}</span>@endif
</div>
</div>
</div>
<div class="audio-player mb-5" x-data="{ playing: false, progress: 0 }" x-init="
  const a = new Audio('{{ $episode->audio_url }}');
  a.addEventListener('timeupdate', () => { if(a.duration) progress=(a.currentTime/a.duration)*100; });
  a.addEventListener('ended', () => playing=false);
  $watch('playing', v => v ? a.play() : a.pause());
  fetch('{{ route('podcast.episodes.play', [$podcast, $episode]) }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}});
">
<div class="flex items-center gap-4">
<button @click="playing=!playing" class="w-12 h-12 gradient-bg rounded-full flex items-center justify-center">
<svg x-show="!playing" class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
<svg x-show="playing" class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
</button>
<div class="flex-1">
<div class="progress-bar cursor-pointer"><div class="progress-fill" :style="'width:'+progress+'%'"></div></div>
</div>
</div>
</div>
@if($episode->show_notes)<div class="prose prose-invert max-w-none text-slate-300 text-sm leading-relaxed">{!! nl2br(e($episode->show_notes)) !!}</div>@endif
@if($podcast->user_id === auth()->id())
<div class="mt-6 pt-4 border-t border-dark-border">
<form action="{{ route('podcast.episodes.destroy', [$podcast, $episode]) }}" method="POST" onsubmit="return confirm('Delete episode?')">
@csrf @method('DELETE')
<button type="submit" class="text-xs text-red-400 hover:text-red-300">Delete Episode</button>
</form>
</div>
@endif
</div>
</div>
@endsection
