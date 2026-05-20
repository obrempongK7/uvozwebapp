@extends('layouts.app')
@section('title', $voicePost->title)
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
<div class="card p-6">
<div class="flex items-start gap-4 mb-5">
<div class="w-16 h-16 rounded-2xl bg-dark-border flex-shrink-0 overflow-hidden">
@if($voicePost->cover_image)<img src="{{ $voicePost->cover_url }}" class="w-full h-full object-cover">
@else<div class="w-full h-full gradient-bg flex items-center justify-center"><svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg></div>@endif
</div>
<div class="flex-1">
<h1 class="text-xl font-bold text-white mb-2">{{ $voicePost->title }}</h1>
<div class="flex items-center gap-3">
<a href="{{ route('profile.show', $voicePost->user->username) }}" class="flex items-center gap-2">
<img src="{{ $voicePost->user->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
<div><p class="text-sm font-medium text-white">{{ $voicePost->user->display_name }}</p><p class="text-xs text-slate-400">{{ $voicePost->published_at?->diffForHumans() }}</p></div>
</a>
@if($voicePost->user->is_verified)<svg class="w-4 h-4 text-purple-400" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>@endif
</div>
</div>
</div>
<!-- Audio Player -->
<div class="audio-player mb-5" x-data="{ playing: false, progress: 0, currentTime:'0:00', duration:'0:00' }" x-init="
  const a = new Audio('{{ $voicePost->audio_url }}');
  a.addEventListener('timeupdate',()=>{ if(a.duration){progress=(a.currentTime/a.duration)*100; currentTime=fmt(a.currentTime);} });
  a.addEventListener('loadedmetadata',()=>{ duration=fmt(a.duration); });
  a.addEventListener('ended',()=>{ playing=false; progress=0; });
  function fmt(s){return Math.floor(s/60)+':'+(Math.floor(s%60)+'').padStart(2,'0');}
  $watch('playing',v=>{ v?a.play():a.pause(); });
  // Track play
  fetch('{{ route('voice.play',$voicePost) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}});
">
<div class="flex items-center gap-4">
<button @click="playing=!playing" class="w-12 h-12 gradient-bg rounded-full flex items-center justify-center flex-shrink-0 transition-transform hover:scale-105">
<svg x-show="!playing" class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
<svg x-show="playing" class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
</button>
<div class="flex-1">
<div class="progress-bar" @click="const r=$el.getBoundingClientRect();a.currentTime=((event.clientX-r.left)/r.width)*a.duration">
<div class="progress-fill" :style="'width:'+progress+'%'"></div>
</div>
<div class="flex justify-between mt-1">
<span class="text-xs text-slate-500" x-text="currentTime"></span>
<span class="text-xs text-slate-500" x-text="duration"></span>
</div>
</div>
</div>
</div>
@if($voicePost->description)<p class="text-slate-300 text-sm leading-relaxed mb-4">{{ $voicePost->description }}</p>@endif
@if($voicePost->hashtags)<div class="flex flex-wrap gap-2 mb-4">@foreach($voicePost->hashtags as $tag)<a href="{{ route('search', ['q'=>'#'.$tag]) }}" class="badge badge-purple">#{{ $tag }}</a>@endforeach</div>@endif
<!-- Actions -->
<div class="flex items-center gap-4 pt-4 border-t border-dark-border" x-data="{ liked: false, likeCount: {{ $voicePost->likes_count }} }">
<button @click="
  fetch('{{ route('voice.like',$voicePost) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})
  .then(r=>r.json()).then(d=>{ liked=d.liked; likeCount=d.count; })"
class="flex items-center gap-2 text-sm transition-colors" :class="liked?'text-pink-400':'text-slate-400 hover:text-pink-400'">
<svg class="w-5 h-5" :fill="liked?'currentColor':'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
<span x-text="likeCount"></span>
</button>
<span class="flex items-center gap-2 text-sm text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>{{ number_format($voicePost->comments_count) }}</span>
<span class="flex items-center gap-2 text-sm text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>{{ number_format($voicePost->plays_count) }} plays</span>
@auth @if($voicePost->user_id === auth()->id())
<div class="ml-auto flex gap-2">
<a href="{{ route('voice.edit',$voicePost) }}" class="text-xs text-slate-400 hover:text-white">Edit</a>
<form action="{{ route('voice.destroy',$voicePost) }}" method="POST" onsubmit="return confirm('Delete this post?')">@csrf @method('DELETE')<button type="submit" class="text-xs text-red-400 hover:text-red-300">Delete</button></form>
</div>
@endif @endauth
</div>
</div>
<!-- Comments -->
<div class="card p-5">
<h3 class="font-semibold text-white mb-4">Comments ({{ $voicePost->comments_count }})</h3>
@auth
<form class="flex gap-3 mb-6" x-data x-on:submit.prevent="
  fetch('{{ route('voice.comment',$voicePost) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({content:$refs.comment.value})})
  .then(r=>r.json()).then(d=>{ $refs.comment.value=''; location.reload(); })">
<img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
<div class="flex-1 flex gap-2">
<input x-ref="comment" type="text" placeholder="Write a comment..." class="input-field text-sm flex-1">
<button type="submit" class="btn-primary py-2 px-4 text-sm">Post</button>
</div>
</form>
@endauth
<div class="space-y-4">
@forelse($comments as $comment)
<div class="flex gap-3">
<img src="{{ $comment->user->avatar_url }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0 mt-0.5">
<div class="flex-1">
<div class="bg-dark-border rounded-2xl px-4 py-3">
<div class="flex items-center gap-2 mb-1">
<a href="{{ route('profile.show',$comment->user->username) }}" class="text-sm font-semibold text-white hover:text-purple-400">{{ $comment->user->display_name }}</a>
<span class="text-xs text-slate-500">{{ $comment->created_at->diffForHumans() }}</span>
</div>
<p class="text-sm text-slate-300">{{ $comment->content }}</p>
</div>
</div>
</div>
@empty
<p class="text-slate-400 text-sm text-center py-4">No comments yet. Be the first!</p>
@endforelse
</div>
{{ $comments->links() }}
</div>
</div>
@endsection
