@extends('layouts.app')
@section('title','Active Sessions')
@section('content')
<div class="max-w-2xl mx-auto">
<h1 class="text-2xl font-bold text-white mb-6">Active Sessions</h1>
<div class="card overflow-hidden">
@forelse($sessions as $session)
<div class="flex items-center gap-4 p-4 border-b border-dark-border last:border-0">
<div class="w-10 h-10 rounded-xl bg-dark-border flex items-center justify-center flex-shrink-0">
<svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
</div>
<div class="flex-1 min-w-0">
<p class="text-sm font-medium text-white">{{ $session->device_name ?? ($session->browser . ' on ' . $session->os) }}</p>
<p class="text-xs text-slate-400">{{ $session->ip_address }} · {{ $session->last_active_at?->diffForHumans() }}</p>
</div>
@if($session->is_current)
<span class="badge badge-green text-xs">Current</span>
@else
<form action="{{ route('settings.sessions.revoke', $session->id) }}" method="POST">
@csrf @method('DELETE')
<button type="submit" class="text-xs text-red-400 hover:text-red-300">Revoke</button>
</form>
@endif
</div>
@empty
<div class="p-8 text-center"><p class="text-slate-400">No active sessions found.</p></div>
@endforelse
</div>
</div>
@endsection
