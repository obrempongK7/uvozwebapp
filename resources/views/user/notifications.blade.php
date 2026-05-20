@extends('layouts.app')
@section('title','Notifications')
@section('content')
<div class="max-w-2xl mx-auto">
<div class="flex items-center justify-between mb-6">
<h1 class="text-2xl font-bold text-white">Notifications</h1>
<form action="{{ route('notification.read-all') }}" method="POST">@csrf<button type="submit" class="btn-secondary text-sm py-2 px-4">Mark all read</button></form>
</div>
<div class="card overflow-hidden">
@forelse($notifications as $notif)
@php $data = $notif->data; @endphp
<div class="flex items-start gap-4 p-4 border-b border-dark-border last:border-0 hover:bg-dark-hover transition-colors {{ $notif->read_at ? 'opacity-70' : '' }}">
<div class="w-10 h-10 gradient-bg rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold text-sm">{{ strtoupper(substr($data['title']??'N',0,1)) }}</div>
<div class="flex-1 min-w-0">
<div class="flex items-start justify-between gap-2">
<div>
<p class="text-sm font-semibold text-white">{{ $data['title'] ?? 'Notification' }}</p>
<p class="text-sm text-slate-400 mt-0.5">{{ $data['message'] ?? '' }}</p>
<p class="text-xs text-slate-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
</div>
@if(!$notif->read_at)<div class="w-2 h-2 rounded-full gradient-bg flex-shrink-0 mt-1.5"></div>@endif
</div>
</div>
</div>
@empty
<div class="p-12 text-center"><svg class="w-12 h-12 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg><p class="text-slate-400">No notifications yet</p></div>
@endforelse
</div>
{{ $notifications->links() }}
</div>
@endsection
