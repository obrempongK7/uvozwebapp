@extends('layouts.app')
@section('title',$post->title)
@section('content')
<div class="max-w-2xl mx-auto">
<a href="{{ route('blog.index') }}" class="flex items-center gap-2 text-sm text-slate-400 hover:text-white mb-6"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back to Blog</a>
@if($post->featured_image)<img src="{{ $post->featured_image_url }}" class="w-full h-64 object-cover rounded-3xl mb-6">@endif
<h1 class="text-3xl font-black text-white mb-4">{{ $post->title }}</h1>
<div class="flex items-center gap-3 mb-8 pb-6 border-b border-dark-border">
<img src="{{ $post->author->avatar_url }}" class="w-10 h-10 rounded-full object-cover">
<div><p class="text-sm font-medium text-white">{{ $post->author->display_name }}</p><p class="text-xs text-slate-400">{{ $post->published_at?->format('M d, Y') }} · {{ number_format($post->views_count) }} views</p></div>
</div>
<div class="text-slate-300 leading-relaxed space-y-4">{!! nl2br(e($post->content)) !!}</div>
</div>
@endsection
