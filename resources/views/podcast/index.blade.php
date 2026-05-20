@extends('layouts.app')
@section('title','Podcasts')
@section('content')
<div class="flex items-center justify-between mb-6">
<div><h1 class="text-2xl font-bold text-white">Podcasts</h1><p class="text-slate-400 text-sm mt-1">Discover and subscribe to shows</p></div>
<a href="{{ route('podcast.create') }}" class="btn-primary text-sm">+ New Podcast</a>
</div>
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
@forelse($podcasts as $podcast)
<a href="{{ route('podcast.show',$podcast) }}" class="card p-4 card-hover flex flex-col">
<div class="w-full h-40 rounded-2xl object-cover bg-dark-border mb-4 overflow-hidden">
@if($podcast->cover_artwork)<img src="{{ $podcast->cover_url }}" class="w-full h-full object-cover">
@else<div class="w-full h-full gradient-bg flex items-center justify-center"><svg class="w-12 h-12 text-white opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.536a5 5 0 001.414 1.414m2.828-9.9a9 9 0 0112.728 0"/></svg></div>@endif
</div>
<h3 class="font-semibold text-white mb-1 line-clamp-1">{{ $podcast->title }}</h3>
<p class="text-xs text-slate-400 mb-2">by {{ $podcast->user->display_name }}</p>
@if($podcast->description)<p class="text-xs text-slate-500 line-clamp-2 flex-1">{{ $podcast->description }}</p>@endif
<div class="flex items-center justify-between mt-3">
<div class="flex items-center gap-3">
<span class="text-xs text-slate-400">{{ number_format($podcast->subscribers_count) }} subs</span>
<span class="text-xs text-slate-400">{{ number_format($podcast->episodes_count) }} eps</span>
</div>
@if($podcast->type !== 'free')<span class="badge badge-amber text-xs">{{ ucfirst($podcast->type) }}</span>@endif
</div>
</a>
@empty
<div class="sm:col-span-2 lg:col-span-3 card p-12 text-center"><svg class="w-12 h-12 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.536a5 5 0 001.414 1.414m2.828-9.9a9 9 0 0112.728 0"/></svg><h3 class="text-lg font-semibold text-white mb-2">No podcasts yet</h3><a href="{{ route('podcast.create') }}" class="btn-primary mt-2 inline-flex">Create First Podcast</a></div>
@endforelse
</div>
{{ $podcasts->links() }}
@endsection
