@extends('layouts.app')
@section('title','Blog')
@section('content')
<div class="max-w-4xl mx-auto">
<div class="text-center mb-10"><h1 class="text-3xl font-black text-white mb-2">UVOZ <span class="gradient-text">Blog</span></h1><p class="text-slate-400">Tips, updates and stories from our community</p></div>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
@forelse($posts as $post)
<a href="{{ route('blog.show',$post->slug) }}" class="card overflow-hidden card-hover flex flex-col">
@if($post->featured_image)<img src="{{ $post->featured_image_url }}" class="w-full h-40 object-cover">
@else<div class="w-full h-40 gradient-bg opacity-60"></div>@endif
<div class="p-4 flex-1">
@if($post->category)<span class="badge badge-purple text-xs mb-2">{{ $post->category }}</span>@endif
<h3 class="font-semibold text-white mb-2 line-clamp-2">{{ $post->title }}</h3>
@if($post->excerpt)<p class="text-sm text-slate-400 line-clamp-3">{{ $post->excerpt }}</p>@endif
<div class="flex items-center gap-2 mt-3"><img src="{{ $post->author->avatar_url }}" class="w-6 h-6 rounded-full object-cover"><p class="text-xs text-slate-500">{{ $post->author->display_name }} · {{ $post->published_at?->format('M d, Y') }}</p></div>
</div>
</a>
@empty<div class="md:col-span-3 card p-12 text-center"><p class="text-slate-400">No blog posts yet.</p></div>@endforelse
</div>
{{ $posts->links() }}
</div>
@endsection
