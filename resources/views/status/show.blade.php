@extends('layouts.app')
@section('title','Status Post')
@section('content')
<div class="max-w-xl mx-auto">
<a href="{{ route('status.index') }}" class="flex items-center gap-2 text-sm text-slate-400 hover:text-white mb-4">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back
</a>
<div class="card p-5">
<div class="flex items-center gap-3 mb-4">
<img src="{{ $statusPost->user->avatar_url }}" class="w-10 h-10 rounded-full object-cover">
<div><p class="font-semibold text-white">{{ $statusPost->user->display_name }}</p><p class="text-xs text-slate-500">{{ $statusPost->created_at->diffForHumans() }}</p></div>
</div>
@if($statusPost->content)<p class="text-slate-300 mb-4 leading-relaxed">{{ $statusPost->content }}</p>@endif
@if($statusPost->media_file && $statusPost->type==='image')<img src="{{ asset('storage/'.$statusPost->media_file) }}" class="w-full rounded-2xl mb-4">@endif
<div class="flex items-center gap-4 pt-3 border-t border-dark-border">
<span class="text-sm text-slate-400">{{ number_format($statusPost->likes_count) }} likes · {{ number_format($statusPost->views_count) }} views</span>
@if(auth()->id() === $statusPost->user_id)
<form action="{{ route('status.destroy', $statusPost) }}" method="POST" class="ml-auto inline" onsubmit="return confirm('Delete?')">
@csrf @method('DELETE')
<button class="text-xs text-red-400 hover:text-red-300">Delete</button>
</form>
@endif
</div>
</div>
</div>
@endsection
