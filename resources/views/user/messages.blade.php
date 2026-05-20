@extends('layouts.app')
@section('title','Messages')
@section('content')
<div class="max-w-4xl mx-auto">
<h1 class="text-2xl font-bold text-white mb-6">Messages</h1>
<div class="card overflow-hidden">
@forelse($convos as $convo)
@php
  $other = $convo->participants->where('id','!=',auth()->id())->first();
  $unread = $convo->messages()->where('sender_id','!=',auth()->id())->where('is_read',false)->count();
@endphp
<a href="{{ route('message.show',$convo) }}" class="flex items-center gap-4 p-4 border-b border-dark-border last:border-0 hover:bg-dark-hover transition-colors">
<div class="relative">
<img src="{{ $other?->avatar_url ?? asset('images/default-avatar.png') }}" class="w-12 h-12 rounded-full object-cover">
@if($unread > 0)<span class="absolute -top-1 -right-1 w-5 h-5 gradient-bg rounded-full text-xs text-white flex items-center justify-center font-bold">{{ $unread }}</span>@endif
</div>
<div class="flex-1 min-w-0">
<div class="flex items-center justify-between">
<p class="font-semibold text-white">{{ $other?->display_name ?? 'Unknown' }}</p>
<p class="text-xs text-slate-500">{{ $convo->last_message_at?->diffForHumans() }}</p>
</div>
<p class="text-sm text-slate-400 truncate mt-0.5">{{ $convo->lastMessage?->content ?? 'Start a conversation' }}</p>
</div>
</a>
@empty
<div class="p-12 text-center">
<svg class="w-12 h-12 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
<p class="text-slate-400">No messages yet. Find someone to talk to!</p>
<a href="{{ route('explore') }}" class="btn-primary mt-4 inline-flex text-sm py-2 px-5">Explore Creators</a>
</div>
@endforelse
</div>
{{ $convos->links() }}
</div>
@endsection
