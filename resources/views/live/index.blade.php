@extends('layouts.app')
@section('title','Live Rooms')
@section('content')
<div class="flex items-center justify-between mb-6">
<div><h1 class="text-2xl font-bold text-white">Live Audio Rooms</h1><p class="text-slate-400 text-sm mt-1">Join real-time voice discussions</p></div>
<a href="{{ route('live.create') }}" class="btn-primary text-sm">🎤 Start Room</a>
</div>
<div class="grid md:grid-cols-2 gap-4">
@forelse($rooms as $room)
<div class="card p-5 card-hover">
<div class="flex items-start gap-3 mb-3">
<div class="flex-1">
<div class="flex items-center gap-2 mb-1">
@if($room->status==='live')<span class="flex items-center gap-1.5 text-xs text-red-400 font-semibold"><span class="w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>LIVE</span>@else<span class="text-xs text-slate-400">Scheduled</span>@endif
</div>
<h3 class="font-semibold text-white">{{ $room->title }}</h3>
<div class="flex items-center gap-2 mt-1">
<img src="{{ $room->host->avatar_url }}" class="w-5 h-5 rounded-full object-cover">
<span class="text-xs text-slate-400">{{ $room->host->display_name }}</span>
</div>
</div>
<div class="text-right">
<p class="text-lg font-bold text-white">{{ number_format($room->listeners_count) }}</p>
<p class="text-xs text-slate-500">listeners</p>
</div>
</div>
@if($room->description)<p class="text-sm text-slate-400 mb-3 line-clamp-2">{{ $room->description }}</p>@endif
<div class="flex items-center justify-between">
<div class="flex -space-x-2">
@foreach($room->participants->take(5) as $p)<img src="{{ $p->user->avatar_url }}" class="w-7 h-7 rounded-full object-cover border-2 border-dark-card">@endforeach
</div>
<a href="{{ route('live.show',$room) }}" class="{{ $room->status==='live'?'btn-primary':'btn-secondary' }} text-sm py-2 px-4">{{ $room->status==='live'?'Join Room':'View' }}</a>
</div>
</div>
@empty
<div class="md:col-span-2 card p-12 text-center"><svg class="w-12 h-12 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg><h3 class="text-lg font-semibold text-white mb-2">No live rooms right now</h3><p class="text-slate-400 text-sm mb-4">Be the first to start a conversation!</p><a href="{{ route('live.create') }}" class="btn-primary inline-flex">Start a Room</a></div>
@endforelse
</div>
{{ $rooms->links() }}
@endsection
